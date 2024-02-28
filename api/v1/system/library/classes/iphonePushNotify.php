<?php
//iphonePushNotify.cls
//////////////////////////////////////////////////////////
# This Class will be use to send push notification to iPhone device.
//////////////////////////////////////////////////////////
class iphonePushNotify
{
    private $apnPath;
    private $authId;
    function __construct($apnPath = '')
    {
        if (!empty($apnPath)) {
            $this->apnPath = $apnPath;
        } else {
            $this->apnPath = 'cert.pem';
        }
    }
    ///STACK FLOW EXAMPLE CODE
    function authenticate()
    {
    }
    function sendMessageToPhone($device_token, $message, $title, $badge)
    {
        
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->apnPath);
        stream_context_set_option($ctx, 'ssl', 'passphrase', APNS_PASSCODE);
        $fp = stream_socket_client(APNS_SERVER, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $ctx); // Local
        if (!$fp) {
            //exit("Failed to connect: $error $errorString" . PHP_EOL);
        } else {
            // $message = "{\"aps\": {\"sound\":\"default\",\"alert\": \" " . $message . "\",\"badge\": " . $badge . "}}";

            $body['aps'] = array('alert' => array('title' => $title, 'body' => $message), 'sound' => 'default');
            $message = json_encode($body);
            
            $msg = chr(0) . pack("n", 32) . pack('H*', $device_token) . pack("n", strlen($message)) . $message;
            $fwrite = fwrite($fp, $msg);
            fclose($fp);
        }
    }
    public function send_direct($tokenid, $message, $title = 'Hello')
    {
        #!/usr/bin/env php
        $deviceToken = $tokenid;  // masked for security reason
        // Get the parameters from http get or from command line
        //$message="You have buzzed 0 business services. Are you satisfied with it?";
        $badge = 1;
        $sound = 'default';
        //$userid=102;
        //$type="0";
        //$title="Notification from Bizzyfind application";
        // Construct the notification payload
        $body = array();
        $body['aps'] = array('alert' => $message);
        if ($badge)
            $body['aps']['badge'] = $badge;
        if ($sound)
            $body['aps']['sound'] = $sound;
        $body['aps']['title'] = $title;
        //$body='{"aps": {"badge":1,"sound":"deault","alert": " '.$message.'","title": "'.$title.'","type": "'.$type.'","userid": "'.$userid.'"}}';
        /* End of Configurable Items */
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->apnPath);
        // assume the private key passphase was removed.
        // stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
        // for production change the server to ssl://gateway.push.apple.com:2195
        if (!$fp) {
            print "Failed to connect $err $errstr\n";
            return;
        } else {
            print "Connection OK\n";
        }
        $payload = json_encode($body);
        $msg = chr(0) . pack("n", 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n", strlen($payload)) . $payload;
        print "sending message :" . $payload . "\n";
        fwrite($fp, $msg);
        fclose($fp);
    }
}