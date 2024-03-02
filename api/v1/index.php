<?php
if (!isset($_POST['data'])) {
    $response = array(
        "status" => "Fail",
        "code" => 400,
        "message" => "Error: bad request 0"
    );
    response_end($response);
    exit;
}

$post_data_string = $_POST['data'];
$data = json_decode($post_data_string);

if (!isset($data->method) || empty($data->method)) {
    $response = array(
        "status" => "Fail",
        "code" => 400,
        "message" => "Error: bad request 1"
    );
    response_end($response);
    exit;
}
$method = $data->method;

require_once('GeneralClass.php');
$GeneralClassObj = new GeneralClass();

if (isset($data->error) && !empty($data->error)) {
    # code...
    ini_set('display_errors',1);
}

// verify app version
if(isset($data->version)){
    $VersionValid = $GeneralClassObj->verifyVersion($data->version);
    if (!$VersionValid) {
        $ResponseData = array(
            "message" => "Update now.",
            "code" => UPDATE_APP,
            "status" => "Fail");
        response_end($ResponseData);
        exit;
    }
}

/************* List of web-servise ****************/

// verify user token
if(isset($data->token)){
    $verify_data = $GeneralClassObj->verifyToken($data->token);
    if (!$verify_data) {
        $ResponseData = array(
            "message" => "Invalid token Id",
            "code" => UNAUTHORIZED,
            "status" => "Fail");
        response_end($ResponseData);
        exit;
    }
    $user_id = $verify_data['userId'];
    
    if (file_exists( "webservice/" . $method . ".php")) {
        require_once("webservice/" . $method . ".php");
    } else {
        $response = array(
            "status" => "Fail",
            "code" => 400,
            "message" => "Error: bad request 3"
        );
        response_end($response);
        exit;
    }

    $obj = new WebService();
    $obj->params = $data;

    $result = $obj->$method($user_id);

    
}else{

    if (file_exists("webservice/" . $method . ".php")) {
        require_once("webservice/" . $method . ".php");
    } else {
        $response = array(
            "status" => "Fail",
            "code" => 400,
            "message" => "Error: bad request 4"
        );
        response_end($response);
        exit;
    }
    
    $obj = new WebService();
    $obj->params = $data;

    $result = $obj->$method(null);

    // if ($method == 'login') {
    //     $result = $obj->login();
    // }
    // if ($method == 'register') {
    //     $result = $obj->register();
    // }
    
}

if(isset($result)){
    return $result;
}
else{
    $response = array(
        "status" => "Fail",
        "code" => 400,
        "message" => "Error: bad request 5"
    );
    response_end($response);
}

function response_end($response)
{
    ob_start();

    // Send your response.
    echo json_encode($response);

    $size = ob_get_length();
    header("Content-Encoding: none");
    header("Content-Length: {$size}");
    header("Connection: close");
    ob_end_flush();
    ob_flush();
    flush();
    if (session_id()) session_write_close();
}

function indent($json)
{

    $result = '';
    $pos = 0;
    $strLen = strlen($json);
    $indentStr = '  ';
    $newLine = "\n";
    $prevChar = '';
    $outOfQuotes = true;

    for ($i = 0; $i <= $strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

            // If this character is the end of an element,
            // output a new line and indent the next line.
        } else if (($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos--;
            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element,
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        $prevChar = $char;
    }

    return $result;
}

?>
