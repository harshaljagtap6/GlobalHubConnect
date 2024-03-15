<?php

class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function add_home()
    {
        $data = (object) $this->params;
        $country = $this->requiredParameter($data, 'country', "country  is required");
        $state = $this->requiredParameter($data, 'state', "state  is required");
        $city = $this->requiredParameter($data, 'city', "city should not be empty");
        $bhk = $this->requiredParameter($data, 'bhk', "bhk should not be empty");
        $duration = $this->requiredParameter($data, 'duration', "duration should not be empty");
        $address = $this->requiredParameter($data, 'address', "address is required");
        $telephone = $this->requiredParameter($data, 'telephone', "telephone is required");
        $email = $this->requiredParameter($data, 'email', "email should not be empty");
        $details = $this->requiredParameter($data, 'details', "details should not be empty");
        $rent = $this->requiredParameter($data, 'rent', "rent should not be empty");
        $user_id  = $this->requiredParameter($data, 'user_id', "user_id should not be empty");

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
            "country" => $country,
            "state" => $state,
            'city' => 'city',
            "bhk" => $bhk,
            "duration" => $duration,
            "address" => $address,
            'telephone' => $telephone,
            'email' => $email,
            'details' => $details,
            'rent' => $rent,
            'user_id' => $user_id
        );

        $add_home_id = $this->db->insert('rent_home', $data);

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