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
            $idSender = $json->{'idsender'};
            $idReceiver = $json->{'idreceive'};
            $message = $json->{'message'};
            $messageKey = $json->{'messagekey'};
            $messageHash = $json->{'messagehash'};
            
            $response = $userHandler->sendMessage($idSender, $idReceiver, $message, $messageKey, $messageHash);
            break;

        case 'setTester':
            $id = $json->{'id'};
            $tester = $json->{'tester'};
            
            $response = $userHandler->setTester($id, $tester);
            break;

        case 'updateKey':
            $id = $json->{'id'};
            $publicKey = $json->{'publickey'};

            $response = $userHandler->updatePublicKey($id, $publicKey);
            break;
    }
    
    
    echo $response;
}
?>