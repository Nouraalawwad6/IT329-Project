<?php
include("user_auth.php");
include("db.php");

if(isset($_GET['id'])){

    $recipe_id = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id = $_SESSION['userID'];

    $delete_sql = "DELETE FROM Favourites 
                   WHERE userID='$user_id' 
                   AND recipeID='$recipe_id'";

    mysqli_query($conn, $delete_sql);
}

header("Location: UserPage.php");
exit();
?>