<?php
include 'userHandler.php';
include 'jsonHandler.php';

$data = $_POST['data'];
if(isset($data)) {
    $json = json_decode($data);
}

$jsonHandler = new JSONHandler();
$userHandler = new UserHandler($jsonHandler);

if(isset($json)) {
    $action = $json->{'action'};
}
$response = null;

if(isset($action)) {
    switch($action) {
        case 'checkreg':
            $phoneNumber = $json->{'phonenumber'};
        
            if(!isset($phoneNumber)) {
                $response = $jsonHandler->createMessage(1, "INVALID_DATA");
                break;
            }
               
            $response = $userHandler->checkRegister($phoneNumber);
            break;
        
        case 'register':
            $phoneNumber = $json->{'phonenumber'};
            $userName = $json->{'username'};
            $gcm_id = $json->{'gcmid'};
            $public_key = $json->{'publickey'};
        
            if(!isset($phoneNumber) || !isset($userName) || !isset($gcm_id) || !isset($public_key)) {
                //$response = "ERROR";
                $response = $jsonHandler->createSimpleResponseMessage(1, "INVALID_DATA");
                break;
            }
        
            $response = $userHandler->registerUser($phoneNumber, $userName, $gcm_id, $public_key);            
            break;
        
        case 'updateuser':
            $phoneNumber = $json->{'phonenumber'};
            $userName = $json->{'username'};
            $gcm_id = $json->{'gcmid'};
            $public_key = $json->{'publickey'};
            
            if(!isset($phoneNumber) || !isset($userName) || !isset($gcm_id) || !isset($public_key)) {
                //$response = "ERROR";
                $response = $jsonHandler->createSimpleResponseMessage(1, "INVALID_DATA");
                break;
            }
            
            $response = $userHandler->updateUser($phoneNumber, $userName, $gcm_id, $public_key);
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