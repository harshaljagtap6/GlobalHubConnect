<?php
if (file_exists('../config.php')) {
    require_once('../config.php');
} else {
    require_once(__DIR__ . '/../config.php');
}
require_once('system/library/classes/MysqliDb.php');

class GeneralClass
{
    protected $db;
    private $_cacheObj;
    private $_tableName;
    public $currencies;
    public $params = array();

    /**
     * Construct
     *
     */
    function __construct()
    {
        // Define DB level object
        // $this->db = new Mysqlidb (DB_HOST_NAME, DB_USER_NAME, DB_PASSWORD, DB_NAME);
        $this->db = new MysqliDb (Array (
                'host' => 'localhost',
                'username' => 'root', 
                'password' => '',
                'db'=> 'globalhubconnect',
                'charset' => 'utf8mb4'));
        
        $this->_initTranslate();

    }

    /**
     * Translate initialise
     *
     */
    protected function _initTranslate()
    {
        $dataArr = (array)require_once('system/language/english.php');
        if (isset($dataArr) and !empty($dataArr)) {
            foreach ($dataArr as $key => $value) {
                define($key, $value);
            }
        }
    }

    /**
     * Translate
     *
     * @param unknown_type $TranslateText
     * @return unknown
     */
    protected function translate($TranslateText)
    {
        return !defined($TranslateText) ? $TranslateText : constant($TranslateText);
    }

    function verifyToken($token)
    {
        $this->db->where("auth_token", $token);
        // $this->db->where("active", 'yes');
        $result = $this->db->getOne("signup", "id");
        
        if (empty($result)) {
            return false;
        }
        
        return array('userId'=>$result['id']);
    }
    function authCheck($user_id)
    {
        if (empty($user_id)) {
            $ResponseData = array(
                "message" => "Access denied!",
                "code" => UNAUTHORIZED,
                "status" => "Fail");
            $this->responseReturn($ResponseData);
        }
    }

    // https://stackoverflow.com/a/1289114
    function data_encrypted($string)
    {
        return $encrypted_string=openssl_encrypt($string,"AES-128-ECB",DATA_ENCRYPTION_KEY);
    }

    function data_decrypted($string)
    {
        return $decrypted_string=openssl_decrypt($string,"AES-128-ECB",DATA_ENCRYPTION_KEY);
    }

    function responseReturn($result)
    {
        $this->db->disconnectAll();
        ob_end_clean();
        ob_start('ob_gzhandler');
        header('Access-Control-Allow-Origin: *');

        $json_result = json_encode($result);
        echo $json_result;

        @ob_end_flush();
        @ob_flush();
        flush();
        if (session_id()) session_write_close();
        exit;
    }

    function responseEnd($response)
    {
        $this->db->disconnectAll();
        ob_start();

        // Send your response.
        echo json_encode($response);

        $size = ob_get_length();
        header("Content-Encoding: none");
        header("Content-Length: {$size}");
        header("Connection: close");
        ob_end_flush();
        ob_flush();
        flush();
        if (session_id()) session_write_close();
    }

    function escapeJsonString($value)
    {
        # list from www.json.org: (\b backspace, \f formfeed)
        $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
        $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
        $result = str_replace($escapers, $replacements, $value);
        return $result;
    }

    function verifyVersion($version)
    {
        return (APP_VERSION > $version) ? false : true;
    }

    function getDbDateTime()
    {
        $result = $this->db->rawQuery("select NOW() as DATETIME");
        return $result[0]['DATETIME'];
    }

    public function pushNotificationIos($device_id, $message, $title = COMPANY_NAME, $badge_count=1)
    {
        $this->objPushNotifyIphone = new iphonePushNotify(__DIR__ . '/system/pem_file/' . APNS_CERT);
        $this->objPushNotifyIphone->sendMessageToPhone($device_id, $message, $title, $badge_count);
    }

