-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2025 at 04:28 AM
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

--
-- Dumping data for table `demographics`
--

INSERT INTO `demographics` (`dm_id`, `user_id`, `youth_classification`, `specific_needs`, `educational_background`, `register_sk_voter`, `vote_last_sk_election`, `registered_national_voter`, `attended_sk_assembly`) VALUES
(1, 1, 'In_School_Youth', 'None', 'High School Level', 1, 1, 0, 1),
(2, 2, 'In_School_Youth', 'None', '', 1, 1, 1, 1),
(3, 3, 'Youth_with_Specific_Needs', 'PWD', 'Elementary Level', 0, 0, 0, 1),
(4, 4, 'Working_Youth', 'None', 'College Level', 1, 1, 1, 1),
(5, 5, 'Out_of_School_Youth', 'PWD', 'College Level', 0, 0, 1, 0),
(6, 6, 'In_School_Youth', 'None', 'College Level', 1, 0, 1, 0),
(7, 7, 'In_School_Youth', 'None', 'College Level', 1, 1, 1, 1),
(8, 8, 'In_School_Youth', 'None', 'Elementary Level', 0, 0, 0, 0);

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

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `feedback_type`, `message`, `date_submitted`) VALUES
(1, 1, 'Website Feedback', 'fix sidebar', '2025-03-28 09:25:51'),
(2, 1, 'SK Feedback', 'sk members are doing well with providing the youth with opportunities', '2025-03-28 11:40:55');

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

--
-- Dumping data for table `guardian_info`
--

INSERT INTO `guardian_info` (`guardian_id`, `user_id`, `guardian_name`, `guardian_contact`, `relationship`) VALUES
(1, 1, 'Liberty Trinidad', '09176981920', 'Parent'),
(2, 3, 'Charles Brazal', '09819523817', 'Legal Guardian'),
(3, 4, 'testing', '09819523817', 'Parent'),
(4, 8, 'william', '09182745918', 'Relative');

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

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`login_id`, `usernm`, `passwrd`, `email`, `created_at`, `role`) VALUES
(1, 'under18', '$2y$10$e7E4XfCIDqQohsDuu2tSjOEw16qA7iXMMR4EXfBdazUEQQcvHxAPS', 'yologames6@gmail.com', '2025-02-21 21:27:31', 'user'),
(2, 'over18', '$2y$10$UvMSDL0glTDAvdx4av/aLuPN61EVq7fdTxhQGJ4UjEKaYvQsm8tga', 'asd@yahoo.com', '2025-02-21 21:28:31', 'user'),
(3, 'testing', '$2y$10$cO9LqclA46NTXWKJA4r3PuCLbnKpeGiumAXbdTtMsrYehwgdVOTGe', 'asd@gmail.com', '2025-02-21 21:29:38', 'user'),
(4, 'admin', '$2y$10$8i5LzpvPxRg8MZ1T50ms...wi1u6fUmPACvcw/GSTbHj9rQbR5gY6', 'yologames6@gmail.com', '2025-02-25 10:52:40', 'admin'),
(5, 'another', '$2y$10$2wz6AY5IbN2kgHiRIR6UNudtICW4ffOHwB241uRXHiWOQt.GcSd2a', 'testt@gmail.com', '2025-02-27 07:53:57', 'user'),
(6, 'vikisnothere', '$2y$10$oSibKu3eeCFCaIGnsiCGpudsVvF0Fo5tFOAZMpXseix8mOy5xRSXy', 'asd@gmail.com', '2025-02-27 08:02:21', 'user'),
(7, 'william', '$2y$10$8lM65GE3UkxR9dM8DHFmHOpa8vGHV6sPp5RhtQTy7HiExWnDEWvKS', 'william@gmail.com', '2025-03-28 06:57:01', 'user'),
(8, '123', '$2y$10$hVwb2zf.nmNoCZxDwo0syubRr/7n4/ZDDLXYpqPndKNg51kEGX5ja', '123@gmail.com', '2025-03-28 06:59:33', 'user'),
(9, 'sarls', '$2y$10$SIjDT7PHhKbtTe06zf2q4eaQQ7JMm4/usVle3M62wH6py09h6SIUG', 'sasdihasj@gmail.com', '2025-03-28 19:36:56', 'user'),
(10, 'lodi', '$2y$10$EbLRJK8lvcvqIbw33XgBrON/hPKilGKMrV3ldJM87YgCNJ3lfDSrW', 'lodi123@gmail.com', '2025-03-28 22:45:54', 'user');

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

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `program_name`, `description`, `status`, `start_date`, `end_date`) VALUES
(1, 'testing', 'description update test', 'Active', '2025-02-28', '2025-03-01'),
(2, 'testing 2', 'adminadmin', 'Inactive', '2025-04-15', '2025-04-19'),
(3, 'Scholarship Program', 'This program allows the SK to provide scholarship to students in need', 'Active', '2026-03-29', '2026-04-17'),
(4, 'Feeding Program', 'Free Food for the children', 'Active', NULL, NULL),
(5, 'Educational Program', 'Studying material for the youth', 'Active', '2025-03-28', '2026-03-28'),
(6, 'Dental Clinic', 'Free dental services', 'Active', '2025-04-05', '2025-04-06');

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

