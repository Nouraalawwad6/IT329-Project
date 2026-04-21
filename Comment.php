
<?php
include("user_auth.php");
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get comment info from form
    $recipeID = $_POST['recipeID']; 
    $comment = trim($_POST["comment"]);
    $userID = $_SESSION['userID'];

    // Check if comment is empty then do not allow
        if(trim($comment) === ""){
        header("Location: viewRecipe.php?id=$recipeID");
        exit();
        }
 
       // Insert comment into database with current userID[from SESSION] and recipeID[from Query String URL in 10a]
        mysqli_query($conn,
        "INSERT INTO Comment (recipeID, userID, comment, date)
        VALUES ('$recipeID', '$_SESSION[userID]', '$comment', NOW())"
        );

    // Redirect to refresh the page and display the new comment
        header("Location: viewRecipe.php?id=$recipeID");
        exit();
    
}
?>
        
    