<?php
class WebService extends GeneralClass
{

    function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $data = $this->params;
        $dataArray = (array) $this->params;
        if (empty($dataArray)) {
            $ResponseData = array(
                "message" => $this->translate('EMPTY_PARAMETER'),
                "code" => PARAMETER_MISSING,
                "status" => $this->translate('STATUS_FAILD')
            );
            $this->responseReturn($ResponseData);
        }
        $email = $this->requiredParameter($data, 'email', "email is required");
        $password = $this->optionalParameter($data, 'password', 'password is required');

        $data = null;

        /*-------------------------------------------------------------------*/

        $userLogin = $this->getEmail($email);

        // email is not register then ask for signup
        if (!$userLogin || empty($userLogin['mail'])) {

            $userinfo = array();
            $ResponseData = array(
                "message" => "Your are not registered",
                'status' => $this->translate('STATUS_SUCCESS'),
                "code" => SUCCESS,
                // 'new_user' => 1
            );
            $this->responseReturn($ResponseData);

        }

        // only email check for first step
        // if (empty($password)) {

        //     // if user register with social media then give error
        //     if ($userLogin['signup_with'] != 'email') {
        //         $ResponseData = array(
        //             "message" => 'You can not login with email. You should login with ' . $userLogin['signup_with'],
        //             "signup_with" => $userLogin['signup_with'],
        //             "code" => FAILED,
        //             "status" => $this->translate('STATUS_FAILD')
        //         );
        //         $this->responseReturn($ResponseData);
        //     }

        //     $userinfo = array("email" => $email, "name" => $userLogin['name'], "photo" => $userLogin['photo']);
        //     $userinfo['photo_url'] = '';
        //     if (!empty($userLogin['photo'])) {
        //         $userinfo['photo_url'] = AWS_S3_URL . '/users/' . $userLogin['id'] . '/photo/' . $userLogin['photo'];
        //     }
        //     $ResponseData = array(
        //         "user_info" => $userinfo,
        //         "message" => "Enter your password",
        //         'status' => $this->translate('STATUS_SUCCESS'),
        //         "code" => SUCCESS,
        //         'new_user' => 0
        //     );
        //     $this->responseReturn($ResponseData);
        // }


        // second step login with password

        if (!$userLogin || $password != $userLogin['password']) {
            $ResponseData = array(
                "message" => "Email or password is incorrect. Please try again",
                "code" => UNAUTHORIZED,
                "status" => $this->translate('STATUS_FAILD')
            );
            $this->responseReturn($ResponseData);
        }

        // check inactive account
        // if ($userLogin['active'] == 'no') {
        //     $ResponseData = array(
        //         "message" => $this->translate('INACTIVE_ACCOUNT'),
        //         "code" => FAILED,
        //         "status" => $this->translate('STATUS_FAILD')
        //     );
        //     $this->responseReturn($ResponseData);
        // }


        $userId = $userLogin['id'];
        $userEmail = $userLogin['mail'];

        /*----------------------------| Login |----------------------------------*/
        // update user token
        $token_data = time() . "_" . $userId . "-" . $userEmail;
        $auth_token = $this->createToken($token_data);

        $data = array(
            'auth_token' => $auth_token,
            'login_time' => DATETIME,
            // 'updated' => DATETIME
        );
        $this->db->where('id', $userId);
        $this->db->update('signup', $data);

        // $userinfo = array("email" => $userLogin['email'], "name" => $userLogin['name'], "photo" => $userLogin['photo']);
        // $userinfo['photo_url'] = '';
        // if (!empty($userLogin['photo'])) {
        //     $userinfo['photo_url'] = AWS_S3_URL . '/users/' . $userLogin['id'] . '/photo/' . $userLogin['photo'];
        // }

        // $is_new_user = (empty($userLogin['email'])) ? 1 : 0;

        $ResponseData = array(
            "message" => $this->translate('LOGGED_IN_SUCCESSFULLY'),
            'status' => $this->translate('STATUS_SUCCESS'),
            "code" => SUCCESS,
            "auth_token" => $auth_token,
            "name" => $userLogin['first_name']
            // 'user_info' => $userinfo,
            // 'new_user' => $is_new_user
        );
        $this->responseReturn($ResponseData);

    }

    function getEmail($email)
    {
        $this->db->where("mail", $email);
        $result = $this->db->getOne("signup", "first_name,id,mail,password");

        if (empty($result)) {
            return false;
        } else {
            return $result;
        }
    }
    function getAddress($user_id)
    {
        $this->db->where("user_id", $user_id);
        $this->db->where("is_default", 1);
        $result = $this->db->getOne("addresses", "address,latitude,longitude");
        if (empty($result))
            return array();

        return $result;
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