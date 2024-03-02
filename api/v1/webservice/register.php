<?php

class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function register()
    {
        $data = (object) $this->params;
        $email = $this->requiredParameter($data, 'email', "email  is required");
        $password = $this->requiredParameter($data, 'password', "password  is required");
        $device_id = $this->requiredParameter($data, 'device_id', "device id should not be empty");
        $device_type = $this->requiredParameter($data, 'device_type', "device type should not be empty");
        $name = $this->requiredParameter($data, 'name', $this->translate('EMPTY_NAME'));
        $phone = $this->requiredParameter($data, 'phone', $this->translate('EMPTY_PHONE_SIGNUP'));
        $country_code = $this->requiredParameter($data, 'country_code',"Please select country code");
        $city = $this->requiredParameter($data, 'city',"city should not be empty");
        $zipcode = $this->requiredParameter($data, 'zipcode',"zipcode should not be empty");
        $data = null;

        $get_email = $this->getEmail($email);
        if ($get_email) {
            $ResponseData = array(
                "message" => "This email is already register",
                "code" => FAILED,

            );
            $this->responseReturn($ResponseData);
        }
        if (!$this->validatePassword($password)) {
            $ResponseData = array(
                "message" => "Password should not be less than 8 characters.",
                "code" => FAILED,
                "status" => $this->translate('STATUS_FAILD')
            );
            $this->responseReturn($ResponseData);
        }

        $datetime = DATETIME;

        $password = password_hash($password, PASSWORD_DEFAULT);
        $verification_code = $this->generateRandomString(20);

        // add users data in users
        $data = array(
            "email" => $email,
            "password" => $password,
            'signup_with' => 'email',
            "verification_code" => $verification_code,
            "device_id" => $device_id,
            "device_type" => $device_type,
            'created' => $datetime,
            'updated' => $datetime,
            'name' => $name , 
            'phone' => $phone,
            'country_code' => $country_code,
            'city' => $city,
            'zipcode' => $zipcode,
        );
        print_r($data);

        $user_id = $this->db->insert('users', $data);
        

        $a=$this->db->getLastQuery();
        print_r($a);
        if (mysqli_affected_rows($this->db) > 0) {
            echo "Data inserted successfully!";
        } else {
            echo "Data not inserted.";
        }
        /* $sql = "INSERT INTO `login`.`users` (`email`, `password`, `signup_with`, `verification_code`, `device_id`, `device_type`, `created`, `updated`)
        VALUES ($email, $password, $email, $verification_code, $device_id, $device_type, $datetime, $datetime)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssssss", $email, $password, 'email', $verification_code, $device_id, $device_type, $datetime, $datetime);
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();*/

        print_r($user_id);
        if (!$user_id) {
            $ResponseData = array(
                "message" => $this->translate('REGISTRATION_FAILED'),
                "code" => FAILED,
                "status" => $this->translate('STATUS_FAILD')
            );
            $this->responseReturn($ResponseData);
        }

        $userinfo = array("email" => $email, "photo_url" => '');

        if (!empty($_FILES)) {

            $aws = Aws::factory(
                array(
                    'key' => S3_ACCESS_KEY,
                    'secret' => S3_SECRET_KEY,
                    // 'region'  => AmazonS3_region,
                )
            );
            $S3Client = $aws->get('s3');

            $target_file = __DIR__ . "/../upload/users/";

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
                                'Key' => 'users/' . $user_id . '/photo/' . $_FILES[$image]["name"] . '',
                                'SourceFile' => $target_file . basename($_FILES[$image]["name"]),
                                'ACL' => 'public-read',
                                // public-read // private
                            )
                        );
                        unlink($target_file . basename($_FILES[$image]["name"]));

                        $update_data = array(
                            'photo' => $_FILES[$image]["name"]
                        );
                        $this->db->where('id', $user_id);
                        $this->db->update('users', $update_data);

                        $userinfo['photo_url'] = AWS_S3_URL . '/users/' . $user_id . '/photo/' . $_FILES[$image]["name"];
                    }

                }
            }
        }

        $mail = new PHPMailer(true);

        // update users token
        $token_data = time() . "_" . $user_id . "-" . $email;
        $auth_token = $this->createToken($token_data);

        $this->db->where('id', $user_id);
        $this->db->update('users', array('auth_token' => $auth_token));

        $ResponseData = array(
            "message" => $this->translate('REGISTRATION_SUCCESS'),
            'status' => $this->translate('STATUS_SUCCESS'),
            "code" => SUCCESS,
            "auth_token" => $auth_token,
            "user_info" => $userinfo
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
    function getEmail($email)
    {
        $this->db->where("email", $email);
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
        $tokenGeneric = ENCRYPTION_KEY . $_SERVER["SERVER_NAME"]; // It can be 'stronger' of course
        /* Encoding token */
        $token = hash('sha256', $tokenGeneric . $data);
        return $token;
    }

}
?>