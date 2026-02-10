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
