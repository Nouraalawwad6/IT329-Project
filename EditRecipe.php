<?php
include("auth.php");
include("db.php");

// --- 9a: Check recipe ID from query string ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: MyRecipes.php");
    exit();
}

$recipeID = (int)$_GET['id'];

// Fetch recipe basic info
$recipeStmt = $conn->prepare("
    SELECT r.id, r.name, r.description, r.photoFileName, r.videoFilePath, r.categoryID,
           rc.categoryName
    FROM Recipe r
    JOIN RecipeCategory rc ON r.categoryID = rc.id
    WHERE r.id = ?
");
$recipeStmt->bind_param("i", $recipeID);
$recipeStmt->execute();
$recipeResult = $recipeStmt->get_result();

if ($recipeResult->num_rows === 0) {
    header("Location: MyRecipes.php");
    exit();
}

$recipe = $recipeResult->fetch_assoc();

// Fetch ingredients
$ingStmt = $conn->prepare("SELECT ingredientName, ingredientQuantity FROM Ingredients WHERE recipeID = ?");
$ingStmt->bind_param("i", $recipeID);
$ingStmt->execute();
$ingredients = $ingStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch instructions ordered by stepOrder
$insStmt = $conn->prepare("SELECT step, stepOrder FROM Instructions WHERE recipeID = ? ORDER BY stepOrder ASC");
$insStmt->bind_param("i", $recipeID);
$insStmt->execute();
$instructions = $insStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch all categories for the dropdown
$categories = $conn->query("SELECT id, categoryName FROM RecipeCategory")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Recipe</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="edit-page-bg">

<!-- ===== HEADER ===== -->
<header class="wf-header">
  <div class="wf-header-inner">
    <div class="logo-box">
      <img src="images/logo.png" alt="Logo" id="Logo">
    </div>
  </div>
</header>

<main class="edit-page">
  <section class="edit-hero">
    <h1 class="edit-title">Edit Recipe</h1>
    <p class="edit-subtitle">
      Update the details below. Use the buttons to add more ingredients and instruction steps.
    </p>
  </section>

  <section class="edit-card">
    <!-- 9b: Form submits to updateRecipe.php -->
    <form id="editRecipeForm" class="edit-form" action="UpdateRecipe.php" method="POST" enctype="multipart/form-data">

      <!-- Hidden recipe ID -->
      <input type="hidden" name="recipeID" value="<?php echo $recipe['id']; ?>">
      <!-- Hidden current photo filename (to keep if no new photo uploaded) -->
      <input type="hidden" name="currentPhoto" value="<?php echo htmlspecialchars($recipe['photoFileName']); ?>">

      <div class="edit-grid-2">
        <div class="edit-field">
          <label for="editRecipeName">Recipe Name</label>
          <input id="editRecipeName" name="recipeName" type="text" required
                 value="<?php echo htmlspecialchars($recipe['name']); ?>">
        </div>

        <div class="edit-field">
          <label for="editCategory">Category</label>
          <select id="editCategory" name="categoryID" required>
            <option value="">Select</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?php echo $cat['id']; ?>"
                <?php echo ($cat['id'] == $recipe['categoryID']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cat['categoryName']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="edit-field">
        <label for="editDescription">Description</label>
        <textarea id="editDescription" name="description" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
      </div>

      <!-- Photo -->
      <div class="edit-grid-2">
        <div class="edit-field">
          <label>Current Photo</label>
          <div class="edit-current-box" id="currentPhotoBox">
            <img id="currentPhotoImg"
                 src="images/<?php echo htmlspecialchars($recipe['photoFileName']); ?>"
                 alt="Current recipe photo">
          </div>
        </div>
        <div class="edit-field">
          <label for="editPhoto">Upload New Recipe Photo (Optional)</label>
          <input id="editPhoto" name="newPhoto" type="file" accept="image/*">
        </div>
      </div>

      <!-- Ingredients -->
      <div class="edit-section-title">
        <h3>Ingredients</h3>
        <button type="button" id="editAddIngredientBtn" class="edit-btn edit-btn-ghost">+ Add ingredient</button>
      </div>

      <div id="editIngredientsContainer" class="edit-stack">
        <?php if (!empty($ingredients)): ?>
          <?php foreach ($ingredients as $ing): ?>
            <div class="edit-row edit-ingredient-row">
              <input type="text" name="ingredientNames[]" placeholder="Ingredient name" required
                     value="<?php echo htmlspecialchars($ing['ingredientName']); ?>">
              <input type="text" name="ingredientQuantities[]" placeholder="Quantity" required
                     value="<?php echo htmlspecialchars($ing['ingredientQuantity']); ?>">
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="edit-row edit-ingredient-row">
            <input type="text" name="ingredientNames[]" placeholder="Ingredient name" required>
            <input type="text" name="ingredientQuantities[]" placeholder="Quantity" required>
          </div>
        <?php endif; ?>
      </div>

      <!-- Instructions -->
      <div class="edit-section-title">
        <h3>Instructions</h3>
        <button type="button" id="editAddInstructionBtn" class="edit-btn edit-btn-ghost">+ Add step</button>
      </div>

      <div id="editInstructionsContainer" class="edit-stack">
        <?php if (!empty($instructions)): ?>
          <?php foreach ($instructions as $index => $ins): ?>
            <div class="edit-row">
              <input type="text" name="steps[]"
                     placeholder="Step <?php echo $index + 1; ?>" required
                     value="<?php echo htmlspecialchars($ins['step']); ?>">
              <input type="hidden" name="stepOrders[]" value="<?php echo $ins['stepOrder']; ?>">
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="edit-row">
            <input type="text" name="steps[]" placeholder="Step 1" required>
            <input type="hidden" name="stepOrders[]" value="1">
          </div>
        <?php endif; ?>
      </div>

      <!-- Video URL -->
      <div class="edit-section-title">
        <h3>Video (Optional)</h3>
      </div>

      <div class="edit-field">
        <label for="editVideoUrl">Video URL (Optional)</label>
        <input id="editVideoUrl" name="videoUrl" type="url" placeholder="https://..."
               value="<?php echo htmlspecialchars($recipe['videoFilePath'] ?? ''); ?>">
      </div>

      <div class="edit-actions">
        <a class="edit-btn edit-btn-outline" href="MyRecipes.php">Cancel</a>
        <button type="submit" class="edit-btn edit-btn-primary" id="editSubmitBtn">Update Recipe</button>
      </div>

    </form>
  </section>
</main>

<script src="script2.js"></script>
</body>
</html>