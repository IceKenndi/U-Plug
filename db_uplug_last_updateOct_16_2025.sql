-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2025 at 06:14 PM
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
  `department` enum('SHS','CITE','CCJE','CAHS','CAS','CEA','CELA','CMA','COL') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_users`
--

INSERT INTO `faculty_users` (`seq_id`, `faculty_id`, `full_name`, `first_name`, `last_name`, `email`, `password_hash`, `department`) VALUES
(1, 'FAC-1-CCJE', 'Kenneth Dela Cruz', 'Kenneth', 'Dela Cruz', 'kenneth@faculty.com', '$2y$10$MexlMIxssmmOp1DAFmpq2Oh0iCP5ZYgG7XOf2ETB4DP/8e0Pjg88W', 'CCJE'),
(2, 'FAC-2-COL', 'Mark Bandong', 'Mark', 'Bandong', 'bandonglaw@faculty.com', '$2y$10$ayumS9bK78qtIWlgyW92ruq2PDEGMRLGfvMdvS9UDYVWfvxslLgUu', 'COL'),
(3, 'FAC-3-CITE', 'Jan Julien Narvasa', 'Jan Julien', 'Narvasa', 'jjnarvasa@faculty.com', '$2y$10$l4BWXtBorpSVBdq9dRJswubEmjSEuzKSHjCs/DHvIbH8if7QyxhqG', 'CITE');

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
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `sender_type`, `receiver_id`, `receiver_type`, `content`, `sent_at`) VALUES
(46, 'STU-4-CEA', 'student', 'STU-1-CITE', 'student', 'bangon nigga', '2025-10-15 20:49:13'),
(47, 'STU-1-CITE', 'student', 'STU-4-CEA', 'student', 'angas nigger', '2025-10-15 20:49:45'),
(48, 'STU-4-CEA', 'student', 'STU-1-CITE', 'student', 'yo', '2025-10-15 20:51:56'),
(49, 'STU-4-CEA', 'student', 'STU-1-CITE', 'student', 'tite', '2025-10-15 20:52:48'),
(50, 'STU-4-CEA', 'student', 'STU-1-CITE', 'student', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis condimentum fringilla velit, convallis ornare ipsum maximus vitae. Aliquam erat volutpat. Phasellus eleifend, metus ut porta volutpat, quam ligula tempor mi, et porta eros massa et elit. Nullam ut arcu finibus, egestas nibh nec, ornare mauris. Aenean ut lorem eros. Quisque ut erat vitae odio tempor faucibus vel vel ipsum. Donec magna justo, facilisis in mi in, tempus ultricies dui. Praesent eu sagittis velit. Nullam tristique lacus sit amet lectus euismod, a bibendum metus ullamcorper. Duis nec lobortis purus. Praesent eget feugiat nisl, eu volutpat quam. Fusce gravida egestas augue, quis.', '2025-10-15 20:53:45'),
(51, 'STU-4-CEA', 'student', 'STU-1-CITE', 'student', 'test', '2025-10-15 21:05:45'),
(52, 'FAC-3-CITE', 'faculty', 'STU-1-CITE', 'student', 'Wala tayong pasok bukas, next week na ang checking niyo. Also yung defense niyo is moved to October 32-36. yun lang thank you!', '2025-10-15 21:21:34'),
(53, 'STU-1-CITE', 'student', 'FAC-3-CITE', 'faculty', 'Okay po sir, thank you!', '2025-10-15 21:22:02'),
(54, 'FAC-3-CITE', 'faculty', 'STU-1-CITE', 'student', 'hello', '2025-10-15 21:55:05'),
(55, 'FAC-3-CITE', 'faculty', 'STU-1-CITE', 'student', 'yo', '2025-10-15 21:55:30'),
(56, 'STU-1-CITE', 'student', 'FAC-3-CITE', 'faculty', 'kakantutin kita', '2025-10-15 22:27:05'),
(57, 'FAC-3-CITE', 'faculty', 'STU-1-CITE', 'student', 'chuchupa na yan', '2025-10-15 22:27:20'),
(58, 'STU-1-CITE', 'student', 'FAC-3-CITE', 'faculty', 'yo', '2025-10-15 22:29:44'),
(59, 'FAC-3-CITE', 'faculty', 'FAC-2-COL', 'faculty', 'tropa mark', '2025-10-15 22:30:42'),
(60, 'FAC-2-COL', 'faculty', 'FAC-3-CITE', 'faculty', 'wsg jj', '2025-10-15 22:31:21'),
(61, 'FAC-3-CITE', 'faculty', 'FAC-2-COL', 'faculty', 'You: tropa mark  October 15, 2025 - 10:30 PM', '2025-10-15 22:32:08'),
(62, 'FAC-3-CITE', 'faculty', 'FAC-2-COL', 'faculty', 'You: tropa mark  October 15, 2025 - 10:30 PM', '2025-10-15 22:32:09'),
(63, 'FAC-3-CITE', 'faculty', 'FAC-2-COL', 'faculty', 'You: tropa mark  October 15, 2025 - 10:30 PM', '2025-10-15 22:32:09'),
(64, 'FAC-3-CITE', 'faculty', 'FAC-2-COL', 'faculty', 'You: tropa mark  October 15, 2025 - 10:30 PM', '2025-10-15 22:32:09'),
(65, 'FAC-3-CITE', 'faculty', 'FAC-2-COL', 'faculty', 'You: tropa mark  October 15, 2025 - 10:30 PM', '2025-10-15 22:32:10'),
(66, 'FAC-3-CITE', 'faculty', 'FAC-2-COL', 'faculty', 'You: tropa mark  October 15, 2025 - 10:30 PM', '2025-10-15 22:32:11'),
(67, 'FAC-3-CITE', 'faculty', 'FAC-2-COL', 'faculty', 'You: tropa mark  October 15, 2025 - 10:30 PM', '2025-10-15 22:32:12'),
(68, 'FAC-3-CITE', 'faculty', 'STU-1-CITE', 'student', 'check', '2025-10-15 23:30:26'),
(69, 'FAC-3-CITE', 'faculty', 'FAC-2-COL', 'faculty', 'test', '2025-10-15 23:47:31'),
(70, 'FAC-3-CITE', 'faculty', 'STU-4-CEA', 'student', 'working?', '2025-10-15 23:47:38'),
(71, 'FAC-3-CITE', 'faculty', 'FAC-1-CCJE', 'faculty', 'request ni paul tumaas to sa preview', '2025-10-15 23:50:30'),
(72, 'FAC-1-CCJE', 'faculty', 'FAC-3-CITE', 'faculty', 'olol', '2025-10-15 23:51:02'),
(73, 'FAC-3-CITE', 'faculty', 'STU-3-CAHS', 'student', 'hi', '2025-10-16 00:01:27');

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
  `edited_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `content`, `author_id`, `post_type`, `author_department`, `create_date`, `edited_at`) VALUES
