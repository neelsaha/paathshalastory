<?php
require_once("DbrLogin.php");
require_once("../utils/security.php");
error_reporting(0);
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $postbody = file_get_contents("php://input");
    $postbody = json_decode($postbody,true);
    if(!__checkSecurity($postbody['secToken'])){
        $aDbrLogin = new DbrLogin();
        $responseCode = $aDbrLogin->authenticate($postbody['username'],$postbody['password']);
        http_response_code($responseCode);
        if($responseCode == 401){
            $err = array('error' => array('Incorrect username or password.'));
            echo json_encode($err);
        } else if($responseCode == 200) {
            $msg = array('data' => array('role' => $_SESSION['role'],'secToken' => $_SESSION['secToken'], 'last_login' => $_SESSION['last_login']));
            echo json_encode($msg);
        }else if($responseCode == 500){
            $err = array('error' => array('Internal Error. Please contact helpdesk.'));
            echo json_encode($err);
        }
    }else{
        http_response_code(400);
    }
}else {
    http_response_code(405);
}
?>