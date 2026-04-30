<?php
include("db.php");
session_start();

$user_id = intval($_SESSION['userID']);
$recipe_id = intval($_POST['id']);

$sql = "DELETE FROM Favourites 
        WHERE userID='$user_id' 
        AND recipeID='$recipe_id'";

if(mysqli_query($conn, $sql)){
    echo "true";
} else {
    echo "false";
}
?>