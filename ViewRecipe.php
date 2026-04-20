<?php
include("user_auth.php");
include("db.php");

// --- 10a: Check recipe ID from query string ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: Userpage.php");
    exit();
}

$recipeID = (int)$_GET['id'];

// --- 10b: Retrieve all recipe info and its creator ---
$stmt = $conn->prepare("
    SELECT r.id, r.name, r.description, r.photoFileName, r.videoFilePath,
           rc.categoryName,
           u.firstName, u.lastName, u.photoFileName AS userPhoto
    FROM Recipe r
    JOIN RecipeCategory rc ON r.categoryID = rc.id
    JOIN User u ON r.userID = u.id
    WHERE r.id = ?
");
$stmt->bind_param("i", $recipeID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: Userpage.php");
    exit();
}

$recipe = $result->fetch_assoc();

// Fetch ingredients
$ingStmt = $conn->prepare("SELECT ingredientName, ingredientQuantity FROM Ingredients WHERE recipeID = ?");
$ingStmt->bind_param("i", $recipeID);
$ingStmt->execute();
$ingredients = $ingStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch instructions
$insStmt = $conn->prepare("SELECT step FROM Instructions WHERE recipeID = ? ORDER BY stepOrder ASC");
$insStmt->bind_param("i", $recipeID);
$insStmt->execute();
$instructions = $insStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch comments
$comStmt = $conn->prepare("
    SELECT c.comment, c.date, u.firstName, u.photoFileName AS userPhoto
    FROM Comment c
    LEFT JOIN User u ON c.userID = u.id
    WHERE c.recipeID = ?
    ORDER BY c.date DESC
");
$comStmt->bind_param("i", $recipeID);
$comStmt->execute();
$comments = $comStmt->get_result()->fetch_all(MYSQLI_ASSOC);

// --- Task 10-c: Handle new comment submission
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Get comment text from form
        $comment = trim($_POST["comment"]);
        
    // Check if comment is empty then do not allow
        if(trim($comment) === ""){
        header("Location: viewRecipe.php?id=$recipeID");
        exit();
    }
    
    // Insert comment into database with current userID[from SESSION] and recipeID[from Query String URL in 10a]
        mysqli_query($conn,
        "INSERT INTO Comment (recipeID, userID, comment, date)
        VALUES ('$recipeID', '$_SESSION[userID]', '$comment', NOW())"
        );
        
    // Redirect to refresh the page and display the new comment
        header("Location: viewRecipe.php?id=$recipeID");
        exit();
    }

// --- Task 10-d Check if user already interacted with the recipe
    // check if logged-in user has already liked this recipe
    $likeCheck = mysqli_query($conn, "SELECT * FROM Likes WHERE userID='$_SESSION[userID]' AND recipeID='$recipeID'");
    $hasLiked = mysqli_num_rows($likeCheck) > 0;

    // check it's already in the user's favorites
    $favCheck = mysqli_query($conn, "SELECT * FROM Favourites WHERE userID='$_SESSION[userID]' AND recipeID='$recipeID'");
    $hasFav = mysqli_num_rows($favCheck) > 0;

    // check if the user has already reported this recipe
    $repCheck = mysqli_query($conn, "SELECT * FROM Report WHERE userID='$_SESSION[userID]' AND recipeID='$recipeID'");
    $hasRep = mysqli_num_rows($repCheck) > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Winter Flavors | View Recipe</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>

<body class="vr-body">

<!-- ===== HEADER ===== -->
<header class="wf-header">
  <div class="wf-header-inner">
    <div class="logo-box">
      <img src="images/logo.png" alt="Logo" id="Logo">
    </div>
  </div>
</header>

<!-- ===== BREADCRUMB ===== -->
<div class="wf-breadcrumb-bar">
  <div class="wf-breadcrumb-inner">
    <ol class="wf-breadcrumbs">
      <li><a href="Userpage.php">User Page</a></li>
      <li class="separator">/</li>
      <li class="current" aria-current="page">View Recipe</li>
    </ol>
  </div>
</div>

<main class="vr-page">
  <article class="vr-card">

    <header class="vr-top">
      <div></div>
      <div class="vr-actions">
          
        <!--Favourite button -->  
      <?php
      if($hasFav){
      ?>
      <button class="vr-btn" disabled>Favourite</button>
      <?php } else { ?>
      <a class="vr-btn" href="favourite.php?id=<?php echo $recipeID; ?>"><i class="fa-regular fa-heart"></i>Favourite</a>
      <?php } ?>
        
       <!--like button -->
      <?php
      if($hasLiked){
      ?>
      <button class="vr-btn" disabled>Like</button>
      <?php } else { ?>
      <a class="vr-btn" href="like.php?id=<?php echo $recipeID; ?>"><i class="fa-regular fa-heart"></i>Like</a>
      <?php } ?>
      
       <!--Report button --> 
      <?php
      if($hasRep){
      ?>
      <button class="vr-btn" disabled>Report</button>
      <?php } else { ?>
      <a class="vr-btn vr-btn-outline" href="report.php?id=<?php echo $recipeID; ?>"><i class="fa-regular fa-flag"></i>Report</a>
      <?php } ?>
       
      </div>
    </header>

    <!-- Title -->
    <h1 class="vr-title"><?php echo htmlspecialchars($recipe['name']); ?></h1>

    <!-- Image -->
    <div class="vr-media">
      <img
        class="vr-image"
        src="images/<?php echo htmlspecialchars($recipe['photoFileName']); ?>"
        alt="<?php echo htmlspecialchars($recipe['name']); ?>"
      />
    </div>

    <!-- Creator -->
    <section class="vr-section">
      <h3 class="vr-h">Recipe Creator</h3>
      <div class="vr-creator">
        <img src="images/<?php echo htmlspecialchars($recipe['userPhoto'] ?? 'profile.png'); ?>"
             alt="<?php echo htmlspecialchars($recipe['firstName']); ?>">
        <span><?php echo htmlspecialchars($recipe['firstName'] . ' ' . $recipe['lastName']); ?></span>
      </div>
    </section>

    <!-- Category -->
    <section class="vr-section">
      <h3 class="vr-h">Category</h3>
      <div class="vr-field"><?php echo htmlspecialchars($recipe['categoryName']); ?></div>
    </section>

    <!-- Description -->
    <section class="vr-section">
      <h3 class="vr-h">Description</h3>
      <div class="vr-field"><?php echo htmlspecialchars($recipe['description']); ?></div>
    </section>

    <!-- Ingredients -->
    <section class="vr-section">
      <h3 class="vr-h">Ingredients</h3>
      <ul class="vr-list">
        <?php if (!empty($ingredients)): ?>
          <?php foreach ($ingredients as $ing): ?>
            <li><?php echo htmlspecialchars($ing['ingredientQuantity'] . ' ' . $ing['ingredientName']); ?></li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No ingredients listed.</li>
        <?php endif; ?>
      </ul>
    </section>

    <!-- Instructions -->
    <section class="vr-section">
      <h3 class="vr-h">Instructions</h3>
      <ol class="vr-list">
        <?php if (!empty($instructions)): ?>
          <?php foreach ($instructions as $ins): ?>
            <li><?php echo htmlspecialchars($ins['step']); ?></li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No instructions listed.</li>
        <?php endif; ?>
      </ol>
    </section>

    <!-- Video -->
    <section class="vr-section">
      <h3 class="vr-h">Video</h3>
      <?php if (!empty($recipe['videoFilePath'])): ?>
        <a class="vr-link"
           href="<?php echo htmlspecialchars($recipe['videoFilePath']); ?>"
           target="_blank" rel="noopener noreferrer">
          Watch video
        </a>
      <?php else: ?>
        <p class="muted">No video available for this recipe.</p>
      <?php endif; ?>
    </section>

    <!-- Comments -->
    <section class="vr-section">
      <h3 class="vr-h">Comments</h3>

      <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $comment): ?>
          <div class="vr-comment">
            <img
              class="vr-comment-avatar"
              src="images/<?php echo htmlspecialchars($comment['userPhoto'] ?? 'profile.png'); ?>"
              alt="<?php echo htmlspecialchars($comment['firstName']); ?>"
            />
            <div class="vr-comment-content">
              <div class="vr-comment-top">
                <strong class="vr-comment-name"><?php echo htmlspecialchars($comment['firstName']); ?></strong>
                <span class="vr-comment-time"><?php echo date('M d, Y • h:i A', strtotime($comment['date'])); ?></span>
              </div>
              <p><?php echo htmlspecialchars($comment['comment']); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="muted">No comments yet. Be the first to comment!</p>
      <?php endif; ?>
       
    <form method="POST">
      <textarea class="vr-input" name="comment" placeholder="Add a comment..."></textarea>
      <button class="vr-post">Post Comment</button>
    </form>
    
    </section>

  </article>
</main>

</body>
</html>