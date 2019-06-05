<?php
class Logout{
    public static function signOut(){
        session_destroy();
        return 200;
    }
}
?>