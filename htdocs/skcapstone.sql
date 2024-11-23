-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2024 at 08:56 PM
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
  `person_id` int(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `education_level` enum('Elementary (Grade 1-6)','High School (Grade 7-10)','Senior High School (Grade 11-12)') DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `hobbies` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `demographics`
--

INSERT INTO `demographics` (`person_id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `phone_number`, `address`, `education_level`, `religion`, `hobbies`, `created_at`) VALUES
(1, 'Paul William', 'Trinidad', '2002-06-04', 'Male', '09052665538', 'nagkaisang nayon', 'Elementary (Grade 1-6)', 'Catholic', 'reading', '2024-10-11 18:44:45'),
(2, 'Paul William', 'Trinidad', '2002-06-04', 'Male', '09052665538', 'nagkaisang nayon', 'Elementary (Grade 1-6)', 'Catholic', 'reading', '2024-10-11 18:44:45'),
(3, 'William', 'Lopez', '1999-03-26', 'Male', '09254781254', 'nagkaisang nayon', 'High School (Grade 7-10)', 'iglesia ni chris tiu', 'maglulu', '2024-10-11 18:44:45'),
(4, 'Trinidad', 'James', '2000-04-27', 'Female', '09124783528', 'nagkaisang nayon', 'Senior High School (Grade 11-12)', 'catholic', 'maglaro', '2024-10-11 22:25:40'),
(5, 'Nina Daphne', 'Catapang', '2000-04-27', 'Female', '09251436574', 'malinta', 'Senior High School (Grade 11-12)', 'catholic', 'crochet', '2024-10-11 22:46:56'),
(6, 'Nina Daphne', 'Catapang', '2000-04-27', 'Female', '09251436574', 'malinta', 'Senior High School (Grade 11-12)', 'catholic', 'crochet', '2024-10-11 22:49:34'),
(7, 'rayyan', 'tambidan', '2003-08-19', 'Male', '09384922881', 'nagkaisang nayon', 'Senior High School (Grade 11-12)', 'iglesia ni chris tiu', 'maglulu', '2024-10-11 22:51:27'),
(8, 'paulo', 'pol', '2000-01-01', 'Male', '09384922881', 'nagkaisang nayon', 'Elementary (Grade 1-6)', 'iglesia ni chris tiu', 'maglulu', '2024-10-12 02:32:40');

-- --------------------------------------------------------

--
-- Table structure for table `prinfo`
--

CREATE TABLE `prinfo` (
  `id` int(255) NOT NULL,
  `fname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `mname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `age` int(100) NOT NULL,
  `birthday` date NOT NULL,
  `users_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `usernm` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `passwrd` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `usernm`, `passwrd`, `email`, `created_at`) VALUES
(6, 'polsnottrue', '$2y$10$/VIhCZa83iuAGCTfODp6Se/1ykTupwyCG6mEezWpSOyBxsSrnshJK', 'falsenottrue@yahoo.com', '2024-10-05 00:00:00'),
(7, 'testing123', '$2y$10$jru74k8AyTYUPDBHJ2GTlupg476IhhpFTG92P0MRd4GGjpgQRWwcm', 'testing@yahoo.com', '2024-10-06 00:00:00'),
(8, 'testing', '$2y$10$lLS9cpDkppqB5g2NBfzbauSacGdY8yx6.AzVOU2U/hl/CJJrT0x2m', 'yologames6@gmail.com', '2024-10-06 22:27:43'),
(9, 'test', '$2y$10$dUZ97.JW0C2eIctA0jgO9.Y20ljrOFMaMmesg.CplV2ZiphO3fJ..', 'pol@yahoo.com', '2024-10-10 20:42:52'),
(10, 'asd', '$2y$10$wNjvkIown3s9ksiYMSZAOeJW6bqL8p2XnJaNr5ldWXBxwUhEn83Li', 'asd@gmail.com', '2024-10-10 21:20:09'),
(11, 'lol', '$2y$10$iuSNH6ihnjY/hMWtu0LI6eIMyaTWdHbTRUdg5L7DdMcZjmsuS4aLK', 'pols@hotmail.com', '2024-10-11 18:38:08'),
(12, 'luap', '$2y$10$yj1idHOz8Uo/sq15t8KuU.yrdXcEnDpwghZPYHr33oA88m.877dvW', 'lol@yagoo.com', '2024-10-11 22:24:42'),
(14, 'paul', '$2y$10$nDLKOq4BzpYAhDDMrhAvPOn530SthOy95cK4.PFEO7ofDa8KpXwWq', 'paul@bcp.com', '2024-10-11 22:43:44'),
(16, 'nine_reds', '$2y$10$gpj9v7KuaEHPvnuXshhBteD7y2iQOcr/1310oAYRev1NNxbObE.eG', 'polsss@gmail.com', '2024-10-11 22:49:07'),
(17, 'rayyan', '$2y$10$kTO3C2oPz0AlqjRmyj1nXOQQEiUzS72hU1po5MK0kykAUvnnk4Hvm', 'rayy@gmail.com', '2024-10-11 22:50:31'),
(18, 'charles', '$2y$10$8m3GfWWusjTAY5m6KPJw5eqlfCkV/ohGPGg9Z1gssNCdwBs7jDJzq', 'charles@gmail.com', '2024-10-11 22:59:48'),
(19, 'paulo', '$2y$10$2.ugh.G5.inuE9YiD.FBOOIHmoFGDgESx.xkeHyYidtanTI6H9nN.', 'paulo@gmail.com', '2024-10-12 02:32:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `demographics`
--
ALTER TABLE `demographics`
  ADD UNIQUE KEY `fk_person_id` (`person_id`);

--
-- Indexes for table `prinfo`
--
ALTER TABLE `prinfo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`usernm`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `demographics`
--
ALTER TABLE `demographics`
  MODIFY `person_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `prinfo`
--
ALTER TABLE `prinfo`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `prinfo`
--
ALTER TABLE `prinfo`
  ADD CONSTRAINT `prinfo_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
