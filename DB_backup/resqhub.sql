-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 02:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `resqhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `reference_type` enum('user','request','assignment','donation') NOT NULL,
  `reference_id` int(11) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `user_id`, `action`, `reference_type`, `reference_id`, `timestamp`) VALUES
(1, 7, 'Created emergency request', 'request', 1, '2026-01-17 14:35:33'),
(2, 8, 'Created emergency request', 'request', 2, '2026-01-17 14:35:33'),
(3, 11, 'Created emergency request', 'request', 0, '2026-01-19 14:34:28'),
(4, 11, 'Created emergency request', 'request', 0, '2026-01-19 14:34:55'),
(5, 11, 'Created emergency request', 'request', 0, '2026-01-19 14:48:37'),
(6, 11, 'Created emergency request', 'request', 0, '2026-01-19 14:48:52'),
(7, 11, 'Created donation', 'donation', 0, '2026-01-19 16:42:07'),
(8, 18, 'Overrode request status', 'request', 1, '2026-01-20 07:06:48');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `currency_code` char(3) NOT NULL,
  `currency_name` varchar(50) NOT NULL,
  `symbol` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`currency_code`, `currency_name`, `symbol`) VALUES
('BDT', 'Bangladeshi Taka', '৳'),
('EUR', 'Euro', '€'),
('USD', 'US Dollar', '$');

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE `donation` (
  `donation_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `donation_amount` decimal(10,2) NOT NULL,
  `currency_code` char(3) NOT NULL,
  `donor_region` enum('Bangladesh','Europe','USA') NOT NULL,
  `donation_status` enum('pending','completed','failed') DEFAULT 'pending',
  `donation_date` datetime DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation`
--

INSERT INTO `donation` (`donation_id`, `donor_id`, `donation_amount`, `currency_code`, `donor_region`, `donation_status`, `donation_date`, `remarks`) VALUES
(1, 11, 500.00, 'BDT', '', 'pending', '2026-01-19 16:42:07', 'ANYTHING');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_request`
--

