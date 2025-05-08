-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 07:31 AM
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
  `thumbnail` varchar(255) DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `body`, `privacy`, `category`, `author_id`, `post_date`, `thumbnail`, `last_updated`) VALUES
(2005, 'Promoting Cleanliness and Responsible Pet Ownership', 'To all valued residents of Greenwater Village,\r\n\r\nIn line with our commitment to maintaining a clean, healthy, and safe environment for everyone, we would like to remind all residents to take part in preserving the cleanliness of our community. Please ensure the following:\r\n1. Keep your surroundings clean. Sweep and dispose of trash properly. Let’s work together to prevent the buildup of garbage in streets, canals, and public areas.\r\n2. Clean up after your pets. Pet owners are kindly reminded to immediately clean up their pets’ excrements when walking or letting them out in public spaces. Bring a disposable bag and ensure proper disposal of pet waste.\r\n3. Avoid littering. Use the designated trash bins around the barangay. Segregate your waste accordingly.\r\n\r\nIntegrity is the quality of being honest and having strong moral principles that you refuse to change. Let’s be responsible citizens and lead by example.\r\n\r\nThank you for your cooperation.', 'Public', 'Public Notice', 4, '2025-04-17 10:11:01', '../../uploads/attachments/thumbnail_1744877461_thumbnailtest01.jpg', NULL),
(2009, 'Holy Week 2025 Schedule', 'FROM MALACAÑANG:\r\n\r\nMemorandum Circular No. 81, s. 2025\r\n\r\nPursuant to Memorandum Circular No. 81, government offices shall adopt work-from-home (WFH) arrangements from 8:00 AM to 12:00 PM on April 16, 2025.\r\nWork shall be suspended from 12:00 PM onwards to give employees full opportunity to properly observe Maundy Thursday and Good Friday and to travel to and from the different regions.\r\nThe adoption of WFH and suspension of work for private companies and offices is left to the discretion of their respective employers.', 'Private', 'Memorandum', 1, '2025-04-17 11:06:26', '../../uploads/attachments/thumbnail_1744880786_thumbnailtest02.jpg', NULL),
(2010, 'Test Announcement', 'This is a test description w/o thumbnail, w/o attachment.', 'Public', 'Test Category', 2, '2025-04-17 11:22:52', NULL, NULL),
(2011, 'Test Private Announcement', 'This is a test private description w/o thumbnail, w/o attachment', 'Private', 'Test Category', 2, '2025-04-28 06:17:41', NULL, '2025-04-28 06:17:41'),
(2020, 'Edited Test Announcement', 'Announcement for testing the edit function (edit: announcement has been edited)', 'Private', 'Test Category', 1, '2025-05-07 18:35:19', '../../uploads/attachments/thumbnail_1746635545_test.png', '2025-05-07 18:35:19');

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
(3010, 2009, '../../uploads/attachments/attachment_1744880786_attachmenttest01.pdf', 'attachment_1744880786_attachmenttest01.pdf', '2025-04-17 11:06:26'),
(3037, 2020, '../../uploads/attachments/attachment_1745815302_test_pdf.pdf', 'attachment_1745815302_test_pdf.pdf', '2025-04-28 12:41:42');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `property_owner` varchar(255) NOT NULL,
  `issued_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1001, 'Admin', 'Santos', 'Dela Cruz', '2000-01-01', 'M', 'Philippines', 'Atheist', 'Single', 'Punong Barangay', 3, '09123456789', '../../uploads/default_profile.jpg'),
(1002, 'Mark Simon', 'Zuilan', 'Bringas', '2005-02-16', 'M', 'Baguio City, Philippines', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, '09123456789', '../../uploads/profiles/680e0acd0d07a.jpg'),
(1004, 'John', 'Smith', 'Doe', '2000-01-02', 'M', 'Philippines', 'Atheist', 'Legally Separated', 'Sangguniang Barangay Member', 2, '09111111111', '../../uploads/profiles/680f803160a28.jpg'),
(1009, 'Jamie', 'Smith', 'Brown', '2000-01-03', 'F', 'Philippines', 'Roman Catholic', 'Married', 'Barangay Secretary', 2, '09333333333', '../../uploads/profiles/680f94f412a96.jpg'),
(1013, 'Tarou', 'Tanaka', 'Sakamoto', '1997-11-21', 'M', 'Japan', 'Atheist', 'Married', 'Other Barangay Personnel', 1, '09444444444', '../../uploads/profiles/680e3def12dc7.png'),
(1014, 'Kento', 'Kokusen', 'Nanami', '1990-07-03', 'M', 'Japan', 'Shinto', 'Single', 'Barangay Secretary', 2, '09555555555', '../../uploads/profiles/680e412a573ca.jpg'),
(1020, 'Jane', 'Turner', 'Smith', '2000-01-01', 'F', 'Philippines', 'Atheist', 'Married', 'Barangay Secretary', 3, '09000000000', '../../uploads/profiles/6813a9c249b2a.jpg'),
(1028, 'Sarah Mae', 'Marquez', 'Capis', '2000-01-04', 'F', 'Manila', 'Roman Catholic', 'Married', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(1029, 'Allan', 'Manuel', 'Garcia', '2000-01-03', 'M', 'Manila', 'Roman Catholic', 'Married', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg');

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
(219, 'Hitori', 'Bocchi', 'Gotou', '2009-02-21', 'F', 'Japan', 'Shinto', 'Single', 'Other Barangay Personnel', 1, '09000000000', '../../uploads/temp/680cf3482d20b.jpg'),
(220, 'a', 'a', 'a', '1111-11-11', 'M', 'a', 'a', 'Single', 'Punong Barangay', 3, '09111111111', '../../uploads/default_profile.jpg'),
(221, 'a', 'a', 'a', '1111-11-11', 'M', 'a', 'a', 'Single', 'Punong Barangay', 3, '09111111111', '../../uploads/default_profile.jpg'),
(222, 'Tarou', 'Tanaka', 'Sakamoto', '1997-11-21', 'M', 'Japan', 'Atheist', 'Married', 'Other Barangay Personnel', 1, '09444444444', '../../uploads/temp/680e3def12dc7.png'),
(223, 'Kento', 'Kokusen', 'Nanami', '1990-07-03', 'M', 'Japan', 'Shinto', 'Single', 'Barangay Secretary', 2, '09555555555', '../../uploads/temp/680e3f3d12fd1.jpg'),
(224, 'Kento', 'Kokusen', 'Nanami', '1990-07-03', 'M', 'Japan', 'Shinto', 'Single', 'Barangay Secretary', 2, '09555555555', '../../uploads/temp/680e412a573ca.jpg'),
(225, 'a', 'a', 'a', '1111-11-11', 'M', 'a', 'a', 'Legally Separated', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(226, 'R J', 'Guerrero', 'Salcedo', '2004-04-01', 'F', 'Pangasinan', 'Roman Catholic', 'Divorced', 'Other Barangay Personnel', 1, '09504788697', '../../uploads/temp/68139752ef140.jpg'),
(227, 'qwerty', 'qwerty', 'qwerty', '1111-11-11', 'M', 'qwerty', 'qwerty', 'Single', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(228, 'asdf', 'asdf', 'asdf', '1111-11-11', 'M', 'asdf', 'asdf', 'Single', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/temp/6813a84816a15.jpg'),
(229, 'zxcv', 'zxcv', 'zxcv', '1111-11-11', 'M', 'zxcv', 'zxcv', 'Single', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(230, 'Jane', 'Turner', 'Smith', '2000-01-01', 'F', 'Philippines', 'Atheist', 'Married', 'Barangay Secretary', 3, '09000000000', '../../uploads/temp/6813a9c249b2a.jpg'),
(231, 'a', 'a', 'a', '1111-11-11', 'M', 'a', 'a', 'Single', 'Other Barangay Personnel', 1, '09222222222', '../../uploads/default_profile.jpg'),
(232, 's', 's', 's', '1111-11-11', 'M', 's', 's', 'Single', 'Other Barangay Personnel', 1, '09222222222', '../../uploads/default_profile.jpg'),
(233, 'q', 'q', 'q', '1111-11-11', 'M', 'q', 'q', 'Legally Separated', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/temp/6813aae47a889.jpg'),
(234, 'w', 'w', 'w', '1111-11-11', 'M', 'w', 'w', 'Legally Separated', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(235, 'a', 'a', 'a', '1111-11-11', 'M', 'a', 'a', 'Single', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(236, 'a', 'a', 'a', '1111-11-11', 'M', 'a', 'a', 'Single', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(237, 'q', 'q', 'q', '1111-11-11', 'M', 'q', 'q', 'Single', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(238, 'q', 'q', 'q', '1111-11-11', 'F', 'q', 'q', 'Single', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(239, 'a', 'a', 'a', '1111-11-11', 'M', 'a', 'a', 'Single', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(240, 's', 's', 's', '1111-11-11', 'M', 's', 's', 'Single', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(241, 'Allan', 'Manuel', 'Garcia', '2000-01-03', 'M', 'Manila', 'Roman Catholic', 'Married', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg'),
(242, 'Sarah Mae', 'Ramos', 'Marquez', '2000-01-04', 'F', 'Manila', 'Roman Catholic', 'Divorced', 'Other Barangay Personnel', 1, '09111111111', '../../uploads/default_profile.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `employee_update`
--

CREATE TABLE `employee_update` (
  `update_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `update_first_name` varchar(255) NOT NULL,
  `update_middle_name` varchar(255) NOT NULL,
  `update_last_name` varchar(255) NOT NULL,
  `update_date_of_birth` date NOT NULL,
  `update_sex` varchar(1) NOT NULL,
  `update_address` text NOT NULL,
  `update_religion` varchar(100) NOT NULL,
  `update_civil_status` varchar(20) NOT NULL,
  `update_legislature` varchar(100) NOT NULL,
  `update_access_level` tinyint(4) NOT NULL,
  `update_status` enum('Pending','Approved','Denied') NOT NULL DEFAULT 'Pending',
  `update_request_date` datetime NOT NULL DEFAULT current_timestamp(),
  `update_reason` text DEFAULT 'N/A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_update`
