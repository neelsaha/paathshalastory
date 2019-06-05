<?php
require_once("DbrLogin.php");
class Login{
    public function execute($iData,&$oResponse,&$oError){
        $aData = json_decode(json_encode($iData), true);
        $aResponseCode = 500;
        $aDbrLogin = new DbrLogin();
        $aResponseCode = $aDbrLogin->authenticate($aData['username'],$aData['password']);
        if($aResponseCode == 401){
            $oError = array('error' => array('Incorrect username or password.'));
        }else if($aResponseCode == 200) {
            $oResponse = array('data' => array('role' => $_SESSION['role'],'secToken' => $_SESSION['secToken'], 'last_login' => $_SESSION['last_login']));
        }else if($aResponseCode == 500){
            $oError = array('error' => array('Internal Error. Please contact helpdesk.'));
        }
        return $aResponseCode;
    }
}
?>