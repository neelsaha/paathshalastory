<?php
date_default_timezone_set('Asia/Kolkata');
function _LOG($filename,$str){
    $logFile = '../logs/LOG_'.date("Y-m-d").'.txt';
    /*if(!file_exists($filename)){
        $fp = fopen($filename)
    }*/
    $fp = fopen($logFile,'a+');
    if($fp){
        $token = "noToken";
        $username = "noUsername";
        if(isset($_SESSION['token']))
            $token = $_SESSION['secToken'];
        if(isset($_SESSION['username']))
            $username = $_SESSION['username'];
        $logStr = "ERROR = ".date("Y-m-d h:i:s:u")." = token: ".$token." = username: ".$username." = msg: ".$str." = filename: ".$filename;
        fwrite($fp,  "\r\n" . $logStr);
        fclose($fp);
    }
}
?>