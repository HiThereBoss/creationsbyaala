-- phpMyAdmin SQL Dump
-- version 5.2.2-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 27, 2025 at 01:11 AM
-- Server version: 9.1.0-commercial
-- PHP Version: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bozkurte_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `availability` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `quick_description` text COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(5,2) NOT NULL DEFAULT '0.00',
  `processing_time` int NOT NULL DEFAULT '24'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `category`, `availability`, `description`, `quick_description`, `price`, `processing_time`) VALUES
(1, 'Strawberry Cake', 'cake', 1, 'A delicious strawberry cake made with love.\r\n\r\nIngredients:\r\n- flour\r\n- milk\r\n- eggs\r\n- sugar', 'Delicious.', 36.00, 24),
(2, 'Chocolate Cake', 'cake', 1, 'A delicious chocolate cake made with love.\r\n\r\nIngredients:\r\n- flour\r\n- milk\r\n- eggs\r\n- sugar', 'Simply delicious.', 40.00, 24),
(3, 'Chocolate Cake Special', 'cake', 1, 'Very delicious\r\n\r\nIngredients:\r\n- chocolate\r\n- special ingredient\r\n- egg', 'Yum', 12.00, 48);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
