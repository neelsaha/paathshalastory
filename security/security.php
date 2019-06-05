<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once("../utils/Regex.php");
require_once("../utils/Logger.php");
require_once("../utils/constants.php");
require_once("../login/Login.php");
require_once("../logout/Logout.php");
session_start();
$filename = "security.php";
function checkData($data,&$pureData){
    $regex = new Regex();
    foreach($data as $index => $value){
        if($index<1)
            continue;
        if($regex->matchAlphaNum($value)){
            array_push($pureData,$value);
        }else{
            _LOG($GLOBALS['filename'],"AlphaNum regex failed for string: ".$value);
            return false;
        }
    }
    return true;
}
function extractData(&$req,&$url){
    
    $pureData = array();
    $res = false;
    $url = (explode("/",$_GET['url']))[0];
    if($_SERVER['REQUEST_METHOD'] == "GET"){
        $req = explode("/",$_GET['url']);
        $res = checkData($data,$pureData);
    }else if($_SERVER['REQUEST_METHOD'] == "POST"){
        $req = json_decode(file_get_contents('php://input'));
        $res = checkData($data,$pureData);
    }
    return $res;
}

//declare
$loggedIn = false;
$url = "";
$token = "";
$requestData = "";


if(isset($_SERVER["HTTP_AUTHORIZATION"])){
    $token = str_replace("Bearer ", "", $_SERVER["HTTP_AUTHORIZATION"]);
    if(isset($_SESSION['secToken']) && $token == $_SESSION['secToken']){
        $loggedIn = true;
    }
}
echo $url;
if(!extractData($requestData,$url)){
    http_response_code(400);
}else if($loggedIn){
    $responsecode = 500;
    if($url == kLogoutUrl){
        $responsecode = Logout::signOut();
    }else if($url == kLoginUrl){
        if($_SERVER['REQUEST_METHOD'] == "POST")
            $responsecode = 200;
        else   
            $responsecode = 405;
    }else{
        $responsecode = 400;
    }
    http_response_code($responsecode);
}else if($url == kLoginUrl){
    $resCode = 500;
    if($_SERVER['REQUEST_METHOD'] != "POST"){
        $resCode = 405;
    }else{
        $response = array();
        $error = array();
        $login = new Login();
        $resCode = $login->execute($requestData,$response,$error);
        if($resCode == 200){
            echo json_encode($response);
        }else{
            echo json_encode($error);
        }
    }
    http_response_code($resCode);
}    
else
    http_response_code(401);
?>