<?php

$host = "localhost";
$username = "root";
$password = "root";
$database = "winter_flavors";
$port = 8889;

$conn = new mysqli($host, $username, $password, $database ,$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
