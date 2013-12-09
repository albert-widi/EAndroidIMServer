<?php
Class Tools {
    public function getFixedFriendList($string) {
        $phoneNumber = explode(";", $string);
        $fixedList = "";
        $content = "";

        if(count($phoneNumber) > 0) {
            $replace = array(" ", "-");
            $replacer = array("", "");
            foreach($phoneNumber as $key => $value) {
                $stringChecker = str_replace($replace, $replacer, $value);
                $stringFilter = substr($stringChecker, 0, 1);
                $stringChecker = substr($stringChecker, 0, 2);
                
                if($stringFilter == "0" || $stringFilter == "+") {
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
        }
        else {
            $content = "kosong";
            return false;
        }
        
        //$fixedList = substr($fixedList, 0, -1);
        //echo "Fixed friend : ".$fixedList."<br>";
        $content = $fixedList;
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/listlog.txt","wb");
        fwrite($fp,$content);
        fclose($fp);
        
        return substr($fixedList, 0, -1);
    }
}
?>