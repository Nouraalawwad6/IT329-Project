<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = trim($_POST["fname"]);
    $lastName = trim($_POST["lname"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $photoFileName = "profile.png";
    $uploadedPhoto = null;
    $userType = "user";

    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $uploadedPhoto = $_FILES["photo"];
    }

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

    // hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // insert user first with default photo
    $stmt = $conn->prepare("INSERT INTO `User` (userType, firstName, lastName, emailAddress, password, photoFileName) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $userType, $firstName, $lastName, $email, $hashedPassword, $photoFileName);

    if ($stmt->execute()) {

        $userID = $stmt->insert_id;

        // if user uploaded photo, rename it using user ID
        if ($uploadedPhoto != null) {

            $originalName = basename($uploadedPhoto["name"]);
            $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);

            $photoFileName = $userID . "_profile." . $fileExtension;

            move_uploaded_file(
                $uploadedPhoto["tmp_name"],
                "images/" . $photoFileName
            );

            $updatePhoto = $conn->prepare("UPDATE `User` SET photoFileName = ? WHERE id = ?");
            $updatePhoto->bind_param("si", $photoFileName, $userID);
            $updatePhoto->execute();
        }

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