    public function pushNotificationAndroid($device_id, $message, $title, $badge_count)
    {
        
        $msg = array(
                    'body'  => $message,
                    'title' => $title,
                    'icon'  => 'myicon',/*Default Icon*/
                    'sound' => 'notification'/*Default sound*/
              );
        $fields = array(
                    'to'        => $device_id,
                    'notification'  => $msg
                );
        
        $headers = array(
                    'Authorization: key=' . GOOGLE_PUSH_API_KEY,
                    'Content-Type: application/json'
                );
        
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );
            curl_close( $ch );
        return true;
        
    }

    public function setNotification($user_id,$message,$title='',$badge_count='')
    {
        $title = (empty($title))? COMPANY_NAME:$title;
        $badge_count = (empty($badge_count))? 1:$badge_count;

        $this->db->where("id", $user_id);
        $user_detail =   $this->db->getOne("barbers","device_type,device_id");
        if($user_detail['device_type'] == 'ios' && !empty($user_detail['device_id'])){

            $this->pushNotificationIos($user_detail['device_id'], $message, $title, $badge_count);
        }else if($user_detail['device_type'] == 'android' && !empty($user_detail['device_id'])){

            $this->pushNotificationAndroid($user_detail['device_id'], $message, $title, $badge_count);
        }
    }

    public function mobilPushNotificationAndroid($notification,$title,$device_id_array, $sound='')
    {
        $msg = array(
                    'body'  => $notification,
                    'title' => COMPANY_NAME,
                    'icon'  => 'myicon',/*Default Icon*/
                    'sound' => 'notification'/*Default sound*/
              );
        $fields = array(
                    'to'        => $device_id_array,
                    'notification'  => $msg
                );
        
        
        $headers = array(
                    'Authorization: key=' . GOOGLE_PUSH_API_KEY,
                    'Content-Type: application/json'
                );
        
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch );
            curl_close( $ch );
        return true;
    }

    public function updateCoupon($coupon_id)
    {
        $coupon_data_update['total_used'] = $this->db->inc(1);
        $this->db->where('id', $coupon_id);
        $this->db->update('coupons', $coupon_data_update);
    }

    protected function santize($value='')
    {
        if(empty($value)) return '';

        return trim(strip_tags($value));
    }
    
    protected function random_number($value='')
    {
        for ($randomNumber = mt_rand(1, 9), $i = 1; $i < 10; $i++) {
            $randomNumber .= mt_rand(0, 9);
        }
        return $randomNumber;
    }

    function generateRandomString($length = 6) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function requiredParameter($data, $key, $msg=''){
        if(!isset($data->$key)) {
            $ResponseData = array(
                "message" => (!empty($msg))? $msg : $this->translate('MISSING_PARAMETER'),
                "code" => PARAMETER_MISSING,
                "status" => $this->translate('STATUS_FAILD'));
            $this->responseReturn($ResponseData);
        } else {
            // return $data->$key;
            return $this->sanitize($data->$key);
        }
    }

    function optionalParameter($data, $key, $default_value=""){
        if(!isset($data->$key)) {
            if(!isset($default_value)){
                return "";
            } else {
                return $default_value;
            }
        } else {
            // return $data->$key;
            return $this->sanitize($data->$key);
        }
    }

    function sanitize($string)
    {
        if(empty($string)) return '';
        //$string = stripslashes($string);
        $string = strip_tags($string);
        // $string = mysqli_real_escape_string ($string);
        return $string;
    }

    function validatePassword($password = "")
    {
        if(!empty($password) && strlen($password) >= 6) return true;
        return false;
    }

    function get_timeago($datetime)
    {
        $seconds_ago = (time() - strtotime($datetime));

        if ($seconds_ago >= 31536000) {
            return "" . intval($seconds_ago / 31536000) . " years ago";
        } elseif ($seconds_ago >= 2419200) {
            return "" . intval($seconds_ago / 2419200) . " months ago";
        } elseif ($seconds_ago >= 86400) {
            return "" . intval($seconds_ago / 86400) . " days ago";
        } elseif ($seconds_ago >= 3600) {
            return "" . intval($seconds_ago / 3600) . " hours ago";
        } elseif ($seconds_ago >= 60) {
            return "" . intval($seconds_ago / 60) . " minutes ago";
        } else {
            return "minute ago";
        }
    }
    function getRemainingQuizDays($quiz_date)
    {
        
        $now = time(); // or your date as well
        $your_date = strtotime($quiz_date);
        $datediff = $now - $your_date;
        $datediff = round($datediff / (60 * 60 * 24));
        if ($datediff <= QUIZ_DAY) {
            $remaining_quiz_days = QUIZ_DAY - $datediff;
        }else{
            $remaining_quiz_days = 0;
        }
        return $remaining_quiz_days;
    }

}	