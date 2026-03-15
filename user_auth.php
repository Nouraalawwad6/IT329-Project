<?php
session_start();

if (!isset($_SESSION["userID"]) || !isset($_SESSION["userType"])) {
    header("Location: login.html");
    exit();
}

if ($_SESSION["userType"] != "user") {
    header("Location: login.html");
    exit();
}
?>