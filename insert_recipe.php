<?php
include("user_auth.php");
include("db.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: AddRecipe.php");
    exit();
}

$userID = $_SESSION["userID"];

$recipeName = trim($_POST["recipeName"]);
$categoryID = intval($_POST["categoryID"]);
$description = trim($_POST["description"]);
$videoUrl = trim($_POST["videoUrl"]);

$ingredientNames = $_POST["ingredientName"] ?? [];
$ingredientQuantities = $_POST["ingredientQuantity"] ?? [];
$instructions = $_POST["instructions"] ?? [];

$photoFileName = "";
$videoFilePath = "";

/* ---------- Upload photo ---------- */
if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
    $photoExt = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
    $photoFileName = "recipe_" . time() . "_" . rand(1000, 9999) . "." . $photoExt;
    move_uploaded_file($_FILES["photo"]["tmp_name"], "images/" . $photoFileName);
}

/* ---------- Upload video OR save URL ---------- */
if (isset($_FILES["videoFile"]) && $_FILES["videoFile"]["error"] == 0) {
    $videoExt = pathinfo($_FILES["videoFile"]["name"], PATHINFO_EXTENSION);
    $videoFileName = "video_" . time() . "_" . rand(1000, 9999) . "." . $videoExt;
    move_uploaded_file($_FILES["videoFile"]["tmp_name"], "videos/" . $videoFileName);
    $videoFilePath = "videos/" . $videoFileName;
} elseif (!empty($videoUrl)) {
    $videoFilePath = $videoUrl;
}

/* ---------- Insert recipe ---------- */
$stmt = $conn->prepare("INSERT INTO Recipe (userID, categoryID, name, description, photoFileName, videoFilePath) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissss", $userID, $categoryID, $recipeName, $description, $photoFileName, $videoFilePath);
$stmt->execute();

$recipeID = $stmt->insert_id;

/* ---------- Insert ingredients ---------- */
$ingredientStmt = $conn->prepare("INSERT INTO Ingredients (recipeID, ingredientName, ingredientQuantity) VALUES (?, ?, ?)");

for ($i = 0; $i < count($ingredientNames); $i++) {
    $ingredientName = trim($ingredientNames[$i]);
    $ingredientQuantity = trim($ingredientQuantities[$i]);

    if ($ingredientName !== "" && $ingredientQuantity !== "") {
        $ingredientStmt->bind_param("iss", $recipeID, $ingredientName, $ingredientQuantity);
        $ingredientStmt->execute();
    }
}

/* ---------- Insert instructions ---------- */
$instructionStmt = $conn->prepare("INSERT INTO Instructions (recipeID, step, stepOrder) VALUES (?, ?, ?)");

for ($i = 0; $i < count($instructions); $i++) {
    $step = trim($instructions[$i]);
    $stepOrder = $i + 1;

    if ($step !== "") {
        $instructionStmt->bind_param("isi", $recipeID, $step, $stepOrder);
        $instructionStmt->execute();
    }
}

header("Location: MyRecipes.php");
exit();
?>