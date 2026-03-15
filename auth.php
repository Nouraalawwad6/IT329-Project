<?php
session_start();

if (!isset($_SESSION["userID"]) || !isset($_SESSION["userType"])) {
    header("Location: login.html");
    exit();
}
?>