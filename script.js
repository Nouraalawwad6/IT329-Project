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



/*----------------------MY RECIPES-------------------*/


const tbody = document.querySelector("#myRecipesTable tbody");

function createRecipeCell(recipe) {
  const td = document.createElement("td");

  const wrap = document.createElement("div");
  wrap.className = "recipe-cell";

  const img = document.createElement("img");
  img.className = "thumb";
  img.alt = recipe.name;
  img.src = recipe.photo || DEFAULT_RECIPE_PHOTO;
  img.onerror = () => { img.src = DEFAULT_RECIPE_PHOTO; };

  const info = document.createElement("div");

  const a = document.createElement("a");
  a.className = "link";
  a.href = `view-recipe.html?id=${recipe.id}`;  // placeholder
  a.textContent = recipe.name;

  info.appendChild(a);
  wrap.appendChild(img);
  wrap.appendChild(info);
  td.appendChild(wrap);

  return td;
}

function createIngredientsCell(ingredients) {
  const td = document.createElement("td");
  const ul = document.createElement("ul");
  ul.className = "mini-list";

  ingredients.forEach(x => {
    const li = document.createElement("li");
    li.textContent = `${x.item} - ${x.qty}`;
    ul.appendChild(li);
  });

  td.appendChild(ul);
  return td;
}

function createInstructionsCell(steps) {
  const td = document.createElement("td");
  const ol = document.createElement("ol");
  ol.className = "mini-list";

  steps.forEach(step => {
    const li = document.createElement("li");
    li.textContent = step;
    ol.appendChild(li);
  });

  td.appendChild(ol);
  return td;
}

function createVideoCell(url) {
  const td = document.createElement("td");
  if (!url) {
    td.textContent = "No video for recipe";
    td.className = "muted";
    return td;
  }
  const a = document.createElement("a");
  a.className = "link";
  a.href = url;
  a.target = "_blank";
  a.rel = "noopener noreferrer";
  a.textContent = "Video link";
  td.appendChild(a);
  return td;
}

function createTextCell(text) {
  const td = document.createElement("td");
  td.textContent = text;
  return td;
}

function createEditCell(recipe) {
  const td = document.createElement("td");
  const a = document.createElement("a");
  a.className = "link";
  a.href = `edit-recipe.html?id=${recipe.id}`; // placeholder
  a.textContent = "Edit";
  td.appendChild(a);
  return td;
}

function createDeleteCell(recipe) {
  const td = document.createElement("td");
  const a = document.createElement("a");
  a.className = "link danger";
  a.href = "my-recipes.html"; // not functional in phase 1
  a.textContent = "Delete";
  td.appendChild(a);
  return td;
}

function renderTable() {
  tbody.innerHTML = "";

  MY_RECIPES.forEach(recipe => {
    const tr = document.createElement("tr");

    tr.appendChild(createRecipeCell(recipe));
    tr.appendChild(createIngredientsCell(recipe.ingredients));
    tr.appendChild(createInstructionsCell(recipe.instructions));
    tr.appendChild(createVideoCell(recipe.videoUrl));
    tr.appendChild(createTextCell(String(recipe.likes)));
    tr.appendChild(createEditCell(recipe));
    tr.appendChild(createDeleteCell(recipe));

    tbody.appendChild(tr);
  });
}

renderTable();

/*----------------------Add recipe--------------------*/

// Add Ingredient
document.getElementById("addIngredientBtn").addEventListener("click", () => {
  const container = document.getElementById("ingredientsContainer");

  const div = document.createElement("div");
  div.className = "row";

  div.innerHTML = `
    <input type="text" placeholder="Ingredient name" required>
    <input type="text" placeholder="Quantity" required>
  `;

  container.appendChild(div);
});

// Add Instruction
let stepCount = 1;

document.getElementById("addInstructionBtn").addEventListener("click", () => {
  stepCount++;

  const container = document.getElementById("instructionsContainer");
  const input = document.createElement("input");

  input.type = "text";
  input.placeholder = `Step ${stepCount}`;
  input.required = true;

  container.appendChild(input);
});

// Submit form
document.getElementById("addRecipeForm").addEventListener("submit", (e) => {
  e.preventDefault();

  // Phase 1: no saving, just redirect
  window.location.href = "MyRecipes.html";
});
/*----------------------Edit recipe--------------------*/
// ===== Ingredients =====
const erAddIngBtn = document.getElementById("editAddIngredientBtn");
const erIngContainer = document.getElementById("editIngredientsContainer");

let erIngIndex = 1;

erAddIngBtn.addEventListener("click", () => {
  erIngIndex++;

  const erIngRow = document.createElement("div");
  erIngRow.className = "edit-row edit-ingredient-row";

  erIngRow.innerHTML = `
    <input type="text" placeholder="Ingredient name" required>
    <input type="text" placeholder="Quantity" required>
  `;

  erIngContainer.appendChild(erIngRow);
});


// ===== Instructions =====
const erAddStepBtn = document.getElementById("editAddInstructionBtn");
const erStepsContainer = document.getElementById("editInstructionsContainer");

let erStepIndex = 1;

erAddStepBtn.addEventListener("click", () => {
  erStepIndex++;

  const erStepInput = document.createElement("input");
  erStepInput.type = "text";
  erStepInput.placeholder = `Step ${erStepIndex}`;
  erStepInput.required = true;

  erStepsContainer.appendChild(erStepInput);
});


// ===== Form Submit =====
const erForm = document.getElementById("editRecipeForm");

erForm.addEventListener("submit", function (e) {
  e.preventDefault();
  window.location.href = "MyRecipes.html";
});
