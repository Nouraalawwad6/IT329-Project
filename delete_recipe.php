<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("user_auth.php");
include("db.php");

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: MyRecipes.php");
    exit();
}

$recipeID = (int) $_GET["id"];
$userID = $_SESSION["userID"];

// 1. Get file names BEFORE deleting the recipe
$stmt = $conn->prepare("SELECT photoFileName, videoFilePath FROM Recipe WHERE id = ? AND userID = ?");
$stmt->bind_param("ii", $recipeID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: MyRecipes.php");
    exit();
}

$recipe = $result->fetch_assoc();
$photoFileName = $recipe["photoFileName"];
$videoFilePath = $recipe["videoFilePath"];

// 2. Delete local photo file
if (!empty($photoFileName) && $photoFileName !== "default.png" && $photoFileName !== "profile.png") {
    $photoPath = __DIR__ . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . $photoFileName;

    if (file_exists($photoPath)) {
        unlink($photoPath);
    }
}

// 3. Delete local video file if not URL
if (!empty($videoFilePath) && !filter_var($videoFilePath, FILTER_VALIDATE_URL)) {
    $videoPath = __DIR__ . DIRECTORY_SEPARATOR . $videoFilePath;

    if (file_exists($videoPath)) {
        unlink($videoPath);
    }
}

// 4. Delete related data
$tables = ["Ingredients", "Instructions", "Comment", "Likes", "Favourites", "Report"];

foreach ($tables as $table) {
    $deleteStmt = $conn->prepare("DELETE FROM `$table` WHERE recipeID = ?");
    $deleteStmt->bind_param("i", $recipeID);
    $deleteStmt->execute();
}

// 5. Delete recipe itself
$deleteRecipe = $conn->prepare("DELETE FROM Recipe WHERE id = ? AND userID = ?");
$deleteRecipe->bind_param("ii", $recipeID, $userID);
$deleteRecipe->execute();

header("Location: MyRecipes.php");
exit();
?>