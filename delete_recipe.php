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

// نتأكد أن الوصفة تخص المستخدم الحالي
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

// نحذف البيانات المرتبطة أولًا
$deleteIngredients = $conn->prepare("DELETE FROM Ingredients WHERE recipeID = ?");
$deleteIngredients->bind_param("i", $recipeID);
$deleteIngredients->execute();

$deleteInstructions = $conn->prepare("DELETE FROM Instructions WHERE recipeID = ?");
$deleteInstructions->bind_param("i", $recipeID);
$deleteInstructions->execute();

$deleteComments = $conn->prepare("DELETE FROM Comment WHERE recipeID = ?");
$deleteComments->bind_param("i", $recipeID);
$deleteComments->execute();

$deleteLikes = $conn->prepare("DELETE FROM Likes WHERE recipeID = ?");
$deleteLikes->bind_param("i", $recipeID);
$deleteLikes->execute();

$deleteFavourites = $conn->prepare("DELETE FROM Favourites WHERE recipeID = ?");
$deleteFavourites->bind_param("i", $recipeID);
$deleteFavourites->execute();

$deleteReports = $conn->prepare("DELETE FROM Report WHERE recipeID = ?");
$deleteReports->bind_param("i", $recipeID);
$deleteReports->execute();

// ثم نحذف الوصفة نفسها
$deleteRecipe = $conn->prepare("DELETE FROM Recipe WHERE id = ? AND userID = ?");
$deleteRecipe->bind_param("ii", $recipeID, $userID);

if ($deleteRecipe->execute()) {

    // حذف الصورة إذا كانت موجودة وليست الصورة الافتراضية
    if (!empty($photoFileName) && $photoFileName !== "default.png" && $photoFileName !== "profile.png") {
        $photoPath = "images/" . $photoFileName;
        if (file_exists($photoPath)) {
            unlink($photoPath);
        }
    }

    // حذف الفيديو إذا كان ملف محلي وليس رابط
    if (!empty($videoFilePath) && !filter_var($videoFilePath, FILTER_VALIDATE_URL)) {
        if (file_exists($videoFilePath)) {
            unlink($videoFilePath);
        }
    }
}

header("Location: MyRecipes.php");
exit();
?>