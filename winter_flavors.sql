-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 15, 2026 at 05:39 AM
-- Server version: 8.0.44
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `winter_flavors`
--

-- --------------------------------------------------------

--
-- Table structure for table `BlockedUser`
--

CREATE TABLE `BlockedUser` (
  `id` int NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `BlockedUser`
--

INSERT INTO `BlockedUser` (`id`, `firstName`, `lastName`, `emailAddress`) VALUES
(1, 'Gadah', 'Ahmad', 'g@gmail.com'),
(2, 'Faris', 'Karem', 'Fa@gmail.com'),
(3, 'dana', 'blal', 'd@gmail.com');
-- --------------------------------------------------------

--
-- Table structure for table `Comment`
--

CREATE TABLE `Comment` (
  `id` int NOT NULL,
  `recipeID` int NOT NULL,
  `userID` int NOT NULL,
  `comment` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Comment`
--

INSERT INTO `Comment` (`id`, `recipeID`, `userID`, `comment`, `date`) VALUES
(3, 4, 9, 'Nice', '2026-04-12 06:32:24'),
(4, 4, 9, 'good', '2026-04-12 06:45:49'),
(5, 6, 10, 'good recipe', '2026-04-12 07:07:45'),
(7, 7, 9, 'Looks delicious, but I am lazy', '2026-04-12 08:29:14');

-- --------------------------------------------------------

--
-- Table structure for table `Favourites`
--

CREATE TABLE `Favourites` (
  `userID` int NOT NULL,
  `recipeID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Favourites`
--

INSERT INTO `Favourites` (`userID`, `recipeID`) VALUES
(3, 4),
(9, 4),
(3, 7),
(9, 7);

-- --------------------------------------------------------

--
-- Table structure for table `Ingredients`
--

CREATE TABLE `Ingredients` (
  `id` int NOT NULL,
  `recipeID` int NOT NULL,
  `ingredientName` varchar(100) NOT NULL,
  `ingredientQuantity` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Ingredients`
--

INSERT INTO `Ingredients` (`id`, `recipeID`, `ingredientName`, `ingredientQuantity`) VALUES
(19, 7, 'Flour', '3 cups'),
(20, 7, 'Butter', '1 cup'),
(21, 7, 'Brown Sugar', '1 cup'),
(22, 7, 'White Sugar', '1/2 cup'),
(23, 7, 'Eggs', '2'),
(24, 7, 'Vanilla', '1 teaspoon'),
(25, 7, 'Baking soda', '1 teaspoon'),
(26, 7, 'Salt', '1/2 teaspoon'),
(27, 7, 'Chocolate chips', '2 cups'),
(28, 6, 'Flour', '3 cups'),
(29, 6, 'Baking soda', '1 tsp'),
(30, 6, 'Ground ginger', '2 tsp'),
(31, 6, 'Cinnamon', '1 tsp'),
(32, 6, 'Cloves', '1/4 tsp'),
(33, 6, 'Salt', '1/2 tsp'),
(34, 6, 'Softened butter', '3/4 cup'),
(35, 6, 'Brown sugar', '3/4 cup'),
(36, 6, 'Egg', '1'),
(37, 6, 'Molasses', '1/2 cup'),
(38, 6, 'Vanilla', '1 tsp'),
(39, 4, 'unsweetened almond or oat milk', '1 cup'),
(40, 4, 'unsweetened cocoa powder', '1 tbsp'),
(41, 4, 'maple syrup or honey', '1 tbsp'),
(42, 4, 'vanilla extract', '1/4 tsp'),
(43, 4, 'sea salt', 'A pinch'),
(44, 4, 'Optional:  cinnamon', 'A tiny pinch'),
(45, 5, 'chicken breasts (chopped into small cubes)', '2'),
(46, 5, 'carrots (sliced)', '2'),
(47, 5, 'celery stalks (chopped)', '2'),
(48, 5, 'onion(diced)', '1'),
(49, 5, 'cloves garlic (minced)', '2'),
(50, 5, 'chicken broth', '6 cups'),
(51, 5, 'dried thyme', '1 tsp'),
(52, 5, 'black pepper', '1/2 tsp'),
(53, 5, 'salt', 'your taste'),
(54, 5, 'optional: potato (cubed)', '1');

-- --------------------------------------------------------

--
-- Table structure for table `Instructions`
--

CREATE TABLE `Instructions` (
  `id` int NOT NULL,
  `recipeID` int NOT NULL,
  `step` text NOT NULL,
  `stepOrder` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Dumping data for table `Instructions`
--

INSERT INTO `Instructions` (`id`, `recipeID`, `step`, `stepOrder`) VALUES
(18, 7, 'Preheat & Prep: Preheat your oven to 175°C. Line a baking sheet with parchment paper so the cookies don\'t stick.', 1),
(19, 7, 'Cream Butter & Sugar: In a large bowl, beat the softened butter, brown sugar, and white sugar together until the mixture is light, fluffy, and creamy.', 2),
(20, 7, 'Add Wet Ingredients: Add the eggs one at a time, beating well after each addition. Stir in the vanilla extract.', 3),
(21, 7, 'Mix Dry Ingredients: In a separate bowl, whisk together the flour, baking soda, and salt. Gradually add this to the wet mixture, mixing at low speed until just combined. Do not overmix.', 4),
(22, 7, 'Fold in Chocolate: Gently fold in the chocolate chips using a spatula or a wooden spoon until they are evenly distributed.', 5),
(23, 7, 'Scoop & Space: Use a tablespoon or an ice cream scoop to drop balls of dough onto the prepared baking sheet. Leave about 5cm of space between each ball as they will spread.', 6),
(24, 7, 'Bake: Bake for 10–12 minutes or until the edges are golden brown. The centers might still look a bit soft—this is the secret to a chewy cookie!', 7),
(25, 7, 'Cool: Let the cookies rest on the baking sheet for 5 minutes to firm up, then transfer them to a wire rack to cool completely.', 8),
(26, 6, 'Mix Dry: Whisk flour, baking soda, and spices in a bowl. Set aside.', 1),
(27, 6, 'Cream Wet: Beat butter and brown sugar until fluffy. Add egg, molasses, and vanilla; mix well.', 2),
(28, 6, 'Combine: Gradually stir the dry ingredients into the wet until a dough forms.', 3),
(29, 6, 'Chill: Divide dough in two, wrap in plastic, and refrigerate for at least 1 hour (essential for handling).', 4),
(30, 6, 'Roll & Cut: Preheat oven to 175°C. Roll dough on a floured surface to 6mm thickness and cut into shapes.', 5),
(31, 6, 'Bake: Place on parchment-lined trays. Bake for 8–10 minutes.', 6),
(32, 6, 'Cool: Let sit on the tray for 5 minutes, then move to a wire rack to crisp up.', 7),
(33, 4, 'Whisk: Place all ingredients in a small saucepan over medium-low heat.', 1),
(34, 4, 'Heat: Whisk constantly to break up cocoa clumps and prevent the milk from scorching.', 2),
(35, 4, 'Steam: Heat until hot but not boiling (about 2–3 minutes).', 3),
(36, 4, 'Froth: If you want it fancy, use a handheld frother for 30 seconds before pouring.', 4),
(37, 5, 'Heat a little olive oil in a large pot. Sauté the onion, carrots, and celery for 5 minutes until soft. Add the garlic for the last 30 seconds.', 1),
(38, 5, 'Add the chicken cubes to the pot and cook until the outside is no longer pink.', 2),
(39, 5, 'Pour in the broth and add the thyme and pepper. Bring to a boil, then reduce heat and simmer for 15–20 minutes.', 3),
(40, 5, 'If using potato , add it in the last 8 minutes of simmering. Stir in fresh parsley at the end if you have it.', 4);

-- --------------------------------------------------------

--
-- Table structure for table `Likes`
--

CREATE TABLE `Likes` (
  `userID` int NOT NULL,
  `recipeID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Likes`
--

INSERT INTO `Likes` (`userID`, `recipeID`) VALUES
(9, 4),
(10, 6),
(7, 7),
(9, 7),
(10, 7);
-- --------------------------------------------------------

--
-- Table structure for table `Recipe`
--

CREATE TABLE `Recipe` (
  `id` int NOT NULL,
  `userID` int NOT NULL,
  `categoryID` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `photoFileName` varchar(255) NOT NULL,
  `videoFilePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Recipe`
--

INSERT INTO `Recipe` (`id`, `userID`, `categoryID`, `name`, `description`, `photoFileName`, `videoFilePath`) VALUES
(4, 2, 1, 'Healthy Hot Chocolate', 'Warm healthy hot chocolate for cold winter days', 'hot chocolate.png', NULL),
(5, 3, 2, 'Chicken Vegetable Soup', 'Healthy chicken soup with vegetables', 'Chicken Vegetable Soup.png', NULL),
(6, 2, 3, 'Gingerbread Cookies', 'Classic winter gingerbread cookies', 'Gingerbread Cookies.png', NULL),
(7, 10, 3, 'Chocolate Chip Cookies', 'Soft, hot cookies filled with melted chocolate chips', 'recipe_7_1775970452.jpg', '');

-- --------------------------------------------------------

--
-- Table structure for table `RecipeCategory`
--

CREATE TABLE `RecipeCategory` (
  `id` int NOT NULL,
  `categoryName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `RecipeCategory`
--

INSERT INTO `RecipeCategory` (`id`, `categoryName`) VALUES
(1, 'Hot Drinks'),
(2, 'Soups & Warm Meals'),
(3, 'Winter Sweets');

-- --------------------------------------------------------

--
-- Table structure for table `Report`
--

CREATE TABLE `Report` (
  `id` int NOT NULL,
  `userID` int NOT NULL,
  `recipeID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Report`
--

INSERT INTO `Report` (`id`, `userID`, `recipeID`) VALUES
(3, 10, 5),
(7, 9, 7),
(8, 9, 6),
(9, 3, 7);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` int NOT NULL,
  `userType` varchar(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photoFileName` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `userType`, `firstName`, `lastName`, `emailAddress`, `password`, `photoFileName`) VALUES
(1, 'admin', 'Admin', 'User', 'admin@winter.com', '$2y$10$hhoDQk1syE7Qnv3IJ30ryOHkZt6RzyK9TKq/bYDli3o9eNiyfEyKW', 'profile.png'),
(2, 'user', 'Sara', 'Ali', 'sara@test.com', '$2y$10$hhoDQk1syE7Qnv3IJ30ryOHkZt6RzyK9TKq/bYDli3o9eNiyfEyKW', 'profile.png'),
(3, 'user', 'Omar', 'Khalid', 'omar@test.com', '$2y$10$hhoDQk1syE7Qnv3IJ30ryOHkZt6RzyK9TKq/bYDli3o9eNiyfEyKW', 'profile.png'),
(7, 'user', 'Norah', 'nasser', 'n@gmail.com', '$2y$10$OH8G2orBvfG4F7BpZLmBke5ubPKPsXh9TOkbw3KH7/p2Oj.vPLV1y', 'profile.png'),
(8, 'admin', 'rowan', 'nasser', 'admin2@gmail.com', '$2y$10$OH8G2orBvfG4F7BpZLmBke5ubPKPsXh9TOkbw3KH7/p2Oj.vPLV1y', 'profile.png'),
(9, 'user', 'Layan', 'Fahad', 'La@gmail.com', '$2y$10$9Y2S8RGo7Bo7MKN0bdAxoeX.ZhxaLXnCsVFxHwH1V3RvSzWDaH.3u', '14.jpg'),
(10, 'user', 'millesa', 'jay', 'm@gmail.com', '$2y$10$hhoDQk1syE7Qnv3IJ30ryOHkZt6RzyK9TKq/bYDli3o9eNiyfEyKW', 'coolcat.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `BlockedUser`
--
ALTER TABLE `BlockedUser`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- Indexes for table `Comment`
--
ALTER TABLE `Comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comment_recipe` (`recipeID`),
  ADD KEY `fk_comment_user` (`userID`);

--
-- Indexes for table `Favourites`
--
ALTER TABLE `Favourites`
  ADD PRIMARY KEY (`userID`,`recipeID`),
  ADD KEY `fk_favourites_recipe` (`recipeID`);

--
-- Indexes for table `Ingredients`
--
ALTER TABLE `Ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ingredients_recipe` (`recipeID`);

--
-- Indexes for table `Instructions`
--
ALTER TABLE `Instructions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_instructions_recipe` (`recipeID`);

--
-- Indexes for table `Likes`
--
ALTER TABLE `Likes`
  ADD PRIMARY KEY (`userID`,`recipeID`),
  ADD KEY `fk_likes_recipe` (`recipeID`);

--
-- Indexes for table `Recipe`
--
ALTER TABLE `Recipe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_recipe_user` (`userID`),
  ADD KEY `fk_recipe_category` (`categoryID`);

--
-- Indexes for table `RecipeCategory`
--
ALTER TABLE `RecipeCategory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categoryName` (`categoryName`);

--
-- Indexes for table `Report`
--
ALTER TABLE `Report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_report_user` (`userID`),
  ADD KEY `fk_report_recipe` (`recipeID`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `BlockedUser`
--
ALTER TABLE `BlockedUser`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Comment`
--
ALTER TABLE `Comment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Ingredients`
--
ALTER TABLE `Ingredients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Instructions`
--
ALTER TABLE `Instructions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Recipe`
--
ALTER TABLE `Recipe`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `RecipeCategory`
--
ALTER TABLE `RecipeCategory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Report`
--
ALTER TABLE `Report`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Comment`
--
ALTER TABLE `Comment`
  ADD CONSTRAINT `fk_comment_recipe` FOREIGN KEY (`recipeID`) REFERENCES `Recipe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`userID`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Favourites`
--
ALTER TABLE `Favourites`
  ADD CONSTRAINT `fk_favourites_recipe` FOREIGN KEY (`recipeID`) REFERENCES `Recipe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favourites_user` FOREIGN KEY (`userID`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Ingredients`
--
ALTER TABLE `Ingredients`
  ADD CONSTRAINT `fk_ingredients_recipe` FOREIGN KEY (`recipeID`) REFERENCES `Recipe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Instructions`
--
ALTER TABLE `Instructions`
  ADD CONSTRAINT `fk_instructions_recipe` FOREIGN KEY (`recipeID`) REFERENCES `Recipe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Likes`
--
ALTER TABLE `Likes`
  ADD CONSTRAINT `fk_likes_recipe` FOREIGN KEY (`recipeID`) REFERENCES `Recipe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_likes_user` FOREIGN KEY (`userID`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Recipe`
--
ALTER TABLE `Recipe`
  ADD CONSTRAINT `fk_recipe_category` FOREIGN KEY (`categoryID`) REFERENCES `RecipeCategory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_recipe_user` FOREIGN KEY (`userID`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Report`
--
ALTER TABLE `Report`
  ADD CONSTRAINT `fk_report_recipe` FOREIGN KEY (`recipeID`) REFERENCES `Recipe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_report_user` FOREIGN KEY (`userID`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
