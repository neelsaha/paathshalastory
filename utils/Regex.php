<?php
class Regex{
    private static $patterns;
    public function __construct(){}
    public static function init(){
        self::$patterns = json_decode(file_get_contents("../regex.json"),true);
    }
    public function matchAlphaNum($str){
        if(preg_match(self::$patterns['alphaNum'],$str)){
            return true;
        }else{
            return false;
        }
    }
}
Regex::init();
?>