<?php
function __checkSecurity($secToken){
    session_start();
    if(isset($_SESSION['secToken']) && isset($_SESSION['session_id']) && $secToken == $_SESSION['secToken'] && $_SESSION['session_id'] == session_id()){
        return true;
    }
    return false;
}
?>