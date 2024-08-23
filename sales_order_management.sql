-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 23, 2024 at 07:35 PM
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
-- Database: `sales_order_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `agent_id` int(11) NOT NULL,
  `agent_name` varchar(100) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`agent_id`, `agent_name`, `contact_info`) VALUES
(1, 'Robert Wilson', 'robert.wilson@example.com, +1234567890'),
(2, 'Linda Martinez', 'linda.martinez@example.com, +0987654321'),
(3, 'James Anderson', 'james.anderson@example.com, +1122334455'),
(4, 'Patricia Taylor', 'patricia.taylor@example.com, +2233445566'),
(5, 'William Thomas', 'william.thomas@example.com, +3344556677');

-- --------------------------------------------------------

--
-- Table structure for table `buyer`
--

CREATE TABLE `buyer` (
  `buyer_id` int(11) NOT NULL,
  `buyer_name` varchar(100) NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buyer`
--

INSERT INTO `buyer` (`buyer_id`, `buyer_name`, `contact_info`, `address`) VALUES
(1, 'John Doe', 'john.doe@example.com, +1234567890', '123 Elm Street, Springfield, IL, 62701'),
(2, 'Jane Smith', 'jane.smith@example.com, +0987654321', '456 Oak Avenue, Rivertown, TX, 75001'),
(3, 'Robert Brown', 'robert.brown@example.com, +1122334455', '789 Pine Road, Lakedale, CA, 90210'),
(4, 'Emily Davis', 'emily.davis@example.com, +2233445566', '101 Maple Lane, Hilltown, NY, 10001'),
(5, 'Michael Johnson', 'michael.johnson@example.com, +3344556677', '202 Birch Blvd, Valleyview, FL, 33101');

-- --------------------------------------------------------

--
-- Table structure for table `fibretype`
--

CREATE TABLE `fibretype` (
  `fibre_id` int(11) NOT NULL,
  `fibre_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fibretype`
--

INSERT INTO `fibretype` (`fibre_id`, `fibre_name`) VALUES
(1, 'Cotton'),
(2, 'Wool'),
(3, 'Silk'),
(4, 'Linen'),
(5, 'Polyester');

-- --------------------------------------------------------

--
-- Table structure for table `salesorder`
--

CREATE TABLE `salesorder` (
  `sales_order_no` varchar(50) NOT NULL,
  `order_type` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency_type` varchar(10) NOT NULL,
  `payment_terms` varchar(255) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL,
  `date_of_confirmation` date DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `order_details` text DEFAULT NULL,
  `fibre_id` int(11) DEFAULT NULL,
  `type_of_selvedge` varchar(50) DEFAULT NULL,
  `selvedge_id` int(11) DEFAULT NULL,
  `selvedge_width` float DEFAULT NULL,
  `selvedge_weave` varchar(50) DEFAULT NULL,
  `inspection_type` varchar(50) DEFAULT NULL,
  `inspection_standard` varchar(50) DEFAULT NULL,
  `piece_length` varchar(50) DEFAULT NULL,
  `packing_type` varchar(50) DEFAULT NULL,
  `freight` varchar(255) DEFAULT NULL,
  `invoice_address` text DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `commission` varchar(50) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `confirmed` varchar(50) DEFAULT NULL,
  `edit` int(11) DEFAULT NULL,
  `order_quantity` int(11) NOT NULL,
  `order_status` enum('Ongoing','Completed') NOT NULL DEFAULT 'Ongoing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selvedgetype`
--

CREATE TABLE `selvedgetype` (
  `selvedge_id` int(11) NOT NULL,
  `selvedge_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `selvedgetype`
--

INSERT INTO `selvedgetype` (`selvedge_id`, `selvedge_name`) VALUES
(1, 'Twill'),
(2, 'Plain'),
(3, 'Satin'),
(4, 'Herringbone'),
(5, 'Jacquard');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `role` enum('Marketing Team','Director','Purchase Team') NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `gender`, `mobile`, `role`, `email`, `username`, `password`, `created_at`) VALUES
(2, 'Rahul Devligri', 'Male', '8290813304', 'Director', 'rahuldevligri@gmail.com', 'rahuldevligri', '$2y$10$Xu5azgrpoG.WD4U2G7Tvr.cARfDL5sQdc1q33ivB2.40.kTWBWWiq', '2024-08-16 09:15:55'),
(3, 'kareena', 'Female', '9999999999', 'Marketing Team', 'kareena@gmail.com', 'kareena', '$2y$10$BvkE6XxuSNabdBHLRSvljOrxYSsneVd.ySrFnXdzv3dP/GNBMVFkm', '2024-08-16 09:45:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`agent_id`);

--
-- Indexes for table `buyer`
--
ALTER TABLE `buyer`
  ADD PRIMARY KEY (`buyer_id`);

--
-- Indexes for table `fibretype`
--
ALTER TABLE `fibretype`
  ADD PRIMARY KEY (`fibre_id`);

--
-- Indexes for table `salesorder`
--
ALTER TABLE `salesorder`
  ADD PRIMARY KEY (`sales_order_no`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `agent_id` (`agent_id`),
  ADD KEY `fibre_id` (`fibre_id`),
  ADD KEY `selvedge_id` (`selvedge_id`);

--
-- Indexes for table `selvedgetype`
--
ALTER TABLE `selvedgetype`
  ADD PRIMARY KEY (`selvedge_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `agent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `buyer`
--
ALTER TABLE `buyer`
  MODIFY `buyer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fibretype`
--
ALTER TABLE `fibretype`
  MODIFY `fibre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `selvedgetype`
--
ALTER TABLE `selvedgetype`
  MODIFY `selvedge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `salesorder`
--
ALTER TABLE `salesorder`
  ADD CONSTRAINT `salesorder_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`buyer_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salesorder_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `agent` (`agent_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salesorder_ibfk_3` FOREIGN KEY (`fibre_id`) REFERENCES `fibretype` (`fibre_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salesorder_ibfk_4` FOREIGN KEY (`selvedge_id`) REFERENCES `selvedgetype` (`selvedge_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