--
-- Dumping data for table `program_registrations`
--

INSERT INTO `program_registrations` (`registration_id`, `login_id`, `program_id`, `status`, `notified`, `created_at`) VALUES
(3, 1, 1, 'Completed', 0, '2025-02-27 08:00:47'),
(4, 1, 1, 'Completed', 0, '2025-02-27 08:00:47'),
(5, 1, 2, 'Registered', 0, '2025-02-27 08:00:47'),
(6, 2, 2, 'Registered', 0, '2025-02-27 08:00:47'),
(7, 2, 1, 'Completed', 0, '2025-02-27 08:00:47'),
(8, 3, 2, 'Completed', 0, '2025-02-27 08:00:47'),
(9, 5, 1, 'Completed', 0, '2025-02-27 08:00:47'),
(10, 5, 2, 'Registered', 0, '2025-02-27 08:00:47'),
(11, 6, 1, 'Completed', 0, '2025-02-27 08:02:50'),
(12, 6, 3, 'Completed', 0, '2025-02-27 08:12:53'),
(13, 6, 4, 'Registered', 0, '2025-03-07 14:59:20'),
(14, 1, 5, 'Registered', 0, '2025-03-28 14:56:33'),
(15, 1, 6, 'Registered', 0, '2025-03-28 18:40:14'),
(16, 1, 4, 'Registered', 0, '2025-03-28 18:47:19');

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `gender`, `birth_date`, `contact_number`, `address`, `status`, `occupation`, `created_at`) VALUES
(1, 'Paul William', 'Trinidad', 'Male', '2012-04-06', '09052665538', 'nagkaisang nayon', 'Married', 'Working_Student', '2025-02-21 21:27:56'),
(2, 'Paul Lance', 'Masungsong', 'Male', '2005-07-12', '09185274142', 'nagkaisang nayon', 'Single', 'Student', '2025-02-21 21:29:04'),
(3, 'Paul Lance', 'Masungsong', 'Female', '2014-09-23', '09338672371', 'nagkaisang nayon', 'Married', 'Working_Student', '2025-02-21 21:30:57'),
(4, 'admin', 'admin', 'Male', '2025-02-02', '09185274142', 'nagkaisang nayon', 'Single', 'Student', '2025-02-25 10:52:54'),
(5, 'Nina Daphne', 'Catapang', 'Female', '2003-04-27', '09185274142', 'nagkaisang nayon', 'Single', 'Student', '2025-02-27 07:54:24'),
(6, 'Paul William', 'Trinidad', 'Male', '2004-04-06', '09052665538', 'nagkaisang nayon', 'Single', 'Student', '2025-02-27 08:02:37'),
(7, 'testing', 'asd', 'Male', '2005-06-08', '09338672371', 'nagkaisang nayon', 'Single', 'Student', '2025-03-28 06:58:10'),
(8, 'asd', 'asdf', 'Male', '2017-11-15', '09184928142', 'nagkaisang nayon', 'Single', 'Student', '2025-03-28 07:08:25');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `demographics`
--
ALTER TABLE `demographics`
  MODIFY `dm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `guardian_info`
--
ALTER TABLE `guardian_info`
  MODIFY `guardian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `program_registrations`
--
ALTER TABLE `program_registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
