-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2025 at 03:46 PM
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
-- Database: `ubish`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee_details`
--

CREATE TABLE `employee_details` (
  `emp_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `sex` varchar(1) NOT NULL,
  `address` text NOT NULL,
  `religion` varchar(100) NOT NULL,
  `civil_status` varchar(20) NOT NULL,
  `legislature` varchar(100) NOT NULL,
  `access_level` tinyint(4) NOT NULL,
  `phone_no` varchar(11) NOT NULL,
  `picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_details`
--

INSERT INTO `employee_details` (`emp_id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `sex`, `address`, `religion`, `civil_status`, `legislature`, `access_level`, `phone_no`, `picture`) VALUES
(1001, 'Admin', 'Santos', 'Dela Cruz', '2000-01-01', 'F', 'Philippines', 'Atheist', 'Single', 'Punong Barangay', 3, '09123456789', '../uploads/default_profile.jpg'),
(1002, 'Mark Simon', 'Zuilan', 'Bringas', '2005-02-16', 'M', 'Baguio City, Philippines', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, '09987654321', '../uploads/67ebdf0643e66.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `login_details`
--

CREATE TABLE `login_details` (
  `user_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_details`
--

INSERT INTO `login_details` (`user_id`, `emp_id`, `username`, `email`, `password`) VALUES
(1, 1001, 'admin', 'admin@email.com', '$2y$10$0iylyu1wZasjzL9CnMRLN.8yCdNHV7UITtmyUctM4VRdah17Rjm46'),
(2, 1002, 'mbringas', 'mbringas@email.com', '$2y$10$GS81dsvvhFZwDUqRJ73OmOFZgKEzctb9Aod9sb7OOLcHdrZNPTXxi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee_details`
--
ALTER TABLE `employee_details`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `login_details`
--
ALTER TABLE `login_details`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_user_emp` (`emp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee_details`
--
ALTER TABLE `employee_details`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1003;

--
-- AUTO_INCREMENT for table `login_details`
--
ALTER TABLE `login_details`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login_details`
--
ALTER TABLE `login_details`
  ADD CONSTRAINT `fk_user_emp` FOREIGN KEY (`emp_id`) REFERENCES `employee_details` (`emp_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
