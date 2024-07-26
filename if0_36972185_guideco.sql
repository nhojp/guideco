-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql308.infinityfree.com
-- Generation Time: Jul 25, 2024 at 08:23 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_36972185_guideco`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `about_text` text NOT NULL,
  `about_image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `about_text`, `about_image`, `created_at`) VALUES
(3, 'Canda is a village in Balayan, Western Batangas, Calabarzon. Canda is situated nearby to the villages Sambat and Lagnas', 'uploads/church.jpg', '2024-03-05 04:23:58'),
(9, 'Canda is a barangay in the municipality of Balayan, in the province of Batangas. Its population as determined by the 2020 Census was 1,371. This represented 1.43% of the total population of Balayan.\r\n', 'uploads/ab.jpg', '2024-04-26 07:01:56');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `created_at`, `first_name`, `middle_name`, `last_name`, `position`, `birthdate`, `sex`, `contact_number`, `address`) VALUES
(2, 'jpaulmar', 'john14manjac', 'johnpaulmarmanjac@gmail.com', '2024-07-18 13:02:12', 'john paulmar', 'lontoc', 'manjac', 'Developer/Creator', '2003-07-14', 'Male', '09304365359', 'sampaga balayan batangas'),
(5, 'juspher', '1234', NULL, '2024-07-25 05:26:46', 'juspher', NULL, 'pedraza', 'Data Analyst', NULL, NULL, NULL, NULL),
(6, 'paulo', '1234', NULL, '2024-07-25 23:41:54', 'ferdinand', NULL, 'sacdalan', 'app developer', NULL, NULL, NULL, NULL),
(7, 'nikori', '1234', NULL, '2024-07-26 00:36:27', 'nicoll', NULL, 'quiroz', 'Quality Assurance', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `victimFirstName` varchar(255) DEFAULT NULL,
  `victimMiddleName` varchar(255) DEFAULT NULL,
  `victimLastName` varchar(255) DEFAULT NULL,
  `victimDOB` date DEFAULT NULL,
  `victimAge` int(11) DEFAULT NULL,
  `victimSex` varchar(10) DEFAULT NULL,
  `victimGrade` varchar(50) DEFAULT NULL,
  `victimSection` varchar(50) DEFAULT NULL,
  `victimAdviser` varchar(255) DEFAULT NULL,
  `victimContact` varchar(50) DEFAULT NULL,
  `victimAddress` text DEFAULT NULL,
  `motherName` varchar(255) DEFAULT NULL,
  `motherOccupation` varchar(255) DEFAULT NULL,
  `motherAddress` text DEFAULT NULL,
  `motherContact` varchar(50) DEFAULT NULL,
  `fatherName` varchar(255) DEFAULT NULL,
  `fatherOccupation` varchar(255) DEFAULT NULL,
  `fatherAddress` text DEFAULT NULL,
  `fatherContact` varchar(50) DEFAULT NULL,
  `complainantFirstName` varchar(255) DEFAULT NULL,
  `complainantMiddleName` varchar(255) DEFAULT NULL,
  `complainantLastName` varchar(255) DEFAULT NULL,
  `relationshipToVictim` varchar(255) DEFAULT NULL,
  `complainantContact` varchar(50) DEFAULT NULL,
  `complainantAddress` text DEFAULT NULL,
  `complainedFirstName` varchar(255) DEFAULT NULL,
  `complainedMiddleName` varchar(255) DEFAULT NULL,
  `complainedLastName` varchar(255) DEFAULT NULL,
  `complainedDOB` date DEFAULT NULL,
  `complainedAge` int(11) DEFAULT NULL,
  `complainedSex` varchar(10) DEFAULT NULL,
  `complainedDesignation` varchar(100) DEFAULT NULL,
  `complainedGrade` varchar(50) DEFAULT NULL,
  `complainedSection` varchar(50) DEFAULT NULL,
  `complainedAdviser` varchar(255) DEFAULT NULL,
  `complainedContact` varchar(50) DEFAULT NULL,
  `complainedAddress` text DEFAULT NULL,
  `caseDetails` text DEFAULT NULL,
  `actionTaken` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `victimFirstName`, `victimMiddleName`, `victimLastName`, `victimDOB`, `victimAge`, `victimSex`, `victimGrade`, `victimSection`, `victimAdviser`, `victimContact`, `victimAddress`, `motherName`, `motherOccupation`, `motherAddress`, `motherContact`, `fatherName`, `fatherOccupation`, `fatherAddress`, `fatherContact`, `complainantFirstName`, `complainantMiddleName`, `complainantLastName`, `relationshipToVictim`, `complainantContact`, `complainantAddress`, `complainedFirstName`, `complainedMiddleName`, `complainedLastName`, `complainedDOB`, `complainedAge`, `complainedSex`, `complainedDesignation`, `complainedGrade`, `complainedSection`, `complainedAdviser`, `complainedContact`, `complainedAddress`, `caseDetails`, `actionTaken`, `recommendations`, `teacher_id`) VALUES
