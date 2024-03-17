<?php


class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_rent_home()
    {

        $data = (object) $this->params;
        $user_id = $this->requiredParameter($data, 'user_id', "user_id should not be empty");
        $data = null;

        $get_user = $this->getUser($user_id);
        if (!$get_user) {
            $ResponseData = array(
                "message" => "User not found",
                "code" => FAILED,
                "status" => $this->translate('STATUS_FAILD')
            );
            $this->responseReturn($ResponseData);
        }

        $ResponseData = array(
            "message" => "Found",
            'status' => $this->translate('STATUS_SUCCESS'),
            "code" => SUCCESS,
            "get_user" => $get_user
        );
        $this->responseReturn($ResponseData);
    }

    function getUser($id)
    {
        $this->db->where("user_id", $id);
        $result = $this->db->get("rent_home");
        if (empty ($result)) {
            return false;
        } else {
            return $result;
        }
    }


}

?>