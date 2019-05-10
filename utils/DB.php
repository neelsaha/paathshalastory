<?php
require_once("../utils/logger.php");
error_reporting(0);
$filename = "DB.php";
class DB {
    private $conn;
    public function __construct(){
        $settings = json_decode(file_get_contents("../dbconfig.json"));
        $conn = new PDO('mysql:host='.$settings->host.';dbname='.$settings->dbname.';charset=utf8', $settings->username, $settings->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn = $conn;
    }

    public function selectQuery($query, $params = array()){
        try{
            $statement = $this->conn->prepare($query);
            $statement->execute($params);
            $data = $statement->fetchAll();
            return $data;
        } catch(Exception $e){
            _LOG($GLOBALS['filename'],$e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
    
    public function query($query,$params = array()){
        $res = false;
        try{
            $statement = $this->conn->prepare($query);
            if($statement->execute($params))
                $res = true;
        } catch(Exception $e){
            _LOG($GLOBALS['filename'],$e->getMessage());
            throw new Exception($e->getMessage());
        }
        return $res;
    }
}
?>