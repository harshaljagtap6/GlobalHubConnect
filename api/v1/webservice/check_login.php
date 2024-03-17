<?php
class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function check_login($user_id = '')
    {
        $this->authCheck($user_id);

        /*-------------------------------------------------------------------*/
        // $user_data = $this->getInfo($user_id);        
        // $userinfo = array(
        //     'name' => $user_data['name'],
        //     'email' => $user_data['email'],
        //     'phone' => $user_data['phone'],
        //     "fan_score" => $user_data['fan_score'],
        //     "city" => $user_data['city'],
        //     "zipcode" => $user_data['zipcode'],
        //     "country_code" => $user_data['country_code'],
        //     "remaining_quiz_days" => $remaining_quiz_days
        // );

        $ResponseData = array(
            'status' => $this->translate('STATUS_SUCCESS'),
            "code" => SUCCESS,
            "message" => "access approved",
            "user_id" => $user_id
        );
        $this->responseReturn($ResponseData);

    }

    // function getInfo($id)
    // {
    //     $this->db->where("id", $id);
    //     $result =   $this->db->getOne("users","*");
    //     if (empty($result)) return false;
    //     return $result;
    // }

}

?>