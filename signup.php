<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = trim($_POST["fname"]);
    $lastName = trim($_POST["lname"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $photoFileName = "profile.png";
    $userType = "user";

    // check if email already exists in User table
    $checkUser = $conn->prepare("SELECT id FROM `User` WHERE emailAddress = ?");
    $checkUser->bind_param("s", $email);
    $checkUser->execute();
    $checkUser->store_result();

    // check if email exists in BlockedUser table
    $checkBlocked = $conn->prepare("SELECT id FROM BlockedUser WHERE emailAddress = ?");
    $checkBlocked->bind_param("s", $email);
    $checkBlocked->execute();
    $checkBlocked->store_result();

    if ($checkUser->num_rows > 0 || $checkBlocked->num_rows > 0) {
       header("Location: Signuppage.html?error=email_exists");
        exit();
    }

    // handle uploaded photo
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $photoFileName = basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], "images/" . $photoFileName);
    }

    // hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO `User` (userType, firstName, lastName, emailAddress, password, photoFileName) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $userType, $firstName, $lastName, $email, $hashedPassword, $photoFileName);

    if ($stmt->execute()) {
        $userID = $stmt->insert_id;

        $_SESSION["userID"] = $userID;
        $_SESSION["userType"] = $userType;

       header("Location: Userpage.php");
        exit();
    } else {
        echo "Insert error: " . $stmt->error;
        exit();
    }
}
?>
