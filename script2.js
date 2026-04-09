/*----------------------Edit recipe--------------------*/
document.addEventListener("DOMContentLoaded", function () {
  const ingredientBtn = document.getElementById("editAddIngredientBtn");
  const ingredientsContainer = document.getElementById("editIngredientsContainer");

  const instructionBtn = document.getElementById("editAddInstructionBtn");
  const instructionsContainer = document.getElementById("editInstructionsContainer");

  // Add new ingredient row
  if (ingredientBtn && ingredientsContainer) {
    ingredientBtn.addEventListener("click", function () {
      const row = document.createElement("div");
      row.className = "edit-row edit-ingredient-row";

      row.innerHTML = `
        <input type="text" name="ingredientNames[]" placeholder="Ingredient name" required>
        <input type="text" name="ingredientQuantities[]" placeholder="Quantity" required>
      `;

      ingredientsContainer.appendChild(row);
    });
  }

  // Add new instruction row
  if (instructionBtn && instructionsContainer) {
    instructionBtn.addEventListener("click", function () {
      const currentSteps = instructionsContainer.querySelectorAll('input[name="steps[]"]').length;
      const nextStepNumber = currentSteps + 1;

      const row = document.createElement("div");
      row.className = "edit-row";

      row.innerHTML = `
        <input type="text" name="steps[]" placeholder="Step ${nextStepNumber}" required>
        <input type="hidden" name="stepOrders[]" value="${nextStepNumber}">
      `;

      instructionsContainer.appendChild(row);
    });
  }
});
