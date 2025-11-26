-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 05:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.5.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digital_college_it`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `action_type` enum('login','add_computer','delete_computer','add_printer','delete_printer','add_landline','delete_landline') NOT NULL,
  `action_details` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `username`, `action_type`, `action_details`, `created_at`) VALUES
(1, 1, 'admin', 'login', NULL, '2025-11-23 21:45:32'),
(2, 1, 'admin', 'login', NULL, '2025-11-24 12:55:27'),
(3, 1, 'admin', 'add_computer', 'تمت إضافة جهاز: hp 2008', '2025-11-24 13:24:59'),
(4, 1, 'admin', 'login', NULL, '2025-11-24 14:11:00'),
(5, 1, 'admin', 'add_computer', 'تمت إضافة جهاز: dell 2025', '2025-11-24 14:33:57'),
(6, 1, 'admin', 'add_computer', 'تمت إضافة جهاز: asus 2010', '2025-11-24 19:46:32'),
(7, 1, 'admin', 'add_computer', 'تمت إضافة جهاز: canon 27654', '2025-11-24 19:50:28');

-- --------------------------------------------------------

--
-- Table structure for table `computers`
--

CREATE TABLE `computers` (
  `id` int(11) NOT NULL,
  `asset_tag` varchar(50) NOT NULL,
  `type` enum('Desktop','Laptop','All-in-One') NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `cpu` varchar(100) NOT NULL,
  `ram_gb` int(11) NOT NULL,
  `storage_type` enum('SSD','HDD') NOT NULL,
  `storage_gb` int(11) NOT NULL,
  `os` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `status` enum('قيد الاستخدام','تحت الصيانة','معطل','مخزن') NOT NULL DEFAULT 'قيد الاستخدام',
  `assigned_to` varchar(100) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `warranty_expiry` date DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `computers`
--

INSERT INTO `computers` (`id`, `asset_tag`, `type`, `make`, `model`, `serial_number`, `cpu`, `ram_gb`, `storage_type`, `storage_gb`, `os`, `location`, `status`, `assigned_to`, `purchase_date`, `warranty_expiry`, `notes`) VALUES
(1, '654323', 'Laptop', 'hp', '2020', '65432', 'i5', 32, '', 500, 'win 11', 'wherehouse', 'مخزن', 'abualaziz', '2021-06-24', '2028-12-31', ''),
(2, '6532', 'All-in-One', 'dell', '2025', '9ol8656', 'i9', 64, '', 1024, 'windows 11  pro', 'office33', 'تحت الصيانة', 'fadi_ahmed', '2025-11-19', '2036-12-31', ''),
(3, 'op4333', 'Laptop', 'asus', '2010', '345fgvb7890', 'i3', 18, '', 500, 'windows_xp home', 'offfice23', 'تحت الصيانة', 'nori', '2010-01-01', '2025-11-30', ''),
(5, '8765', 'Laptop', 'lenovo', '2025', '678905', 'i9', 64, '', 1024, 'windows 11 pro', 'office44', 'قيد الاستخدام', 'farah', '2025-11-01', '2029-12-25', '');

-- --------------------------------------------------------

--
-- Table structure for table `landlines`
--

CREATE TABLE `landlines` (
  `id` int(11) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `department` varchar(100) NOT NULL,
  `status` enum('نشط','غير متاح') NOT NULL DEFAULT 'نشط',
  `assigned_to` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `landlines`
--

INSERT INTO `landlines` (`id`, `phone_number`, `extension`, `location`, `department`, `status`, `assigned_to`, `notes`) VALUES
(1, '4567', '64', 'office22', 'it_os', '', 'john', ''),
(2, '675432', '7', 'مكتب3', 'المهام  العامة', '', 'عبدالله', ''),
(3, '8765', NULL, 'op', 'iy', '', NULL, NULL),
(4, '76554', NULL, 'lap4', 'office3', '', NULL, NULL),
(7, '7654', NULL, 'lap5', 'it6', '', NULL, NULL),
(8, '87654', NULL, 'kjl', 'it8', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `printers`
--

CREATE TABLE `printers` (
  `id` int(11) NOT NULL,
  `asset_tag` varchar(50) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `type` enum('Laser','Inkjet','Multifunction') NOT NULL,
  `location` varchar(255) NOT NULL,
  `status` enum('يعمل','تحتاج حبر/حبر نافذ','تحت الصيانة','معطل') NOT NULL DEFAULT 'يعمل',
  `ip_address` varchar(15) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `printers`
--

INSERT INTO `printers` (`id`, `asset_tag`, `make`, `model`, `serial_number`, `type`, `location`, `status`, `ip_address`, `purchase_date`, `notes`) VALUES
(1, 'ui', 'hp', '678', '321568', 'Inkjet', 'kj4', 'تحت الصيانة', '', '2025-11-02', ''),
(2, '76543', 'canon', '27654', '2erf6789', 'Inkjet', 'office6', 'معطل', NULL, '2025-11-12', ''),
(4, '986543', 'sony', '2020', '7652e68', 'Inkjet', 'lap4', 'يعمل', '43216790', '2020-06-25', '');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `user_id`, `username`, `message`, `status`, `created_at`) VALUES
(1, 1, 'admin', 'مرحبا', 'open', '2025-11-24 12:59:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$12$sf3ZfNg7EnrqAIkc8INr/ezl96luz3rtSs7N3ao2f789NHyG7CITm', 'مدير النظام', 'admin', '2025-11-23 20:44:51'),
(3, 'it1', '$2y$10$8K1O/aOq8p9p5r7s3t2v.uO1qH6pN9sT0vW5xY3zA7cB4eD8fG2hI6jK0lM4nQ', 'مستخدم الدعم الفني', 'user', '2025-11-24 14:30:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `computers`
--
ALTER TABLE `computers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asset_tag` (`asset_tag`),
  ADD UNIQUE KEY `serial_number` (`serial_number`);

--
-- Indexes for table `landlines`
--
ALTER TABLE `landlines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD UNIQUE KEY `extension` (`extension`);

--
-- Indexes for table `printers`
--
ALTER TABLE `printers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asset_tag` (`asset_tag`),
  ADD UNIQUE KEY `serial_number` (`serial_number`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `computers`
--
ALTER TABLE `computers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `landlines`
--
ALTER TABLE `landlines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `printers`
--
ALTER TABLE `printers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
