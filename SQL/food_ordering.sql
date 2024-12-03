-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 03, 2024 at 06:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_ordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `item_name`, `description`, `price`, `image`, `created_at`, `updated_at`, `category`) VALUES
(12, 'Spaghetti Carbonara', 'Classic Italian pasta dish with eggs, cheese, pancetta, and pepper.', 12.99, 'images/spaghetti_carbonara.jpg', '2024-10-15 14:47:51', '2024-10-16 04:09:39', 'Pasta'),
(13, 'Margherita Pizza', 'Traditional pizza topped with fresh mozzarella, tomatoes, and basil.', 10.49, 'images/marg.jpg', '2024-10-15 14:47:51', '2024-10-16 04:12:59', 'Pizza'),
(14, 'Caesar Salad', 'Crisp romaine lettuce with Caesar dressing, croutons, and parmesan cheese.', 8.99, 'images/caesar.jpg', '2024-10-15 14:47:51', '2024-10-16 15:43:23', 'Salads'),
(15, 'Cheeseburger', 'Juicy beef burger topped with cheese, lettuce, tomato, and onion.', 11.49, 'images/cheese.jpg', '2024-10-15 14:47:51', '2024-10-16 15:48:41', 'Burgers'),
(16, 'Club Sandwich', 'Triple-layered sandwich with turkey, bacon, lettuce, and tomato.', 9.99, 'images/club.jpg', '2024-10-15 14:47:51', '2024-10-16 16:05:49', 'Sandwiches'),
(18, 'French Fries', 'Crispy golden fries served with ketchup.', 3.99, 'images/fries.jpg', '2024-10-15 14:47:51', '2024-10-16 16:04:31', 'Sides'),
(19, 'Chocolate Cake', 'Rich chocolate cake layered with creamy chocolate frosting.', 5.49, 'images/choco.jpg', '2024-10-15 14:47:51', '2024-10-16 04:24:34', 'Desserts'),
(20, 'Penne Arrabbiata', 'Penne pasta tossed in a spicy tomato sauce with garlic and chili.', 12.99, 'images/penne_arrabbiata.jpg', '2024-10-15 15:16:01', '2024-10-16 04:11:25', 'Pasta'),
(21, 'Fettuccine Alfredo', 'Creamy fettuccine pasta with parmesan cheese and butter.', 13.49, 'images/fettu.jpg', '2024-10-15 15:16:01', '2024-10-16 04:10:53', 'Pasta'),
(22, 'Pepperoni Pizza', 'Classic pizza topped with pepperoni and mozzarella cheese.', 11.99, 'images/pep.jpg', '2024-10-15 15:16:01', '2024-10-16 04:15:11', 'Pizza'),
(23, 'BBQ Chicken Pizza', 'Grilled chicken with BBQ sauce, onions, and cilantro on a cheesy base.', 12.99, 'images/bbqc.jpg', '2024-10-15 15:16:01', '2024-10-16 04:16:27', 'Pizza'),
(24, 'Greek Salad', 'Fresh vegetables topped with feta cheese and olives.', 7.99, 'images/greek.jpg', '2024-10-15 15:16:01', '2024-10-16 15:45:09', 'Salads'),
(25, 'Caprese Salad', 'Sliced fresh mozzarella and tomatoes drizzled with balsamic reduction.', 8.49, 'images/caprese.jpg', '2024-10-15 15:16:01', '2024-10-16 15:46:25', 'Salads'),
(26, 'Veggie Burger', 'A delicious plant-based burger.', 10.49, 'images/vegb.jpg', '2024-10-15 15:16:01', '2024-10-16 15:50:44', 'Burgers'),
(28, 'Turkey Club Sandwich', 'Classic club sandwich with turkey breast and crispy bacon.', 10.99, 'images/turk.jpg', '2024-10-15 15:16:01', '2024-10-16 16:07:44', 'Sandwiches'),
(29, 'BLT Sandwich', 'Bacon, lettuce and tomato on toasted bread.', 9.49, 'images/blt.jpg', '2024-10-15 15:16:01', '2024-10-16 16:08:49', 'Sandwiches'),
(32, 'Onion Rings', 'Crispy onion rings served with dipping sauce.', 4.99, 'images/onion.jpg', '2024-10-15 15:16:01', '2024-10-16 16:02:11', 'Sides'),
(33, 'Garlic Bread', 'Toasted bread topped with garlic butter and herbs.', 3.49, 'images/garlic.jpg', '2024-10-15 15:16:01', '2024-10-16 16:03:16', 'Sides'),
(34, 'Cheesecake', 'Creamy cheesecake topped with a strawberry glaze.', 6.49, 'images/ccake.jpg', '2024-10-15 15:16:01', '2024-10-16 04:22:49', 'Desserts'),
(35, 'Tiramisu', 'Classic Italian dessert made of coffee-soaked ladyfingers and mascarpone cheese.', 6.99, 'images/tira.jpg', '2024-10-15 15:16:01', '2024-10-16 04:21:36', 'Desserts'),
(39, 'Vegetarian Pizza', 'Loaded with fresh vegetables and mozzarella cheese.', 11.49, 'images/veg.jpg', '2024-10-15 15:32:03', '2024-10-16 04:19:24', 'Pizza'),
(42, 'Mushroom Swiss Burger', 'Juicy beef burger topped with sautéed mushrooms and Swiss cheese.', 12.49, 'images/mushroom.jpg', '2024-10-15 15:32:03', '2024-10-16 15:53:08', 'Burgers'),
(43, 'Spicy Jalapeño Burger', 'Beef burger topped with jalapeños and pepper jack cheese.', 11.99, 'images/jala.jpg', '2024-10-15 15:32:03', '2024-10-16 15:55:08', 'Burgers'),
(44, 'Grilled Cheese Sandwich', 'Classic grilled cheese on toasted bread.', 6.99, 'images/grill.jpg', '2024-10-15 15:32:03', '2024-10-16 16:10:29', 'Sandwiches'),
(48, 'Mozzarella Sticks', 'Fried mozzarella cheese served with marinara sauce.', 5.99, 'images/mazz.jpg', '2024-10-15 15:32:03', '2024-10-16 16:00:56', 'Sides'),
(51, 'Fruit Tart', 'Delicious tart filled with pastry cream and topped with fresh fruit.', 5.99, 'images/fruit.jpg', '2024-10-15 15:32:03', '2024-10-16 04:26:55', 'Desserts');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `item_name`, `price`, `quantity`, `created_at`) VALUES
(1, 1, 'Spaghetti Carbonara', 12.99, 2, '2024-11-05 19:09:34'),
(2, 1, 'Spaghetti Carbonara', 12.99, 4, '2024-11-05 19:14:05'),
(3, 1, 'Vegetarian Pizza', 11.49, 1, '2024-11-05 19:14:05'),
(4, 1, 'Garlic Bread', 3.49, 1, '2024-11-05 19:14:05'),
(5, 1, 'Spaghetti Carbonara', 12.99, 1, '2024-11-05 19:15:16'),
(6, 1, 'Fettuccine Alfredo', 13.49, 1, '2024-11-05 19:15:16'),
(7, 1, 'Spaghetti Carbonara', 12.99, 1, '2024-11-05 19:17:22'),
(8, 1, 'Fettuccine Alfredo', 13.49, 1, '2024-11-05 19:17:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(1, 'Cherry', '$2y$10$HyEMQ8SaXT/QYXtPQe.reuL84tJmmaIrWlgJcul0fV6JmnKqYSI.C', 'cherry@gmail.com'),
(2, 'Chaitravi', '$2y$10$g4dMp8RYj1.7GN7.eXrUQeYX.ZDMhW4jlYuzL/pAUw8Ux9/AQge.W', 'chaitravi@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
