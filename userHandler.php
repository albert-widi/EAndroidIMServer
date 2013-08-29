<?php
include 'mysqlDB.php';

Class UserHandler {
    private $database;
    private $jsonHandler;
    
    function __construct($json_handler) {
        $this->database = new MysqlDB();
        $this->jsonHandler = $json_handler;
    }
    
    public function checkRegister($phoneNumber) {
        $queryString = "SELECT name FROM users WHERE phone_number = '".$phoneNumber."'";
        
        $this->database->query($queryString);
        
        if($this->database->result) {
            if($this->database->result->num_rows > 0) {
                $row = $this->database->result->fetch_array(MYSQLI_ASSOC);
                
                $messageArray = array("status" => "exists",
                                      "name" => $row['name']);
                
                return $this->jsonHandler->encodeJson($messageArray);
                //return "EXISTS";
            }
            else {
                $messageArray = array("status" => "noexists",
                                      "name" => "");
                
                return $this->jsonHandler->encodeJson($messageArray);
                //return "NOEXISTS";
            }
        }
        else {
            return $this->jsonHandler->createSimpleResponseMessage(1, "CHECK_ERROR");
        }
    }
    
    //register user
    public function registerUser($phoneNumber, $userName, $gcmId, $publicKey) {
        $queryString = "INSERT INTO users(phone_number, name, gcm_id, public_key) ". 
                       "VALUES('".$phoneNumber."', '".$userName."', '".$gcmId."', '".$publicKey."')";
            
        $this->database->query($queryString);
        if($this->database->result) {
            //return "REG_SUCCESS";
            return $this->jsonHandler->createSimpleResponseMessage(0, "REG_SUCCESS");
        }
        else {
            //return "REG_ERROR";
            return $this->jsonHandler->createSimpleResponseMessage(1, "REG_ERROR");
        }
    }
    
    //update user
    public function updateUser($phoneNumber, $userName, $gcmId, $publicKey) {
        $queryString = "UPDATE users SET name = '".$userName."', gcm_id = '".$gcm_id."', public_key = '".$publicKey."'".
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
        $queryString = "SELECT * FROM users WHERE phone_number IN(".$friendListString.")";
        
        $this->database->query($queryString);      
        if($this->database->result) {
            $friendList = array();
            $friendList['error'] = 0;
            while($row = $this->database->result->fetch_array(MYSQLI_ASSOC)) {
                $friendList[$row['phone_number']] = $row['gcm_id'];
                $friendList[$row['phone_number']] = $row['public_key'];
            }
            
            return $this->jsonHandler($friendList);
        }
        else {
            return $this->jsonHandler->createMessage(1, "FRIEND_SEARCH_FAILED");
        }
    }
    
    //send
    public function sendMessage() {
        
    }
}
?>