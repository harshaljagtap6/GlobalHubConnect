<?php

class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function add_recruite()
    {
        $data = (object) $this->params;
        $jobTitle = $this->requiredParameter($data, 'jobTitle', "jobTitle  is required");
        $jobDesc = $this->requiredParameter($data, 'jobDesc', "jobDesc  is required");
        $companyDesc = $this->requiredParameter($data, 'companyDesc', "companyDesc should not be empty");
        $location = $this->requiredParameter($data, 'location', "location should not be empty");
        $salary = $this->requiredParameter($data, 'salary', "duration should not be empty");
        $qualification = $this->requiredParameter($data, 'qualification', "address is required");
        $experience = $this->requiredParameter($data, 'experience', "telephone is required");
        $due = $this->requiredParameter($data, 'due', "due should not be empty");
        $apply = $this->requiredParameter($data, 'apply', "apply should not be empty");
        $countryCode = $this->requiredParameter($data, 'countryCode', "countryCode should not be empty");
        $telephone  = $this->requiredParameter($data, 'telephone', "telephone should not be empty");
        $email  = $this->requiredParameter($data, 'email', "email should not be empty");
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
            "jobTitle" => $jobTitle,
            "jobDesc" => $jobDesc,
            'companyDesc' => $companyDesc,
            "location" => $location,
            "salary" => $salary,
            "qualification" => $qualification,
            'experience' => $experience,
            'due' => $due,
            'apply' => $apply,
            "email"=>$email,
            'countryCode' => $countryCode,
            'telephone'=>$telephone,
            'user_id' => $user_id
        );

        $add_recruite_id = $this->db->insert('recruite', $data);

        /* $sql = "INSERT INTO `login`.`users` (`email`, `password`, `signup_with`, `verification_code`, `device_id`, `device_type`, `created`, `updated`)
        VALUES ($email, $password, $email, $verification_code, $device_id, $device_type, $datetime, $datetime)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssssss", $email, $password, 'email', $verification_code, $device_id, $device_type, $datetime, $datetime);
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();*/

        if (!$add_recruite_id) {
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
            "add_recruite_id" => $add_recruite_id
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