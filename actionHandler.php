<?php
Class ActionHandler {
	private $response;

	function __construct($action, $jsonData) {
		if(isset($action)) {
		    switch($action) {
		        case 'checkreg':
		            $phoneNumber = $json->{'phonenumber'};
		               
		            $this->response = $userHandler->checkRegister($phoneNumber);
		            break;
		        
		        case 'register':
		            $phoneNumber = $json->{'phonenumber'};
		            $userName = $json->{'username'};
		            $gcmId = $json->{'gcmid'};
		            $publicKey = $json->{'publickey'};
		        
		            $this->response = $userHandler->registerUser($phoneNumber, $userName, $gcmId, $publicKey);            
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
		        
		            $this->response = $userHandler->getUserFriends($friendList);
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
		            
		            $this->response = $userHandler->setTester($id, $tester);
		            break;
			}
		}		
	}

	public function getActionResponse() {
		return $this->response;
	}
}
?>