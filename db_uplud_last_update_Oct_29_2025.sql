-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 08:59 PM
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
-- Database: `db_uplug`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `verified` tinyint(1) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expiry` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`admin_id`, `email`, `password_hash`, `verified`, `reset_token`, `reset_expiry`) VALUES
(1, 'uplug.noreply@gmail.com', '$2y$10$m8WD5SOwvxf6sCmc6UqTZ..NPWi1e4VWLn9fEP6oseOzh/CWiqZqO', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_users`
--

CREATE TABLE `faculty_users` (
  `seq_id` int(11) NOT NULL,
  `faculty_id` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `department` enum('SHS','CITE','CCJE','CAHS','CAS','CEA','CELA','CMA','COL') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expiry` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` varchar(255) NOT NULL,
  `sender_type` enum('student','faculty') NOT NULL,
  `receiver_id` varchar(255) NOT NULL,
  `receiver_type` enum('student','faculty') NOT NULL,
  `content` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `seen` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `sender_type`, `receiver_id`, `receiver_type`, `content`, `sent_at`, `seen`) VALUES
(113, 'STU-25-CITE', 'student', 'STU-27-CAHS', 'student', 'hi bbgirl', '2025-10-26 17:38:33', 1),
(114, 'STU-27-CAHS', 'student', 'STU-25-CITE', 'student', 'wsg', '2025-10-26 17:38:40', 1),
(115, 'STU-27-CAHS', 'student', 'STU-25-CITE', 'student', 'wsg', '2025-10-26 17:38:43', 1),
(116, 'STU-27-CAHS', 'student', 'STU-25-CITE', 'student', 'wsg', '2025-10-26 17:38:44', 1),
(117, 'STU-27-CAHS', 'student', 'STU-25-CITE', 'student', 'wsg', '2025-10-26 17:38:46', 1),
(118, 'STU-27-CAHS', 'student', 'STU-25-CITE', 'student', 'wsg', '2025-10-26 17:38:46', 1),
(119, 'STU-28-CITE', 'student', 'STU-25-CITE', 'student', 'Yo', '2025-10-26 22:40:29', 1),
(120, 'STU-28-CITE', 'student', 'STU-25-CITE', 'student', 'Tite', '2025-10-26 22:40:31', 1),
(121, 'STU-28-CITE', 'student', 'STU-25-CITE', 'student', 'eut', '2025-10-26 22:40:33', 1),
(122, 'STU-28-CITE', 'student', 'STU-25-CITE', 'student', '😁', '2025-10-26 23:04:46', 1),
(123, 'STU-29-CITE', 'student', 'STU-26-CITE', 'student', 'low hey!', '2025-10-28 11:27:45', 0),
(124, 'STU-31-CITE', 'student', 'STU-25-CITE', 'student', 'lllalallaal', '2025-10-28 11:31:49', 1),
(125, 'STU-25-CITE', 'student', 'STU-31-CITE', 'student', 'lalaallalalala', '2025-10-28 11:32:04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` varchar(255) NOT NULL,
  `post_type` varchar(255) NOT NULL,
  `author_department` enum('SHS','CITE','CCJE','CAHS','CAS','CEA','CELA','CMA','COL') NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `edited_at` datetime DEFAULT NULL,
  `toast_status` tinyint(1) DEFAULT 0,
  `toast_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `content`, `author_id`, `post_type`, `author_department`, `create_date`, `edited_at`, `toast_status`, `toast_message`) VALUES
(67, 'Test', 'Delete this shit', 'STU-28-CITE', 'official', 'CITE', '2025-10-28 07:57:32', NULL, 1, 'CITE Student - Khezy Lee Calacsan posted a new official post!');

-- --------------------------------------------------------

--
-- Table structure for table `student_users`
--

CREATE TABLE `student_users` (
  `seq_id` int(11) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `department` enum('SHS','CITE','CCJE','CAHS','CAS','CEA','CELA','CMA','COL') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expiry` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_users`
--

INSERT INTO `student_users` (`seq_id`, `student_id`, `full_name`, `first_name`, `last_name`, `email`, `password_hash`, `department`, `profile_picture`, `verified`, `reset_token`, `reset_expiry`) VALUES
(26, 'STU-26-CITE', 'Zhyll Aguinaldo', 'Zhyll', 'Aguinaldo', 'zhra.aguinaldo.up@phinmaed.com', '$2y$10$B3l40p3V9ochPpCWn85XguFeCTOlClktH535JWN8oxK3JZksjxePe', 'CITE', 'assets/images/student-profiles/CITE/STU-26-CITE.png', 1, NULL, NULL),
(27, 'STU-27-CAHS', 'Janel Romero', 'Janel', 'Romero', 'jado.romero.up@phinmaed.com', '$2y$10$99NLhqooVZSHSHt9BeCW0up2YUQBVMErrBRXFeIX2uXxAl0lHm2u6', 'CAHS', 'assets/images/student-profiles/CAHS/STU-27-CAHS.png', 1, NULL, NULL),
(28, 'STU-28-CITE', 'Khezy Lee Calacsan', 'Khezy Lee', 'Calacsan', 'khes.calacsan.up@phinmaed.com', '$2y$10$phdLCGGq4PlUumGJLzTy.eTWIk0Anvy90Rb9Nw1arHjmjNNYNKuAi', 'CITE', 'assets/images/student-profiles/CITE/STU-28-CITE.png', 1, NULL, NULL),
(29, 'STU-29-CITE', 'Tyler Madriaga', 'Tyler', 'Madriaga', 'typi.madriaga.up@phinmaed.com', '$2y$10$SErMVYnQxMAFIL367.v5ROzIZL655tr/lVPhUKXeqEFsplx3L5c6W', 'CITE', 'assets/images/student-profiles/CITE/STU-29-CITE.jpg', 1, NULL, NULL),
(31, 'STU-31-CITE', 'Travis Landon Bucatcat', 'Travis Landon', 'Bucatcat', 'troc.bucatcat.up@phinmaed.com', '$2y$10$0Pd68.aTIGAFRyuxBQeZ2e92uTQlnkCEVh.VQ3c9Eu/UgN1a6R14i', 'CITE', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `toast_acknowledgments`
--

CREATE TABLE `toast_acknowledgments` (
  `id` int(11) NOT NULL,
  `user_id` varchar(50) DEFAULT NULL,
  `post_id` varchar(50) DEFAULT NULL,
  `acknowledged_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `toast_acknowledgments`
--

INSERT INTO `toast_acknowledgments` (`id`, `user_id`, `post_id`, `acknowledged_at`) VALUES
(30, 'STU-25-CITE', '46', '2025-10-26 01:53:12'),
(31, 'STU-25-CITE', '47', '2025-10-26 02:12:38'),
(32, 'STU-25-CITE', '54', '2025-10-26 02:18:49'),
(33, 'STU-25-CITE', '<br />\n<b>Warning</b>:  Undefined array key ', '2025-10-26 02:19:18'),
(34, 'STU-25-CITE', '56', '2025-10-26 02:20:32'),
(35, 'STU-25-CITE', 'reset_68fd1545b61504.53059541', '2025-10-26 02:22:04'),
(36, 'STU-25-CITE', 'upload_68fd1551151848.99933733', '2025-10-26 02:22:12'),
(37, 'STU-25-CITE', '58', '2025-10-26 02:26:09'),
(38, 'STU-27-CAHS', '56', '2025-10-26 17:36:27'),
(39, 'STU-27-CAHS', '58', '2025-10-26 17:36:27'),
(40, 'STU-27-CAHS', 'upload_68fdebc30f8820.72328721', '2025-10-26 17:37:09'),
(41, 'STU-27-CAHS', '<br />\n<b>Warning</b>:  Undefined array key ', '2025-10-26 17:37:12'),
(42, 'STU-27-CAHS', '61', '2025-10-26 17:39:24'),
(43, 'STU-28-CITE', '56', '2025-10-26 22:35:42'),
(44, 'STU-28-CITE', '58', '2025-10-26 22:35:42'),
(45, 'STU-28-CITE', '61', '2025-10-26 22:35:43'),
(46, 'STU-28-CITE', 'reset_68fe320a7c7ea9.07503976', '2025-10-26 22:37:12'),
(49, 'STU-25-CITE', '63', '2025-10-26 23:09:12'),
(50, 'STU-31-CITE', '56', '2025-10-28 11:32:08'),
(51, 'STU-31-CITE', '58', '2025-10-28 11:32:09'),
(52, 'STU-31-CITE', '61', '2025-10-28 11:32:10'),
(54, 'STU-31-CITE', '63', '2025-10-28 11:32:11'),
(55, 'STU-28-CITE', '66', '2025-10-28 11:39:21'),
(56, 'STU-25-CITE', '62', '2025-10-28 13:39:49'),
(57, 'STU-25-CITE', '66', '2025-10-28 13:39:50'),
(58, 'STU-25-CITE', '56', '2025-10-28 13:40:45'),
(59, 'STU-25-CITE', '58', '2025-10-28 13:40:46'),
(60, 'STU-25-CITE', '62', '2025-10-28 13:40:48'),
(61, 'STU-25-CITE', '63', '2025-10-28 13:40:49'),
(62, 'STU-25-CITE', '66', '2025-10-28 13:40:50'),
(63, 'STU-25-CITE', '56', '2025-10-28 13:42:19'),
(64, 'STU-25-CITE', '58', '2025-10-28 13:42:20'),
(65, 'STU-25-CITE', '62', '2025-10-28 13:42:21'),
(66, 'STU-25-CITE', '63', '2025-10-28 13:42:21'),
(67, 'STU-25-CITE', '66', '2025-10-28 13:42:21'),
(68, 'STU-25-CITE', '56', '2025-10-28 13:44:59'),
(69, 'STU-25-CITE', '58', '2025-10-28 13:45:00'),
(70, 'STU-25-CITE', '62', '2025-10-28 13:45:00'),
(71, 'STU-25-CITE', '63', '2025-10-28 13:45:00'),
(72, 'STU-25-CITE', '66', '2025-10-28 13:45:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `faculty_users`
--
ALTER TABLE `faculty_users`
  ADD PRIMARY KEY (`faculty_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `seq_id` (`seq_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `student_users`
--
ALTER TABLE `student_users`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `seq_id` (`seq_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `toast_acknowledgments`
--
ALTER TABLE `toast_acknowledgments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `faculty_users`
--
ALTER TABLE `faculty_users`
  MODIFY `seq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `student_users`
--
ALTER TABLE `student_users`
  MODIFY `seq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `toast_acknowledgments`
--
ALTER TABLE `toast_acknowledgments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
