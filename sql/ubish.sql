-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 03:21 PM
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
(2020, 'Edited Test Announcement', 'Announcement for testing the edit function (edit: announcement has been edited)', 'Private', 'Test Category', 1, '2025-04-28 06:41:42', '../../uploads/attachments/thumbnail_1745815302_new_thumbnail.webp', '2025-04-28 06:41:42');

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
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `uploaded_by` int(11) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`document_id`, `title`, `document_name`, `document_path`, `uploaded_by`, `upload_date`) VALUES
(20, 'Upload Test (PDF)', 'Upload Test (PDF)', '../../uploads/documents/680efec219838-test_pdf.pdf', 1, '2025-04-27 22:06:26'),
(21, 'Test Document', 'Test Document', '../../uploads/documents/680f0043ccdf3-test_document.docx', 1, '2025-04-27 22:12:51');

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
(1002, 'Mark Simon', 'Zuilan', 'Bringas', '2005-02-16', 'M', 'Baguio City, Philippines', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, '09123456789', '../../uploads/profiles/680e0acd0d07a.jpg'),
(1004, 'John', 'Smith', 'Doe', '2000-01-02', 'M', 'Philippines', 'Atheist', 'Legally Separated', 'Sangguniang Barangay Member', 2, '09111111111', '../../uploads/profiles/680f803160a28.jpg'),
(1008, 'Jane', 'Turner', 'Smith', '2000-01-02', 'F', 'Philippines', 'Atheist', 'Legally Separated', 'Barangay Secretary', 2, '09222222222', '../../uploads/profiles/680cb23623f39.jpg'),
(1009, 'Jamie', 'Smith', 'Brown', '2000-01-03', 'F', 'Philippines', 'Roman Catholic', 'Married', 'Barangay Secretary', 2, '09333333333', '../../uploads/default_profile.jpg'),
(1013, 'Tarou', 'Tanaka', 'Sakamoto', '1997-11-21', 'M', 'Japan', 'Atheist', 'Married', 'Other Barangay Personnel', 1, '09444444444', '../../uploads/profiles/680e3def12dc7.png'),
(1014, 'Kento', 'Kokusen', 'Nanami', '1990-07-03', 'M', 'Japan', 'Shinto', 'Single', 'Barangay Secretary', 2, '09555555555', '../../uploads/profiles/680e412a573ca.jpg');

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
(224, 'Kento', 'Kokusen', 'Nanami', '1990-07-03', 'M', 'Japan', 'Shinto', 'Single', 'Barangay Secretary', 2, '09555555555', '../../uploads/temp/680e412a573ca.jpg');

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
(405, 1008, 'Jane', 'Turner', 'Smith', '2000-01-02', 'F', 'Philippines', 'Atheist', 'Legally Separated', 'Barangay Secretary', 2, 'Approved', '2025-04-27 22:01:27', 'Legally separated with John doe on 04/27/2025.'),
(406, 1004, 'John', 'Smith', 'Doe', '2000-01-02', 'M', 'Philippines', 'Atheist', 'Legally Separated', 'Sangguniang Barangay Member', 2, 'Approved', '2025-04-27 22:02:37', 'Legally separated with Jane Doe on 04/27/2025.'),
(407, 1009, 'Jamie', 'Smith', 'Brown', '2000-01-03', 'F', 'Philippines', 'Roman Catholic', 'Married', 'Barangay Secretary', 2, 'Approved', '2025-04-27 22:08:26', 'Promoted on 04/27/2025.'),
(408, 1002, 'Wanderer', 'Scaramouche', 'Kabukimono', '2005-02-16', 'M', 'Baguio City, Philippines', 'Roman Catholic', 'Single', 'Other Barangay Personnel', 1, 'Denied', '2025-04-27 22:12:37', 'Bro I\'m literally Scaramouche.'),
(409, 1013, 'Tarou', 'Tanaka', 'Sakamoto', '1997-11-21', 'M', 'Japan', 'Shinto', 'Married', 'Other Barangay Personnel', 1, 'Pending', '2025-04-27 22:39:21', 'Converted to Shintoism after marrying.'),
(410, 1014, 'Kento', 'Kokusen', 'Nanami', '1990-07-03', 'M', 'Kuantan, Malaysia', 'Shinto', 'Single', 'Other Barangay Personnel', 1, 'Pending', '2025-04-27 22:41:12', 'Moved to retirement residence. Stepped down of Secretary position to settle in the new residence.');

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
(1, 1001, 'admin', 'admin@email.com', '1234'),
(2, 1002, 'mbringas', 'mbringas@email.com', '$2y$10$GS81dsvvhFZwDUqRJ73OmOFZgKEzctb9Aod9sb7OOLcHdrZNPTXxi'),
(4, 1004, 'jdoe', 'jdoe@email.com', '$2y$10$VCPFcQVgrnx1lzn4DxZWbedaopwO.0jAZWLzRMuMkqI7MMIfz5noq'),
(8, 1008, 'janedoe', 'janedoe@email.com', '$2y$10$8suHYTzlraplsOOpfTltpe/mpQ3tWk4zxlGYB4Pl2vJmUx6wb/Uzu'),
(9, 1009, 'jamiedoe', 'jamied@email.com', '$2y$10$C0oC34M1ynaxxgdDXhHPxeWKHaVWUCcHB5wGfp6WTjz2VoA2kW4/C'),
(13, 1013, 'sakamoto', 'sakamoto@tarou.com', '$2y$10$4iHzxPWm9Qmw9ebGUDK1FOE30fc80XjdVvEh0Nk/sHP0p6hPv8b5S'),
(14, 1014, 'nanami', 'nanami@kento.com', '$2y$10$9CY88e9UqU7MBgVSkWJNY.i3WhhDPSmQnvaJKSmdnZQ2LCzYKRbGe');

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
(324, 'nanami', 'nanami@kento.jjk', '$2y$10$9CY88e9UqU7MBgVSkWJNY.i3WhhDPSmQnvaJKSmdnZQ2LCzYKRbGe');

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
(124, 224, 324, 'Approved', '2025-04-27 22:38:16');

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
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

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
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2018;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3024;

--
-- AUTO_INCREMENT for table `employee_details`
--
ALTER TABLE `employee_details`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1015;

--
-- AUTO_INCREMENT for table `employee_registration`
--
ALTER TABLE `employee_registration`
  MODIFY `registration_emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT for table `employee_update`
--
ALTER TABLE `employee_update`
  MODIFY `update_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=411;

--
-- AUTO_INCREMENT for table `login_details`
--
ALTER TABLE `login_details`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `login_registration`
--
ALTER TABLE `login_registration`
  MODIFY `registration_login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=325;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

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
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `login_details` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_update`
--
ALTER TABLE `employee_update`
  ADD CONSTRAINT `fk_emp_id` FOREIGN KEY (`emp_id`) REFERENCES `employee_details` (`emp_id`) ON DELETE CASCADE;

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
