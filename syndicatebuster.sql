-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2026 at 08:48 PM
-- Server version: 8.0.39
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `syndicatebuster`
--

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches` (
  `batch_id` int NOT NULL,
  `parent_batch_id` int DEFAULT NULL,
  `commodities_id` int NOT NULL,
  `owner_id` int NOT NULL,
  `quantity` int NOT NULL,
  `harvest_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `commodities`
--

CREATE TABLE `commodities` (
  `commodities_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `unit_type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `perishable` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `govt_price_cap`
--

CREATE TABLE `govt_price_cap` (
  `cap_id` int NOT NULL,
  `commodities_id` int NOT NULL,
  `max_price` int NOT NULL,
  `effective_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int NOT NULL,
  `role_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1, 'Farmer'),
(2, 'Middleman'),
(3, 'Wholesaler'),
(4, 'Retailer'),
(5, 'Admin'),
(6, 'Inspector');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int NOT NULL,
  `batch_id` int DEFAULT NULL,
  `seller_id` int NOT NULL,
  `buyer_id` int NOT NULL,
  `unit_price` int NOT NULL,
  `quantity` int NOT NULL,
  `transaction_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `role_id` int DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Active'
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `phone`, `password`, `role_id`, `status`) VALUES
(1, 'Rahim Hossain', 'rahim.farmer@gmail.com', '01711111111', 'farmer123', 1, 'Active'),
(2, 'Admin Kabir', 'admin.kabir@gmail.com', '01800011100', 'admin123', 5, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `violation_id` int NOT NULL,
  `transaction_id` int NOT NULL,
  `seller_id` int NOT NULL,
  `buyer_id` int NOT NULL,
  `commodities_id` int NOT NULL,
  `reported_price` int NOT NULL,
  `max_allowed_price` int NOT NULL,
  `violation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`batch_id`),
  ADD KEY `fk_commodities` (`commodities_id`),
  ADD KEY `fk_owner` (`owner_id`),
  ADD KEY `fk_parent_batch` (`parent_batch_id`);

--
-- Indexes for table `commodities`
--
ALTER TABLE `commodities`
  ADD PRIMARY KEY (`commodities_id`);

--
-- Indexes for table `govt_price_cap`
--
ALTER TABLE `govt_price_cap`
  ADD PRIMARY KEY (`cap_id`),
  ADD KEY `fk_cap_commodity` (`commodities_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fk_seller` (`seller_id`),
  ADD KEY `fk_buyer` (`buyer_id`),
  ADD KEY `fk_batchid` (`batch_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`violation_id`),
  ADD KEY `fk_violation_transaction` (`transaction_id`),
  ADD KEY `fk_violation_seller` (`seller_id`),
  ADD KEY `fk_violation_buyer` (`buyer_id`),
  ADD KEY `fk_violation_commodity` (`commodities_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `batch_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `commodities`
--
ALTER TABLE `commodities`
  MODIFY `commodities_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `govt_price_cap`
--
ALTER TABLE `govt_price_cap`
  MODIFY `cap_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `violation_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batches`
--
ALTER TABLE `batches`
  ADD CONSTRAINT `fk_commodities` FOREIGN KEY (`commodities_id`) REFERENCES `commodities` (`commodities_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_parent_batch` FOREIGN KEY (`parent_batch_id`) REFERENCES `batches` (`batch_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `govt_price_cap`
--
ALTER TABLE `govt_price_cap`
  ADD CONSTRAINT `fk_cap_commodity` FOREIGN KEY (`commodities_id`) REFERENCES `commodities` (`commodities_id`) ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_batchid` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`batch_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_buyer` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_seller` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);

--
-- Constraints for table `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `fk_violation_buyer` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_violation_commodity` FOREIGN KEY (`commodities_id`) REFERENCES `commodities` (`commodities_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_violation_seller` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_violation_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
