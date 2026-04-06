-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Apr 06, 2026 at 08:26 PM
-- Server version: 12.1.2-MariaDB-ubu2404
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `mileage` int(11) NOT NULL,
  `fuelType` varchar(50) NOT NULL,
  `transmission` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `imageUrl` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `brand`, `model`, `year`, `price`, `mileage`, `fuelType`, `transmission`, `status`, `imageUrl`, `description`) VALUES
(1, 'BMW', '320d', 2019, 2342.00, 84200, 'Diesel', 'Automatic', 'Sold', 'https://images.unsplash.com/photo-1555215695-3004980ad54e', 'Well-maintained BMW 320d with automatic transmission, efficient diesel engine, and premium interior.'),
(2, 'Audi', 'A3', 2018, 18900.00, 96750, 'Petrol', 'Manual', 'Available', 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341', 'Compact and reliable Audi A3, ideal for city and highway driving.'),
(3, 'Mercedes-Benz', 'C200', 2020, 31400.00, 55200, 'Petrol', 'Automatic', 'Sold', 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738', 'Elegant Mercedes-Benz C200 with modern comfort features and smooth driving experience.'),
(4, 'Volkswagen', 'Golf', 2017, 14950.00, 118400, 'Diesel', 'Manual', 'Available', 'https://images.unsplash.com/photo-1503376780353-7e6692767b70', 'Practical Volkswagen Golf in good condition, economical and comfortable for everyday use.'),
(6, 'Aud', 'rqefwr', 1966, 13422.00, 13425, 'Diesel', 'wrf', 'Available', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSY4y5SlwGL853bRyUZKU8EV3KLxyFKJxnkPw&s', 'qte'),
(9, 'qw', 'qwe', 1965, 123.00, 123, 'Electric', 'Manual', 'Available', '13', '13');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `carId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `adminReply` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'new',
  `publicToken` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `carId`, `name`, `email`, `message`, `createdAt`, `adminReply`, `status`, `publicToken`) VALUES
(1, 1, 'Zakhar', 'zakhar@example.com', 'Hi, I am interested in this car. Is it still available?', '2026-04-05 17:38:48', NULL, 'new', ''),
(2, 2, 'Zak', 'zal@gmail.com', 'adcd', '2026-04-05 17:48:29', NULL, 'handled', ''),
(3, 2, 'pam', 'pam@gmail.com', 'nice car', '2026-04-05 18:45:32', 'Tnx', 'new', ''),
(4, 1, 'Zakhar', 'zakhar@example.com', 'Hi, is this car still available?', '2026-04-05 21:24:37', NULL, 'new', ''),
(5, 1, 'pam', 'pam@gmail.com', 'pam param\n', '2026-04-05 21:50:21', NULL, 'new', ''),
(6, 1, 'oop', 'oop@gmail.com', 'ooopa\n', '2026-04-05 22:09:14', NULL, 'new', ''),
(7, 1, 'pam', '1@gmail.com', 'adfs', '2026-04-05 22:32:56', NULL, 'new', ''),
(8, 2, 'oop', 'op@gmail.com', 'qwerty\n', '2026-04-05 22:33:50', NULL, 'new', ''),
(9, 2, 'erw', 'qw@gmail.com', 'qrt', '2026-04-05 22:59:01', NULL, 'new', ''),
(10, 2, 'p', 'qw@gmail.com', 'ewrf', '2026-04-05 23:08:10', NULL, 'new', ''),
(11, 2, 'cadsv', 'dziublykz@gmail.com', 'afv', '2026-04-05 23:09:04', NULL, 'new', ''),
(12, 2, 'cavsv', 'dziublykz@gmail.com', 'afsvadgbd', '2026-04-05 23:26:32', NULL, 'new', ''),
(13, 1, 'Pam', 'pa@gmail.com', 'AAAA', '2026-04-06 12:22:31', NULL, 'new', ''),
(14, 2, 'qef', 'dziublykz@gmail.com', 'qefq', '2026-04-06 14:35:18', NULL, 'new', 'd39a69b6d0a0b956f168efe09923c71b0ff35f5d5c223e69d49c9694ce303fcc'),
(17, 1, 'Buggatti', 'veieron@gmail.com', 'Yes, I want it\n', '2026-04-06 19:07:44', NULL, 'new', '8111f033604107336314f39052ccaabfaa027ca40605be987a13c119fcce56d9');

-- --------------------------------------------------------

--
-- Table structure for table `inquiry_messages`
--

CREATE TABLE `inquiry_messages` (
  `id` int(11) NOT NULL,
  `inquiryId` int(11) NOT NULL,
  `senderType` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `inquiry_messages`
--

INSERT INTO `inquiry_messages` (`id`, `inquiryId`, `senderType`, `message`, `createdAt`) VALUES
(1, 1, 'customer', 'Hi, I am interested in this car. Is it still available?', '2026-04-05 17:38:48'),
(2, 2, 'customer', 'adcd', '2026-04-05 17:48:29'),
(3, 3, 'customer', 'nice car', '2026-04-05 18:45:32'),
(4, 4, 'customer', 'Hi, is this car still available?', '2026-04-05 21:24:37'),
(5, 4, 'admin', 'Yes,it is', '2026-04-05 21:28:23'),
(6, 4, 'admin', 'vrev', '2026-04-05 21:32:39'),
(7, 3, 'admin', '1234656', '2026-04-05 21:32:59'),
(8, 2, 'admin', 'pppppppppp', '2026-04-05 21:33:09'),
(9, 5, 'customer', 'pam param\n', '2026-04-05 21:50:21'),
(10, 5, 'admin', 'Hi pam', '2026-04-05 21:50:58'),
(11, 5, 'customer', 'Hi, how are you?', '2026-04-05 21:52:00'),
(12, 5, 'customer', 'Hi, how are you?', '2026-04-05 21:52:00'),
(13, 5, 'admin', 'u', '2026-04-05 21:52:27'),
(14, 5, 'customer', 'mb mb', '2026-04-05 21:52:47'),
(15, 6, 'customer', 'ooopa\n', '2026-04-05 22:09:14'),
(16, 6, 'admin', 'hi, whats\'up', '2026-04-05 22:15:22'),
(17, 7, 'customer', 'adfs', '2026-04-05 22:32:56'),
(18, 8, 'customer', 'qwerty\n', '2026-04-05 22:33:50'),
(19, 9, 'customer', 'qrt', '2026-04-05 22:59:01'),
(20, 9, 'admin', 'frewg', '2026-04-05 22:59:33'),
(21, 10, 'customer', 'ewrf', '2026-04-05 23:08:10'),
(22, 10, 'admin', 'acddvav', '2026-04-05 23:08:37'),
(23, 11, 'customer', 'afv', '2026-04-05 23:09:04'),
(24, 11, 'admin', 'dafgvdb', '2026-04-05 23:16:56'),
(25, 11, 'customer', 'dadcvfdac', '2026-04-05 23:18:01'),
(26, 11, 'customer', 'argqegtw', '2026-04-05 23:18:05'),
(27, 12, 'customer', 'afsvadgbd', '2026-04-05 23:26:32'),
(28, 11, 'admin', 'dvfavdfsac', '2026-04-05 23:28:28'),
(29, 13, 'customer', 'AAAA', '2026-04-06 12:22:31'),
(30, 13, 'customer', 'dewrfe', '2026-04-06 12:22:37'),
(31, 11, 'customer', 'acdvsfabd', '2026-04-06 14:04:04'),
(32, 11, 'customer', 'sfv', '2026-04-06 14:04:14'),
(33, 14, 'customer', 'qefq', '2026-04-06 14:35:18'),
(34, 14, 'customer', 'areg', '2026-04-06 14:37:16'),
(35, 14, 'customer', 'agre', '2026-04-06 14:37:19'),
(36, 14, 'admin', 'eqfqef', '2026-04-06 14:37:59'),
(40, 2, 'admin', 'Hiiiii', '2026-04-06 15:29:37'),
(44, 17, 'customer', 'Yes, I want it\n', '2026-04-06 19:07:44'),
(45, 17, 'customer', 'oop', '2026-04-06 19:08:02'),
(46, 17, 'admin', 'Hi, me too', '2026-04-06 19:08:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin User', 'admin@dbcauto.nl', '$2y$12$P3EMsSQ6jhPwGx7E/TfpEuFju01u99NARqI9fgGSiCb8iFqyLjq9K', 'admin'),
(2, 'Employee User', 'employee@dbcauto.nl', '$2y$12$P3EMsSQ6jhPwGx7E/TfpEuFju01u99NARqI9fgGSiCb8iFqyLjq9K', 'employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carId` (`carId`);

--
-- Indexes for table `inquiry_messages`
--
ALTER TABLE `inquiry_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inquiry_messages_inquiry` (`inquiryId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `inquiry_messages`
--
ALTER TABLE `inquiry_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD CONSTRAINT `1` FOREIGN KEY (`carId`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inquiry_messages`
--
ALTER TABLE `inquiry_messages`
  ADD CONSTRAINT `fk_inquiry_messages_inquiry` FOREIGN KEY (`inquiryId`) REFERENCES `inquiries` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
