<?php
include("db.php");
include("admin_auth.php"); // Ensure only admins can access this page

// Retrieve report ID and the chosen action from the Admin form
$reportID = $_POST['reportID'];
$action = $_POST['action'];

// Get the Recipe ID associated with this report
$get = mysqli_fetch_assoc(mysqli_query($conn, "SELECT recipeID FROM Report WHERE id='$reportID'"));
$recipeID = $get['recipeID'];

// Find the User ID who created this reported recipe
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT userID FROM Recipe WHERE id='$recipeID'"));
$userID = $user['userID'];

// Execute if the admin chose the "Block" action
if($action == "block"){
    // Get all recipe files before deletion
$files = mysqli_query($conn,
"SELECT photoFileName, videoFilePath 
FROM Recipe 
WHERE userID='$userID'"
);

while($file = mysqli_fetch_assoc($files)){

    // Delete image file
    if(!empty($file['photoFileName'])){

        $imagePath = "images/" . $file['photoFileName'];

        if(file_exists($imagePath)){
            unlink($imagePath);
        }
    }

        // Delete video file only if it is local upload
    if(!empty($file['videoFilePath'])){

        // Ignore external links
        if(strpos($file['videoFilePath'], 'http') === false){// I used strpos to check  inside the string if the video path contains "http"

            if(file_exists($file['videoFilePath'])){
                unlink($file['videoFilePath']);
            }
        }
    
    }
}
    // 1. Delete the recipe (associated data will be deleted via DB CASCADE)
mysqli_query($conn,"DELETE FROM Recipe WHERE userID='$userID'");    
    // 2. Fetch user details before deleting from User table to transfer them to BlockedUser
    $u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT firstName, lastName, emailAddress FROM User WHERE id='$userID'"));

    if(!$u){
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    // 3. Insert user info into the BlockedUser table
    mysqli_query($conn, "INSERT INTO BlockedUser (firstName, lastName, emailAddress) 
                         VALUES ('{$u['firstName']}', '{$u['lastName']}', '{$u['emailAddress']}')");    

    // 4. Permanently remove the user from the main User table
    mysqli_query($conn, "DELETE FROM User WHERE id='$userID'");
    
}

// Delete the report record in both cases (Block or Dismiss)
mysqli_query($conn, "DELETE FROM Report WHERE id='$reportID'");

// Return JSON response to the AJAX call
if($action == "block"){
    echo json_encode([
        "status" => "blocked",
        "firstName" => $u['firstName'],
        "lastName" => $u['lastName'],
        "email" => $u['emailAddress']
    ]);
} else {
    echo json_encode(["status" => "dismissed"]);
}
exit;
?>