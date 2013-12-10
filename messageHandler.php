<?php
Class MessageHandler {
	private $database;

	function __construct($db) {
		$this->database = $db;
	}

	public function sendMessage($phoneSender, $phoneReceive, $senderName, $message, $messageKey, $messageHash) {
		$success = true;

		if(!isset($gcmSender) || !isset($gcmReceive) || !isset($message) || !isset($messageHash)) {
            return $this->jsonHandler->createSimpleResponseMessage(1, "INVALID_DATA");
        }
        
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
        $api_key = "AIzaSyAL4humet5NGC_NqiHb11WA_1ojc_rdjI4";

        //lookup for user gcmid
        $queryString = "SELECT gcm_id FROM users WHERE phone_number = '".$phoneReceive."'";
        $db->query($queryString);
        if(!$db->result) {
           return $this->jsonHandler->createSimpleResponseMessage(1, "SEND_FAILED"); 
        }
         $row = $db->result->fetch_array(MYSQLI_ASSOC);
         $gcmId = $row['gcm_id'];
        
        $reg_id = array($gcmId);
        $fields = array('registration_ids' => $reg_id,
                        'data' => array("message" => $message, "key" => $messageKey, "hash" => $messagehash, "whosent" => $phoneSender, "name" => $senderName));
        
        $headers = array('Authorization: key=' . $api_key,
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
        	$success = false;
            die('Curl failed: ' . curl_error($ch));
        }
        else {
        	$success = true;
            return $this->jsonHandler->createSimpleResponseMessage(0, "SEND_SUCCESS"); 
        }
              
        // Close connection
        curl_close($ch);

        //save to log
        $status;
        if($success) {
        	$status = "SUCCESS";

        }
        else {
        	$status = "FAILED";
        }

        $this->saveMessagetoLog($phoneSender, $phoneReceive, $senderName, $message, $messageKey, $messageHash, $status);
	}

	private function saveMessagetoLog($phoneSender, $phoneReceive, $senderName, $message, $messageKey, $messageHash, $status) {
		$queryString = "INSERT INTO msg_log(phone_sender, phone_receiver, sender_name, message, message_key, message_hash, status) ".
					   "VALUES('".$phoneSender."', '".$phoneReceive."', '".$senderName."', '".$message."', '".$messageKey."', '".$messageHash."', '".$status."')"	;
		$this->database->query($queryString);

		if($this->database->result) {

		}
		else {

		}
	}
}
?>