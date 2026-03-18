<?php
// Include authentication file (checks login + user type)
include("auth.php"); 

// Include database connection
include("db.php");

// Get logged-in user ID from session
$user_id = $_SESSION['userID']; 

// Get user information from database
$user_sql = "SELECT * FROM User WHERE id='$user_id'";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

// If user not found, stop execution
if(!$user){
    die("User not found.");
}

/* ================= COUNT USER RECIPES ================= */
// Count how many recipes this user has created
$count_sql = "SELECT COUNT(*) AS totalRecipes FROM Recipe WHERE userID='$user_id'";
$count_result = mysqli_query($conn,$count_sql);
$count = mysqli_fetch_assoc($count_result);

/* ================= TOTAL LIKES ================= */
// Count total likes for all recipes created by this user
$likes_sql = "SELECT COUNT(*) AS totalLikes
FROM Likes
JOIN Recipe ON Likes.recipeID = Recipe.id
WHERE Recipe.userID='$user_id'";
$likes_result = mysqli_query($conn,$likes_sql);
$likes = mysqli_fetch_assoc($likes_result);

/* ================= GET ALL CATEGORIES ================= */
// Retrieve all recipe categories for filter dropdown
$cat_sql = "SELECT * FROM RecipeCategory";
$cat_result = mysqli_query($conn,$cat_sql);

/* ================= GET RECIPES (GET / POST) ================= */

// If user selected a category (POST request)
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['category'])){

    // Prevent SQL injection
    $cat = mysqli_real_escape_string($conn, $_POST['category']);

    // Get recipes filtered by selected category
    $recipes_sql = "SELECT Recipe.*, User.firstName, User.photoFileName AS userPhoto,
    RecipeCategory.categoryName,
    COUNT(Likes.recipeID) AS likes

    FROM Recipe
    JOIN User ON Recipe.userID = User.id
    JOIN RecipeCategory ON Recipe.categoryID = RecipeCategory.id
    LEFT JOIN Likes ON Recipe.id = Likes.recipeID

    WHERE RecipeCategory.categoryName='$cat'
    GROUP BY Recipe.id";

}else{

    // If no filter → get all recipes
    $recipes_sql = "SELECT Recipe.*, User.firstName, User.photoFileName AS userPhoto,
    RecipeCategory.categoryName,
    COUNT(Likes.recipeID) AS likes

    FROM Recipe
    JOIN User ON Recipe.userID = User.id
    JOIN RecipeCategory ON Recipe.categoryID = RecipeCategory.id
    LEFT JOIN Likes ON Recipe.id = Likes.recipeID

    GROUP BY Recipe.id";
}

// Execute recipes query
$recipes_result = mysqli_query($conn,$recipes_sql);

/* ================= FAVOURITE RECIPES ================= */
// Get user's favourite recipes
$fav_sql = "SELECT Recipe.*
FROM Favourites
JOIN Recipe ON Favourites.recipeID = Recipe.id
WHERE Favourites.userID='$user_id'";

