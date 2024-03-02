<?php
ini_set('display_errors',1);

$root_path = 'http://localhost/GlobalHubConnect/api/';
$api_path = 'http://localhost/GlobalHubConnect/api/v1/';
$DB_HOST_NAME="localhost";
$DB_USER_NAME="root";
$DB_PASSWORD="";
$DB_NAME="globalhubconnect";

$conn= new mysqli($DB_HOST_NAME,$DB_USER_NAME,$DB_PASSWORD,$DB_NAME);
if($conn -> connect_errno ){
    echo $conn -> connect_error;
}

/*define('root_path', $root_path);
define('api_path', $api_path);

// databse detail
define('DB_HOST_NAME', 'localhost');
define('DB_USER_NAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'users');*/

/**********************| Server setting |****************************/
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 3600);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(3600);

date_default_timezone_set('UTC');

/************************ BASIC CONFIGURATION ****************************/

define('ENCRYPTION_KEY', '4TJ78UCVDWCRHUL3K4M5P7Q8FS');
define('DATA_ENCRYPTION_KEY', '2u7x/A?D(G+KbPeShVmYp3s6v9y$B&E)H@MgrfRjA3nZW3t7w!z%C*F-JacDvgadkc');

// Application min version
define("APP_VERSION", 1);

define("DATETIME",date("Y-m-d H:i:s"));

// admin email
define('ADMIN_EMAIL', '');
define('COMPANY_EMAIL_ADDRESS', '');
define('COMPANY_NAME', 'Rumbum');

define('SMTP_EMAIL', '');
define('SMTP_PASSWORD', '');

/*******| Push Notification Setting |********/

define('GOOGLE_PUSH_API_KEY', '');

/************************ ERROR CODE ****************************/

define('SUCCESS', 200);
define('FAILED', 201);
define('PARAMETER_MISSING', 400);
define('UNAUTHORIZED', 401);
define('UPDATE_APP', 491);
define('NO_RECORD', 100);
define('OTP_VERIFY', 204);
