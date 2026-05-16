<?php
include("db.php");

$category = $_POST['category'];

if($category == ""){
    
    $sql = "SELECT Recipe.*, User.firstName,
    RecipeCategory.categoryName,
    COUNT(Likes.recipeID) AS likes
    FROM Recipe
    JOIN User ON Recipe.userID = User.id
    JOIN RecipeCategory ON Recipe.categoryID = RecipeCategory.id
    LEFT JOIN Likes ON Recipe.id = Likes.recipeID
    GROUP BY Recipe.id";

} else {

    $sql = "SELECT Recipe.*, User.firstName,
    RecipeCategory.categoryName,
    COUNT(Likes.recipeID) AS likes
    FROM Recipe
    JOIN User ON Recipe.userID = User.id
    JOIN RecipeCategory ON Recipe.categoryID = RecipeCategory.id
    LEFT JOIN Likes ON Recipe.id = Likes.recipeID
    WHERE RecipeCategory.categoryName='$category'
    GROUP BY Recipe.id";
}

$result = mysqli_query($conn, $sql);

$recipes = array();

while($row = mysqli_fetch_assoc($result)){
    $recipes[] = $row;
}

echo json_encode($recipes);
?>