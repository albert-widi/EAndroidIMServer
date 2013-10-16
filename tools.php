<?php
Class Tools {
    public function getFixedFriendList($string) {
        $phoneNumber = explode(";", $string);
        $fixedList = "";
        
        if(count($phoneNumber) > 0) {
            $replace = array(" ", "-");
            $replacer = array("", "");
            foreach($phoneNumber as $key => $value) {
                $stringChecker = str_replace($replace, $replacer, $value);
                $stringChecker = substr($stringChecker, 0, 2);
                
                if($stringChecker = "08") {
                    $stringLength = strlen($value);
                    $fixedPhoneNumber = "'+62".substr($value, 1, $stringLength)."'";
                    $fixedList .= $fixedPhoneNumber.","; 
                }
                else {
                    $fixedList .= $value.",";
                }
            }
        }
        else {
            return false;
        }
        
        /*$content = $fixedList;
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/listlog.txt","wb");
        fwrite($fp,$content);
        fclose($fp);*/
        
        return substr($fixedList, 0, -1);
    }
}
?>