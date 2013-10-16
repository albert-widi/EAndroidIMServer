<?php
include 'mysqlDB.php';
include 'tools.php';

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
        
        $queryString = "SELECT id, name FROM users WHERE phone_number = '".$phoneNumber."'";
        
        $this->database->query($queryString);
        
        if($this->database->result) {
            if($this->database->result->num_rows > 0) {
                $row = $this->database->result->fetch_array(MYSQLI_ASSOC);
                
                $messageArray = array("status" => "exists",
                                      "id" => $row['id'],
                                      "name" => $row['name']);
                
                return $this->jsonHandler->encodeJson($messageArray);
            }
            else {
                $messageArray = array("status" => "noexists",
                                      "id" => "0",
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
        $responseMessage = array();
        $queryString = "INSERT INTO users(phone_number, name, gcm_id, public_key) ". 
                       "VALUES('".$phoneNumber."', '".$userName."', '".$gcmId."', '".$publicKey."')";
            
        $this->database->query($queryString);
        if($this->database->result) {
            $id = $this->database->insertId();
            
            $responseMessage['error'] = 0;
            $responseMessage['message'] = "REG_SUCCESS";
            $responseMessage['userid'] = $id;
            
            return $this->jsonHandler->encodeJson($responseMessage);
            //return $this->jsonHandler->createSimpleResponseMessage(0, "REG_SUCCESS");
        }
        else {
            $responseMessage['error'] = 1;
            $responseMessage['message'] = "REG_ERROR";
            
            return $this->jsonHandler->encodeJson($responseMessage);
            //return $this->jsonHandler->createSimpleResponseMessage(1, "REG_ERROR");
        }
    }
    
    //update user
    public function updateUser($phoneNumber, $userName, $gcmId, $publicKey) {
        if(!isset($phoneNumber) || !isset($userName) || !isset($gcmId) || !isset($publicKey)) {
            return $this->jsonHandler->createSimpleResponseMessage(1, "INVALID_DATA");
        }
        
        $queryString = "UPDATE users SET name = '".$userName."', gcm_id = '".$gcmId."', public_key = '".$publicKey."'".
                       "WHERE phone_number = '".$phoneNumber."'";
        
        $this->database->query($queryString);
        if($this->database->result) {
            return $this->jsonHandler->createSimpleResponseMessage(0, "REG_SUCCESS");
        }
        else {
            return $this->jsonHandler->createSimpleResponseMessage(1, "REG_ERROR");
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
                $friendList[$row['phone_number']] = $row['name'].";";
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
    public function sendMessage($gcmSender, $gcmReceive, $message, $messageHash) {
        if(!isset($gcmSender) || !isset($gcmReceive) || !isset($message) || !isset($messageHash)) {
            return $this->jsonHandler->createSimpleResponseMessage(1, "INVALID_DATA");
        }
        
         // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
        $api_key = "AIzaSyAmxWvTVG-0OvAzV0m55Leip-EzaVq-Pts";
        
        
    }
}
?>