(17, 'Nicah', 'louisse', 'Prescillas', '2011-12-06', 12, 'Female', 'Grade 11', 'Rose', 'Ferdinand Paulo Sacdalan', NULL, NULL, 'magda lontoc', 'businesswoman', 'sta. rosa laguna', '09781239876', 'andronico prescillas', 'carpenter', 'consolacion cebu', '09389209865', 'juspher', 'balagtas', 'pedraza', 'secret', '09128366781', 'canda balayan batangas', 'john paulmar', 'lontoc', 'manjac', '0000-00-00', 2003, '21', 'Male', NULL, NULL, NULL, '09304365359', 'sampaga balayan batangas', 'napikon sinuntok', 'pinagayos pinatawan ng parusa', 'wag na uulit', 16),
(20, 'Nicah', 'louisse', 'Prescillas', '2011-12-06', 12, 'Female', 'Grade 11', 'Rose', 'Ferdinand Paulo Sacdalan', NULL, NULL, 'magda lontoc', 'businesswoman', 'sta. rosa laguna', '09781239876', 'andronico prescillas', 'carpenter', 'consolacion cebu', '09389209865', 'juspher', 'asd', 'asd', 'zxc', '09876712356', 'canda balayan batangas', 'john paulmar', 'lontoc', 'manjac', '0000-00-00', 2003, '21', 'Male', NULL, NULL, NULL, '09304365359', 'sampaga balayan batangas', 'bullying', 'fixed', 'dont do again', 16);

-- --------------------------------------------------------

--
-- Table structure for table `complaints_student`
--