$fav_result = mysqli_query($conn,$fav_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Winter Flavors | User Page</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<header class="wf-header">
  <div class="wf-header-inner">

    <!-- Logo -->
    <div class="logo-box">
      <img src="images/logo.png" id="Logo">
    </div>

    <!-- Welcome message -->
    <div class="header-center">
      <h2 class="welcome-text">
        Welcome <span class="username"><?php echo $user['firstName']; ?></span>
      </h2>
    </div>

    <!-- Logout button -->
    <a href="logout.php" class="logout">Log-out</a>

  </div>
</header>

<main class="user-page">

<!-- ================= USER INFORMATION ================= -->
<section class="box info-box">
  <div>
    <h3>My Information</h3>

    <!-- Display full name -->
    <p><strong>Name:</strong>
      <?php echo $user['firstName']." ".$user['lastName']; ?>
    </p>

    <!-- Display email -->
    <p><strong>Email:</strong>
      <?php echo $user['emailAddress']; ?>
    </p>
  </div>

<?php
// Choose profile image (default if none)
$photo = (!empty($user['photoFileName']) && $user['photoFileName'] != 'profile.png') 
          ? $user['photoFileName'] 
          : 'comments.png';
?>

<!-- Display profile image -->
<img id="profileImage" src="images/<?php echo $photo; ?>" class="profile-photo" alt="User Photo">
</section>


<!-- ================= MY RECIPES SUMMARY ================= -->
<section class="box">
  <!-- Link to My Recipes page -->
  <h3><a href="MyRecipes.php" class="link">My Recipes</a></h3>

  <!-- Total recipes -->
  <p>Total Recipes: <?php echo $count['totalRecipes']; ?></p>

  <!-- Total likes -->
  <p>Total Likes: <?php echo $likes['totalLikes']; ?></p>
</section>


<!-- ================= ALL RECIPES ================= -->
<section class="box">
  <h3>All Available Recipes</h3>

<!-- Filter form -->
<form method="POST" class="filter">
  <select name="category">
    <option value="">All Categories</option>

    <?php 
    // Loop through categories
    mysqli_data_seek($cat_result, 0); 
    while($c = mysqli_fetch_assoc($cat_result)){ 
        $selected = (isset($_POST['category']) && $_POST['category'] == $c['categoryName']) ? "selected" : "";
    ?>
      <option value="<?php echo $c['categoryName']; ?>" <?php echo $selected; ?>>
        <?php echo $c['categoryName']; ?>
      </option>
    <?php } ?>
  </select>

  <button class="filter-btn">Filter</button>
</form>

<!-- If no recipes found -->
<?php if(mysqli_num_rows($recipes_result) == 0){ ?>

<p>No recipes found</p>

<?php } else { ?>

<!-- Recipes table -->
<table id="recipesTable">
<thead>
<tr>
<th>Recipe Name</th>
<th>Recipe Photo</th>
<th>Recipe Creator</th>
<th>Likes</th>
<th>Category</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($recipes_result)){ ?>

<tr>

<!-- Recipe name with link to view page -->
<td>
<a href="viewRecipe.php?id=<?php echo $row['id']; ?>" class="link">
<?php echo $row['name']; ?>
</a>
</td>

<!-- Recipe image -->
<td>
<img src="images/<?php echo $row['photoFileName']; ?>" class="recipe-photo">
</td>

<!-- Creator name + photo -->
<td>
<?php echo $row['firstName']; ?><br>

<?php
$profile = !empty($row['userPhoto']) ? $row['userPhoto'] : "comments.png";
?>

<img src="images/<?php echo $profile; ?>" class="profile-photo">
</td>

<!-- Likes count -->
<td><?php echo $row['likes']; ?></td>

<!-- Category -->
<td><?php echo $row['categoryName']; ?></td>

</tr>

<?php } ?>

</tbody>
</table>

<?php } ?>

</section>


<!-- ================= FAVOURITES ================= -->
<section class="box">
<h3>My Favourite Recipes ♥</h3>

<!-- If no favourites -->
<?php if(mysqli_num_rows($fav_result)==0){ ?>

<p>No favourite recipes</p>

<?php } else { ?>

<table>

<tr>
<th>Recipe Name</th>
<th>Recipe Photo</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($fav_result)){ ?>

<tr>

<!-- Recipe link -->
<td>
<a href="viewRecipe.php?id=<?php echo $row['id']; ?>" class="link">
<?php echo $row['name']; ?>
</a>
</td>

<!-- Recipe image -->
<td>
<img src="images/<?php echo $row['photoFileName']; ?>" class="recipe-photo">
</td>

<!-- Remove from favourites -->
<td>
<a href="removeFavourite.php?id=<?php echo $row['id']; ?>" class="link">
Remove
</a>
</td>

</tr>

<?php } ?>

</table>

<?php } ?>

</section>

</main>

</body>
</html>