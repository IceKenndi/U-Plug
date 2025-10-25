-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2025 at 09:25 AM
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
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_users`
--

INSERT INTO `faculty_users` (`seq_id`, `faculty_id`, `full_name`, `first_name`, `last_name`, `email`, `password_hash`, `department`, `profile_picture`) VALUES
(1, 'FAC-1-CCJE', 'Kenneth Dela Cruz', 'Kenneth', 'Dela Cruz', 'kenneth@faculty.com', '$2y$10$MexlMIxssmmOp1DAFmpq2Oh0iCP5ZYgG7XOf2ETB4DP/8e0Pjg88W', 'CCJE', 'assets/images/faculty-profiles/CCJE/FAC-1-CCJE.png'),
(2, 'FAC-2-COL', 'Mark Bandong', 'Mark', 'Bandong', 'bandonglaw@faculty.com', '$2y$10$ayumS9bK78qtIWlgyW92ruq2PDEGMRLGfvMdvS9UDYVWfvxslLgUu', 'COL', NULL),
(4, 'FAC-4-CITE', 'Phoenix Hidalgo', 'Phoenix', 'Hidalgo', 'phoenix@faculty.com', '$2y$10$gMreQSYoovc9KWuvPmXCMO6wKNbmi4sJtWrDJv/3M3MMBisIKeGOq', 'CITE', 'assets/images/faculty-profiles/CITE/FAC-4-CITE.png');

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
(92, 'FAC-1-CCJE', 'faculty', 'STU-1-CITE', 'student', 'Hi! Messaging test.', '2025-10-16 06:33:39', 1),
(93, 'STU-1-CITE', 'student', 'FAC-1-CCJE', 'faculty', 'Hello!', '2025-10-16 06:33:43', 1),
(94, 'FAC-1-CCJE', 'faculty', 'STU-1-CITE', 'student', 'hi!', '2025-10-25 01:12:52', 1),
(95, 'FAC-4-CITE', 'faculty', 'STU-1-CITE', 'student', 'yo', '2025-10-25 01:18:31', 1),
(96, 'FAC-4-CITE', 'faculty', 'STU-1-CITE', 'student', 'bingo bango bongo bish bash bosh', '2025-10-25 01:18:41', 1),
(97, 'STU-1-CITE', 'student', 'FAC-4-CITE', 'faculty', 'wsg nigga', '2025-10-25 01:18:49', 1),
(98, 'STU-1-CITE', 'student', 'STU-6-COL', 'student', 'yo', '2025-10-25 01:47:48', 1),
(99, 'STU-1-CITE', 'student', 'STU-6-COL', 'student', 'wanna hop on cs', '2025-10-25 01:47:51', 1),
(100, 'STU-6-COL', 'student', 'STU-1-CITE', 'student', 'fuck yeah', '2025-10-25 01:47:58', 1),
(101, 'STU-1-CITE', 'student', 'STU-6-COL', 'student', 'bet', '2025-10-25 01:48:16', 1),
(102, 'STU-1-CITE', 'student', 'STU-6-COL', 'student', 'test', '2025-10-25 01:48:23', 1),
(103, 'STU-1-CITE', 'student', 'STU-6-COL', 'student', 'test', '2025-10-25 01:48:26', 1);

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
(2, 'Yo', 'test', 'FAC-1-CCJE', 'department', 'CCJE', '2025-10-07 14:51:09', NULL, 0, NULL),
(5, 'as', 'asd', 'FAC-1-CCJE', 'department', 'CCJE', '2025-10-07 15:44:34', NULL, 0, NULL),
(6, 'asd', 'asd', 'STU-1-CITE', 'department', 'CITE', '2025-10-07 15:46:03', NULL, 0, NULL),
(7, 'asd', 'asd', 'STU-1-CITE', 'department', 'CITE', '2025-10-07 15:46:06', NULL, 0, NULL),
(10, 'test CITE official', 'test edit go to top\r\n', 'STU-1-CITE', 'official', 'CITE', '2025-10-09 12:55:19', '2025-10-15 02:01:04', 0, NULL),
(12, 'COL', 'Hi, *nakalimutan line*', 'FAC-2-COL', 'official', 'COL', '2025-10-09 12:58:32', NULL, 0, NULL),
(17, 'official front', 'testing my new tooltip\r\n', 'FAC-1-CCJE', 'official', 'CCJE', '2025-10-12 12:11:00', '2025-10-13 17:09:20', 0, NULL),
(26, 'profile first ever test', 'testing my new profile posting under the post type = \"personal\"', 'STU-1-CITE', 'personal', 'CITE', '2025-10-13 19:13:53', NULL, 0, NULL),
(27, 'second test', 'testing my location header from server function', 'STU-1-CITE', 'personal', 'CITE', '2025-10-13 19:15:49', NULL, 0, NULL),
(28, 'Third test', 'showing ts off like a fucking God', 'STU-1-CITE', 'personal', 'CITE', '2025-10-13 19:16:21', NULL, 0, NULL),
(29, 'Test department justin', 'edits', 'STU-2-CCJE', 'department', 'CCJE', '2025-10-14 14:28:16', '2025-10-14 22:31:18', 0, NULL),
(30, 'personal test', 'oten', 'STU-2-CCJE', 'personal', 'CCJE', '2025-10-14 14:28:30', NULL, 0, NULL),
(33, 'titekomalaki', 'tite', 'STU-4-CEA', 'personal', 'CEA', '2025-10-15 13:02:01', NULL, 0, NULL),
(34, 'jj oten', 'jjasd', 'FAC-3-CITE', 'official', 'CITE', '2025-10-15 14:42:40', '2025-10-15 22:42:46', 0, NULL),
(35, 'test', 'sd', 'FAC-1-CCJE', 'official', 'CCJE', '2025-10-24 17:13:00', NULL, 1, 'CCJE Faculty - Kenneth Dela Cruz posted a new official post!'),
(36, 'asd', 'asd', 'STU-1-CITE', 'official', 'CITE', '2025-10-24 17:13:23', NULL, 1, 'CITE Student - Kenneth Dela Cruz posted a new official post!'),
(37, 'this nigga called me a nigga??', 'lol fuck u', 'FAC-4-CITE', 'personal', 'CITE', '2025-10-24 17:19:07', NULL, 0, 'CITE Faculty - Phoenix Hidalgo posted a new personal post!'),
(38, 'tanginamo STU-1-CITE', 'asd', 'FAC-4-CITE', 'official', 'CITE', '2025-10-24 17:24:54', NULL, 1, 'CITE Faculty - Phoenix Hidalgo posted a new official post!');

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
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_users`
--