--

INSERT INTO `employee_update` (`update_id`, `emp_id`, `update_first_name`, `update_middle_name`, `update_last_name`, `update_date_of_birth`, `update_sex`, `update_address`, `update_religion`, `update_civil_status`, `update_legislature`, `update_access_level`, `update_status`, `update_request_date`, `update_reason`) VALUES
(401, 1009, 'Jamie', 'Smith', 'Doe', '2000-01-03', 'F', 'Philippines', 'Roman Catholic', 'Single', 'Sangguniang Barangay Member', 2, 'Denied', '2025-04-27 18:58:46', 'N/A'),
(402, 1002, 'Wanderer', 'Scaramouche', 'Kabukimono', '1525-01-03', 'M', 'Inazuma City, Inazuma', 'Atheist', 'Single', 'Punong Barangay', 3, 'Denied', '2025-04-27 21:12:08', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed enim libero, dictum quis bibendum quis, luctus et ex. Nulla cursus dui id pharetra lacinia. Mauris ac sollicitudin elit. Sed efficitur at dolor eget malesuada. Morbi massa diam, auctor et condimentum nec, interdum non diam. Integer gravida, enim sed facilisis cursus, orci turpis aliquam urna, vitae egestas leo arcu vel nisi. Etiam egestas ligula a interdum cursus. Vestibulum ultricies sit amet diam eget pulvinar. Integer finibus nibh pellentesque orci fringilla feugiat. Vestibulum ut lorem nisi. Maecenas et risus semper, maximus mauris vitae, malesuada neque.'),
(403, 1009, 'Jamie', 'Smith', 'Brown', '2000-01-03', 'F', 'Philippines', 'Roman Catholic', 'Married', 'Other Barangay Personnel', 1, 'Approved', '2025-04-27 21:46:50', 'Married on January 1, 2005.'),
(404, 1002, 'Mark Simon', 'Zuilan', 'Bringas', '2005-02-16', 'M', 'Baguio City, Philippines', 'Roman Catholic', 'Single', 'Sangguniang Kabataan Member', 1, 'Denied', '2025-04-27 21:49:50', 'Elected as SK Member in 2023.'),
(406, 1004, 'John', 'Smith', 'Doe', '2000-01-02', 'M', 'Philippines', 'Atheist', 'Legally Separated', 'Sangguniang Barangay Member', 2, 'Approved', '2025-04-27 22:02:37', 'Legally separated with Jane Doe on 04/27/2025.'),
(407, 1009, 'Jamie', 'Smith', 'Brown', '2000-01-03', 'F', 'Philippines', 'Roman Catholic', 'Married', 'Barangay Secretary', 2, 'Approved', '2025-04-27 22:08:26', 'Promoted on 04/27/2025.'),
(408, 1002, 'Wanderer', 'Scaramouche', 'Kabukimono', '2005-02-16', 'M', 'Baguio City, Philippines', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, 'Denied', '2025-04-27 22:12:37', 'Bro I\'m literally Scaramouche.'),
(409, 1013, 'Tarou', 'Tanaka', 'Sakamoto', '1997-11-21', 'M', 'Japan', 'Shinto', 'Married', 'Other Barangay Personnel', 1, 'Pending', '2025-04-27 22:39:21', 'Converted to Shintoism after marrying.'),
(410, 1014, 'Kento', 'Kokusen', 'Nanami', '1990-07-03', 'M', 'Kuantan, Malaysia', 'Shinto', 'Single', 'Other Barangay Personnel', 1, 'Pending', '2025-04-27 22:41:12', 'Moved to retirement residence. Stepped down of Secretary position to settle in the new residence.'),
(412, 1029, 'Allan', 'Manuel', 'Garcia', '2000-01-03', 'M', 'Baguio', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, 'Denied', '2025-05-07 23:45:49', NULL),
(413, 1028, 'Sarah Mae', 'Marquez', 'Capiz', '2000-01-04', 'F', 'Manila', 'Roman Catholic', 'Married', 'Other Barangay Personnel', 1, 'Approved', '2025-05-07 23:46:34', NULL),
(414, 1029, 'Allan', 'Manuel', 'Garcia', '2000-01-03', 'M', 'Baguio', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, 'Denied', '2025-05-07 23:51:19', NULL),
(415, 1028, 'Sarah Mae', 'Marquez', 'Capis', '2000-01-04', 'F', 'Manila', 'Roman Catholic', 'Married', 'Other Barangay Personnel', 1, 'Approved', '2025-05-07 23:52:13', 'Typo on last name.'),
(416, 1001, 'Admin', 'Santos', 'Dela Cruz', '2000-01-01', 'M', 'Philippines', 'Atheist', 'Single', 'Punong Barangay', 3, 'Approved', '2025-05-08 00:13:46', 'Debug');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `file_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `privacy` enum('Public','Private') NOT NULL DEFAULT 'Public',
  `file_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`file_id`, `title`, `file_name`, `file_path`, `uploaded_by`, `upload_date`, `privacy`, `file_type`) VALUES
