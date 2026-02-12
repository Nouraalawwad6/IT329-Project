// Fake placeholder data for Phase 1

const MY_RECIPES = [
  {
    id: 1,
    name: "Hot Chocolate",
    photo: "images/hot.png",
    ingredients: [
      { item: "Milk", qty: "1.5 cup" },
      { item: "chocolate protein", qty: "1/2 scoop" },
      { item: "Honey or sweetener of choice", qty: "1 tbsp" },
      { item: "vanilla extract", qty: "1/4 teaspoon" },
      { item: "A pinch of sea salt", qty: "" },
    ],
    instructions: [
      "Heat the milk in a pan over medium heat until it begins to gently simmer.",
      "Whisk in the cocoa powder and protein powder until fully dissolved.",
      "Remove from heat.",
      "Stir in the vanilla extract and honey (or sweetener of choice).",
      "Froth the mixture or whisk by hand, then serve and enjoy.",
    ],
    videoUrl: "https://youtu.be/k2repFoV-Vg?si=djQ-Cnqw0_MrIQ0p",
    likes: 45
  },
  {
    id: 2,
    name: "Cinnamon Apple Pie",
    photo: "images/Cinnamon Apple Pie.png",
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
    videoUrl: "https://youtu.be/KBB_fD4krvc?si=Lm4MYXfCke4EJXk6",
    likes: 27
  },
  {
    id: 3,
    name: "Chicken Vegetable Soup",
    photo: "images/IMG_4463.png",
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
    likes: 30
  }
];

const DEFAULT_RECIPE_PHOTO = "images/IMG_4464.png";