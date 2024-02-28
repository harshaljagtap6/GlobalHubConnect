<?php
require_once(__DIR__ . '/../../library/classes/aws/aws-autoloader.php');
use Aws\Common\Aws;
class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function update_profile($user_id='')
    {
        $this->authCheck($user_id);

        $data = (object)$this->params;
        $name = $this->requiredParameter($data, 'name', $this->translate('EMPTY_NAME'));
        $phone = $this->requiredParameter($data, 'phone', $this->translate('EMPTY_PHONE_SIGNUP'));
        $country_code = $this->requiredParameter($data, 'country_code',"Please select country code");
        $city = $this->requiredParameter($data, 'city',"city should not be empty");
        $zipcode = $this->requiredParameter($data, 'zipcode',"zipcode should not be empty");
        $data = null;
        
        $get_user = $this->getUser($user_id);
        if (!$get_user) {
            $ResponseData = array(
                "message" => "User not found",
                "code" => FAILED,
                "status" => $this->translate('STATUS_FAILD'));
            $this->responseReturn($ResponseData);
        }

        $datetime = DATETIME;

        // add users data in users
        $data = array(
            "name" => $name,
            "phone" => $phone,
            "country_code" => $country_code,
            "city" => $city,
            'zipcode' => $zipcode,
            'updated' => $datetime
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
        
        $user_info = array(
            "name" => $name,
            "country_code" => $country_code,
            "phone" => $phone,
            "city" => $city,
            'zipcode' => $zipcode
        );

        if (!empty($_FILES)) {
            
            $aws = Aws::factory(array(
                'key'    => S3_ACCESS_KEY,
                'secret' => S3_SECRET_KEY,
                // 'region'  => AmazonS3_region,
            ));
            $S3Client = $aws->get('s3');

            $target_file = __DIR__."/../upload/users/";

                $image = 'photo';
                if (isset($_FILES[$image]['name']) && !empty($_FILES[$image]['name'])) {
                    
                    if (!file_exists($target_file))
                        mkdir($target_file, 0777, true);
                    @chmod($target_file, 0777);

                    if (isset($_FILES[$image]['name']) && !empty($_FILES[$image]['name'])) {

                        $upload = move_uploaded_file($_FILES[$image]["tmp_name"], $target_file . basename($_FILES[$image]["name"]));
                        if ($upload) {
                            # code...
                            $result = $S3Client->putObject(
                                array(
                                    'Bucket' => S3_BUCKET,
                                    'Key'    => 'users/'.$user_id.'/photo/'.$_FILES[$image]["name"].'',
                                    'SourceFile'   => $target_file . basename($_FILES[$image]["name"]),
                                    'ACL'    => 'public-read', // public-read // private
                                )
                            );
                            unlink($target_file . basename($_FILES[$image]["name"]));

                            $update_data = array(
                                'photo' => $_FILES[$image]["name"]
                            );
                            $this->db->where('id', $user_id);
                            $this->db->update('users', $update_data);

                            $user_info['photo_url'] = AWS_S3_URL.'/users/'.$user_id.'/photo/'.$_FILES[$image]["name"];

                        }

                    }
                }
        }

        $ResponseData = array(
            "message" => "Updated",
            'status' => $this->translate('STATUS_SUCCESS'),
            "code" => SUCCESS,
            "user_info" => $user_info
        );
        $this->responseReturn($ResponseData);
    }

    function getPhone($phone)
    {
        $this->db->where("phone", $phone);
        $result = $this->db->getOne("users", "id");
        if (empty($result)) {
            return false;
        } else {
            return $result;
        }
    }
    function getUser($id)
    {
        $this->db->where("id", $id);
        $result = $this->db->getOne("users", "id");
        if (empty($result)) {
            return false;
        } else {
            return $result;
        }
    }
    function createToken($data)
    {
        /* Create a part of token using secretKey */
        $tokenGeneric = ENCRYPTION_KEY.$_SERVER["SERVER_NAME"]; // It can be 'stronger' of course
        /* Encoding token */
        $token = hash('sha256', $tokenGeneric.$data);
        return $token;
    }

}

?>
