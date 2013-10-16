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
               
            $response = $userHandler->checkRegister($phoneNumber);
            break;
        
        case 'register':
            $phoneNumber = $json->{'phonenumber'};
            $userName = $json->{'username'};
            $gcmId = $json->{'gcmid'};
            $publicKey = $json->{'publickey'};
        
            $response = $userHandler->registerUser($phoneNumber, $userName, $gcmId, $publicKey);            
            break;
        
        case 'updateuser':
            $phoneNumber = $json->{'phonenumber'};
            $userName = $json->{'username'};
            $gcmId = $json->{'gcmid'};
            $publicKey = $json->{'publickey'};
            
            $response = $userHandler->updateUser($phoneNumber, $userName, $gcmId, $publicKey);
            break;
        
        case 'getFriendList':
            $friendList = $json->{'friendlist'};
        
            $response = $userHandler->getUserFriends($friendList);
            break;
        
        case 'sendMessage':
            $senderID = $json->{'senderid'};
            $gcmSender = $json->{'gcmsender'};
            $gcmReceive = $json->{'gcmreceive'};
            $message = $json->{'message'};
            $messageHash = $json->{'messagehash'};
            
            $response = $userHandler->sendMessage($gcmSender, $gcmReceive, $message, $messageHash);
            break;
    }
    
    
    echo $response;
}
?>