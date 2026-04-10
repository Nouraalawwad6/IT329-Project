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

-- --------------------------------------------------------

--
-- Table structure for table `Favourites`
--

CREATE TABLE `Favourites` (
  `userID` int NOT NULL,
  `recipeID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `Likes`
--

CREATE TABLE `Likes` (
  `userID` int NOT NULL,
  `recipeID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(6, 2, 3, 'Gingerbread Cookies', 'Classic winter gingerbread cookies', 'Gingerbread Cookies.png', NULL);

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
(1, 'admin', 'Admin', 'User', 'admin@winter.com', '$2y$10$e0NRv8S5i6wR7rH0qFhO0e7hE8Q5vJvVn1o6oJx9p4k8Q8q0ZqOeS', 'profile.png'),
(2, 'user', 'Sara', 'Ali', 'sara@test.com', '$2y$10$e0NRv8S5i6wR7rH0qFhO0e7hE8Q5vJvVn1o6oJx9p4k8Q8q0ZqOeS', 'profile.png'),
(3, 'user', 'Omar', 'Khalid', 'omar@test.com', '$2y$10$e0NRv8S5i6wR7rH0qFhO0e7hE8Q5vJvVn1o6oJx9p4k8Q8q0ZqOeS', 'profile.png'),
(7, 'user', 'Norah', 'nasser', 'n@gmail.com', '$2y$10$OH8G2orBvfG4F7BpZLmBke5ubPKPsXh9TOkbw3KH7/p2Oj.vPLV1y', 'profile.png'),
(8, 'admin', 'rowan', 'nasser', 'admin2@gmail.com', '$2y$10$OH8G2orBvfG4F7BpZLmBke5ubPKPsXh9TOkbw3KH7/p2Oj.vPLV1y', 'profile.png');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
