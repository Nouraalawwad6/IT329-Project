<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if user is blocked
    $checkBlocked = $conn->prepare("SELECT id FROM BlockedUser WHERE emailAddress = ?");
    $checkBlocked->bind_param("s", $email);
    $checkBlocked->execute();
    $checkBlocked->store_result();

    if ($checkBlocked->num_rows > 0) {
        header("Location: login.html?error=blocked");
        exit();
    }

    // Check user credentials
    $stmt = $conn->prepare("SELECT id, userType, password FROM `User` WHERE emailAddress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["userID"] = $user["id"];
            $_SESSION["userType"] = $user["userType"];

            if ($user["userType"] == "admin") {
                header("Location: Admin.php");
            } else {
                header("Location: Userpage.php");
            }
            exit();

        } else {
            header("Location: login.html?error=invalid");
            exit();
        }

    } else {
        header("Location: login.html?error=invalid");
        exit();
    }
}
?>