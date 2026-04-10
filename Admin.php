<?php
include("admin_auth.php");// Ensure only admins can access this page
include("db.php");

$adminID = $_SESSION['userID'];

// Fetch admin's personal information
$admin = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT * FROM User WHERE id='$adminID'"
));

// Retrieve pending reports by joining Report, Recipe, and User tables
// [ This shows which recipe was reported and who created it ]
$reports = mysqli_query($conn,
"SELECT Report.id AS reportID,
Recipe.id AS recipeID,
Recipe.name,
User.firstName,
User.lastName,
User.photoFileName
FROM Report
JOIN Recipe ON Report.recipeID = Recipe.id
JOIN User ON Recipe.userID = User.id"
);

// Fetch the list of all users who have been blocked
$blocked = mysqli_query($conn,
"SELECT * FROM BlockedUser"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Winter Flavors | Admin</title>

  <link rel="stylesheet" href="style.css">
</head>

<body>
  <main >

<!-- ===== HEADER ===== -->
<header class="wf-header">
  <div class="wf-header-inner">
    <div class="logo-box">
      <img src="images/logo.png" alt="Logo" Id="Logo">
    </div>
     
      <h2 class="admin-welcome">
        Welcome To the Admin Panel 
        <span class="admin-name"> <?php echo $admin['firstName']." ".$admin['lastName']; ?></span>
      </h2>

<a href="logout.php" class="logout">Log-out</a>


  </div>
</header>

<section class="admin-page">

<section class="admin-P">
   
    </section>

    <!-- Admin Info -->
    <section class="admin-box">
      <h3>My Information</h3>
      <p><strong>Name:</strong> <?php echo $admin['firstName']." ".$admin['lastName']; ?></p>
      <p><strong>Email:</strong> <?php echo $admin['emailAddress']; ?></p>
    </section>

    <!-- Reports -->
    <section class="admin-box">
      <h3>Pending Recipe Reports</h3>

      <table class="admin-table">
        <thead>
          <tr>
            <th>Recipe Name</th>
            <th>Recipe Creator</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
            <?php while($r = mysqli_fetch_assoc($reports)){ ?>
          <tr>
            <td>    
              <a class="admin-link" href="viewRecipe.php?id=<?php echo $r['recipeID']; ?>">
             <?php echo $r['name']; ?>
              </a>
            </td>

            <td>
             <?php echo $r['firstName']." ".$r['lastName']; ?><br>

            <?php
            //Check if there is an image or usse defult image
            $img = !empty($r['photoFileName']) ? $r['photoFileName'] : "profile.png";
            ?>
            
             <img src="images/<?php echo $img; ?>" width="50" height="50" class="admin-img">
            </td>

            <td>
              <form method="POST" action="admin_action.php">
                  <input type="hidden" name="reportID" value="<?php echo $r['reportID']; ?>">
                
                  <input type="radio" name="action" value="block" required>
                  Block User<br>
                
                  <input type="radio" name="action" value="dismiss">
                  Dismiss <br>
                  
                <button class="admin-btn">Submit</button>
              </form>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </section>

    <!-- Blocked Users -->
    <section class="admin-box">
      <h3>Blocked Users</h3>

      <table class="admin-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
          </tr>
        </thead>

        <tbody>
           <?php while($b = mysqli_fetch_assoc($blocked)){ ?>
            
          <tr>
            <td><?php echo $b['firstName']." ".$b['lastName']; ?></td>
            <td><?php echo $b['emailAddress']; ?></td>
          </tr>
          <?php } ?>
          
        </tbody>
      </table>
    </section>
</section>
  </main>
</body>
</html>