CREATE TABLE `complaints_student` (
  `id` int(11) NOT NULL,
  `victimFirstName` varchar(255) DEFAULT NULL,
  `victimMiddleName` varchar(255) DEFAULT NULL,
  `victimLastName` varchar(255) DEFAULT NULL,
  `victimDOB` date DEFAULT NULL,
  `victimAge` int(11) DEFAULT NULL,
  `victimSex` enum('Male','Female','Other') DEFAULT NULL,
  `victimGrade` varchar(50) DEFAULT NULL,
  `victimSection` varchar(50) DEFAULT NULL,
  `victimAdviser` varchar(255) DEFAULT NULL,
  `victimContact` varchar(20) DEFAULT NULL,
  `victimAddress` text DEFAULT NULL,
  `motherName` varchar(255) DEFAULT NULL,
  `motherOccupation` varchar(255) DEFAULT NULL,
  `motherAddress` text DEFAULT NULL,
  `motherContact` varchar(20) DEFAULT NULL,
  `fatherName` varchar(255) DEFAULT NULL,
  `fatherOccupation` varchar(255) DEFAULT NULL,
  `fatherAddress` text DEFAULT NULL,
  `fatherContact` varchar(20) DEFAULT NULL,
  `complainantFirstName` varchar(255) DEFAULT NULL,
  `complainantMiddleName` varchar(255) DEFAULT NULL,
  `complainantLastName` varchar(255) DEFAULT NULL,
  `relationshipToVictim` varchar(255) DEFAULT NULL,
  `complainantContact` varchar(20) DEFAULT NULL,
  `complainantAddress` text DEFAULT NULL,
  `complainedFirstName` varchar(255) DEFAULT NULL,
  `complainedMiddleName` varchar(255) DEFAULT NULL,
  `complainedLastName` varchar(255) DEFAULT NULL,
  `complainedDOB` date DEFAULT NULL,
  `complainedAge` int(11) DEFAULT NULL,
  `complainedSex` enum('Male','Female','Other') DEFAULT NULL,
  `complainedGrade` varchar(50) DEFAULT NULL,
  `complainedSection` varchar(50) DEFAULT NULL,
  `complainedAdviser` varchar(255) DEFAULT NULL,
  `complainedContact` varchar(20) DEFAULT NULL,
  `complainedAddress` text DEFAULT NULL,
  `complainedMotherName` varchar(255) DEFAULT NULL,
  `complainedMotherOccupation` varchar(255) DEFAULT NULL,
  `complainedMotherAddress` text DEFAULT NULL,
  `complainedMotherContact` varchar(20) DEFAULT NULL,
  `complainedFatherName` varchar(255) DEFAULT NULL,
  `complainedFatherOccupation` varchar(255) DEFAULT NULL,
  `complainedFatherAddress` text DEFAULT NULL,
  `complainedFatherContact` varchar(20) DEFAULT NULL,
  `caseDetails` text DEFAULT NULL,
  `actionTaken` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `reportedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `complaints_student`
--

INSERT INTO `complaints_student` (`id`, `victimFirstName`, `victimMiddleName`, `victimLastName`, `victimDOB`, `victimAge`, `victimSex`, `victimGrade`, `victimSection`, `victimAdviser`, `victimContact`, `victimAddress`, `motherName`, `motherOccupation`, `motherAddress`, `motherContact`, `fatherName`, `fatherOccupation`, `fatherAddress`, `fatherContact`, `complainantFirstName`, `complainantMiddleName`, `complainantLastName`, `relationshipToVictim`, `complainantContact`, `complainantAddress`, `complainedFirstName`, `complainedMiddleName`, `complainedLastName`, `complainedDOB`, `complainedAge`, `complainedSex`, `complainedGrade`, `complainedSection`, `complainedAdviser`, `complainedContact`, `complainedAddress`, `complainedMotherName`, `complainedMotherOccupation`, `complainedMotherAddress`, `complainedMotherContact`, `complainedFatherName`, `complainedFatherOccupation`, `complainedFatherAddress`, `complainedFatherContact`, `caseDetails`, `actionTaken`, `recommendations`, `reportedAt`, `student_id`) VALUES
(22, 'andrea', '', 'politon', '0000-00-00', 0, 'Male', 'Grade 11', 'Rose', 'Ferdinand Paulo Sacdalan', '', NULL, '', '', '', '', '', '', '', '', 'asd', 'asxa', 'asc', 'zcxzc', 'wqe', 'zxcs', 'brian', '', 'alano', '0000-00-00', 0, 'Male', 'Grade 11', 'Rose', 'Ferdinand Paulo Sacdalan', '', NULL, '', '', '', '', '', '', '', '', 'asdq', 'zxcas', 'cwa', '2024-07-25 08:12:59', 9),
(25, 'korini', '', 'quiroz', '0000-00-00', 0, 'Male', 'Grade 11', 'Daisy', 'nicoll quiroz', '', NULL, '', '', '', '', '', '', '', '', 'Korini', '', 'Quiroz', '', '', '', 'korini', '', 'quiroz', '0000-00-00', 0, 'Male', 'Grade 11', 'Daisy', 'nicoll quiroz', '', NULL, '', '', '', '', '', '', '', '', 'Test', 'Test', 'Test', '2024-07-25 13:35:47', 24004),
(26, 'korini', '', 'quiroz', '0000-00-00', 0, 'Male', 'Grade 11', 'Daisy', 'nicoll quiroz', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'korini', '', 'quiroz', '0000-00-00', 0, 'Male', 'Grade 11', 'Daisy', 'nicoll quiroz', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '2024-07-25 14:05:46', 24004);

-- --------------------------------------------------------

--
-- Table structure for table `fathers`
--

CREATE TABLE `fathers` (
  `parent_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fathers`
--

INSERT INTO `fathers` (`parent_id`, `student_id`, `name`, `contact_number`, `email`, `occupation`, `address`) VALUES
(1, 1, 'apolinar manjac', '09304365359', 'paulmarmanjac@gmail.com', 'welder', 'sampaga balayan batangass'),
(2, 2, 'apolinar manjac', '0991723986', 'apolinar@gmail.com', 'welder', 'sampaga balayan batangas'),
(3, 3, NULL, NULL, NULL, NULL, NULL),
(4, 4, 'andronico prescillas', '09389209865', 'andronico@gmail.com', 'carpenter', 'consolacion cebu'),
(5, 5, NULL, NULL, NULL, NULL, NULL),
(6, 6, NULL, NULL, NULL, NULL, NULL),
(7, 7, NULL, NULL, NULL, NULL, NULL),
(8, 8, NULL, NULL, NULL, NULL, NULL),
(9, 9, NULL, NULL, NULL, NULL, NULL),
(10, 10, NULL, NULL, NULL, NULL, NULL),
(11, 11, NULL, NULL, NULL, NULL, NULL),
(12, 12, NULL, NULL, NULL, NULL, NULL),
(13, 13, NULL, NULL, NULL, NULL, NULL),
(14, 14, NULL, NULL, NULL, NULL, NULL),
(15, 15, NULL, NULL, NULL, NULL, NULL),
(16, 16, NULL, NULL, NULL, NULL, NULL),
(17, 17, NULL, NULL, NULL, NULL, NULL),
(18, 18, NULL, NULL, NULL, NULL, NULL),
(19, 19, NULL, NULL, NULL, NULL, NULL),
(20, 20, NULL, NULL, NULL, NULL, NULL),
(21, 21, NULL, NULL, NULL, NULL, NULL),
(22, 22, NULL, NULL, NULL, NULL, NULL),
(23, 23, NULL, NULL, NULL, NULL, NULL),
(24, 24, NULL, NULL, NULL, NULL, NULL),
(25, 25, NULL, NULL, NULL, NULL, NULL),
(26, 26, NULL, NULL, NULL, NULL, NULL),
(27, 27, NULL, NULL, NULL, NULL, NULL),
(24002, 24002, NULL, NULL, NULL, NULL, NULL),
(24003, 24003, NULL, NULL, NULL, NULL, NULL),
(24004, 24004, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `grade_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `grade_name`) VALUES
(1, 'Grade 11'),
(2, 'Grade 12');

