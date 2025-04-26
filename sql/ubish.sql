-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 04:56 PM
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
(2005, 'Promoting Cleanliness and Responsible Pet Ownership', 'To all valued residents of Greenwater Village,\r\n\r\nIn line with our commitment to maintaining a clean, healthy, and safe environment for everyone, we would like to remind all residents to take part in preserving the cleanliness of our community. Please ensure the following:\r\n1. Keep your surroundings clean. Sweep and dispose of trash properly. Let’s work together to prevent the buildup of garbage in streets, canals, and public areas.\r\n2. Clean up after your pets. Pet owners are kindly reminded to immediately clean up their pets’ excrements when walking or letting them out in public spaces. Bring a disposable bag and ensure proper disposal of pet waste.\r\n3. Avoid littering. Use the designated trash bins around the barangay. Segregate your waste accordingly.\r\n\r\nIntegrity is the quality of being honest and having strong moral principles that you refuse to change. Let’s be responsible citizens and lead by example.\r\n\r\nThank you for your cooperation.', 'Public', 'Public Notice', 4, '2025-04-17 10:11:01', '../../uploads/attachments/thumbnail_1744877461_thumbnailtest01.jpg'),
(2009, 'Holy Week 2025 Schedule', 'FROM MALACAÑANG:\r\n\r\nMemorandum Circular No. 81, s. 2025\r\n\r\nPursuant to Memorandum Circular No. 81, government offices shall adopt work-from-home (WFH) arrangements from 8:00 AM to 12:00 PM on April 16, 2025.\r\nWork shall be suspended from 12:00 PM onwards to give employees full opportunity to properly observe Maundy Thursday and Good Friday and to travel to and from the different regions.\r\nThe adoption of WFH and suspension of work for private companies and offices is left to the discretion of their respective employers.', 'Private', 'Memorandum', 1, '2025-04-17 11:06:26', '../../uploads/attachments/thumbnail_1744880786_thumbnailtest02.jpg'),
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
(3010, 2009, '../../uploads/attachments/attachment_1744880786_attachmenttest01.pdf', 'attachment_1744880786_attachmenttest01.pdf', '2025-04-17 11:06:26');

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
(1001, 'Admin', 'Santos', 'Dela Cruz', '2000-01-01', 'F', 'Philippines', 'Atheist', 'Single', 'Punong Barangay', 3, '09123456789', '../../uploads/default_profile.jpg'),
(1002, 'Mark Simon', 'Zuilan', 'Bringas', '2005-02-16', 'M', 'Baguio City, Philippines', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, '09987654321', '../../uploads/profiles/67ebdf0643e66.jpg'),
(1004, 'John', 'Smith', 'Doe', '2000-01-02', 'M', 'Philippines', 'Atheist', 'Single', 'Sangguniang Barangay Member', 2, '09111111111', '../../uploads/default_profile.jpg'),
(1008, 'Jane', 'Smith', 'Doe', '2000-01-02', 'F', 'Philippines', 'Atheist', 'Married', 'Barangay Secretary', 2, '09222222222', '../../uploads/profiles/680cb23623f39.jpg'),
(1009, 'Jamie', 'Smith', 'Doe', '2000-01-03', 'I', 'Philippines', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, '09333333333', '../../uploads/default_profile.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `employee_registration`
--

CREATE TABLE `employee_registration` (
  `registration_emp_id` int(11) NOT NULL,
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
-- Dumping data for table `employee_registration`
--

INSERT INTO `employee_registration` (`registration_emp_id`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `sex`, `address`, `religion`, `civil_status`, `legislature`, `access_level`, `phone_no`, `picture`) VALUES
(202, 'William', 'Defiesta', 'Dosil', '2005-01-01', 'M', 'Isabela, Philippines', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, '09000000000', '../../uploads/temp/680bc45e12b02.jpg'),
(203, 'R J', 'Guerero', 'Salcedo', '2004-04-01', 'M', 'Tayug, Pangasinan', 'Roman Catholic', 'Married', 'Other Barangay Personnel', 1, '09444444444', '../../uploads/temp/680c9581afec1.jpg'),
(204, 'Jun Waleng', 'Delmas', 'Pasing', '2000-01-01', 'M', 'La Trinidad, Benguet', 'Roman Catholic', 'Divorced', 'Other Barangay Personnel', 1, '09555555555', '../../uploads/temp/680c97d6d7991.jpg'),
(205, 'Jamie', 'Smith', 'Doe', '2000-12-31', 'I', 'Philippines', 'Atheist', 'Single', 'Other Barangay Personnel', 1, '09222222222', '../../uploads/temp/680cace2ef55d.jpg'),
(206, 'Jamie', 'Smith', 'Doe', '2000-12-31', 'M', 'Philippines', 'Atheist', 'Single', 'Other Barangay Personnel', 1, '09222222222', '../../uploads/temp/680caeb2ad77e.jpg'),
(207, 'Jane', 'Smith', 'Doe', '2000-01-02', 'F', 'Philippines', 'Atheist', 'Married', 'Barangay Secretary', 2, '09222222222', '../../uploads/temp/680cb23623f39.jpg'),
(208, 'a', 'a', 'a', '1111-11-11', 'M', 'a', 'a', 'Legally Separated', 'Other Barangay Personnel', 1, '1', '../../uploads/default_profile.jpg'),
(209, 'Jamie', 'Smith', 'Doe', '2000-01-03', 'I', 'Philippines', 'Atheist', 'Single', 'Other Barangay Personnel', 1, '09333333333', '../../uploads/default_profile.jpg'),
(210, 'a', 'a', 'a', '1111-11-11', 'M', 'a', 'a', 'Single', 'Other Barangay Personnel', 1, '1', '../../uploads/default_profile.jpg'),
(211, 'a', 'a', 'a', '1111-11-11', 'M', 'a', 'a', 'Single', 'Punong Barangay', 3, '1', '../../uploads/default_profile.jpg'),
(212, 'Jamie', 'Smith', 'Doe', '2000-01-03', 'I', 'Philippines', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, '09333333333', '../../uploads/default_profile.jpg'),
(213, 'Mayor', 'Mayora', 'Major', '1999-01-01', 'F', 'Baguio City, Philippines', 'Roman Catholic', 'Married', 'Punong Barangay', 3, '09987654321', '../../uploads/temp/680cd13daccfb.jpg'),
(214, 'a', 'a', 'a', '1111-11-11', 'M', 'a', 'a', 'Married', 'Punong Barangay', 3, '09111111111', '../../uploads/temp/680cd8abaeae8.jpg'),
(215, 'q', 'q', 'q', '1111-11-11', 'F', 'q', 'q', 'Single', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(216, 'a', 'a', 'a', '1111-11-11', 'F', 'a', 'a', 'Single', 'Other Barangay Personnel', 1, '09999999999', '../../uploads/default_profile.jpg'),
(217, 'q', 'q', 'q', '1111-11-11', 'M', 'q', 'q', 'Married', 'Punong Barangay', 3, '09111111111', '../../uploads/default_profile.jpg'),
(218, 's', 's', 's', '1111-11-11', 'M', 's', 's', 'Single', 'Sangguniang Barangay Member', 2, '09111111111', '../../uploads/temp/680cdb61e1987.jpg'),
(219, 'Hitori', 'Bocchi', 'Gotou', '2009-02-21', 'F', 'Japan', 'Shinto', 'Single', 'Other Barangay Personnel', 1, '09000000000', '../../uploads/temp/680cf3482d20b.jpg');

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
(4, 1004, 'jdoe', 'jdoe@email.com', '$2y$10$VCPFcQVgrnx1lzn4DxZWbedaopwO.0jAZWLzRMuMkqI7MMIfz5noq'),
(8, 1008, 'janedoe', 'janedoe@email.com', '$2y$10$8suHYTzlraplsOOpfTltpe/mpQ3tWk4zxlGYB4Pl2vJmUx6wb/Uzu'),
(9, 1009, 'jamiedoe', 'jamied@email.com', '$2y$10$uEcnXGaJyvI.wzT9iKlgF.PU4YwjgSyjn2q6UhFibszCwK0aPziZ6');

-- --------------------------------------------------------

--
-- Table structure for table `login_registration`
--

CREATE TABLE `login_registration` (
  `registration_login_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_registration`
--

INSERT INTO `login_registration` (`registration_login_id`, `username`, `email`, `password`) VALUES
(302, 'wdosil', 'wdosil@gmail.com', '$2y$10$rm/LXAJATeIcrluIPMZum.GBDvhPS1WMyiyzQOuY1O.8ToMIboaj2'),
(303, 'rjsalcedo', 'salcedo@gmail.com', '$2y$10$dI/Fr1J/vBPHg/nQA/wYP.MRBfHR.s4K455dtxn4y4piuYVyTvkkK'),
(304, 'jpasing', 'jpasing@gmail.com', '$2y$10$Di8D3mYDhfz9enXdaDlg1.BQZpp8ozI7EFcNTxasIeWcp85J/z2L2'),
(305, 'jamiedoe', 'jamied@gmail.com', '$2y$10$seG.DrMp0eXS3l7ADtzIW.mT4.3oFPucenB.S7QrmrUfprclSC3RW'),
(306, 'jamiedoe', 'jamied@gmail.com', '$2y$10$g1DKtk.PqdQn5Qd7qKxLy.PGTnm8l50H0KdCDbvZK/GutTkbLL6Oe'),
(307, 'janedoe', 'janedoe@email.com', '$2y$10$8suHYTzlraplsOOpfTltpe/mpQ3tWk4zxlGYB4Pl2vJmUx6wb/Uzu'),
(308, 'a', 'a@a', '$2y$10$gsmlEiGFEigFRhVeVtWFLurJDPaFyibZfToi5MjhqDhqygyk9bGxu'),
(309, 'jamiedoe', 'jamied@gmail.com', '$2y$10$0b5aMkLWDA6XgAFOYmZZOuW/HCq9cuC3JwW2kR97STmzxiCGIweqO'),
(310, 'a', 'a@a', '$2y$10$4rGjh49nnrfrmjhJhvngDurWXted42vBO5uJWh3LTExInypcazzVa'),
(311, 'a', 'a@a', '$2y$10$vqTTD4cVAPBwWMj.SFatmuuoiXpaID0utdjt8b76mDBnO16Jz4rQS'),
(312, 'jamiedoe', 'jamied@email.com', '$2y$10$bF8Afn4vKyOP3I7ENCePxe0f19VGzxX.ss1VBR0bMszk/YfZtA/Ia'),
(313, 'mayor', 'mayor@gmail.com', '$2y$10$Z0zIgrdz7/cC5dHMz9cpYuKscp37zqakWTrA4iXch5ebWww6joNHS'),
(314, 'a', 'a@a', '$2y$10$ajYZtL4yWiszo/VcpqSk7.3tsgeqwAQ6D6H5X9G/XnnYbOwwNoWEW'),
(315, 'q', 'q@q', '$2y$10$zUD.d6RN.BUPxgfsHHnu9OWbDU/XUIfutu8rlxR5rnVCOZW0v9OKK'),
(316, 'a', 'a@a', '$2y$10$fiy74fASccvgYl3OY3ZMz.2Yc.WPFMgW.9wNyW0IajX0ei47v1i9m'),
(317, 'q', 'q@q', '$2y$10$FKZZpyn0Sjkth9uCwk9plemLZvlMfTUArGXn4Cx3yqJI20p14hjtO'),
(318, 's', 's@s', '$2y$10$AV2UNkC32gTESbZddpLqZOI0CZ9JI03gVH/4vjtSbxmBjgP1cqEri'),
(319, 'bocchi', 'bocchi@the.rock', '$2y$10$SEQTYjpR5QmwUxl.w5KuZuZwwvPKC96rnQaIZdZVO3sJL8L7WGC7C');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `registration_id` int(11) NOT NULL,
  `registration_emp_id` int(11) NOT NULL,
  `registration_login_id` int(11) NOT NULL,
  `status` enum('Pending','Approved','Denied') DEFAULT 'Pending',
  `request_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`registration_id`, `registration_emp_id`, `registration_login_id`, `status`, `request_date`) VALUES
(102, 202, 302, 'Denied', '2025-04-26 01:20:39'),
(103, 203, 303, 'Denied', '2025-04-26 16:13:06'),
(104, 204, 304, 'Denied', '2025-04-26 16:23:01'),
(105, 205, 305, 'Approved', '2025-04-26 17:52:52'),
(106, 206, 306, 'Approved', '2025-04-26 18:00:29'),
(107, 207, 307, 'Approved', '2025-04-26 18:15:32'),
(108, 208, 308, 'Denied', '2025-04-26 18:16:50'),
(109, 209, 309, 'Denied', '2025-04-26 18:28:51'),
(110, 210, 310, 'Denied', '2025-04-26 18:38:52'),
(111, 211, 311, 'Denied', '2025-04-26 18:42:51'),
(112, 212, 312, 'Approved', '2025-04-26 18:51:55'),
(113, 213, 313, 'Denied', '2025-04-26 20:28:10'),
(114, 214, 314, 'Denied', '2025-04-26 20:59:30'),
(115, 215, 315, 'Denied', '2025-04-26 20:59:55'),
(116, 216, 316, 'Approved', '2025-04-26 21:09:37'),
(117, 217, 317, 'Approved', '2025-04-26 21:10:00'),
(118, 218, 318, 'Approved', '2025-04-26 21:11:05'),
(119, 219, 319, 'Pending', '2025-04-26 22:53:19');

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
-- Indexes for table `employee_registration`
--
ALTER TABLE `employee_registration`
  ADD PRIMARY KEY (`registration_emp_id`);

--
-- Indexes for table `login_details`
--
ALTER TABLE `login_details`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_user_emp` (`emp_id`);

--
-- Indexes for table `login_registration`
--
ALTER TABLE `login_registration`
  ADD PRIMARY KEY (`registration_login_id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`registration_id`),
  ADD KEY `fk_registration_employee` (`registration_emp_id`),
  ADD KEY `fk_registration_login` (`registration_login_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2015;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3014;

--
-- AUTO_INCREMENT for table `employee_details`
--
ALTER TABLE `employee_details`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1013;

--
-- AUTO_INCREMENT for table `employee_registration`
--
ALTER TABLE `employee_registration`
  MODIFY `registration_emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- AUTO_INCREMENT for table `login_details`
--
ALTER TABLE `login_details`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `login_registration`
--
ALTER TABLE `login_registration`
  MODIFY `registration_login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=320;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

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

--
-- Constraints for table `registration`
--
ALTER TABLE `registration`
  ADD CONSTRAINT `fk_registration_employee` FOREIGN KEY (`registration_emp_id`) REFERENCES `employee_registration` (`registration_emp_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_registration_login` FOREIGN KEY (`registration_login_id`) REFERENCES `login_registration` (`registration_login_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
