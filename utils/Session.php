<?php
class Session{
    public static function setUserId($userId){
        $_SESSION['user_id'] = $userId;
    }
    public static function setUsername($username){
        $_SESSION['username'] = $username;
    }
    public static function setAuthToken($token){
        $_SESSION['auth'] = $token;
    }
    public static function setRole($role){
        $_SESSION['role'] = $role;
    }
}
?>