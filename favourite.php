<?php
include("user_auth.php");
include("db.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'false';
    exit();
}

$recipeID = isset($_POST['recipeID']) && is_numeric($_POST['recipeID']) ? (int)$_POST['recipeID'] : 0;
$userID   = (int)$_SESSION['userID'];

if ($recipeID === 0) {
    echo 'false';
    exit();
}

$stmt = $conn->prepare("INSERT INTO Favourites (userID, recipeID) VALUES (?, ?)");
$stmt->bind_param("ii", $userID, $recipeID);

if ($stmt->execute()) {
    echo 'true';
} else {
    echo 'false';
}