INSERT INTO `student_users` (`seq_id`, `student_id`, `full_name`, `first_name`, `last_name`, `email`, `password_hash`, `department`, `profile_picture`) VALUES
(1, 'STU-1-CITE', 'Kenneth Dela Cruz', 'Kenneth', 'Dela Cruz', 'kenneth@student.com', '$2y$10$5tcIaghybIcQgyjCuteW7.Y803T/MObqpVceP3vA4NMnbLIVWCVvy', 'CITE', 'assets/images/student-profiles/CITE/STU-1-CITE.png'),
(2, 'STU-2-CCJE', 'Justin Nabunturan', 'Justin', 'Nabunturan', 'justin@student.com', '$2y$10$FaxyGQ6YKYz7RyBdLATz7u6xTeVA9GnHQKuusguzJly.AkCZUAymi', 'CCJE', NULL),
(3, 'STU-3-CAHS', 'Janel Romero', 'Janel', 'Romero', 'janel@student.com', '$2y$10$74lyy1op4GXT4lhiITCeJ.fHmBmK0NhWy.ZUKckzbo5/OYLYTnTAu', 'CAHS', NULL),
(4, 'STU-4-CEA', 'cea cea', 'cea', 'cea', 'cea@student.com', '$2y$10$HmY7Kj3B5AVtWf8Ak0YkG.BYQgkI0QgCWAS6rnAx1/XTLXRZRBKLG', 'CEA', NULL),
(6, 'STU-6-COL', 'Zhyll Aguinaldo', 'Zhyll', 'Aguinaldo', 'zhyll@student.com', '$2y$10$kc3ShzWV2EttSpPjMvdEHOiKbIQ70Pc2kIrGa5HCssTYFajZIBl2i', 'COL', 'assets/images/student-profiles/COL/STU-6-COL.png');

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
(1, 'FAC-4-CITE', '36', '2025-10-25 01:24:45'),
(2, 'FAC-4-CITE', '35', '2025-10-25 01:24:46'),
(3, 'STU-3-CAHS', '35', '2025-10-25 01:25:14'),
(4, 'STU-3-CAHS', '36', '2025-10-25 01:25:14'),
(5, 'STU-3-CAHS', '38', '2025-10-25 01:25:15'),
(6, 'STU-1-CITE', '35', '2025-10-25 01:25:19'),
(7, 'STU-1-CITE', '38', '2025-10-25 01:25:19'),
(8, 'STU-6-COL', '35', '2025-10-25 01:47:01'),
(9, 'STU-6-COL', '36', '2025-10-25 01:47:02'),
(10, 'STU-6-COL', '38', '2025-10-25 01:47:03');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `faculty_users`
--
ALTER TABLE `faculty_users`
  MODIFY `seq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `student_users`
--
ALTER TABLE `student_users`
  MODIFY `seq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `toast_acknowledgments`
--
ALTER TABLE `toast_acknowledgments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
