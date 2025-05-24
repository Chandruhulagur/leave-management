-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2025 at 12:26 PM
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
-- Database: `studentleave`
--

-- --------------------------------------------------------

--
-- Table structure for table `hods`
--

CREATE TABLE `hods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hods`
--

INSERT INTO `hods` (`id`, `name`, `email`, `password`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'chandrashekhara', 'chandruhulagur123@gmail.com', '$2y$10$VQzgsylrJsNP6Xs3JH.QxeTkci0OhfKpu1D6bhEQ25pdyP9zto0W.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leave_applications`
--

CREATE TABLE `leave_applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `leave_type` varchar(50) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `applied_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `notification_seen` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_applications`
--

INSERT INTO `leave_applications` (`id`, `student_id`, `leave_type`, `from_date`, `to_date`, `reason`, `status`, `applied_on`, `notification_seen`) VALUES
(1, 1, 'Sick', '2025-05-17', '2025-05-19', 'sick leave', 'Approved', '2025-05-17 05:27:32', 1),
(2, 1, 'Casual', '2025-05-17', '2025-05-20', 'casual leave', 'Rejected', '2025-05-17 05:37:36', 1),
(3, 1, 'Casual', '2025-05-17', '2025-05-20', 'casual leave', 'Approved', '2025-05-17 05:40:16', 1),
(4, 1, 'Earned', '2025-05-19', '2025-05-20', 'earned leave', 'Rejected', '2025-05-17 05:40:47', 1),
(5, 1, 'Earned', '2025-05-19', '2025-05-20', 'earned leave', 'Approved', '2025-05-17 05:42:03', 1),
(6, 2, 'Casual', '2025-05-17', '2025-05-19', 'casual leave', 'Approved', '2025-05-17 06:22:12', 0),
(7, 1, 'Casual', '2025-05-17', '2025-05-18', 'casual leave', 'Approved', '2025-05-17 07:24:36', 1),
(8, 1, 'Sick', '2025-05-17', '2025-05-18', 'sick leave', 'Approved', '2025-05-17 07:30:12', 1),
(10, 1, 'Earned', '2025-05-19', '2025-05-21', 'Dear Sir/Mada,\r\njlsfbkjasf', 'Approved', '2025-05-17 09:46:10', 0),
(11, 3, 'Casual', '2025-05-17', '2025-05-21', 'i want', 'Approved', '2025-05-17 10:16:11', 0),
(12, 3, 'Casual', '2025-05-17', '2025-05-22', 'i need', 'Approved', '2025-05-17 10:17:13', 0);

-- --------------------------------------------------------

--
-- Table structure for table `leave_contributions`
--

CREATE TABLE `leave_contributions` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `amount` decimal(5,2) NOT NULL,
  `note` text DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_contributions`
--

INSERT INTO `leave_contributions` (`id`, `sender_id`, `recipient_id`, `amount`, `note`, `date`) VALUES
(1, 2, 1, 2.00, 'he have ', '2025-05-17 11:56:23'),
(2, 1, 2, 1.00, 'he need', '2025-05-17 15:18:51'),
(3, 3, 1, 0.50, 'he need', '2025-05-17 15:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `casual_leave` int(11) DEFAULT 10,
  `sick_leave` int(11) DEFAULT 5,
  `earned_leave` int(11) DEFAULT 15,
  `leave_balance` decimal(5,2) DEFAULT 0.00,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `password`, `photo`, `casual_leave`, `sick_leave`, `earned_leave`, `leave_balance`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'chandu', 'chandu@gmail.com', '$2y$10$2wNDAOLEXGxIRzdlp6eSCejh8rXJKQ6aC7EyUzAGqrT.37vmDPWeC', 'p1.jpeg', 2, 5, 13, 15.50, '05c723ef2483670d447fdf5bcbe1e2898f28e41987509e0da40f13c711f2dec5', '2025-05-17 23:53:05'),
(2, 'yuvaraj', 'yuvaraj@gmail.com', '$2y$10$1n60ys/fOwB2cXbqkVzOWeOgw8xQZomWr7DBDJA6HI3zG9lgWjBOK', 'p2.jpeg', 10, 5, 15, 14.00, NULL, NULL),
(3, 'shivaji ', 'shivaji@gmail.com', '$2y$10$rb6WXjiaKNmWOCeKwoPYvu9MDX6T2BviIk6L2kJ1Sm1vpSqw91Gpu', 'p3.jpeg', 10, 5, 15, 14.50, NULL, NULL),
(4, 'sneha', 'xyz@gmail.com', '$2y$10$etHhe.8d4taA5xftBnnZIO0m2b1RWZ0BYBvmgh1Q91CM4fM8IX4o2', 'p4.jpeg', 10, 5, 15, 15.00, NULL, NULL),
(5, 'sahana', 'xyzz@gmail.com', '$2y$10$PS9dQDOvhz0GT6bLQTEsuOu6Pvc6lVtEDnXubKJ8V0NzaSm7rtKlm', 'p5.jpeg', 10, 5, 15, 15.00, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hods`
--
ALTER TABLE `hods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `leave_applications`
--
ALTER TABLE `leave_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `leave_contributions`
--
ALTER TABLE `leave_contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hods`
--
ALTER TABLE `hods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `leave_applications`
--
ALTER TABLE `leave_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `leave_contributions`
--
ALTER TABLE `leave_contributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leave_applications`
--
ALTER TABLE `leave_applications`
  ADD CONSTRAINT `leave_applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `leave_contributions`
--
ALTER TABLE `leave_contributions`
  ADD CONSTRAINT `leave_contributions_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `leave_contributions_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
