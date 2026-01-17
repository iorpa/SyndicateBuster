-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 17, 2026 at 10:11 PM
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
-- Database: `syndicatebuster`
--

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches` (
  `batch_id` int(11) NOT NULL,
  `parent_batch_id` int(11) DEFAULT NULL,
  `commodities_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `harvest_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `batches`
--

INSERT INTO `batches` (`batch_id`, `parent_batch_id`, `commodities_id`, `owner_id`, `quantity`, `harvest_date`) VALUES
(1, NULL, 1, 2, 1000, '2024-01-10'),
(2, NULL, 2, 2, 800, '2024-01-05'),
(3, NULL, 3, 3, 500, '2024-01-08'),
(4, NULL, 4, 3, 300, '2024-01-03'),
(5, NULL, 1, 4, 1200, '2024-01-12');

-- --------------------------------------------------------

--
-- Table structure for table `commodities`
--

CREATE TABLE `commodities` (
  `commodities_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit_type` varchar(20) NOT NULL,
  `perishable` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commodities`
--

INSERT INTO `commodities` (`commodities_id`, `name`, `unit_type`, `perishable`) VALUES
(1, 'Rice', 'kg', 1),
(2, 'Wheat', 'kg', 1),
(3, 'Potato', 'kg', 1),
(4, 'Onion', 'kg', 1),
(5, 'Garlic', 'kg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `govt_price_cap`
--

CREATE TABLE `govt_price_cap` (
  `cap_id` int(11) NOT NULL,
  `commodities_id` int(11) NOT NULL,
  `max_price` int(11) NOT NULL,
  `effective_date` date NOT NULL,
  `expire_date` date NOT NULL DEFAULT '2030-01-01',
  `time_duration` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `govt_price_cap`
--

INSERT INTO `govt_price_cap` (`cap_id`, `commodities_id`, `max_price`, `effective_date`, `expire_date`, `time_duration`) VALUES
(1, 1, 60, '2024-01-01', '2030-01-01', 0),
(2, 2, 45, '2024-01-01', '2030-01-01', 0),
(3, 3, 30, '2024-01-01', '2030-01-01', 0),
(4, 4, 45, '2024-01-01', '2030-01-01', 0),
(5, 5, 120, '2024-01-01', '2030-01-01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(20) NOT NULL CHECK (`role_name` in ('Farmer','Middleman','Wholesaler','Retailer','Admin','Inspector'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `syndicate_blacklist`
--

CREATE TABLE `syndicate_blacklist` (
  `flag_id` int(11) NOT NULL,
  `flag_date` date NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `commodities_id` int(11) NOT NULL,
  `reported_price` int(11) NOT NULL,
  `violation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `seller_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `transaction_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `batch_id`, `seller_id`, `buyer_id`, `unit_price`, `quantity`, `transaction_date`) VALUES
(1, 1, 2, 3, 55, 100, '2026-01-16'),
(2, 3, 3, 6, 28, 200, '2026-01-16'),
(3, 5, 4, 5, 58, 150, '2026-01-16'),
(4, NULL, 5, 7, 62, 50, '2026-01-16'),
(5, NULL, 6, 7, 32, 100, '2026-01-16'),
(6, 2, 2, 4, 42, 80, '2026-01-15'),
(7, 4, 3, 5, 40, 60, '2026-01-14'),
(8, NULL, 4, 7, 120, 20, '2026-01-16'),
(9, NULL, 5, 6, 45, 70, '2026-01-16'),
(10, NULL, 6, 7, 50, 40, '2026-01-16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(11) DEFAULT NULL CHECK (`phone` regexp '^[0-9]{11}$'),
  `password` varchar(50) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Active' CHECK (`status` in ('Active','inactive','Suspended','Banned')),
  `location` varchar(50) NOT NULL,
  `trust_score` int(11) DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone`, `password`, `role_id`, `status`, `location`, `trust_score`, `created_at`, `updated_at`) VALUES
(2, 'karim', 'karim@gmail.com', '01711109999', '8888', 1, 'Active', 'Dhaka', 100, '2026-01-15 21:46:08', '2026-01-15 21:46:08'),
(3, 'rahim', 'rahim@gmail.com', '01999888888', '1234', 2, 'Active', 'Chittagong', 100, '2026-01-15 21:46:08', '2026-01-15 21:46:08'),
(4, 'Rifat hossain', 'admin@market.gov', '01700000000', 'admin123', 5, 'Active', 'Dhaka', 100, '2026-01-15 21:46:08', '2026-01-15 21:46:08'),
(5, 'farmer_john', 'john@farmer.com', '01711111111', '1234', 1, 'Active', 'Rajshahi', 85, '2026-01-15 21:46:08', '2026-01-15 21:46:08'),
(6, 'trader_ali', 'ali@trader.com', '01722222222', '1234', 2, 'Active', 'Chittagong', 90, '2026-01-15 21:46:08', '2026-01-15 21:46:08'),
(7, 'wholesale_co', 'wholesale@market.com', '01733333333', '1234', 3, 'Suspended', 'Khulna', 60, '2026-01-15 21:46:08', '2026-01-15 21:46:08'),
(8, 'retail_shop', 'retail@shop.com', '01744444444', '1234', 4, 'Active', 'Sylhet', 95, '2026-01-15 21:46:08', '2026-01-15 21:46:08');

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
-- Indexes for table `syndicate_blacklist`
--
ALTER TABLE `syndicate_blacklist`
  ADD PRIMARY KEY (`flag_id`),
  ADD KEY `fk_violation_transaction` (`transaction_id`),
  ADD KEY `fk_violation_seller` (`seller_id`),
  ADD KEY `fk_violation_buyer` (`buyer_id`),
  ADD KEY `fk_violation_commodity` (`commodities_id`);

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
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `commodities`
--
ALTER TABLE `commodities`
  MODIFY `commodities_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `govt_price_cap`
--
ALTER TABLE `govt_price_cap`
  MODIFY `cap_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `syndicate_blacklist`
--
ALTER TABLE `syndicate_blacklist`
  MODIFY `flag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- Constraints for table `syndicate_blacklist`
--
ALTER TABLE `syndicate_blacklist`
  ADD CONSTRAINT `fk_violation_buyer` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_violation_commodity` FOREIGN KEY (`commodities_id`) REFERENCES `commodities` (`commodities_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_violation_seller` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_violation_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