CREATE TABLE `emergency_request` (
  `request_id` int(11) NOT NULL,
  `citizen_id` int(11) NOT NULL,
  `request_type` enum('food','medical','shelter','rescue','transport') NOT NULL,
  `description` text NOT NULL,
  `location_text` varchar(255) NOT NULL,
  `urgency_level` enum('low','medium','high') NOT NULL,
  `request_status` enum('pending','assigned','in_progress','completed','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_request`
--

INSERT INTO `emergency_request` (`request_id`, `citizen_id`, `request_type`, `description`, `location_text`, `urgency_level`, `request_status`, `created_at`, `updated_at`) VALUES
(1, 7, 'medical', 'Severe injury after road accident', 'Dhanmondi, Dhaka', 'high', '', '2026-01-17 14:35:33', '2026-01-20 07:06:48'),
(2, 8, 'food', 'Family without food after flood', 'Sirajganj', 'medium', 'pending', '2026-01-17 14:35:33', '2026-01-17 14:35:33'),
(3, 11, 'medical', 'KKKKK', 'AHMEDBAGH, BASHABO, DHAKA', 'medium', 'pending', '2026-01-19 14:34:28', '2026-01-19 14:34:28'),
(6, 11, 'medical', 'ASWFDSDF', 'DHALKA', 'low', 'pending', '2026-01-19 14:48:52', '2026-01-19 14:48:52');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `notification_type` enum('status_update','assignment','broadcast') NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_transaction`
--

CREATE TABLE `payment_transaction` (
  `transaction_id` int(11) NOT NULL,
  `donation_id` int(11) NOT NULL,
  `payment_method` enum('bank','card','mobile') NOT NULL,
  `transaction_reference` varchar(100) NOT NULL,
  `payment_status` enum('success','failed','pending') DEFAULT 'pending',
  `processed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_assignment`
--

CREATE TABLE `request_assignment` (
  `assignment_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `volunteer_id` int(11) NOT NULL,
  `assigned_at` datetime DEFAULT current_timestamp(),
  `current_status` enum('accepted','on_the_way','help_provided') DEFAULT 'accepted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('citizen','volunteer','admin') NOT NULL,
  `account_status` enum('active','inactive','suspended') DEFAULT 'active',
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password_hash`, `role`, `account_status`, `phone`, `created_at`, `last_login`) VALUES
(2, 'Red Crescent Medical Team', 'rc_med@resqhub.org', '$2y$10$hashed1', 'volunteer', 'active', '01720000001', '2026-01-17 14:35:33', NULL),
(3, 'City Ambulance Service', 'ambulance@resqhub.org', '$2y$10$hashed2', 'volunteer', 'active', '01720000002', '2026-01-17 14:35:33', NULL),
(4, 'Flood Rescue Unit', 'flood_rescue@resqhub.org', '$2y$10$hashed3', 'volunteer', 'active', '01720000003', '2026-01-17 14:35:33', NULL),
(5, 'Emergency Food Supply NGO', 'food_ngo@resqhub.org', '$2y$10$hashed4', 'volunteer', 'active', '01720000004', '2026-01-17 14:35:33', NULL),
(6, 'Disaster Transport Team', 'transport@resqhub.org', '$2y$10$hashed5', 'volunteer', 'active', '01720000005', '2026-01-17 14:35:33', NULL),
(7, 'Rahim Ahmed', 'rahim@gmail.com', '$2y$10$hashed6', 'citizen', 'active', '01810000001', '2026-01-17 14:35:33', NULL),
(8, 'Karim Uddin', 'karim@gmail.com', '$2y$10$hashed7', 'citizen', 'active', '01810000002', '2026-01-17 14:35:33', NULL),
(9, 'SHAHRIYAR RAHMAN', 'shahriyar.simoon@gmail.com', '$2y$10$ruxJZP85MFqjyzXVgxj72.aWO4EGfrJ2f2KVEcgH.C8o0Mc7/Dm8W', 'citizen', 'active', '01983559925', '2026-01-18 17:41:44', '2026-01-19 03:55:28'),
(10, 'sHahriyar simooN', 'mrkarabasaninfo@gmail.com', '$2y$10$RO/3jqGFcb8DGLQ9xuk.Felzj5OV7Kiot947Enys5RPq7DbKImN.q', 'volunteer', 'active', '01983559925', '2026-01-19 02:43:44', '2026-01-19 03:54:54'),
(11, 'simoonss', 'mdjerin23@gmail.com', '$2y$10$9fgUR86eTeT7Et/6uIZ7l.0yOzfQVB2RqdDtqb9wyegYmRuhqXU/K', 'citizen', 'active', '01983559925', '2026-01-19 14:07:44', '2026-01-20 03:25:00'),
(12, 'SRS', '123@gmail.com', '$2y$10$PLfNW15Md/CXS8ljSndnvOuJbZSGh82GSVgjT7kvczQlAI5ARJnSq', 'volunteer', 'active', '01983559925', '2026-01-20 01:24:24', '2026-01-20 01:24:42'),
(13, 'tasnim', 'tasnim@gmail.com', '$2y$10$39dhX.7igEJg.cO7jr5ncOM9afYb6O/Tdu/l95Si33GqrgqeZ0Z/K', 'volunteer', 'active', '01983559928', '2026-01-20 03:28:35', '2026-01-20 04:02:30'),
(18, 'System Admin', 'admin@resqhub.org', '$2y$10$bBjKroCxGsgG1vBz05wcW..owgde3A5ZGYciYqvgRuxyT/U50sG22', 'admin', 'active', '01710000000', '2026-01-20 05:54:05', '2026-01-20 06:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_profile`
--

CREATE TABLE `volunteer_profile` (
  `volunteer_profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_type` enum('medical','transport','food','rescue') NOT NULL,
  `availability_status` enum('available','unavailable') DEFAULT 'available',
  `total_help_completed` int(11) DEFAULT 0,
  `verified_status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_profile`
--

INSERT INTO `volunteer_profile` (`volunteer_profile_id`, `user_id`, `skill_type`, `availability_status`, `total_help_completed`, `verified_status`) VALUES
(1, 2, 'medical', 'available', 120, 1),
(2, 3, 'medical', 'available', 95, 1),
(3, 4, 'rescue', 'available', 80, 1),
(4, 5, 'food', 'available', 150, 1),
(5, 6, 'transport', 'available', 60, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_log_user` (`user_id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_code`);

--
-- Indexes for table `donation`
--
ALTER TABLE `donation`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `fk_donation_user` (`donor_id`),
  ADD KEY `fk_donation_currency` (`currency_code`);

--
-- Indexes for table `emergency_request`
--
ALTER TABLE `emergency_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_request_user` (`citizen_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_notification_user` (`user_id`);

--
-- Indexes for table `payment_transaction`
--
ALTER TABLE `payment_transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fk_transaction_donation` (`donation_id`);

--
-- Indexes for table `request_assignment`
--
ALTER TABLE `request_assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `fk_assignment_request` (`request_id`),
  ADD KEY `fk_assignment_volunteer` (`volunteer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `volunteer_profile`
--
ALTER TABLE `volunteer_profile`
  ADD PRIMARY KEY (`volunteer_profile_id`),
  ADD KEY `fk_volunteer_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `donation`
--
ALTER TABLE `donation`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `emergency_request`
--
ALTER TABLE `emergency_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_transaction`
--
ALTER TABLE `payment_transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_assignment`
--
ALTER TABLE `request_assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `volunteer_profile`
--
ALTER TABLE `volunteer_profile`
  MODIFY `volunteer_profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `donation`
--
ALTER TABLE `donation`
  ADD CONSTRAINT `fk_donation_currency` FOREIGN KEY (`currency_code`) REFERENCES `currency` (`currency_code`),
  ADD CONSTRAINT `fk_donation_user` FOREIGN KEY (`donor_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `emergency_request`
--
ALTER TABLE `emergency_request`
  ADD CONSTRAINT `fk_request_user` FOREIGN KEY (`citizen_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_transaction`
--
ALTER TABLE `payment_transaction`
  ADD CONSTRAINT `fk_transaction_donation` FOREIGN KEY (`donation_id`) REFERENCES `donation` (`donation_id`) ON DELETE CASCADE;

--
-- Constraints for table `request_assignment`
--
ALTER TABLE `request_assignment`
  ADD CONSTRAINT `fk_assignment_request` FOREIGN KEY (`request_id`) REFERENCES `emergency_request` (`request_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_assignment_volunteer` FOREIGN KEY (`volunteer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `volunteer_profile`
--
ALTER TABLE `volunteer_profile`
  ADD CONSTRAINT `fk_volunteer_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;