<?php
include("auth.php");
include("db.php");

$recipeID = $_GET['id'];
$userID = $_SESSION['userID'];

mysqli_query($conn,
"INSERT INTO Report (userID, recipeID)
VALUES ('$userID', '$recipeID')"
);

header("Location: viewRecipe.php?id=$recipeID");
exit();
?>