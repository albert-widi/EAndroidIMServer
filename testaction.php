<?php
include 'mysqlDB.php';

$db = new MysqlDB();
$action = $_POST['action'];

switch($action) {
    case 'sendmessage':
        echo "Sending Message<br>";
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
    
        $api_key = "AIzaSyAmxWvTVG-0OvAzV0m55Leip-EzaVq-Pts";
        $message = $_POST['message'];
        $queryString = "SELECT gcm_id FROM users WHERE phone_number = '+6281380832112'";
        $db->query($queryString);
        if($db->result) {
            $row = $db->result->fetch_array(MYSQLI_ASSOC);
            
            echo "Sending now<br>";
            echo $row['gcm_id']."<br>";
            $gcm_id = $row['gcm_id'];
            $from = "Albert Widiatmoko";
            
            $reg_id = array($gcm_id);
            $fields = array('registration_ids' => $reg_id,
                            'data' => array("message" => $message));
            
            $headers = array('Authorization: key='.$api_key,
                             'Content-Type: application/json');
            
            // Open connection
            $ch = curl_init();
            
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            
            // Execute post
            $result = curl_exec($ch);
            
            if($result == false) {
                die('Curl failed: ' . curl_error($ch));
            }
            
            // Close connection
            curl_close($ch);
        }
        else {
            echo $queryString;
        }
        break;
}
?>