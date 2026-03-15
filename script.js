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

// =========================
// MY RECIPES 
// =========================
const tbody = document.querySelector("#myRecipesTable tbody");

if (tbody) {
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
    a.href = `ViewRecipe.html?id=${recipe.id}`;
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
    a.href = `EditRecipe.html?id=${recipe.id}`;
    a.textContent = "Edit";
    td.appendChild(a);
    return td;
  }

  function createDeleteCell(recipe) {
    const td = document.createElement("td");
    const a = document.createElement("a");
    a.className = "link danger";
    a.href = "MyRecipes.html"; // not functional in phase 1
    a.textContent = "Delete";
    td.appendChild(a);
    return td;
  }

  function renderTable() {
    tbody.innerHTML = "";

    if (typeof MY_RECIPES === "undefined") {
      console.error("MY_RECIPES is not defined. تأكدي recipesData.js ينقرأ قبل script.js");
      return;
    }

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
}

// ==========================
// ADD RECIPE 
// ==========================
const addIngredientBtn = document.getElementById("addIngredientBtn");
const ingredientsContainer = document.getElementById("ingredientsContainer");

if (addIngredientBtn && ingredientsContainer) {
  addIngredientBtn.addEventListener("click", () => {
    const div = document.createElement("div");

    div.className = "ar-row";

    div.innerHTML = `
      <input type="text" placeholder="Ingredient name" required>
      <input type="text" placeholder="Quantity" required>
    `;
    ingredientsContainer.appendChild(div);
  });
}

let stepCount = 1;
const addInstructionBtn = document.getElementById("addInstructionBtn");
const instructionsContainer = document.getElementById("instructionsContainer");

if (addInstructionBtn && instructionsContainer) {
  addInstructionBtn.addEventListener("click", () => {
    stepCount++;

    const input = document.createElement("input");
    input.type = "text";
    input.placeholder = `Step ${stepCount}`;
    input.required = true;

    instructionsContainer.appendChild(input);
  });
}

const addRecipeForm = document.getElementById("addRecipeForm");
if (addRecipeForm) {
  addRecipeForm.addEventListener("submit", (e) => {
    e.preventDefault();
    window.location.href = "MyRecipes.html";
  });
}
// ==========================
// LOGIN / SIGNUP ERROR MESSAGES
// ==========================
const params = new URLSearchParams(window.location.search);
const error = params.get("error");

// login message
const loginMessage = document.getElementById("loginMessage");
if (loginMessage) {
  if (error === "invalid") {
    loginMessage.textContent = "Invalid email or password.";
    loginMessage.style.display = "block";
  } else if (error === "blocked") {
    loginMessage.textContent = "Your account has been blocked.";
    loginMessage.style.display = "block";
  }
}

// signup message
const signupMessage = document.getElementById("signupError");
if (signupMessage && error === "email_exists") {
  signupMessage.textContent = "Email already registered. ";
  signupMessage.style.display = "block";
}