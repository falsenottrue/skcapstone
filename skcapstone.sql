-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2025 at 03:54 AM
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
-- Database: `skcapstone`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_feedback`
--

CREATE TABLE `ai_feedback` (
  `id` int(11) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `feedback` enum('Yes','No') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announ`
--

CREATE TABLE `announ` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `doc_path` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_allocation`
--

CREATE TABLE `budget_allocation` (
  `id` int(11) NOT NULL,
  `center` varchar(100) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `demographics`
--

CREATE TABLE `demographics` (
  `dm_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `youth_classification` enum('In_School_Youth','Out_of_School_Youth','Working_Youth','Youth_with_Specific_Needs') NOT NULL,
  `specific_needs` enum('PWD','Indigenous','Teen_Parent','Solo_Parent','None') DEFAULT 'None',
  `educational_background` enum('Elementary Level','High School Level','College Level') NOT NULL,
  `register_sk_voter` tinyint(1) DEFAULT 0,
  `vote_last_sk_election` tinyint(1) DEFAULT 0,
  `registered_national_voter` tinyint(1) DEFAULT 0,
  `attended_sk_assembly` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback_type` enum('Website Feedback','SK Feedback','Consultation Feedback') NOT NULL,
  `message` text NOT NULL,
  `date_submitted` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guardian_info`
--

CREATE TABLE `guardian_info` (
  `guardian_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `guardian_name` varchar(100) NOT NULL,
  `guardian_contact` varchar(15) NOT NULL,
  `relationship` enum('Parent','Relative','Legal Guardian') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_id` int(11) NOT NULL,
  `usernm` varchar(32) NOT NULL,
  `passwrd` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(11) NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_registrations`
--

CREATE TABLE `program_registrations` (
  `registration_id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `status` enum('Registered','Completed','Cancelled') DEFAULT 'Registered',
  `notified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `birth_date` date NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `address` text NOT NULL,
  `status` enum('Single','Married','Widowed','Separated','Annulled') NOT NULL,
  `occupation` enum('Student','Working_Student','Unemployed') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_feedback`
--
ALTER TABLE `ai_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announ`
--
ALTER TABLE `announ`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budget_allocation`
--
ALTER TABLE `budget_allocation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `demographics`
--
ALTER TABLE `demographics`
  ADD PRIMARY KEY (`dm_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `guardian_info`
--
ALTER TABLE `guardian_info`
  ADD PRIMARY KEY (`guardian_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `program_registrations`
--
ALTER TABLE `program_registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD KEY `login_id` (`login_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_feedback`
--
ALTER TABLE `ai_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announ`
--
ALTER TABLE `announ`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget_allocation`
--
ALTER TABLE `budget_allocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `demographics`
--
ALTER TABLE `demographics`
  MODIFY `dm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guardian_info`
--
ALTER TABLE `guardian_info`
  MODIFY `guardian_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_registrations`
--
ALTER TABLE `program_registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `demographics`
--
ALTER TABLE `demographics`
  ADD CONSTRAINT `demographics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `guardian_info`
--
ALTER TABLE `guardian_info`
  ADD CONSTRAINT `guardian_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `program_registrations`
--
ALTER TABLE `program_registrations`
  ADD CONSTRAINT `program_registrations_ibfk_1` FOREIGN KEY (`login_id`) REFERENCES `login` (`login_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `program_registrations_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`program_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
