<?php

class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function add_community_pages()
    {
        $data = (object) $this->params;
        $method = $this->requiredParameter($data, 'method', "Method is required");
        $nationality = $this->requiredParameter($data, 'nationality', "Nationality is required");
        $community_name = $this->requiredParameter($data, 'community_name', "Community name should not be empty");
        $community_tag = $this->requiredParameter($data, 'community_tag', "Community tag should not be empty");
        $community_description = $this->requiredParameter($data, 'community_description', "Community description should not be empty");
        $community_rules = $this->requiredParameter($data, 'community_rules', "Community rules should not be empty");
        $admin = $this->requiredParameter($data, 'admin', "Admin is required");
        $user_id = $this->requiredParameter($data, 'user_id', "User ID should not be empty");

        $data = null;

        // $get_email = $this->getEmail($email);
        // if ($get_email) {
        //     $ResponseData = array(
        //         "message" => "This email is already register",
        //         "code" => FAILED,

        //     );
        //     $this->responseReturn($ResponseData);
        // }


        $datetime = DATETIME;

        // add users data in users
        $data = array(
            "user_id" => $user_id,
            "admin" => $admin,
            'community_rules' => $community_rules,
            "community_description" => $community_description,
            "community_tag" => $community_tag,
            "community_name" => $community_name,
            'country' => $nationality            
        );

        $add_home_id = $this->db->insert('community_pages', $data);

        /* $sql = "INSERT INTO `login`.`users` (`email`, `password`, `signup_with`, `verification_code`, `device_id`, `device_type`, `created`, `updated`)
        VALUES ($email, $password, $email, $verification_code, $device_id, $device_type, $datetime, $datetime)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssssss", $email, $password, 'email', $verification_code, $device_id, $device_type, $datetime, $datetime);
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();*/

        if (!$add_home_id) {
            $ResponseData = array(
                "message" => $this->translate('REGISTRATION_FAILED'),
                "code" => FAILED,
                "status" => $this->translate('STATUS_FAILD')
            );
            $this->responseReturn($ResponseData);
        }


        $ResponseData = array(
            "message" => $this->translate('REGISTRATION_SUCCESS'),
            'status' => $this->translate('STATUS_SUCCESS'),
            "code" => SUCCESS,
            "add_home_id" => $add_home_id
        );
        $this->responseReturn($ResponseData);
    }


    function getEmail($email)
    {
        $this->db->where("email", $email);
        $result = $this->db->getOne("users", "id");
        if (empty ($result)) {
            return false;
        } else {
            return $result;
        }
    }


}
?>