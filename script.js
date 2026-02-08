/* Filter recipes based on selected category */
function filterRecipes() {

  // Get selected category from dropdown
  const selected = document.getElementById("categoryFilter").value;

  // Get all recipe rows from the table
  const rows = document.querySelectorAll("#recipesTable tbody tr");

  // Loop through each row
  rows.forEach(row => {

    // Get category text from the 5th column
    const category = row.cells[4].innerText.trim().toLowerCase();

    // Show row if category matches, otherwise hide it
    row.style.display =
      selected === "all" || category === selected.toLowerCase()
        ? ""
        : "none";
  });
}

/* Handle sign up form and save profile image */
const signupForm = document.getElementById("signupForm"); // Sign up form
const imageInput = document.getElementById("imageInput"); // Profile image input

// Check if sign up form exists on the page
if (signupForm) {

  // Listen for form submission
  signupForm.addEventListener("submit", function (e) {

    // Prevent default form submission
    e.preventDefault();

    // Check if user selected an image
    if (imageInput && imageInput.files.length > 0) {

      // Get selected image file
      const file = imageInput.files[0];

      // Create FileReader to read the image
      const reader = new FileReader();

      // When image is fully read
      reader.onload = function () {

        // Save image in localStorage
        localStorage.setItem("profileImage", reader.result);

        // Redirect to user page
        window.location.href = "Userpage.html";
      };

      // Read image as  URL
      reader.readAsDataURL(file);

    } else {

      // Remove stored image if no image was uploaded
      localStorage.removeItem("profileImage");

      // Redirect to user page
      window.location.href = "Userpage.html";
    }
  });
}

/* Load profile image on user page */
window.addEventListener("load", function () {

  // Get profile image element
  const profileImg = document.getElementById("profileImage");

  // Stop if profile image does not exist
  if (!profileImg) return;

  // Get saved profile image from localStorage
  const savedImage = localStorage.getItem("profileImage");

  // Use saved image or default image
  profileImg.src = savedImage
    ? savedImage
    : "./images/comments.png";
});