(2, 'Yo', 'test', 'FAC-1-CCJE', 'department', 'CCJE', '2025-10-07 14:51:09', NULL),
(5, 'as', 'asd', 'FAC-1-CCJE', 'department', 'CCJE', '2025-10-07 15:44:34', NULL),
(6, 'asd', 'asd', 'STU-1-CITE', 'department', 'CITE', '2025-10-07 15:46:03', NULL),
(7, 'asd', 'asd', 'STU-1-CITE', 'department', 'CITE', '2025-10-07 15:46:06', NULL),
(10, 'test CITE official', 'test edit go to top\r\n', 'STU-1-CITE', 'official', 'CITE', '2025-10-09 12:55:19', '2025-10-15 02:01:04'),
(12, 'COL', 'Hi, *nakalimutan line*', 'FAC-2-COL', 'official', 'COL', '2025-10-09 12:58:32', NULL),
(17, 'official front', 'testing my new tooltip\r\n', 'FAC-1-CCJE', 'official', 'CCJE', '2025-10-12 12:11:00', '2025-10-13 17:09:20'),
(26, 'profile first ever test', 'testing my new profile posting under the post type = \"personal\"', 'STU-1-CITE', 'personal', 'CITE', '2025-10-13 19:13:53', NULL),
(27, 'second test', 'testing my location header from server function', 'STU-1-CITE', 'personal', 'CITE', '2025-10-13 19:15:49', NULL),
(28, 'Third test', 'showing ts off like a fucking God', 'STU-1-CITE', 'personal', 'CITE', '2025-10-13 19:16:21', NULL),
(29, 'Test department justin', 'edits', 'STU-2-CCJE', 'department', 'CCJE', '2025-10-14 14:28:16', '2025-10-14 22:31:18'),
(30, 'personal test', 'oten', 'STU-2-CCJE', 'personal', 'CCJE', '2025-10-14 14:28:30', NULL),
(33, 'titekomalaki', 'tite', 'STU-4-CEA', 'personal', 'CEA', '2025-10-15 13:02:01', NULL),
(34, 'jj oten', 'jjasd', 'FAC-3-CITE', 'official', 'CITE', '2025-10-15 14:42:40', '2025-10-15 22:42:46');

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
  `department` enum('SHS','CITE','CCJE','CAHS','CAS','CEA','CELA','CMA','COL') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_users`
--

INSERT INTO `student_users` (`seq_id`, `student_id`, `full_name`, `first_name`, `last_name`, `email`, `password_hash`, `department`) VALUES
(1, 'STU-1-CITE', 'Kenneth Dela Cruz', 'Kenneth', 'Dela Cruz', 'kenneth@student.com', '$2y$10$5tcIaghybIcQgyjCuteW7.Y803T/MObqpVceP3vA4NMnbLIVWCVvy', 'CITE'),
(2, 'STU-2-CCJE', 'Justin Nabunturan', 'Justin', 'Nabunturan', 'justin@student.com', '$2y$10$FaxyGQ6YKYz7RyBdLATz7u6xTeVA9GnHQKuusguzJly.AkCZUAymi', 'CCJE'),
(3, 'STU-3-CAHS', 'Janel Romero', 'Janel', 'Romero', 'janel@student.com', '$2y$10$74lyy1op4GXT4lhiITCeJ.fHmBmK0NhWy.ZUKckzbo5/OYLYTnTAu', 'CAHS'),
(4, 'STU-4-CEA', 'cea cea', 'cea', 'cea', 'cea@student.com', '$2y$10$HmY7Kj3B5AVtWf8Ak0YkG.BYQgkI0QgCWAS6rnAx1/XTLXRZRBKLG', 'CEA');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `faculty_users`
--
ALTER TABLE `faculty_users`
  MODIFY `seq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `student_users`
--
ALTER TABLE `student_users`
  MODIFY `seq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
