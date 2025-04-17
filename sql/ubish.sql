-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2025 at 11:53 AM
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
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `privacy` varchar(7) NOT NULL,
  `category` varchar(100) NOT NULL,
  `author_id` int(11) NOT NULL,
  `post_date` datetime DEFAULT current_timestamp(),
  `thumbnail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `body`, `privacy`, `category`, `author_id`, `post_date`, `thumbnail`) VALUES
(2005, 'Promoting Cleanliness and Responsible Pet Ownership', 'To all valued residents of Greenwater Village,\r\n\r\nIn line with our commitment to maintaining a clean, healthy, and safe environment for everyone, we would like to remind all residents to take part in preserving the cleanliness of our community. Please ensure the following:\r\n1. Keep your surroundings clean. Sweep and dispose of trash properly. Let’s work together to prevent the buildup of garbage in streets, canals, and public areas.\r\n2. Clean up after your pets. Pet owners are kindly reminded to immediately clean up their pets’ excrements when walking or letting them out in public spaces. Bring a disposable bag and ensure proper disposal of pet waste.\r\n3. Avoid littering. Use the designated trash bins around the barangay. Segregate your waste accordingly.\r\n\r\nIntegrity is the quality of being honest and having strong moral principles that you refuse to change. Let’s be responsible citizens and lead by example.\r\n\r\nThank you for your cooperation.', 'Public', 'Public Notice', 4, '2025-04-17 10:11:01', '../uploads/attachments/thumbnail_1744877461_thumbnailtest01.jpg'),
(2009, 'Holy Week 2025 Schedule', 'FROM MALACAÑANG:\r\n\r\nMemorandum Circular No. 81, s. 2025\r\n\r\nPursuant to Memorandum Circular No. 81, government offices shall adopt work-from-home (WFH) arrangements from 8:00 AM to 12:00 PM on April 16, 2025.\r\nWork shall be suspended from 12:00 PM onwards to give employees full opportunity to properly observe Maundy Thursday and Good Friday and to travel to and from the different regions.\r\nThe adoption of WFH and suspension of work for private companies and offices is left to the discretion of their respective employers.', 'Private', 'Memorandum', 1, '2025-04-17 11:06:26', '../uploads/attachments/thumbnail_1744880786_thumbnailtest02.jpg'),
(2010, 'Test Announcement', 'This is a test description w/o thumbnail, w/o attachment.', 'Public', 'Test Category', 2, '2025-04-17 11:22:52', NULL),
(2011, 'Test Private Announcement', 'This is a test private description w/o thumbnail, w/o attachment.', 'Private', 'Test Category', 2, '2025-04-17 11:26:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `attachment_id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `upload_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`attachment_id`, `announcement_id`, `file_path`, `file_name`, `upload_date`) VALUES
(3010, 2009, '../uploads/attachments/attachment_1744880786_attachmenttest01.pdf', 'attachment_1744880786_attachmenttest01.pdf', '2025-04-17 11:06:26');

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
(1002, 'Mark Simon', 'Zuilan', 'Bringas', '2005-02-16', 'M', 'Baguio City, Philippines', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, '09987654321', '../uploads/67ebdf0643e66.jpg'),
(1004, 'John', 'Smith', 'Doe', '2000-01-02', 'M', 'Philippines', 'Atheist', 'Single', 'Sangguniang Barangay Member', 2, '09111111111', '../uploads/default_profile.jpg');

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
(2, 1002, 'mbringas', 'mbringas@email.com', '$2y$10$GS81dsvvhFZwDUqRJ73OmOFZgKEzctb9Aod9sb7OOLcHdrZNPTXxi'),
(4, 1004, 'jdoe', 'jdoe@email.com', '$2y$10$VCPFcQVgrnx1lzn4DxZWbedaopwO.0jAZWLzRMuMkqI7MMIfz5noq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `author` (`author_id`) USING BTREE;

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`attachment_id`),
  ADD KEY `announcement_id` (`announcement_id`);

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
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2012;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3011;

--
-- AUTO_INCREMENT for table `employee_details`
--
ALTER TABLE `employee_details`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1005;

--
-- AUTO_INCREMENT for table `login_details`
--
ALTER TABLE `login_details`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `login_details` (`user_id`);

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`announcement_id`) ON DELETE CASCADE;

--
-- Constraints for table `login_details`
--
ALTER TABLE `login_details`
  ADD CONSTRAINT `fk_user_emp` FOREIGN KEY (`emp_id`) REFERENCES `employee_details` (`emp_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
