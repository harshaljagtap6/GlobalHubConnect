<?php
class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function logout($user_id='')
    {
        $this->authCheck($user_id);

        /*-------------------------------------------------------------------*/

        $userData = array(
            "auth_token" => ''
        );

        $this->db->where('id', $user_id);
        if (!$this->db->update('signup', $userData)) {
            $ResponseData = array(
                "message" => "Something went wrong please try again!",
                "code" => FAILED,
                "status" => $this->translate('STATUS_FAILD'));
            $this->responseReturn($ResponseData);
        }

        $ResponseData = array(
            'status' => $this->translate('STATUS_SUCCESS'),
            "code" => SUCCESS,
            "message" => "Logout successfully!"
        );
        $this->responseReturn($ResponseData);
    
    }

}

?>
