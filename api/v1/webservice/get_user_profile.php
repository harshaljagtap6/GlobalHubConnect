<?php
class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_user_profile($user_id='')
    {
        $this->authCheck($user_id);

        /*-------------------------------------------------------------------*/
        $user_data = $this->getInfo($user_id);        
        $userinfo = array(
            'name' => $user_data['name'],
            'email' => $user_data['email'],
            'phone' => $user_data['phone'],
            "fan_score" => $user_data['fan_score'],
            "city" => $user_data['city'],
            "zipcode" => $user_data['zipcode'],
            "country_code" => $user_data['country_code'],
            "remaining_quiz_days" => $remaining_quiz_days
        );
        $userinfo['photo_url'] = '';
        if (!empty($user_data['photo'])) {
            
            $userinfo['photo_url'] = AWS_S3_URL.'/users/'.$user_data['id'].'/photo/'.$user_data['photo'];
        }

        $ResponseData = array(
            'status' => $this->translate('STATUS_SUCCESS'),
            "code" => SUCCESS,
            "data" => $userinfo
        );
        $this->responseReturn($ResponseData);
    
    }

    function getInfo($id)
    {
        $this->db->where("id", $id);
        $result =   $this->db->getOne("users","*");
        if (empty($result)) return false;
        return $result;
    }

}

?>
