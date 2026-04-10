<?php
include("db.php");

$reportID = $_POST['reportID'];
$action = $_POST['action'];

$get = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT recipeID FROM Report WHERE id='$reportID'"
));

$recipeID = $get['recipeID'];

// get user of recipe
$user = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT userID FROM Recipe WHERE id='$recipeID'"
));

$userID = $user['userID'];

if($action == "block"){

    mysqli_query($conn, "DELETE FROM Recipe WHERE id='$recipeID'");
    mysqli_query($conn, "INSERT INTO BlockedUser SELECT * FROM User WHERE id='$userID'");
    mysqli_query($conn, "DELETE FROM User WHERE id='$userID'");
}

// delete report
mysqli_query($conn, "DELETE FROM Report WHERE id='$reportID'");

header("Location: Admin.php");
?>