<?php

class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function add_events()
    {
        $data = (object) $this->params;
        $method = $this->requiredParameter($data, 'method', "method is required");
        $event_title = $this->requiredParameter($data, 'event_title', "event_title should not be empty");
        $event_description = $this->requiredParameter($data, 'event_description', "event_description should not be empty");
        $country = $this->requiredParameter($data, 'country', "country is required");
        $state = $this->requiredParameter($data, 'state', "state is required");
        $city = $this->requiredParameter($data, 'city', "city should not be empty");
        $location = $this->requiredParameter($data, 'location', "location is required");
        $date = $this->requiredParameter($data, 'date', "date should not be empty");
        $time = $this->requiredParameter($data, 'time', "time should not be empty");
        $duration = $this->requiredParameter($data, 'duration', "duration should not be empty");
        $event_organizer = $this->requiredParameter($data, 'event_organizer', "event_organizer should not be empty");
        $event_guest = $this->requiredParameter($data, 'event_guest', "event_guest should not be empty");
        $event_fee = $this->requiredParameter($data, 'event_fee', "event_fee should not be empty");
        $event_host = $this->requiredParameter($data, 'event_host', "event_host should not be empty");
        $event_promotion = $this->requiredParameter($data, 'event_promotion', "event_promotion should not be empty");
        $telephone = $this->requiredParameter($data, 'telephone', "telephone is required");
        $email = $this->requiredParameter($data, 'email', "email should not be empty");
        $additional_information = $this->requiredParameter($data, 'additional_information', "additional_Information should not be empty");
        $user_id = $this->requiredParameter($data, 'user_id', "user_id should not be empty");

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
            "event_title" => $state,
            'event_title' => $city,
            "event_description" => $event_description,
            "country" => $country,
            "state" => $state,
            'city' => $city,
            'location' => $location,
            'date' => $date,
            'time' => $time,
            'duration' => $duration,
            'event_organizer' => $event_organizer,
            'event_guest' => $event_guest,
            'event_fee' => $event_fee,
            'event_host' => $event_host,
            'event_promotion' => $event_promotion,
            'email' => $email,
            'telephone' => $telephone,
            'additional_information' => $additional_information
        );

        $add_home_id = $this->db->insert('community_events', $data);

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