<?php
require_once("../utils/DB.php");
require_once("../utils/logger.php");
error_reporting(0);
$filename = "DbrLogin.php";
class DbrLogin
{
    private $db;
    public function __construct(){
        try {
            $this->db = new DB();
        } catch(Exception $e){
            #TO DO
            _LOG($GLOBALS['filename'],$e->getMessage());
        }
    }
    
    public function authenticate($iUsername, $iPassword){
        $aResponseCode = 401;
        $aEnPassword = $this->encrptPass($iPassword);
        try{
            $aQuery = "SELECT username,role,last_login from login WHERE username=:username AND password=:enPassword";
            if(!$this->db){
                throw new Exception("DB variable empty."); 
            }
            $aData = $this->db->selectQuery($aQuery,array(':username'=>$iUsername,':enPassword'=>$aEnPassword));
            if ($aData) {
                $key = True;
                $aToken = bin2hex(openssl_random_pseudo_bytes(16, $key));
                $this->setSession($aToken,$iUsername,$aData[0]['role'],$aData[0]['last_login']);
                $aResponseCode = 200;
            }
        } catch(Exception $e){
            #TO DO
            _LOG($GLOBALS['filename'],$e->getMessage());
            $aResponseCode = 500;
        }
        return $aResponseCode;
    }

    private function encrptPass($iPassword){
        return hash('sha256', $iPassword);
    }

    private function setSession($iToken,$iUsername,$iRole,$iLastLogin){
        #TO DO : for different roles
        $aQuery = "SELECT * FROM user where username=:username";
        $aData = $this->db->selectQuery($aQuery,array(':username'=>$iUsername));
        $_SESSION['role'] = $iRole;
        $_SESSION['username'] = $iUsername;
        $_SESSION['secToken'] = $iToken;
        $_SESSION['userId'] = $aData[0]['user_id'];
        $_SESSION['last_login'] = $iLastLogin;
        $_SESSION['session_id'] = session_id();
        $this->updateLastLogin();
        
    }

    private function updateLastLogin(){
        try{
            $query = "UPDATE login SET last_login = NOW() WHERE username =:username";
            if(!$this->db){
                throw new Exception("DB variable empty.");
            }
            if(!$this->db->query($query,array(':username'=>$_SESSION['username']))){
                throw new Exception("Last login update failed.");
            }
        }catch(Exception $e){
            _LOG($GLOBALS['filename'],$e->getMessage());
        }

    }
}

?>