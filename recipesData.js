// Fake placeholder data for Phase 1


const MY_RECIPES = [
  {
    id: 1,
    name: "Hot Chocolate",
    photo: "images/IMG_4461.png",
    ingredients: [
      { item: "Milk", qty: "1 cup" },
      { item: "Cocoa powder", qty: "2 tbsp" },
      { item: "Sugar", qty: "1 tbsp" }
    ],
    instructions: [
      "Heat the milk in a saucepan.",
      "Add cocoa powder and sugar.",
      "Stir well and serve warm."
    ],
    videoUrl: "https://www.youtube.com/watch?v=3nccl60iGXw&pp=ygUUaG90IGNob2NvbGF0ZSByZWNpcGU%3D",
    likes: 18
  },
  {
    id: 2,
    name: "Cinnamon Apple Pie",
    photo: "images/IMG_4462.png",
    ingredients: [
      { item: "Apples", qty: "2 cups" },
      { item: "Cinnamon", qty: "1 tsp" },
      { item: "Pie crust", qty: "1 base" }
    ],
    instructions: [
      "Slice apples and mix with cinnamon.",
      "Place in pie crust.",
      "Bake until golden."
    ],
    videoUrl: "https://www.youtube.com/watch?v=VFQsDAADPLM&pp=ygUSY2lubmFtb24gYXBwbGUgcGll",
    likes: 12
  },
  {
    id: 3,
    name: "Chicken Vegetable Soup",
    photo: "",
    ingredients: [
      { item: "Chicken broth", qty: "2 cups" },
      { item: "Mixed vegetables", qty: "1 cup" },
      { item: "Salt", qty: "to taste" }
    ],
    instructions: [
      "Boil the broth.",
      "Add vegetables.",
      "Simmer for 15 minutes and serve hot."
    ],
    videoUrl: "",
    likes: 20
  }
];
// Optional: a helper for default image (if missing file)
const DEFAULT_RECIPE_PHOTO = "images/IMG_4464.png";