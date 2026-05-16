<?php
include("user_auth.php");
include("db.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: AddRecipe.php");
    exit();
}
if (!isset($_POST["recipeName"], $_POST["categoryID"], $_POST["description"])) {
    die("Upload is too large or form data was not received. Please choose a smaller video.");
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
/* ---------- Insert recipe first without files ---------- */
$stmt = $conn->prepare("INSERT INTO Recipe (userID, categoryID, name, description, photoFileName, videoFilePath) VALUES (?, ?, ?, ?, '', '')");
$stmt->bind_param("iiss", $userID, $categoryID, $recipeName, $description);
$stmt->execute();

$recipeID = $conn->insert_id;

/* ---------- Upload photo using recipe ID ---------- */
if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
    $photoExt = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);

    $photoFileName = "recipe_" . $recipeID . "_photo." . $photoExt;

    move_uploaded_file($_FILES["photo"]["tmp_name"], "images/" . $photoFileName);
}

/* ---------- Upload video OR save URL using recipe ID ---------- */
if (isset($_FILES["videoFile"]) && $_FILES["videoFile"]["error"] == 0) {
    $videoExt = pathinfo($_FILES["videoFile"]["name"], PATHINFO_EXTENSION);

    $videoFileName = "recipe_" . $recipeID . "_video." . $videoExt;

    move_uploaded_file($_FILES["videoFile"]["tmp_name"], "videos/" . $videoFileName);

    $videoFilePath = "videos/" . $videoFileName;

} elseif (!empty($videoUrl)) {
    $videoFilePath = $videoUrl;
}

/* ---------- Update recipe with file names ---------- */
$updateStmt = $conn->prepare("UPDATE Recipe SET photoFileName = ?, videoFilePath = ? WHERE id = ?");
$updateStmt->bind_param("ssi", $photoFileName, $videoFilePath, $recipeID);
$updateStmt->execute();

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
