<?php
Class JSONHandler {
    public function createSimpleResponseMessage($error, $message) {
        $array = array("error" => $error,
                       "message" => $message);
        
        return json_encode($array);
    }
    
    public function encodeJson($array) {
        return json_encode($array);
    }
}
?>