<?php
include 'mysqlDB.php';

Class UserHandler {
    private $database;
    
    function __construct() {
        $this->database = new MysqlDB();
    }
    
    public function registerUser($phoneNumber, $gcmId, $publicKey) {
        $queryString = "INSERT INTO users(phone_number, gcm_id, public_key) ". 
                       "VALUES('".$phoneNumber."', '".$gcmId."', '".$publickKey."')";
        
        $this->database->query($queryString);
        if($this->database->result) {
            return "REG_SUCCESS";
        }
        else {
            return "REG_ERROR";
        }
    }
    
    public function getUserFriends($friendListString) {
        $queryString = "SELECT * FROM users WHERE phone_number IN(".$friendListString.")";
        
        $this->database->query($queryString);      
        if($this->database->result) {
            $friendList = "";
            while($row = $this->database->result->fetch_array(MYSQLI_ASSOC)) {
                $friendList .= $row['phone_number'].",";
                $friendList .= $row['gcm_id'].",";
                $friendList .= $row['public_key'].";";
            }
            $friendList = substr($friendList, 0, -1);
            
            return $friendList;
        }
        else {
            //echo $queryString;
            return "FRIEND_SEARCH_FAILED";
        }
    }
    
    public function sendMessage() {
        
    }
}
?>