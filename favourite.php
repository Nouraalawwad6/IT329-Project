<?php
include("auth.php");
include("db.php");

// --- Task 10-d: Favourites
$recipeID = $_GET['id'];
$userID = $_SESSION['userID'];

// Link user and recipe in Favourites table
mysqli_query($conn,
"INSERT INTO Favourites (userID, recipeID)
VALUES ('$userID', '$recipeID')"
);

// Redirect back to viewRecipe 
header("Location: viewRecipe.php?id=$recipeID");
exit();
?>