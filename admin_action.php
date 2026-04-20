<?php
include("db.php");
include("admin_auth.php");// Ensure only admins can access this page

// --- Task 11-c: Admin Actions(Block or Dismiss Report)

// Retrieve report ID and the chosen action from the Admin form
$reportID = $_POST['reportID'];
$action = $_POST['action'];

// Get the  Recipe ID associated with this report
$get = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT recipeID FROM Report WHERE id='$reportID'"
));
$recipeID = $get['recipeID'];

// Find the User ID who created this reported recipe
$user = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT userID FROM Recipe WHERE id='$recipeID'"
));
$userID = $user['userID'];

// Execute if the admin chose the "Block" action
if($action == "block"){
    
// 1. Delete the recipe (associated data will be deleted via DB CASCADE)
    mysqli_query($conn, "DELETE FROM Recipe WHERE id='$recipeID'");
    
// 2. Transfer user data to the BlockedUser table
    // get user info first
    $u = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT firstName, lastName, emailAddress FROM User WHERE id='$userID'"
    ));

    // insert manually
    mysqli_query($conn,
    "INSERT INTO BlockedUser (firstName, lastName, emailAddress)
    VALUES ('{$u['firstName']}', '{$u['lastName']}', '{$u['emailAddress']}')"
    );    
// 3. Permanently remove the user from the main User table
    mysqli_query($conn, "DELETE FROM User WHERE id='$userID'");
}

// Delete report record in both cases (Block or Dismiss)
mysqli_query($conn, "DELETE FROM Report WHERE id='$reportID'");

// Return to the Admin Panel to see updated lists
header("Location: Admin.php");
?>