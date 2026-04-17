<?php

include("user_auth.php");
include("db.php");

$user_id = $_SESSION['userID'];

/* ===== USER INFO ===== */
$user_sql = "SELECT * FROM User WHERE id='$user_id'";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

/* ===== COUNT RECIPES ===== */
$count_sql = "SELECT COUNT(*) AS totalRecipes FROM Recipe WHERE userID='$user_id'";
$count_result = mysqli_query($conn,$count_sql);
$count = mysqli_fetch_assoc($count_result);

/* ===== TOTAL LIKES ===== */
$likes_sql = "SELECT COUNT(*) AS totalLikes
FROM Likes
JOIN Recipe ON Likes.recipeID = Recipe.id
WHERE Recipe.userID='$user_id'";
$likes_result = mysqli_query($conn,$likes_sql);
$likes = mysqli_fetch_assoc($likes_result);

/* ===== CATEGORIES ===== */
$cat_sql = "SELECT * FROM RecipeCategory";
$cat_result = mysqli_query($conn,$cat_sql);

/* ===== RECIPES ===== */
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['category'])){
    $cat = mysqli_real_escape_string($conn, $_POST['category']);

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
    $recipes_sql = "SELECT Recipe.*, User.firstName, User.photoFileName AS userPhoto,
    RecipeCategory.categoryName,
    COUNT(Likes.recipeID) AS likes
    FROM Recipe
    JOIN User ON Recipe.userID = User.id
    JOIN RecipeCategory ON Recipe.categoryID = RecipeCategory.id
    LEFT JOIN Likes ON Recipe.id = Likes.recipeID
    GROUP BY Recipe.id";
}

$recipes_result = mysqli_query($conn,$recipes_sql);

/* ===== FAVOURITES ===== */
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
<title>User Page</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<!-- ===== HEADER ===== -->
<header class="wf-header">
  <div class="wf-header-inner">

    <div class="logo-box">
      <img src="images/logo.png" id="Logo">
    </div>

    <div class="header-center">
      <h2 class="welcome-text">
        Welcome <span class="username"><?php echo $user['firstName']; ?></span>
      </h2>
    </div>

    <a href="logout.php" class="logout">Log-out</a>

  </div>
</header>

<main class="user-page">

<!-- ===== USER INFO ===== -->
<section class="box info-box">
  <div>
    <h3>My Information</h3>
    <p><strong>Name:</strong>
      <?php echo $user['firstName']." ".$user['lastName']; ?>
    </p>
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

<!-- Display profile image -->
<img id="profileImage" src="images/<?php echo $photo; ?>" class="profile-photo" alt="User Photo">
</section>

<!-- ===== SUMMARY ===== -->
<section class="box">
  <h3><a href="MyRecipes.php" class="link">My Recipes</a></h3>
  <p>Total Recipes: <?php echo $count['totalRecipes']; ?></p>
  <p>Total Likes: <?php echo $likes['totalLikes']; ?></p>
</section>

<!-- ===== ALL RECIPES ===== -->
<section class="box">
  <h3>All Available Recipes</h3>

  <!-- FILTER -->
  <form method="POST" class="filter">
    <select name="category">
      <option value="">All Categories</option>
      <?php while($c = mysqli_fetch_assoc($cat_result)){ ?>
        <option value="<?php echo $c['categoryName']; ?>">
          <?php echo $c['categoryName']; ?>
        </option>
      <?php } ?>
    </select>
    <button type="submit" class="filter-btn">Filter</button>
  </form>

<?php if(mysqli_num_rows($recipes_result) == 0){ ?>
  <p>No recipes found</p>
<?php } else { ?>

<table>
<thead>
<tr>
<th>Recipe Name</th>
<th>Photo</th>
<th>Creator</th>
<th>Likes</th>
<th>Category</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($recipes_result)){ ?>
<tr>

<td>
<a href="viewRecipe.php?id=<?php echo $row['id']; ?>" class="link">
<?php echo $row['name']; ?>
</a>
</td>

<td>
<img src="images/<?php echo $row['photoFileName']; ?>" class="recipe-photo">
</td>

<td>
<?php echo $row['firstName']; ?><br>
<img src="images/<?php echo !empty($row['userPhoto']) ? $row['userPhoto'] : 'comments.png'; ?>" class="profile-photo">
</td>

<td><?php echo $row['likes']; ?></td>
<td><?php echo $row['categoryName']; ?></td>

</tr>
<?php } ?>
</tbody>
</table>

<?php } ?>
</section>

<!-- ===== FAVOURITES ===== -->
<section class="box">
<h3>My Favourite Recipes ♥</h3>

<?php if(mysqli_num_rows($fav_result)==0){ ?>
<p>No favourite recipes</p>
<?php } else { ?>

<table>
<tr>
<th>Name</th>
<th>Photo</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($fav_result)){ ?>
<tr>

<td>
<a href="viewRecipe.php?id=<?php echo $row['id']; ?>" class="link">
<?php echo $row['name']; ?>
</a>
</td>

<td>
<img src="images/<?php echo $row['photoFileName']; ?>" class="recipe-photo">
</td>

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