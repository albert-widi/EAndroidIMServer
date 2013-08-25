<?php
include 'userHandler.php';

$action = $_POST["action"];

$userHandler = new UserHandler();
$response = null;

if(isset($action)) {
    switch($action) {
        case 'register':
            $phoneNumber = $_POST["phonenumber"];
            $gcm_id = $_POST["gcmid"];
            $public_key = $_POST['publickey'];
        
            if(!isset($phoneNumber) || !isset($gcm_id)) {
                $response = "ERROR";
                break;
            }
        
            $response = $userHandler->registerUser($phoneNumber, $gcm_id, $public_key);            
            break;
        
        case 'getFriendList':
            $friendList = $_POST["friendlist"];
            
            if(!isset($friendList)) {
                $response = "ERROR";
                break;
            }
        
            $response = $userHandler->getUserFriends($friendList);
            break;
        
        case 'sendMessage':
            $gcm_sender = $_POST['gcmsender'];
            $gcm_receive = $_POST['gcmreceive'];
            $message = $_POST['message'];
            
        
            break;
    }
    
    echo $response;
}
?>