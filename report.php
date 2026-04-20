<?php
include("user_auth.php");
include("db.php");

// --- Task 10-d: Report
$recipeID = $_GET['id'];
$userID = $_SESSION['userID'];

// Link user and recipe in Report table
mysqli_query($conn,
"INSERT INTO Report (userID, recipeID)
VALUES ('$userID', '$recipeID')"
);

// Redirect back to viewRecipe 
header("Location: viewRecipe.php?id=$recipeID");
exit();
?>