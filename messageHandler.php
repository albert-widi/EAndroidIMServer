<?php
Class MessageHandler {
	private $database;

	function __construct($db) {
		$this->database = $db;
	}

	public function sendMessage($idSender, $idReceiver, $message, $messageKey, $messageHash) {
		$success = true;

		if(!isset($idSender) || !isset($idReceiver) || !isset($message) || !isset($messageHash)) {
            return "INVALID_DATA";
            //return $this->jsonHandler->createSimpleResponseMessage(1, "INVALID_DATA");
        }
        
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
        $api_key = "AIzaSyAL4humet5NGC_NqiHb11WA_1ojc_rdjI4";

        //lookup for user gcmid
        $queryString = "SELECT gcm_id FROM users WHERE id = '".$idReceiver."'";
        $this->database->query($queryString);
        if(!$this->database->result) {
           return $this->jsonHandler->createSimpleResponseMessage(1, "SEND_FAILED"); 
        }
        $row = $this->database->result->fetch_array(MYSQLI_ASSOC);
        $gcmId = $row['gcm_id'];
        
        //set variable to send
        $reg_id = array($gcmId);
        $fields = array('registration_ids' => $reg_id,
                        'data' => array("message" => $message, "key" => $messageKey, "hash" => $messageHash, "whosent" => $idSender));
        
        $headers = array('Authorization: key=' . $api_key,
                         'Content-Type: application/json');

        
        //Open connection
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
        }
              
        // Close connection
        curl_close($ch);

        //save to log
        $status;
        $return;
        if($success) {
        	$status = "SUCCESS";
            $return = "SEND_SUCCESS";
        }
        else {
        	$status = "FAILED";
            $return = "SEND_FAILED";
        }

        $this->saveMessagetoLog($idSender, $idReceiver, $message, $messageKey, $messageHash, $status);
        return $return;
	}

	private function saveMessagetoLog($idSender, $idReceiver, $message, $messageKey, $messageHash, $status) {
        //SENDER NAME
        $senderName = "";
        $queryString = "SELECT name FROM users WHERE id = ".$idSender;
        $this->database->query($queryString);
        if($this->database->result) {
            $row = $this->database->result->fetch_array(MYSQLI_ASSOC);
            $senderName = $row['name'];
        }

        //RECEIVER NAME
        $receiverName = "";
        $queryString = "SELECT name FROM users WHERE id = ".$idReceiver;
        $this->database->query($queryString);
        if($this->database->result) {
            $row = $this->database->result->fetch_array(MYSQLI_ASSOC);
            $receiverName = $row['name'];
        }

		$queryString = "INSERT INTO msg_log(id_sender, id_receiver, sender_name, receiver_name, message, message_key, message_hash, status) ".
					   "VALUES(".$idSender.", ".$idReceiver.", '".$senderName."', '".$receiverName."', '".$message."', '".$messageKey."', '".$messageHash."', '".$status."')"	;
		$this->database->query($queryString);

		if($this->database->result) {
            
		}
		else {
            
		}
	}
}
?>