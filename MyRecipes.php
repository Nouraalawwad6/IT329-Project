<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("user_auth.php");
include("db.php");

$userID = $_SESSION["userID"];

// جلب وصفات المستخدم الحالي
$stmt = $conn->prepare("
    SELECT r.id, r.name, r.description, r.photoFileName, r.videoFilePath
    FROM Recipe r
    WHERE r.userID = ?
    ORDER BY r.id DESC
");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Recipes</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body class="mr-page-bg">

<header class="wf-header">
  <div class="wf-header-inner">
    <div class="logo-box">
      <img src="images/logo.png" alt="Logo" id="Logo"> 
    </div>
  </div>
</header>

<div class="wf-breadcrumb-bar">
  <div class="wf-breadcrumb-inner">
    <ol class="wf-breadcrumbs">
      <li><a href="Userpage.php">User Page</a></li>
      <li class="separator">/</li>
      <li class="current" aria-current="page">My Recipes</li>
    </ol>
  </div>
</div>

<main class="mr-container">
  <section class="mr-card">
    <div class="mr-card-header">
      <h2>My Recipes</h2>
      <a class="mr-btn" href="AddRecipe.php">Add New Recipe</a>
    </div>

    <div class="mr-table-wrap">
      <?php if ($result->num_rows > 0): ?>
        <table class="mr-table">
          <thead>
            <tr>
              <th>Recipe</th>
              <th>Ingredients</th>
              <th>Instructions</th>
              <th>Video</th>
              <th>Number of Likes</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($recipe = $result->fetch_assoc()): ?>
              <tr>
                <!-- Recipe -->
                <td>
                  <div class="recipe-cell">
                    <img
                      class="thumb"
                      src="images/<?php echo htmlspecialchars($recipe['photoFileName']); ?>"
                      alt="<?php echo htmlspecialchars($recipe['name']); ?>"
                      style="width:80px; height:80px; object-fit:cover; border-radius:10px;"
                    >
                    <div>
                      <a class="link" href="ViewRecipe.php?id=<?php echo $recipe['id']; ?>">
                        <?php echo htmlspecialchars($recipe['name']); ?>
                      </a>
                    </div>
                  </div>
                </td>

                <!-- Ingredients -->
                <td>
                  <ul class="mini-list">
                    <?php
                    $ingStmt = $conn->prepare("
                        SELECT ingredientName, ingredientQuantity
                        FROM Ingredients
                        WHERE recipeID = ?
                    ");
                    $ingStmt->bind_param("i", $recipe["id"]);
                    $ingStmt->execute();
                    $ingResult = $ingStmt->get_result();

                    if ($ingResult->num_rows > 0) {
                        while ($ing = $ingResult->fetch_assoc()) {
                            echo "<li>" .
                                htmlspecialchars($ing["ingredientName"]) .
                                " - " .
                                htmlspecialchars($ing["ingredientQuantity"]) .
                                "</li>";
                        }
                    } else {
                        echo "<li>No ingredients</li>";
                    }
                    ?>
                  </ul>
                </td>

                <!-- Instructions -->
                <td>
                  <ol class="mini-list">
                    <?php
                    $insStmt = $conn->prepare("
                        SELECT step
                        FROM Instructions
                        WHERE recipeID = ?
                        ORDER BY stepOrder ASC
                    ");
                    $insStmt->bind_param("i", $recipe["id"]);
                    $insStmt->execute();
                    $insResult = $insStmt->get_result();

                    if ($insResult->num_rows > 0) {
                        while ($ins = $insResult->fetch_assoc()) {
                            echo "<li>" . htmlspecialchars($ins["step"]) . "</li>";
                        }
                    } else {
                        echo "<li>No instructions</li>";
                    }
                    ?>
                  </ol>
                </td>

                <!-- Video -->
                <td>
                  <?php if (!empty($recipe["videoFilePath"])): ?>
                    <?php if (filter_var($recipe["videoFilePath"], FILTER_VALIDATE_URL)): ?>
                      <a class="link" href="<?php echo htmlspecialchars($recipe["videoFilePath"]); ?>" target="_blank">
                        Video link
                      </a>
                    <?php else: ?>
                      <a class="link" href="<?php echo htmlspecialchars($recipe["videoFilePath"]); ?>" target="_blank">
                        View video
                      </a>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="muted">No video for recipe</span>
                  <?php endif; ?>
                </td>

                <!-- Likes -->
                <td>
                  <?php
                  $likeStmt = $conn->prepare("
                      SELECT COUNT(*) AS totalLikes
                      FROM Likes
                      WHERE recipeID = ?
                  ");
                  $likeStmt->bind_param("i", $recipe["id"]);
                  $likeStmt->execute();
                  $likeResult = $likeStmt->get_result();
                  $likeRow = $likeResult->fetch_assoc();
                  echo $likeRow["totalLikes"];
                  ?>
                </td>

                <!-- Edit -->
                <td>
                  <a class="link" href="EditRecipe.php?id=<?php echo $recipe['id']; ?>">Edit</a>
                </td>

                <!-- Delete -->
                <td>
                  <a class="link danger" href="delete_recipe.php?id=<?php echo $recipe['id']; ?>"
                     onclick="return confirm('Are you sure you want to delete this recipe?');">
                    Delete
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="text-align:center; padding:20px;">You have not added any recipes yet.</p>
      <?php endif; ?>
    </div>
  </section>
</main>

</body>
</html>