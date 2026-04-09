<?php
include("auth.php");
include("db.php");

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: MyRecipes.php");
    exit();
}

// --- Collect and sanitize inputs ---
$recipeID    = isset($_POST['recipeID']) && is_numeric($_POST['recipeID']) ? (int)$_POST['recipeID'] : 0;
$recipeName  = trim($_POST['recipeName'] ?? '');
$categoryID  = isset($_POST['categoryID']) && is_numeric($_POST['categoryID']) ? (int)$_POST['categoryID'] : 0;
$description = trim($_POST['description'] ?? '');
$videoUrl    = trim($_POST['videoUrl'] ?? '');
$currentPhoto = trim($_POST['currentPhoto'] ?? '');

if ($recipeID === 0 || $recipeName === '' || $categoryID === 0 || $description === '') {
    header("Location: MyRecipes.php");
    exit();
}

// --- Handle photo upload ---
// Default: keep the old photo
$photoFileName = $currentPhoto;

if (isset($_FILES['newPhoto']) && $_FILES['newPhoto']['error'] === UPLOAD_ERR_OK) {
    $uploadedFile = $_FILES['newPhoto'];
    $extension    = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
    $allowed      = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($extension, $allowed)) {
        // Build a unique filename using the recipe ID
        $newFileName = 'recipe_' . $recipeID . '_' . time() . '.' . $extension;
        $destination = 'images/' . $newFileName;

        if (move_uploaded_file($uploadedFile['tmp_name'], $destination)) {
            // Delete old photo from images/ folder if it exists and is different
            if (!empty($currentPhoto) && $currentPhoto !== $newFileName && file_exists('images/' . $currentPhoto)) {
                unlink('images/' . $currentPhoto);
            }
            $photoFileName = $newFileName;
        }
    }
}

// --- Update Recipe table ---
$updateStmt = $conn->prepare("
    UPDATE Recipe
    SET name = ?, categoryID = ?, description = ?, photoFileName = ?, videoFilePath = ?
    WHERE id = ?
");
$updateStmt->bind_param("sisssi", $recipeName, $categoryID, $description, $photoFileName, $videoUrl, $recipeID);
$updateStmt->execute();

// --- Replace Ingredients ---
// Delete old ones, then insert new ones
$deleteIng = $conn->prepare("DELETE FROM Ingredients WHERE recipeID = ?");
$deleteIng->bind_param("i", $recipeID);
$deleteIng->execute();

$ingNames = $_POST['ingredientNames'] ?? [];
$ingQtys  = $_POST['ingredientQuantities'] ?? [];

$ingInsert = $conn->prepare("INSERT INTO Ingredients (recipeID, ingredientName, ingredientQuantity) VALUES (?, ?, ?)");

for ($i = 0; $i < count($ingNames); $i++) {
    $name = trim($ingNames[$i]);
    $qty  = trim($ingQtys[$i] ?? '');
    if ($name !== '') {
        $ingInsert->bind_param("iss", $recipeID, $name, $qty);
        $ingInsert->execute();
    }
}

// --- Replace Instructions ---
$deleteIns = $conn->prepare("DELETE FROM Instructions WHERE recipeID = ?");
$deleteIns->bind_param("i", $recipeID);
$deleteIns->execute();

$steps      = $_POST['steps'] ?? [];
$stepOrders = $_POST['stepOrders'] ?? [];

$insInsert = $conn->prepare("INSERT INTO Instructions (recipeID, step, stepOrder) VALUES (?, ?, ?)");

for ($i = 0; $i < count($steps); $i++) {
    $step      = trim($steps[$i]);
    $stepOrder = isset($stepOrders[$i]) && is_numeric($stepOrders[$i]) ? (int)$stepOrders[$i] : ($i + 1);
    if ($step !== '') {
        $insInsert->bind_param("isi", $recipeID, $step, $stepOrder);
        $insInsert->execute();
    }
}

$conn->close();

// --- 9b: Redirect to My Recipes page ---
header("Location: MyRecipes.php");
exit();
?>