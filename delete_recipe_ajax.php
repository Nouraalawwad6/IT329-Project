<?php
include("user_auth.php");
include("db.php");

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(false);
    exit();
}

if (!isset($_POST["recipeID"]) || empty($_POST["recipeID"])) {
    echo json_encode(false);
    exit();
}

$recipeID = (int) $_POST["recipeID"];
$userID = $_SESSION["userID"];

/* Get recipe files before deleting */
$stmt = $conn->prepare("SELECT photoFileName, videoFilePath FROM Recipe WHERE id = ? AND userID = ?");
$stmt->bind_param("ii", $recipeID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode(false);
    exit();
}

$recipe = $result->fetch_assoc();
$photoFileName = $recipe["photoFileName"];
$videoFilePath = $recipe["videoFilePath"];

/* Delete photo file from images folder */
if (!empty($photoFileName) && $photoFileName !== "default.png" && $photoFileName !== "profile.png") {
    $photoPath = __DIR__ . "/images/" . $photoFileName;

    if (file_exists($photoPath)) {
        unlink($photoPath);
    }
}

/* Delete video file if it is local */
if (!empty($videoFilePath) && !filter_var($videoFilePath, FILTER_VALIDATE_URL)) {
    $videoPath = __DIR__ . "/" . $videoFilePath;

    if (file_exists($videoPath)) {
        unlink($videoPath);
    }
}

/* Delete related data */
$tables = ["Ingredients", "Instructions", "Comment", "Likes", "Favourites", "Report"];

foreach ($tables as $table) {
    $deleteStmt = $conn->prepare("DELETE FROM `$table` WHERE recipeID = ?");
    $deleteStmt->bind_param("i", $recipeID);
    $deleteStmt->execute();
}

/* Delete recipe */
$deleteRecipe = $conn->prepare("DELETE FROM Recipe WHERE id = ? AND userID = ?");
$deleteRecipe->bind_param("ii", $recipeID, $userID);

if ($deleteRecipe->execute()) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}

exit();
?>