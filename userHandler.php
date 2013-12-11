<?php
include 'mysqlDB.php';
include 'tools.php';
include 'messageHandler.php';

Class UserHandler {
    private $database;
    private $tools;
    private $jsonHandler;
    
    function __construct($json_handler) {
        $this->database = new MysqlDB();
        $this->tools = new Tools();
        $this->jsonHandler = $json_handler;
    }
    
    public function checkRegister($phoneNumber) {
        if(!isset($phoneNumber)) {
            return $this->jsonHandler->createSimpleResponseMessage(1, "INVALID_DATA");
        }
        
        $queryString = "SELECT name FROM users WHERE phone_number = '".$phoneNumber."'";
        
        $this->database->query($queryString);
        
        if($this->database->result) {
            if($this->database->result->num_rows > 0) {
                $row = $this->database->result->fetch_array(MYSQLI_ASSOC);
                
                $messageArray = array("status" => "exists",
                                      "name" => $row['name']);
                
                return $this->jsonHandler->encodeJson($messageArray);
            }
            else {
                $messageArray = array("status" => "noexists",
                                      "name" => "");
                
                return $this->jsonHandler->encodeJson($messageArray);
            }
        }
        else {
            return $this->jsonHandler->createSimpleResponseMessage(1, "CHECK_ERROR");
        }
    }
    
    //register user
    public function registerUser($phoneNumber, $userName, $gcmId, $publicKey) {
        if(!isset($phoneNumber) || !isset($userName) || !isset($gcmId) || !isset($publicKey)) {
            return $this->jsonHandler->createSimpleResponseMessage(1, "INVALID_DATA");
        }
        $queryString = "INSERT INTO users(phone_number, name, gcm_id, public_key) ". 
                       "VALUES('".$phoneNumber."', '".$userName."', '".$gcmId."', '".$publicKey."')";
            
        $this->database->query($queryString);
        if($this->database->result) {
            $userId = $this->database->insertId();
            $messageArray = array("error" => 0,
                                  "message" => "REG_SUCCESS",
                                  "userid" => $userId);

            return $this->jsonHandler->encodeJson($messageArray);
            //return $this->jsonHandler->createSimpleResponseMessage(0, "REG_SUCCESS");
        }
        else {
            $messageArray = array("error" => 1,
                                  "message" => "REG_ERROR",
                                  "userid" => "");

            return $this->jsonHandler->encodeJson($messageArray);
            //return $this->jsonHandler->createSimpleResponseMessage(1, "REG_ERROR");
        }
    }
    
    //update user
    public function updateUser($phoneNumber, $userName, $gcmId, $publicKey) {
        if(!isset($phoneNumber) || !isset($userName) || !isset($gcmId) || !isset($publicKey)) {
            return $this->jsonHandler->createSimpleResponseMessage(1, "INVALID_DATA");
        }

        $userId = 0;
        //security check
        $queryString = "SELECT id, phone_number FROM users WHERE phone_number = '".$phoneNumber."'";
        $this->database->query($queryString);
        if($this->database->result) {
            if($this->database->result->num_rows == 0) {
                $this->registerUser($phoneNumber, $userName, $gcmId, $publicKey);
            }

            $row = $this->database->result->fetch_array(MYSQLI_ASSOC);
            $userId = $row['id'];
        }
        
        $queryString = "UPDATE users SET name = '".$userName."', gcm_id = '".$gcmId."', public_key = '".$publicKey."'".
                       "WHERE phone_number = '".$phoneNumber."'";
        
        $this->database->query($queryString);
        if($this->database->result) {
            $messageArray = array("error" => 0,
                                  "message" => "REG_SUCCESS",
                                  "userid" => $userId);

            return $this->jsonHandler->encodeJson($messageArray);
            //return $this->jsonHandler->createSimpleResponseMessage(0, "REG_SUCCESS");
        }
        else {
            $messageArray = array("error" => 1,
                                  "message" => "REG_ERROR",
                                  "userid" => "");

            return $this->jsonHandler->encodeJson($messageArray);
            //return $this->jsonHandler->createSimpleResponseMessage(1, "REG_ERROR");
        }        
    }
    
    //get user friends
    public function getUserFriends($friendListString) {
        if(!isset($friendListString)) {
            return $this->jsonHandler->createSimpleResponseMessage(1, "INVALID_DATA");
        }
        
        $fixedFriendList = $this->tools->getFixedFriendList($friendListString);
        $queryString = "SELECT * FROM users WHERE phone_number IN(".$fixedFriendList.")";
        
        $this->database->query($queryString); 
        
        if($this->database->result) {
            $friendList = array();
            $friendList['error'] = 0;
            $friendList['message'] = "FRIEND_SEARCH_SUCCESS";
            
            while($row = $this->database->result->fetch_array(MYSQLI_ASSOC)) {
                $friendList[$row['phone_number']] = $row['id'].";";
                $friendList[$row['phone_number']] .= $row['name'].";";
                $friendList[$row['phone_number']] .= $row['gcm_id'].";";
                $friendList[$row['phone_number']] .= $row['public_key'];
            }
            
            return $this->jsonHandler->encodeJson($friendList);
        }
        else {
            //LOG PURPOSE
            $content = $queryString;
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/errorlog.txt","wb");
            fwrite($fp,$content);
            fclose($fp);
            
            //echo $queryString;
            return $this->jsonHandler->createSimpleResponseMessage(1, "FRIEND_SEARCH_FAILED");
        }
    }
    
    //send
    public function sendMessage($idSender, $idReceiver, $message, $messageKey, $messageHash) {
        $messageHandler = new MessageHandler($this->database);
        $response = $messageHandler->sendMessage($idSender, $idReceiver, $message, $messageKey, $messageHash);

        if($response == "INVALID_DATA") {
            return $this->jsonHandler->createSimpleResponseMessage(1, "INVALID_DATA");
        }
        else if($response == "SEND_FAILED") {
            return $this->jsonHandler->createSimpleResponseMessage(1, "SEND_FAILED");
        }
        else if($response == "SEND_SUCCESS") {
            return $this->jsonHandler->createSimpleResponseMessage(0, "SEND_SUCCESS");
        }
    }
}
?>