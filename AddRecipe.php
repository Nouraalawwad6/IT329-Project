<?php
include("user_auth.php");
include("db.php");

$categories = [];
$result = $conn->query("SELECT id, categoryName FROM RecipeCategory ORDER BY categoryName");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Recipe</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="ar-page-bg">

<header class="wf-header">
  <div class="wf-header-inner">
    <div class="logo-box">
      <img src="images/logo.png" alt="Logo" id="Logo"> 
    </div>
  </div>
</header>

<main class="ar-page">
  <section class="ar-hero">
    <h1 class="ar-title">Add New Recipe</h1>
    <p class="ar-subtitle">
      Fill in the details below. Use the buttons to add more ingredients and instruction steps.
    </p>
  </section>

  <section class="ar-card">
    <form id="addRecipeForm" class="ar-form" action="insert_recipe.php" method="POST" enctype="multipart/form-data">

      <div class="ar-grid-2">
        <div class="ar-field">
          <label for="recipeName">Recipe Name</label>
          <input id="recipeName" name="recipeName" type="text" required>
        </div>

        <div class="ar-field">
          <label for="categoryID">Category</label>
          <select id="categoryID" name="categoryID" required>
            <option value="">Select</option>
            <?php foreach ($categories as $category): ?>
              <option value="<?php echo $category['id']; ?>">
                <?php echo htmlspecialchars($category['categoryName']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="ar-field">
        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>
      </div>

      <div class="ar-field">
        <label for="photo">Upload Recipe Photo</label>
        <input id="photo" name="photo" type="file" accept="image/*" required>
      </div>

      <div class="ar-section-title">
        <h3>Ingredients</h3>
        <button type="button" id="addIngredientBtn" class="ar-btn ar-btn-ghost">+ Add ingredient</button>
      </div>

      <div id="ingredientsContainer" class="ar-stack">
        <div class="ar-row">
          <input type="text" name="ingredientName[]" placeholder="Ingredient name" required>
          <input type="text" name="ingredientQuantity[]" placeholder="Quantity" required>
        </div>
      </div>

      <div class="ar-section-title">
        <h3>Instructions</h3>
        <button type="button" id="addInstructionBtn" class="ar-btn ar-btn-ghost">+ Add step</button>
      </div>

      <div id="instructionsContainer" class="ar-stack">
        <input type="text" name="instructions[]" placeholder="Step 1" required>
      </div>

      <div class="ar-section-title">
        <h3>Video (Optional)</h3>
      </div>

      <div class="ar-grid-2">
        <div class="ar-field">
          <label for="videoFile">Upload Video</label>
          <input id="videoFile" name="videoFile" type="file" accept="video/*">
        </div>

        <div class="ar-field">
          <label for="videoUrl">Video URL</label>
          <input id="videoUrl" name="videoUrl" type="url" placeholder="https://...">
        </div>
      </div>

      <div class="ar-actions">
        <a class="ar-btn ar-btn-outline" href="MyRecipes.php">Cancel</a>
        <button type="submit" class="ar-btn ar-btn-primary">Add Recipe</button>
      </div>

    </form>
  </section>
</main>

<script src="script.js"></script>
</body>
</html>