-- --------------------------------------------------------

--
-- Table structure for table `guards`
--

CREATE TABLE `guards` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guards`
--

INSERT INTO `guards` (`id`, `username`, `password`, `first_name`, `middle_name`, `last_name`, `email`, `position`, `birthdate`, `sex`, `contact_number`, `address`) VALUES
(13, 'guard', 'guard', 'Samuel', NULL, 'Batanes', NULL, 'Guard', NULL, NULL, NULL, NULL),
(14, 'guard1', 'guard1', 'Remida', 'solares', 'Soncha', 'remidasolaressonca@gmail.com', 'Guard', '1990-07-31', 'Female', '0991723986', 'sampaga balayan batangas');

-- --------------------------------------------------------

--
-- Table structure for table `mothers`
--

CREATE TABLE `mothers` (
  `parent_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mothers`
--

INSERT INTO `mothers` (`parent_id`, `student_id`, `name`, `contact_number`, `email`, `occupation`, `address`) VALUES
(1, 1, 'mylene', '09923299823', 'johnpaulmarmanjac@gmail.com', 'housewife', 'sampaga balayan batangas'),
(2, 2, 'mylene manjac', '09871239871', 'mylene@gmail.com', 'housewife', 'sampaga balayan batangas'),
(3, 3, NULL, NULL, NULL, NULL, NULL),
(4, 4, 'magda lontoc', '09781239876', 'magda@gmail.com', 'businesswoman', 'sta. rosa laguna'),
(5, 5, NULL, NULL, NULL, NULL, NULL),
(6, 6, NULL, NULL, NULL, NULL, NULL),
(7, 7, NULL, NULL, NULL, NULL, NULL),
(8, 8, NULL, NULL, NULL, NULL, NULL),
(9, 9, NULL, NULL, NULL, NULL, NULL),
(10, 10, NULL, NULL, NULL, NULL, NULL),
(11, 11, NULL, NULL, NULL, NULL, NULL),
(12, 12, NULL, NULL, NULL, NULL, NULL),
(13, 13, NULL, NULL, NULL, NULL, NULL),
(14, 14, NULL, NULL, NULL, NULL, NULL),
(15, 15, NULL, NULL, NULL, NULL, NULL),
(16, 16, NULL, NULL, NULL, NULL, NULL),
(17, 17, NULL, NULL, NULL, NULL, NULL),
(18, 18, NULL, NULL, NULL, NULL, NULL),
(19, 19, NULL, NULL, NULL, NULL, NULL),
(20, 20, NULL, NULL, NULL, NULL, NULL),
(21, 21, NULL, NULL, NULL, NULL, NULL),
(22, 22, NULL, NULL, NULL, NULL, NULL),
(23, 23, NULL, NULL, NULL, NULL, NULL),
(24, 24, NULL, NULL, NULL, NULL, NULL),
(25, 25, NULL, NULL, NULL, NULL, NULL),
(26, 26, NULL, NULL, NULL, NULL, NULL),
(27, 27, NULL, NULL, NULL, NULL, NULL),
(24002, 24002, NULL, NULL, NULL, NULL, NULL),
(24003, 24003, NULL, NULL, NULL, NULL, NULL),
(24004, 24004, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `principal`
--

CREATE TABLE `principal` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `principal`
--

INSERT INTO `principal` (`id`, `username`, `password`, `email`, `first_name`, `last_name`, `contact_number`, `created_at`) VALUES
(1, 'principal', '1234', NULL, '', '', NULL, '2024-07-25 05:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `admin_id`, `student_id`, `date`, `time`, `description`, `location`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2024-07-25', '10:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 13:25:46', '2024-07-24 13:25:46'),
(3, 2, 1, '2024-07-26', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 14:30:37', '2024-07-24 14:30:37'),
(4, 2, 1, '2024-07-26', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 14:32:39', '2024-07-24 14:32:39'),
(5, 2, 1, '2024-07-31', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 14:33:23', '2024-07-24 14:33:23'),
(6, 2, 1, '2024-07-31', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 14:34:29', '2024-07-24 14:34:29'),
(7, 2, 1, '2024-07-31', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 14:34:47', '2024-07-24 14:34:47'),
(8, 2, 1, '2024-07-31', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 14:35:38', '2024-07-24 14:35:38'),
(9, 2, 4, '2024-08-02', '00:00:00', 'Meeting with parents of Nicah Prescillas', 'Nasugbu East Senior High School', '2024-07-24 14:36:03', '2024-07-24 14:36:03'),
(10, 2, 4, '2024-08-02', '00:00:00', 'Meeting with parents of Nicah Prescillas', 'Nasugbu East Senior High School', '2024-07-24 14:36:07', '2024-07-24 14:36:07'),
(11, 2, 4, '2024-08-02', '00:00:00', 'Meeting with parents of Nicah Prescillas', 'Nasugbu East Senior High School', '2024-07-24 14:36:23', '2024-07-24 14:36:23'),
(12, 2, 1, '2024-07-26', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 15:38:55', '2024-07-24 15:38:55'),
(13, 2, 1, '2024-07-27', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 15:43:05', '2024-07-24 15:43:05'),
(14, 2, 1, '2024-07-29', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 15:43:40', '2024-07-24 15:43:40'),
(15, 2, 1, '2024-07-30', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 16:03:43', '2024-07-24 16:03:43'),
(16, 2, 1, '2024-07-29', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-24 16:06:54', '2024-07-24 16:06:54'),
(17, 2, 1, '2024-07-30', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-25 08:01:20', '2024-07-25 08:01:20'),
(18, 2, 1, '2024-07-30', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-25 08:01:25', '2024-07-25 08:01:25'),
(19, 2, 4, '2024-07-29', '00:00:00', 'Meeting with parents of Nicah Prescillas', 'Nasugbu East Senior High School', '2024-07-25 15:57:12', '2024-07-25 15:57:12'),
(20, 2, 4, '2024-07-29', '00:00:00', 'Meeting with parents of Nicah Prescillas', 'Nasugbu East Senior High School', '2024-07-25 15:57:17', '2024-07-25 15:57:17'),
(21, 2, 1, '2024-08-17', '00:00:00', 'Meeting with parents of John Paulmar Manjac', 'Nasugbu East Senior High School', '2024-07-25 15:57:52', '2024-07-25 15:57:52'),
(31, 2, 4, '2024-08-29', '00:00:00', 'Meeting with parents of Nicah Prescillas', 'Nasugbu East Senior High School', '2024-07-25 23:56:46', '2024-07-25 23:56:46');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(10) NOT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`, `grade_id`, `teacher_id`) VALUES
(8, 'Rose', 1, 17),
(9, 'Sampaguita', 1, 16),
(10, 'Gumamela', 1, 18),
(11, 'Daisy', 1, 19),
(12, 'earth', 2, 20),
(13, 'jupiter', 2, 21),
(14, 'saturn', 2, 22),
(15, 'mars', 2, 23),
(16, 'Lilac', 1, 19),
(17, 'Dahlia', 1, 22);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `middle_name`, `last_name`, `age`, `sex`, `section_id`, `contact_number`, `religion`, `birthdate`, `user_id`) VALUES
(1, 'John Paulmar', 'Lontoc', 'Manjac', 25, 'Male', 9, '09304365358', 'Catholic', '1999-02-12', 1),
(2, 'Jade', 'lontoc', 'Manjac', 2024, 'Female', 15, '09304365359', 'catholic', '2010-09-04', 2),
(3, 'Mark', 'lontoc', 'Manjac', NULL, 'Male', 13, '09304365359', 'catholic', '0000-00-00', 3),
(4, 'Nicah', 'louisse', 'Prescillas', 12, 'Female', 8, '09948723942', 'catholic', '2011-12-06', 4),
(5, 'Carla', 'jolongbayan', 'Ramos', NULL, 'Male', 13, '09304365359', 'catholic', '0000-00-00', 5),
(6, 'Benny', 'jolongbayan', 'Ramos', NULL, 'Male', 9, '21312314123', 'catholic', '0000-00-00', 6),
(7, 'jamir', NULL, 'hernandez', NULL, 'Male', 11, NULL, NULL, NULL, 7),
(8, 'bon', NULL, 'de padua', NULL, 'Male', 14, NULL, NULL, NULL, 8),
(9, 'brian', NULL, 'alano', NULL, 'Male', 8, NULL, NULL, NULL, 9),
(10, 'denver', NULL, 'alipustain', NULL, 'Male', 10, NULL, NULL, NULL, 10),
(11, 'andrea', NULL, 'politon', NULL, 'Male', 8, NULL, NULL, NULL, 11),
(12, 'maria', NULL, 'sunday', NULL, 'Male', 8, NULL, NULL, NULL, 12),
(13, 'louisse', NULL, 'lontoc', NULL, 'Male', 15, NULL, NULL, NULL, 13),
(14, 'lucita', NULL, 'fuentes', NULL, 'Male', 14, NULL, NULL, NULL, 14),
(15, 'romana', NULL, 'ilagan', NULL, 'Male', 13, NULL, NULL, NULL, 15),
(16, 'estelita', NULL, 'pelobelo', NULL, 'Male', 12, NULL, NULL, NULL, 16),
(17, 'minerva', NULL, 'salva', NULL, 'Male', 11, NULL, NULL, NULL, 17),
(18, 'vergilio', NULL, 'adiz', NULL, 'Male', 9, NULL, NULL, NULL, 18),
(19, 'noey', NULL, 'de jesus', NULL, 'Male', 15, NULL, NULL, NULL, 19),
(20, 'albert', NULL, 'paytaren', NULL, 'Male', 13, NULL, NULL, NULL, 20),
(21, 'benj', NULL, 'samonte', NULL, 'Male', 12, NULL, NULL, NULL, 21),
(22, 'jason', NULL, 'magsino', NULL, 'Male', 8, NULL, NULL, NULL, 22),
(23, 'melvinn', NULL, 'roxas', NULL, 'Male', 11, NULL, NULL, NULL, 23),
(24, 'renz ', NULL, 'salac', NULL, 'Male', 15, NULL, NULL, NULL, 24),
(25, 'bill', NULL, 'mercado', NULL, 'Male', 12, NULL, NULL, NULL, 25),
(26, 'romeo', NULL, 'concepcion', NULL, 'Male', 10, NULL, NULL, NULL, 26),
(27, 'john loyd', NULL, 'ramos', NULL, 'Male', 8, NULL, NULL, NULL, 27),
(24001, 'john paulmar', 'lontoc', 'manjac', NULL, 'Male', 15, '09304365359', 'catholic', '0000-00-00', 24001),
(24002, 'juspher', NULL, 'pedraza', NULL, 'Male', 8, NULL, NULL, NULL, 24002),
(24003, 'ferdis', NULL, 'sacdy', NULL, 'Male', 12, NULL, NULL, NULL, 24003),
(24004, 'korini', NULL, 'quiroz', NULL, 'Male', 11, NULL, NULL, NULL, 24004);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `username`, `password`, `first_name`, `middle_name`, `last_name`, `email`, `position`, `birthdate`, `sex`, `contact_number`, `address`) VALUES
(16, 'jpaul', 'john14manjac', 'john paulmar', 'lontoc', 'manjac', 'paulmarmanjac@gmail.com', 'Teacher', '2003-07-14', 'Male', '09304365359', 'sampaga balayan batangas'),
(17, 'ferdi', '1234', 'Ferdinand Paulo', 'sam', 'Sacdalan', 'ferdi@gmail.com', 'Teacher', '2002-07-21', 'Male', '09871239871', 'sampaga balayan batangas'),
(18, 'jusphers', '1234', 'juspher ', NULL, 'pedraza', NULL, 'Teacher', NULL, NULL, NULL, NULL),
(19, 'nicoll', '1234', 'nicoll', NULL, 'quiroz', NULL, 'Teacher', NULL, NULL, NULL, NULL),
(20, 'john', '1234', 'john', NULL, 'lontoc', NULL, 'Teacher', NULL, NULL, NULL, NULL),
(21, 'paulman', '1234', 'paul ', NULL, 'marman', NULL, 'Teacher', NULL, NULL, NULL, NULL),
(22, 'frederik', '1234', 'frederik', NULL, 'lapitan', NULL, 'Teacher', NULL, NULL, NULL, NULL),
(23, 'perding', '1234', 'perding', NULL, 'felices', NULL, 'Teacher', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `sex`, `birthdate`, `address`, `contact_number`) VALUES
(1, 'johnpaulmar', 'johnpaulmarmanjac@gmail.com', 'john14manjac', NULL, NULL, NULL, NULL),
(2, 'jade', 'paulenejade@gmail.com', '', NULL, NULL, NULL, NULL),
(3, '3', 'mjmanjac@gmail.com', '3', NULL, NULL, NULL, NULL),
(4, 'nicay', 'nicah@gmail.com', 'nicahlouisse06', NULL, NULL, NULL, NULL),
(5, '5', 'carla@gmail.com', '5', NULL, NULL, NULL, NULL),
(6, '6', 'benita@gmail.com', '6', NULL, NULL, NULL, NULL),
(7, '7', '', '7', NULL, NULL, NULL, NULL),
(8, '8', '', '8', NULL, NULL, NULL, NULL),
(9, '9', '', '9', NULL, NULL, NULL, NULL),
(10, '10', '', '10', NULL, NULL, NULL, NULL),
(11, '11', '', '11', NULL, NULL, NULL, NULL),
(12, '12', '', '12', NULL, NULL, NULL, NULL),
(13, '13', '', '13', NULL, NULL, NULL, NULL),
(14, '14', '', '14', NULL, NULL, NULL, NULL),
(15, '15', '', '15', NULL, NULL, NULL, NULL),
(16, '16', '', '16', NULL, NULL, NULL, NULL),
(17, '17', '', '17', NULL, NULL, NULL, NULL),
(18, '18', '', '18', NULL, NULL, NULL, NULL),
(19, '19', '', '19', NULL, NULL, NULL, NULL),
(20, '20', '', '20', NULL, NULL, NULL, NULL),
(21, '21', '', '21', NULL, NULL, NULL, NULL),
(22, '22', '', '22', NULL, NULL, NULL, NULL),
(23, '23', '', '23', NULL, NULL, NULL, NULL),
(24, '24', '', '24', NULL, NULL, NULL, NULL),
(25, '25', '', '25', NULL, NULL, NULL, NULL),
(26, '26', '', '26', NULL, NULL, NULL, NULL),
(27, '27', '', '27', NULL, NULL, NULL, NULL),
(24001, '24001', 'jp@gmail.com', '24001', 'Male', '0000-00-00', 'sampaga balayan batangas', '09304365359'),
(24002, '24002', '', '24002', NULL, NULL, NULL, NULL),
(24003, '24003', '', '24003', NULL, NULL, NULL, NULL),
(24004, '24004', '', '24004', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `reported_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `guard_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `violation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`id`, `student_id`, `reported_at`, `guard_id`, `teacher_id`, `violation_id`) VALUES
(22, 10, '2024-07-20 13:36:41', NULL, 17, 10),
(23, 11, '2024-07-22 04:26:33', 14, NULL, 10),
(24, 27, '2024-07-22 08:17:15', NULL, 17, 8),
(25, 7, '2024-07-22 08:17:33', NULL, 17, 6),
(29, 26, '2024-07-23 06:04:09', 14, NULL, 3),
(31, 8, '2024-07-23 06:18:01', NULL, 17, 6),
(32, 24001, '2024-07-23 06:22:09', NULL, 17, 10),
(33, 16, '2024-07-23 13:27:19', NULL, 17, 8),
(34, 24, '2024-07-23 13:27:28', NULL, 17, 6),
(35, 13, '2024-07-23 13:27:40', NULL, 17, 7),
(36, 3, '2024-07-23 13:27:49', NULL, 17, 4),
(37, 1, '2024-07-23 13:27:55', NULL, 17, 9),
(38, 22, '2024-07-23 13:28:01', NULL, 17, 10),
(39, 22, '2024-07-23 14:48:17', NULL, 17, 10),
(40, 17, '2024-07-24 02:59:09', NULL, 18, 16),
(41, 22, '2024-07-24 09:06:57', 14, NULL, 5),
(42, 22, '2024-07-24 09:07:04', 14, NULL, 9),
(43, 22, '2024-07-24 09:07:09', 14, NULL, 3),
(44, 4, '2024-07-24 11:03:44', NULL, 18, 2),
(45, 4, '2024-07-25 03:25:59', 14, NULL, 13),
(46, 4, '2024-07-25 03:26:43', 14, NULL, 13),
(47, 4, '2024-07-25 03:28:27', 14, NULL, 13),
(48, 4, '2024-07-25 03:29:36', 14, NULL, 13),
(49, 4, '2024-07-25 03:31:37', 14, NULL, 10),
(50, 4, '2024-07-25 04:28:35', NULL, 17, 10),
(51, 9, '2024-07-26 00:13:22', NULL, 17, 7);

-- --------------------------------------------------------

--
-- Table structure for table `violation_list`
--

CREATE TABLE `violation_list` (
  `id` int(11) NOT NULL,
  `violation_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `violation_list`
--

INSERT INTO `violation_list` (`id`, `violation_description`) VALUES
(13, 'Brawl'),
(6, 'Bullying'),
(7, 'Cheating'),
(5, 'Cutting Classes'),
(8, 'Disrespect to Teachers'),
(4, 'Improper Haircut'),
(3, 'Improper Uniform'),
(9, 'Littering'),
(1, 'Over the Bakod'),
(10, 'Smoking'),
(16, 'stealing'),
(2, 'Wearing Earring');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_complaints_teacher_id` (`teacher_id`);

--
-- Indexes for table `complaints_student`
--
ALTER TABLE `complaints_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_complaints_student_student_id` (`student_id`);

--
-- Indexes for table `fathers`
--
ALTER TABLE `fathers`
  ADD PRIMARY KEY (`parent_id`),
  ADD UNIQUE KEY `unique_student_id_father` (`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guards`
--
ALTER TABLE `guards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `mothers`
--
ALTER TABLE `mothers`
  ADD PRIMARY KEY (`parent_id`),
  ADD UNIQUE KEY `unique_student_id_mother` (`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `principal`
--
ALTER TABLE `principal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grade_id` (`grade_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `fk_guard` (`guard_id`),
  ADD KEY `fk_teacher` (`teacher_id`),
  ADD KEY `fk_violation_id` (`violation_id`);

--
-- Indexes for table `violation_list`
--
ALTER TABLE `violation_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_violation_description` (`violation_description`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `complaints_student`
--
ALTER TABLE `complaints_student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `fathers`
--
ALTER TABLE `fathers`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24005;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `guards`
--
ALTER TABLE `guards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `mothers`
--
ALTER TABLE `mothers`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24005;

--
-- AUTO_INCREMENT for table `principal`
--
ALTER TABLE `principal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240183;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240183;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `violation_list`
--
ALTER TABLE `violation_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `fk_complaints_teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);

--
-- Constraints for table `complaints_student`
--
ALTER TABLE `complaints_student`
  ADD CONSTRAINT `fk_complaints_student_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `fathers`
--
ALTER TABLE `fathers`
  ADD CONSTRAINT `fathers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `mothers`
--
ALTER TABLE `mothers`
  ADD CONSTRAINT `mothers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `fk_guard` FOREIGN KEY (`guard_id`) REFERENCES `guards` (`id`),
  ADD CONSTRAINT `fk_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`),
  ADD CONSTRAINT `fk_violation_id` FOREIGN KEY (`violation_id`) REFERENCES `violation_list` (`id`),
  ADD CONSTRAINT `violations_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
