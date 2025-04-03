-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 09:55 PM
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
-- Database: `bozkurte_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category` varchar(10) NOT NULL,
  `availability` tinyint(1) NOT NULL DEFAULT 1,
  `description` text NOT NULL,
  `quick_description` text NOT NULL,
  `price` decimal(5,2) NOT NULL DEFAULT 0.00,
  `processing_time` time NOT NULL DEFAULT '24:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `category`, `availability`, `description`, `quick_description`, `price`, `processing_time`) VALUES
(1, 'Strawberry Cake', 'cake', 1, 'A delicious strawberry cake made with love.\r\n\r\nIngredients:\r\n- flour\r\n- milk\r\n- eggs\r\n- sugar', 'Simply delicious.', 35.50, '24:00:00'),
(2, 'Chocolate Cake', 'cake', 1, 'A delicious chocolate cake made with love.\r\n\r\nIngredients:\r\n- flour\r\n- milk\r\n- eggs\r\n- sugar', 'Simply delicious.', 40.00, '24:00:00');

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
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
