<?php
session_start();
function __checkSecurity($arr){
    if(isset($_SESSION['secToken']) && isset($_SESSION['session_id']) && $arr['secToken'] == $_SESSION['secToken'] && $_SESSION['session_id'] == session_id()){
        return true;
    }
    return false;
}
?>