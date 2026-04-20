<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['userID']) || $_SESSION['userType'] != 'user'){
    header("Location: login.html?error=YouMustLoginFirst");
    exit();
}
?>