(20, 'Upload Test (PDF)', 'Upload Test (PDF)', '../../uploads/documents/680efec219838-test_pdf.pdf', 1, '2025-04-27 14:06:26', 'Public', NULL),
(21, 'Test Document', 'Test Document', '../../uploads/documents/680f0043ccdf3-test_document.docx', 1, '2025-04-27 14:12:51', 'Public', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `incident_id` int(11) NOT NULL,
  `incident_type` varchar(255) NOT NULL,
  `incident_date` date NOT NULL,
  `place_of_incident` varchar(255) NOT NULL,
  `reporting_person` varchar(255) NOT NULL,
  `home_address` text NOT NULL,
  `narrative` text NOT NULL,
  `involved_parties` text NOT NULL,
  `submitted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`incident_id`, `incident_type`, `incident_date`, `place_of_incident`, `reporting_person`, `home_address`, `narrative`, `involved_parties`, `submitted_by`, `created_at`) VALUES
(1, 'category1', '2025-05-04', 'test', 'test', 'test', 'test', 'test', 1, '2025-05-05 11:44:27'),
(2, 'category2', '2025-05-13', 'test', 'test', 'test', 'test', 'test', 1, '2025-05-05 11:44:36'),
(3, 'category3', '2025-05-21', 'test', 'test', 'test', 'test', 'test', 1, '2025-05-05 11:44:47'),
(13, 'category1', '2025-05-08', 'Greenwater Village', 'Juan Dela Cruz', 'Greenwater Village, Baguio City', 'This is a test narrative for the incident report form.', 'Juana Dela Cruz', 1, '2025-05-07 17:19:15'),
(14, 'test', '0011-11-11', 'test', 'test', 'test', 'test', 'test', 1, '2025-05-08 03:53:59');

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
(1, 1001, 'admin', 'admin@gmail.com', '1234'),
(2, 1002, 'mbringas', 'msimonbringas05v2@gmail.com', '$2y$10$uhb50SXESN/O6VzZd2V9h.lCwlpiRxZweMtR0W5qxH7xjpoFG43/e'),
(4, 1004, 'jdoe', 'jdoe@email.com', '$2y$10$VCPFcQVgrnx1lzn4DxZWbedaopwO.0jAZWLzRMuMkqI7MMIfz5noq'),
(9, 1009, 'jamiedoe', 'jamied@email.com', '$2y$10$C0oC34M1ynaxxgdDXhHPxeWKHaVWUCcHB5wGfp6WTjz2VoA2kW4/C'),
(13, 1013, 'sakamoto', 'sakamoto@tarou.com', '$2y$10$4iHzxPWm9Qmw9ebGUDK1FOE30fc80XjdVvEh0Nk/sHP0p6hPv8b5S'),
(14, 1014, 'nanami', 'nanami@kento.com', '$2y$10$9CY88e9UqU7MBgVSkWJNY.i3WhhDPSmQnvaJKSmdnZQ2LCzYKRbGe'),
(20, 1020, 'janedoe', 'janedoe@email.com', '$2y$10$OFVHhJoT5/TzdPXg5BDpfOz2p/itz4/0zW1FkQ.kRva5KUzVdQ7ae'),
(28, 1028, 'sarahcapis', 'sarahcapis@gmail.com', '$2y$10$AAauWi/6TyEDfZkgmFSYt.U2wPk0vezsGqpMdubXTzIda871L3/9S'),
(29, 1029, 'allangarcia', 'allangarcia@email.com', '$2y$10$aXtdoCiQmftQIOX4pw3LNerF61IMpGYTuk8cx0fJ.eSbzriKGdCCe');

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
(319, 'bocchi', 'bocchi@the.rock', '$2y$10$SEQTYjpR5QmwUxl.w5KuZuZwwvPKC96rnQaIZdZVO3sJL8L7WGC7C'),
(320, 'a', 'a@a', '$2y$10$mkbStg9yYL1ODMSHHFf1eOyyB2z1ymPAAXu76HfWu/XNTHFQQu2UC'),
(321, 'a', 'a@a', '$2y$10$dI/Fr1J/vBPHg/nQA/wYP.MRBfHR.s4K455dtxn4y4p...'),
(322, 'sakamoto', 'sakamoto@tarou.com', '$2y$10$4iHzxPWm9Qmw9ebGUDK1FOE30fc80XjdVvEh0Nk/sHP0p6hPv8b5S'),
(323, 'nanami', 'nanami@kento.jjk', '$2y$10$AVJgJ.vxrvQOz5kjdvM5QuGP7AG8ENBqLcPO4FjZprF0WlFlksXOe'),
(324, 'nanami', 'nanami@kento.jjk', '$2y$10$9CY88e9UqU7MBgVSkWJNY.i3WhhDPSmQnvaJKSmdnZQ2LCzYKRbGe'),
(325, 'a', 'a@a', '$2y$10$BTF/nlhljK/z8Co.6.XW/ujeZFBGhlfhCSBiBQH.qQTbAnjAmynZW'),
(326, 'rjsalcedo', 'rjsalcedo@email.com', '$2y$10$TW3uBHsft8ADAtFyhh844.eT2as0duRVazSGTRBqnb5iorHZtV5y2'),
(327, 'qwerty', 'qwerty@email.com', '$2y$10$rus0Rh7DHNAFS7UzgdtXguhpz/m646OyJOCls9wkQFGH8g/L5LsBy'),
(328, 'asdf', 'asdf@email.com', '$2y$10$Eftmr24eFicr6bMHz7.YSeWaqPYq4P6Fq8UyNOp5ZJQqePxu7muNC'),
(329, 'zxcv', 'zxcv@email.com', '$2y$10$WqQ6Hel6LnpvoAYVf9rRJ.Sc1B.tDdfCwa4msJZA/g44onMkKpkHq'),
(330, 'janedoe', 'janedoe@email.com', '$2y$10$OFVHhJoT5/TzdPXg5BDpfOz2p/itz4/0zW1FkQ.kRva5KUzVdQ7ae'),
(331, 'a', 'a@a', '$2y$10$H8/lookA.hQzFQ493da8.uvtGNzekdk2rB1L0tEyIAoRZdtXx5xam'),
(332, 's', 's@s', '$2y$10$OdDmziVlbCn7H3a8acq7bunWu/KzcwUDRVqXmx6tlRgTJHHjOgdq.'),
(333, 'q', 'q@q', '$2y$10$/YSp68BK7JBcC/xqxO0Jk.nYVvpzUtwWniEqHWUKrhbQCv6mpzQeC'),
(334, 'w', 'w@w', '$2y$10$J3lRg3ZPQVili9TSfVagYukaWV1B7wzZio67BYdP0bdskbcp/RpGC'),
(335, 'a', 'a@a', '$2y$10$pBUYt9XlwwUvHd8TdLSSh.l7qBSuWiFYKccDAyp/5sbofi6PqeCo6'),
(336, 'a', 'a@a', '$2y$10$l8lH5i6vpNkgjbBH09njKeXwLz2mFgTXhudkQFHmFEv90x0EF0Evu'),
(337, 'q', 'q@q', '$2y$10$MXEQ6zeRKcxG63GuvvXnyuscZYr2OlEtX9eWJKUWMPMZ0TH.gkf/C'),
(338, 'q', 'q@q', '$2y$10$1x0hWo0nduY...z8ua/fdeO5lJg.Cv2t1uQTDbgSQtVu5vaXXnsGK'),
(339, 'a', 'a@a', '$2y$10$jZruvUYJHytav3gHYbe91uKFo/7LgHPcd6olZgndWXJc17473PaVC'),
(340, 's', 's@s', '$2y$10$LBT4TV6uRpFvcOzWYJqUyOQbF90mrriIO5kLtENHAKHZ17HaX84KO'),
(341, 'allangarcia', 'allangarcia@email.com', '$2y$10$aXtdoCiQmftQIOX4pw3LNerF61IMpGYTuk8cx0fJ.eSbzriKGdCCe'),
(342, 'sarahmarquez', 'sarahmarquez@gmail.com', '$2y$10$AAauWi/6TyEDfZkgmFSYt.U2wPk0vezsGqpMdubXTzIda871L3/9S');

-- --------------------------------------------------------

--
-- Table structure for table `permits`
--

CREATE TABLE `permits` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `event` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` varchar(50) NOT NULL,
  `venue` varchar(255) NOT NULL,
  `issued_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(119, 219, 319, 'Pending', '2025-04-26 22:53:19'),
(120, 220, 320, 'Denied', '2025-04-27 16:41:17'),
(121, 221, 321, 'Denied', '2025-04-27 18:56:15'),
(122, 222, 322, 'Approved', '2025-04-27 22:24:31'),
(123, 223, 323, 'Denied', '2025-04-27 22:35:59'),
(124, 224, 324, 'Approved', '2025-04-27 22:38:16'),
(125, 225, 325, 'Approved', '2025-05-01 23:36:30'),
(126, 226, 326, 'Approved', '2025-05-01 23:46:39'),
(127, 227, 327, 'Approved', '2025-05-02 00:58:07'),
(128, 228, 328, 'Approved', '2025-05-02 00:58:55'),
(129, 229, 329, 'Approved', '2025-05-02 01:00:40'),
(130, 230, 330, 'Approved', '2025-05-02 01:05:18'),
(131, 231, 331, 'Approved', '2025-05-02 01:07:01'),
(132, 232, 332, 'Approved', '2025-05-02 01:08:10'),
(133, 233, 333, 'Approved', '2025-05-02 01:10:03'),
(134, 234, 334, 'Approved', '2025-05-02 01:10:26'),
(135, 235, 335, 'Approved', '2025-05-07 23:30:39'),
(136, 236, 336, 'Approved', '2025-05-07 23:32:03'),
(137, 237, 337, 'Denied', '2025-05-07 23:38:26'),
(138, 238, 338, 'Denied', '2025-05-07 23:38:59'),
(139, 239, 339, 'Approved', '2025-05-07 23:39:24'),
(140, 240, 340, 'Denied', '2025-05-07 23:42:10'),
(141, 241, 341, 'Approved', '2025-05-07 23:43:57'),
(142, 242, 342, 'Approved', '2025-05-07 23:45:04');

-- --------------------------------------------------------

--
-- Table structure for table `security_questions`
--

CREATE TABLE `security_questions` (
  `security_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `question` varchar(50) NOT NULL,
  `answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `security_questions`
--

INSERT INTO `security_questions` (`security_id`, `emp_id`, `question`, `answer`) VALUES
(501, 1002, 'What city were you born in?', '$2y$10$D7InK94EwJKqdIOHWRVNz.WEvS6YPwhVXJBXqWZhgb14TbDyJTSy2'),
(502, 1001, 'What was your childhood best friend’s nickname?', '$2y$10$jfzVccY7R1BVuPH/.JYAwuhC2E6MLXoqjl7Cp8BotqfIL.3.Wb86y'),
(503, 1004, 'What was your childhood best friend’s nickname?', '$2y$10$Jzo7j62nxGkrSQj1n407F.wCijRv5NerEnZFWT/gh6eLyovog.YGS'),
(504, 1009, 'What was your childhood best friend’s nickname?', '$2y$10$.9wJWF9.AHtxa3mH2xVlFu1D1ZvPKdO/canCU1W7w9UTzC/gmvC6S'),
(505, 1020, 'What was your childhood best friend’s nickname?', '$2y$10$bPoErhkvQFElWxQsCtID3OzNO.DLROWUHGT5qFWIUXtOm4BUYbaga'),
(506, 1013, 'What is your mother\'s maiden name?', '$2y$10$e4qxpaNtsRo4/zyyHxzaye5AVyFIvkY2GNv1PLMlxtxzd3Hk5.tXS'),
(507, 1029, 'What city were you born in?', '$2y$10$QjfyXnMzKqeZzyXscm1Di.odRpbMldmvkzr5ai92CPtlABcY740JC'),
(508, 1028, 'What city were you born in?', '$2y$10$4MVaiOmbhMd2LnwoHbTCROsO5uKKHUYNuWsgoJh9w9TUHx0bYS1zi');

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
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `employee_update`
--
ALTER TABLE `employee_update`
  ADD PRIMARY KEY (`update_id`),
  ADD KEY `fk_emp_id` (`emp_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`incident_id`),
  ADD KEY `submitted_by` (`submitted_by`);

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
-- Indexes for table `permits`
--
ALTER TABLE `permits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`registration_id`),
  ADD KEY `fk_registration_employee` (`registration_emp_id`),
  ADD KEY `fk_registration_login` (`registration_login_id`);

--
-- Indexes for table `security_questions`
--
ALTER TABLE `security_questions`
  ADD PRIMARY KEY (`security_id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2022;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3038;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `employee_details`
--
ALTER TABLE `employee_details`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1030;

--
-- AUTO_INCREMENT for table `employee_registration`
--
ALTER TABLE `employee_registration`
  MODIFY `registration_emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT for table `employee_update`
--
ALTER TABLE `employee_update`
  MODIFY `update_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=417;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `incident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `login_details`
--
ALTER TABLE `login_details`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `login_registration`
--
ALTER TABLE `login_registration`
  MODIFY `registration_login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343;

--
-- AUTO_INCREMENT for table `permits`
--
ALTER TABLE `permits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `security_questions`
--
ALTER TABLE `security_questions`
  MODIFY `security_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=509;

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
-- Constraints for table `employee_update`
--
ALTER TABLE `employee_update`
  ADD CONSTRAINT `fk_emp_id` FOREIGN KEY (`emp_id`) REFERENCES `employee_details` (`emp_id`) ON DELETE CASCADE;

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `login_details` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `incidents_ibfk_1` FOREIGN KEY (`submitted_by`) REFERENCES `login_details` (`user_id`);

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

--
-- Constraints for table `security_questions`
--
ALTER TABLE `security_questions`
  ADD CONSTRAINT `security_questions_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `employee_details` (`emp_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
