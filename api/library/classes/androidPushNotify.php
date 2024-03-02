<?php
//androidPushNotify
//////////////////////////////////////////////////////////
# This Class will be use to send push notification to android device.
//////////////////////////////////////////////////////////
class androidPushNotify
{
    function __construct()
    {
    }
    function sendGoogleCloudMessage($ids, $message, $badge, $post_id)
    {
        //------------------------------
        // Replace with real GCM API
        // key from Google APIs Console
        //
        // https://code.google.com/apis/console/
        //------------------------------
        $apiKey = GOOGLE_API_KEY;
        //------------------------------
        // Define URL to GCM endpoint
        //------------------------------
        $url = 'https://android.googleapis.com/gcm/send';
        //------------------------------
        // Set GCM post variables
        // (Device IDs and push payload)
        //------------------------------
        $data = array(
            'message' => $message,
            'badge' => $badge,
            'post_id' => $post_id
            );
        $post = array(
            'registration_ids' => array($ids),
            'data' => $data,
        );
        //------------------------------
        // Set CURL request headers
        // (Authentication and type)
        //------------------------------
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );
        //------------------------------
        // Initialize curl handle
        //------------------------------
        $ch = curl_init();
        //------------------------------
        // Set URL to GCM endpoint
        //------------------------------
        curl_setopt($ch, CURLOPT_URL, $url);
        //------------------------------
        // Set request method to POST
        //------------------------------
        curl_setopt($ch, CURLOPT_POST, true);
        //------------------------------
        // Set our custom headers
        //------------------------------
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //------------------------------
        // Get the response back as
        // string instead of printing it
        //------------------------------
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //------------------------------
        // Set post data as JSON
        //------------------------------
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        //------------------------------
        // Actually send the push!
        //------------------------------
        $result = curl_exec($ch);
        //------------------------------
        // Error? Display it!
        //------------------------------
        if (curl_errno($ch)) {
            return 'GCM error: ' . curl_error($ch);
        }
        //------------------------------
        // Close curl handle
        //------------------------------
        curl_close($ch);
        //------------------------------
        // Debug GCM response
        //------------------------------
        return $result;
    }
    function sendGoogleCloudMessageWithKey($ids, $message, $badge, $value, $key_name)
    {
        //------------------------------
        // Replace with real GCM API
        // key from Google APIs Console
        //
        // https://code.google.com/apis/console/
        //------------------------------
        $apiKey = GOOGLE_API_KEY;
        //------------------------------
        // Define URL to GCM endpoint
        //------------------------------
        $url = 'https://android.googleapis.com/gcm/send';
        //------------------------------
        // Set GCM post variables
        // (Device IDs and push payload)
        //------------------------------
        $data = array(
            'message' => $message,
            'badge' => $badge,
            ''.$key_name.'' => $value
            );
        $post = array(
            'registration_ids' => array($ids),
            'data' => $data,
        );
        //------------------------------
        // Set CURL request headers
        // (Authentication and type)
        //------------------------------
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );
        //------------------------------
        // Initialize curl handle
        //------------------------------
        $ch = curl_init();
        //------------------------------
        // Set URL to GCM endpoint
        //------------------------------
        curl_setopt($ch, CURLOPT_URL, $url);
        //------------------------------
        // Set request method to POST
        //------------------------------
        curl_setopt($ch, CURLOPT_POST, true);
        //------------------------------
        // Set our custom headers
        //------------------------------
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //------------------------------
        // Get the response back as
        // string instead of printing it
        //------------------------------
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //------------------------------
        // Set post data as JSON
        //------------------------------
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        //------------------------------
        // Actually send the push!
        //------------------------------
        $result = curl_exec($ch);
        //------------------------------
        // Error? Display it!
        //------------------------------
        if (curl_errno($ch)) {
            return 'GCM error: ' . curl_error($ch);
        }
        //------------------------------
        // Close curl handle
        //------------------------------
        curl_close($ch);
        //------------------------------
        // Debug GCM response
        //------------------------------
        return $result;
    }
    function sendSystemGoogleCloudMessage($ids, $message, $badge)
    {
        //------------------------------
        // Replace with real GCM API
        // key from Google APIs Console
        //
        // https://code.google.com/apis/console/
        //------------------------------
        $apiKey = GOOGLE_API_KEY;
        //------------------------------
        // Define URL to GCM endpoint
        //------------------------------
        $url = 'https://android.googleapis.com/gcm/send';
        //------------------------------
        // Set GCM post variables
        // (Device IDs and push payload)
        //------------------------------
        $data = array(
            'message' => $message,
            'badge' => $badge,
            );
        $post = array(
            'registration_ids' => array($ids),
            'data' => $data,
        );
        //------------------------------
        // Set CURL request headers
        // (Authentication and type)
        //------------------------------
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );
        //------------------------------
        // Initialize curl handle
        //------------------------------
        $ch = curl_init();
        //------------------------------
        // Set URL to GCM endpoint
        //------------------------------
        curl_setopt($ch, CURLOPT_URL, $url);
        //------------------------------
        // Set request method to POST
        //------------------------------
        curl_setopt($ch, CURLOPT_POST, true);
        //------------------------------
        // Set our custom headers
        //------------------------------
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //------------------------------
        // Get the response back as
        // string instead of printing it
        //------------------------------
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //------------------------------
        // Set post data as JSON
        //------------------------------
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        //------------------------------
        // Actually send the push!
        //------------------------------
        $result = curl_exec($ch);
        //------------------------------
        // Error? Display it!
        //------------------------------
        if (curl_errno($ch)) {
            return 'GCM error: ' . curl_error($ch);
        }
        //------------------------------
        // Close curl handle
        //------------------------------
        curl_close($ch);
        //------------------------------
        // Debug GCM response
        //------------------------------
        return $result;
    }
}