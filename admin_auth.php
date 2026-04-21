<?php
session_start();

if (!isset($_SESSION["userID"]) || !isset($_SESSION["userType"])) {
    header("Location: login.html?error=loginRequired");
    exit();
}

if ($_SESSION["userType"] != "admin") {
    header("Location: login.html?error=adminOnly");
    exit();
}
?>