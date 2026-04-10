<?php
include("auth.php");
include("db.php");

// --- Task 10-d: Likes
$recipeID = $_GET['id'];
$userID = $_SESSION['userID'];

// Link user and recipe in Likes table
mysqli_query($conn,
"INSERT INTO Likes (userID, recipeID)
VALUES ('$userID', '$recipeID')"
);

// Redirect back to viewRecipe 
header("Location: viewRecipe.php?id=$recipeID");
exit();
?>