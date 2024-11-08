-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 01:09 PM
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
-- Database: `attendance_database`
--
CREATE DATABASE IF NOT EXISTS `attendance_database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `attendance_database`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminID` int(11) NOT NULL,
  `adminFirstname` varchar(100) NOT NULL,
  `adminMiddlename` varchar(100) DEFAULT NULL,
  `adminLastname` varchar(100) NOT NULL,
  `adminAge` int(10) NOT NULL,
  `adminEmail` varchar(100) NOT NULL,
  `adminGender` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminID`, `adminFirstname`, `adminMiddlename`, `adminLastname`, `adminAge`, `adminEmail`, `adminGender`) VALUES
(1, 'Ferdinand Paulo', 'Felices', 'Sacdalan', 0, '22', 'Male'),
(2, 'John Paulmar', NULL, 'Manjac', 22, 'manjac@gmail.com', 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts`
--

CREATE TABLE `admin_accounts` (
  `adminAccountID` int(11) NOT NULL,
  `adminUsername` varchar(20) NOT NULL,
  `adminPassword` varchar(100) NOT NULL,
  `adminID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_accounts`
--

INSERT INTO `admin_accounts` (`adminAccountID`, `adminUsername`, `adminPassword`, `adminID`) VALUES
(1, 'admin', '$2y$10$jG.kZPVEQ36TN8ahSlrwhOvECEFprHf2V3sTXM36dGVlCk/tKt4je', 1),
(3, 'manjac', '1234', 2);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendanceID` int(11) NOT NULL,
  `empID` int(11) NOT NULL,
  `timeIn` time NOT NULL,
  `timeOut` time DEFAULT NULL,
  `date` date NOT NULL,
  `day` varchar(100) NOT NULL,
  `remarks` varchar(100) NOT NULL DEFAULT 'NA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendanceID`, `empID`, `timeIn`, `timeOut`, `date`, `day`, `remarks`) VALUES
(1, 3, '08:00:00', '16:00:00', '2023-10-19', 'Thursday', 'NA'),
(2, 1, '08:00:00', '16:00:00', '2023-10-19', 'Thursday', 'NA'),
(4, 4, '08:00:00', '16:00:00', '2023-10-19', 'Thursday', 'NA'),
(6, 1, '08:10:00', '16:00:00', '2023-10-20', 'Friday', 'Late'),
(8, 3, '08:10:00', '16:00:00', '2023-10-20', 'Friday', 'Late'),
(13, 1, '08:00:00', '16:00:00', '2023-10-01', 'Sunday', 'NA'),
(15, 3, '08:00:00', '16:00:00', '2023-10-01', 'Sunday', 'NA'),
(16, 4, '08:00:00', '16:00:00', '2023-10-01', 'Sunday', 'NA'),
(17, 5, '08:00:00', '16:00:00', '2023-10-01', 'Sunday', 'NA'),
(18, 1, '08:00:00', '16:00:00', '2023-10-02', 'Monday', 'NA'),
(20, 3, '08:00:00', '16:00:00', '2023-10-02', 'Monday', 'NA'),
(22, 5, '08:00:00', '16:00:00', '2023-10-02', 'Monday', 'NA'),
(23, 1, '08:00:00', '16:00:00', '2023-10-03', 'Tuesday', 'NA'),
(25, 3, '08:00:00', '16:00:00', '2023-10-03', 'Tuesday', 'NA'),
(27, 5, '08:00:00', '16:00:00', '2023-10-03', 'Tuesday', 'NA'),
(28, 1, '08:00:00', '16:00:00', '2023-10-04', 'Wednesday', 'NA'),
(30, 3, '08:00:00', '16:00:00', '2023-10-04', 'Wednesday', 'NA'),
(31, 4, '08:00:00', '16:00:00', '2023-10-04', 'Wednesday', 'NA'),
(32, 1, '08:00:00', '16:00:00', '2023-10-05', 'Thursday', 'NA'),
(34, 3, '08:00:00', '16:00:00', '2023-10-05', 'Thursday', 'NA'),
(35, 4, '08:00:00', '16:00:00', '2023-10-05', 'Thursday', 'NA'),
(36, 1, '08:00:00', '16:00:00', '2023-10-06', 'Friday', 'NA'),
(38, 3, '08:00:00', '16:00:00', '2023-10-06', 'Friday', 'NA'),
(39, 4, '08:00:00', '16:00:00', '2023-10-06', 'Friday', 'NA'),
(40, 5, '08:00:00', '16:00:00', '2023-10-06', 'Friday', 'NA'),
(42, 4, '08:00:00', '16:00:00', '2023-10-07', 'Saturday', 'NA'),
(43, 5, '08:00:00', '16:00:00', '2023-10-07', 'Saturday', 'NA'),
(45, 4, '08:00:00', '16:00:00', '2023-10-08', 'Sunday', 'NA'),
(46, 5, '08:00:00', '16:00:00', '2023-10-08', 'Sunday', 'NA'),
(47, 1, '08:00:00', '16:00:00', '2023-10-09', 'Monday', 'NA'),
(48, 3, '08:00:00', '16:00:00', '2023-10-09', 'Monday', 'NA'),
(49, 5, '08:00:00', '16:00:00', '2023-10-09', 'Monday', 'NA'),
(50, 1, '08:00:00', '16:00:00', '2023-10-10', 'Tuesday', 'NA'),
(51, 3, '08:00:00', '16:00:00', '2023-10-10', 'Tuesday', 'NA'),
(52, 5, '08:00:00', '16:00:00', '2023-10-10', 'Tuesday', 'NA'),
(53, 1, '08:00:00', '16:00:00', '2023-10-11', 'Wednesday', 'NA'),
(55, 3, '08:00:00', '16:00:00', '2023-10-11', 'Wednesday', 'NA'),
(56, 4, '08:00:00', '16:00:00', '2023-10-11', 'Wednesday', 'NA'),
(57, 1, '08:00:00', '16:00:00', '2023-10-12', 'Thursday', 'NA'),
(59, 3, '08:00:00', '16:00:00', '2023-10-12', 'Thursday', 'NA'),
(60, 4, '08:00:00', '16:00:00', '2023-10-12', 'Thursday', 'NA'),
(61, 1, '08:00:00', '16:00:00', '2023-10-13', 'Friday', 'NA'),
(63, 3, '08:00:00', '16:00:00', '2023-10-13', 'Friday', 'NA'),
(64, 4, '08:00:00', '16:00:00', '2023-10-13', 'Friday', 'NA'),
(65, 5, '08:00:00', '16:00:00', '2023-10-13', 'Friday', 'NA'),
(67, 4, '08:00:00', '16:00:00', '2023-10-14', 'Saturday', 'NA'),
(68, 5, '08:00:00', '16:00:00', '2023-10-14', 'Saturday', 'NA'),
(70, 4, '08:00:00', '16:00:00', '2023-10-15', 'Sunday', 'NA'),
(71, 5, '08:00:00', '16:00:00', '2023-10-15', 'Sunday', 'NA'),
(72, 1, '08:00:00', '16:00:00', '2023-10-16', 'Monday', 'NA'),
(73, 3, '08:00:00', '16:00:00', '2023-10-16', 'Monday', 'NA'),
(74, 5, '08:00:00', '16:00:00', '2023-10-16', 'Monday', 'NA'),
(75, 1, '08:00:00', '16:00:00', '2023-10-17', 'Tuesday', 'NA'),
(76, 3, '08:00:00', '16:00:00', '2023-10-17', 'Tuesday', 'NA'),
(77, 5, '08:00:00', '16:00:00', '2023-10-17', 'Tuesday', 'NA'),
(78, 1, '08:00:00', '16:00:00', '2023-10-18', 'Wednesday', 'NA'),
(80, 3, '08:00:00', '16:00:00', '2023-10-18', 'Wednesday', 'NA'),
(81, 4, '08:00:00', '16:00:00', '2023-10-18', 'Wednesday', 'NA'),
(87, 4, '08:00:00', '16:00:00', '2023-10-21', 'Saturday', 'NA'),
(88, 5, '08:00:00', '16:00:00', '2023-10-21', 'Saturday', 'NA'),
(90, 4, '08:00:00', '16:00:00', '2023-10-22', 'Sunday', 'NA'),
(91, 5, '08:00:00', '16:00:00', '2023-10-22', 'Sunday', 'NA'),
(92, 1, '08:00:00', '16:00:00', '2023-10-23', 'Monday', 'NA'),
(93, 3, '08:00:00', '16:00:00', '2023-10-23', 'Monday', 'NA'),
(94, 5, '08:00:00', '16:00:00', '2023-10-23', 'Monday', 'NA'),
(95, 1, '08:00:00', '16:00:00', '2023-10-24', 'Tuesday', 'NA'),
(96, 3, '08:00:00', '16:00:00', '2023-10-24', 'Tuesday', 'NA'),
(97, 5, '08:00:00', '16:00:00', '2023-10-24', 'Tuesday', 'NA'),
(98, 1, '08:00:00', '16:00:00', '2023-10-25', 'Wednesday', 'NA'),
(100, 3, '08:00:00', '16:00:00', '2023-10-25', 'Wednesday', 'NA'),
(101, 4, '08:00:00', '16:00:00', '2023-10-25', 'Wednesday', 'NA'),
(102, 1, '08:00:00', '16:00:00', '2023-10-25', 'Thursday', 'NA'),
(104, 3, '08:00:00', '16:00:00', '2023-10-25', 'Thursday', 'NA'),
(105, 4, '08:00:00', '16:00:00', '2023-10-25', 'Thursday', 'NA'),
(106, 1, '08:00:00', '16:00:00', '2023-10-26', 'Friday', 'NA'),
(108, 3, '08:00:00', '16:00:00', '2023-10-26', 'Friday', 'NA'),
(109, 4, '08:00:00', '16:00:00', '2023-10-26', 'Friday', 'NA'),
(110, 5, '08:00:00', '16:00:00', '2023-10-26', 'Friday', 'NA'),
(114, 4, '08:00:00', '16:00:00', '2023-10-27', 'Saturday', 'NA'),
(115, 5, '08:00:00', '16:00:00', '2023-10-27', 'Saturday', 'NA'),
(117, 4, '08:00:00', '16:00:00', '2023-10-28', 'Sunday', 'NA'),
(118, 5, '08:00:00', '16:00:00', '2023-10-28', 'Sunday', 'NA'),
(119, 1, '08:00:00', '16:00:00', '2023-10-29', 'Monday', 'NA'),
(120, 3, '08:00:00', '16:00:00', '2023-10-29', 'Monday', 'NA'),
(121, 5, '08:00:00', '16:00:00', '2023-10-29', 'Monday', 'NA'),
(122, 1, '08:00:00', '16:00:00', '2023-10-30', 'Tuesday', 'NA'),
(123, 3, '08:00:00', '16:00:00', '2023-10-30', 'Tuesday', 'NA'),
(124, 5, '08:00:00', '16:00:00', '2023-10-30', 'Tuesday', 'NA'),
(125, 1, '08:00:00', '16:00:00', '2023-10-31', 'Wednesday', 'NA'),
(127, 3, '08:00:00', '16:00:00', '2023-10-31', 'Wednesday', 'NA'),
(128, 4, '08:00:00', '16:00:00', '2023-10-31', 'Wednesday', 'NA'),
(129, 1, '08:00:00', '16:00:00', '2023-11-01', 'Wednesday', 'NA'),
(132, 1, '21:21:56', '21:22:39', '2023-11-15', 'Wednesday', 'Late');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `empID` int(11) NOT NULL,
  `empFirstname` varchar(100) NOT NULL,
  `empMiddlename` varchar(100) DEFAULT NULL,
  `empLastname` varchar(100) NOT NULL,
  `empGender` varchar(20) NOT NULL,
  `empAge` varchar(100) NOT NULL,
  `empContactNo` int(11) NOT NULL,
  `empEmail` varchar(100) NOT NULL,
  `empRole` varchar(20) NOT NULL,
  `empStatus` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`empID`, `empFirstname`, `empMiddlename`, `empLastname`, `empGender`, `empAge`, `empContactNo`, `empEmail`, `empRole`, `empStatus`) VALUES
(1, 'Jazmine Kaye', 'S.', 'Revilla', 'Female', '22', 12341234, 'jazmine@gmail.com', 'Staff', 'Employed'),
(3, 'Eunice Ann Mae', 'NA', 'Torres', 'Female', '21', 2147483647, 'eunice@gmail.com', 'Manager', 'Employed'),
(4, 'Ferdinand Paulo', 'F.', 'Sacdalan', 'Male', '21', 2147483647, 'ferdinand@gmail.com', 'Staff', 'Employed'),
(5, 'Juspher', 'NA', 'Pedraza', 'Male', '21', 2147483647, 'juspher@gmail.com', 'Staff', 'Employed'),
(24, 'Jemuel', 'NA', 'Liwanag', '', '21', 321321321, 'jemuel@gmail.com', 'Manager', 'Resigned'),
(25, 'Ferdinand Paulo', 'Felices', 'Sacdalan', '', '22', 967843653, 'haha@gmail.com', 'Admin', ''),
(26, 'qwewe', 'NA', '', '', '0', 0, '', '', ''),
(27, 'haha', 'NA', '', '', '0', 0, '', '', ''),
(28, 'kwekwek', 'NA', '', '', '0', 0, '', '', ''),
(29, 'lala', 'NA', '', '', '0', 0, '', '', ''),
(30, 'haha', 'Felices', 'Sacdalan', '', '22', 967843653, 'haha@gmail.com', 'Admin', '');

-- --------------------------------------------------------

--
-- Table structure for table `employee_accounts`
--

CREATE TABLE `employee_accounts` (
  `empAccountID` int(11) NOT NULL,
  `empUsername` varchar(20) NOT NULL,
  `empPassword` varchar(100) NOT NULL,
  `empID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_accounts`
--

INSERT INTO `employee_accounts` (`empAccountID`, `empUsername`, `empPassword`, `empID`) VALUES
(1, 'jazmine', '$2y$10$8bJlbjnywSGoCWZEvuR.H.QlNMIgX.TGsEl79oFDa4SV2u2Ku8xai', 1),
(3, 'eunice', '$2y$10$KRF5hG8GBoc4/hYSdjNO.OczsbVjCeNcrMDSLCK01SiwczTjYRY8q', 3),
(4, 'ferdinand', '$2y$10$IOXdC3qhtSoZpAxRuMdP9OKXRTV8y.zSq90mCRNOqd6/fk9rEGPlq', 4),
(5, 'juspher', '$2y$10$22oMSkzfyeUDJYEV3OmZfe1geU9KlZVTRO/HU.I9sM3VnrkK89UsS', 5),
(19, 'jemuel', '$2y$10$Eu0Hj8EKWdhZe6jSetDWC.c6ovFeFmJHEZLfZyl/IKkAYNThr8CWq', 24),
(20, 'pau', '$2y$10$LqfQvfp/fVx5SO8WU67uEeleUC6doQZrTQ7VhgePGY/LJOs/3XG1.', 25),
(21, 'haha', '$2y$10$KasEjPjjGsHt9thsaMj9HOVcPh0L3XkRXP9a7sogokufWFgH7zhPi', 26),
(22, 'haha', '$2y$10$Q.UScOsDZADp/6SQqoPmS.LJP/8uju46/qvBNYn.Gd6lMYdsDwas.', 27),
(23, 'pau', '', 28),
(24, 'lala', '1234', 29),
(25, 'paulo', '1234', 30);

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary`
--

CREATE TABLE `employee_salary` (
  `empSalaryID` int(11) NOT NULL,
  `empID` int(11) NOT NULL,
  `payPeriodID` int(11) NOT NULL,
  `grossPay` double NOT NULL,
  `sss` double NOT NULL,
  `philHealth` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_salary`
--

INSERT INTO `employee_salary` (`empSalaryID`, `empID`, `payPeriodID`, `grossPay`, `sss`, `philHealth`) VALUES
(1, 1, 2, 1680, 78.4, 22.4),
(2, 3, 2, 1120, 78.4, 22.4),
(3, 4, 2, 560, 78.4, 22.4),
(4, 5, 2, 560, 0, 0),
(9, 1, 1, 3360, 0, 0),
(10, 3, 1, 3360, 0, 0),
(11, 4, 1, 2800, 0, 0),
(12, 5, 1, 3360, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `employee_schedules`
--

CREATE TABLE `employee_schedules` (
  `empScheduleID` int(11) NOT NULL,
  `empID` int(11) NOT NULL,
  `timeIn` time NOT NULL,
  `timeOut` time NOT NULL,
  `isMonday` tinyint(4) NOT NULL,
  `isTuesday` tinyint(4) NOT NULL,
  `isWednesday` tinyint(4) NOT NULL,
  `isThursday` tinyint(4) NOT NULL,
  `isFriday` tinyint(4) NOT NULL,
  `isSaturday` tinyint(4) NOT NULL,
  `isSunday` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_schedules`
--

INSERT INTO `employee_schedules` (`empScheduleID`, `empID`, `timeIn`, `timeOut`, `isMonday`, `isTuesday`, `isWednesday`, `isThursday`, `isFriday`, `isSaturday`, `isSunday`) VALUES
(2, 3, '08:00:00', '16:00:00', 1, 1, 1, 1, 1, 0, 0),
(3, 1, '08:00:00', '16:00:00', 1, 1, 1, 1, 1, 0, 0),
(5, 4, '08:00:00', '16:00:00', 0, 0, 1, 1, 1, 1, 1),
(6, 5, '08:00:00', '16:00:00', 1, 1, 0, 0, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pay_periods`
--

CREATE TABLE `pay_periods` (
  `payPeriodID` int(11) NOT NULL,
  `payRollFrom` date NOT NULL,
  `payRollTo` date NOT NULL,
  `isPosted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pay_periods`
--

INSERT INTO `pay_periods` (`payPeriodID`, `payRollFrom`, `payRollTo`, `isPosted`) VALUES
(1, '2023-10-23', '2023-10-29', 1),
(2, '2023-10-30', '2023-11-05', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_checkin_today`
-- (See below for the actual view)
--
CREATE TABLE `view_checkin_today` (
`checkinsToday` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_dtr`
-- (See below for the actual view)
--
CREATE TABLE `view_dtr` (
`attendanceID` int(11)
,`empID` int(11)
,`timeIn` time
,`timeOut` time
,`date` date
,`day` varchar(100)
,`remarks` varchar(100)
,`grossPay` bigint(12)
,`sss` decimal(14,2)
,`philHealth` decimal(14,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_employees`
-- (See below for the actual view)
--
CREATE TABLE `view_employees` (
`empID` int(11)
,`empName` varchar(303)
,`empAge` varchar(100)
,`empEmail` varchar(100)
,`empRole` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_employee_attendance`
-- (See below for the actual view)
--
CREATE TABLE `view_employee_attendance` (
`empID` int(11)
,`empName` varchar(303)
,`timeIn` time
,`timeOut` time
,`date` varchar(41)
,`day` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_employee_count`
-- (See below for the actual view)
--
CREATE TABLE `view_employee_count` (
`employeeCount` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_emprank_grosspay`
-- (See below for the actual view)
--
CREATE TABLE `view_emprank_grosspay` (
`empFullname` varchar(303)
,`netSalary` double
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_late_today`
-- (See below for the actual view)
--
CREATE TABLE `view_late_today` (
`numberOfLates` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_payroll_report`
-- (See below for the actual view)
--
CREATE TABLE `view_payroll_report` (
`empID` int(11)
,`daysAgo` int(7)
,`grossPay` double
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pay_slip`
-- (See below for the actual view)
--
CREATE TABLE `view_pay_slip` (
`empID` int(11)
,`empSalaryID` int(11)
,`date` varchar(23)
,`empFullname` varchar(303)
,`grossPay` double
,`sss` double
,`philHealth` double
,`netSalary` double
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_previous_net_salary`
-- (See below for the actual view)
--
CREATE TABLE `view_previous_net_salary` (
`empID` int(11)
,`date` varchar(23)
,`empFullname` varchar(303)
,`netSalary` double
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_total_per_period`
-- (See below for the actual view)
--
CREATE TABLE `view_total_per_period` (
`DATE` varchar(23)
,`totalNetPay` double
);

-- --------------------------------------------------------

--
-- Structure for view `view_checkin_today`
--
DROP TABLE IF EXISTS `view_checkin_today`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_checkin_today`  AS SELECT count(0) AS `checkinsToday` FROM `attendance` WHERE `attendance`.`date` = curdate() ;

-- --------------------------------------------------------

--
-- Structure for view `view_dtr`
--
DROP TABLE IF EXISTS `view_dtr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_dtr`  AS SELECT `atd`.`attendanceID` AS `attendanceID`, `atd`.`empID` AS `empID`, `atd`.`timeIn` AS `timeIn`, `atd`.`timeOut` AS `timeOut`, `atd`.`date` AS `date`, `atd`.`day` AS `day`, `atd`.`remarks` AS `remarks`, floor((cast(`atd`.`timeOut` as time) - cast(`atd`.`timeIn` as time)) / 10000) * 70 AS `grossPay`, if(`atd`.`date` = last_day(curdate()),floor((cast(`atd`.`timeOut` as time) - cast(`atd`.`timeIn` as time)) / 10000) * 70 * 0.14,0) AS `sss`, if(`atd`.`date` = last_day(curdate()),floor((cast(`atd`.`timeOut` as time) - cast(`atd`.`timeIn` as time)) / 10000) * 70 * 0.04,0) AS `philHealth` FROM `attendance` AS `atd` ;

-- --------------------------------------------------------

--
-- Structure for view `view_employees`
--
DROP TABLE IF EXISTS `view_employees`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_employees`  AS SELECT `employee`.`empID` AS `empID`, concat(`employee`.`empLastname`,', ',`employee`.`empFirstname`,' ',if(`employee`.`empMiddlename` = 'NA','',`employee`.`empMiddlename`)) AS `empName`, `employee`.`empAge` AS `empAge`, `employee`.`empEmail` AS `empEmail`, `employee`.`empRole` AS `empRole` FROM `employee` ;

-- --------------------------------------------------------

--
-- Structure for view `view_employee_attendance`
--
DROP TABLE IF EXISTS `view_employee_attendance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_employee_attendance`  AS SELECT `emp`.`empID` AS `empID`, concat(`emp`.`empLastname`,', ',`emp`.`empFirstname`,' ',if(`emp`.`empMiddlename` = 'NA','',`emp`.`empMiddlename`)) AS `empName`, `att`.`timeIn` AS `timeIn`, `att`.`timeOut` AS `timeOut`, date_format(`att`.`date`,'%b %d, %Y') AS `date`, `att`.`day` AS `day` FROM (`employee` `emp` left join `attendance` `att` on(`emp`.`empID` = `att`.`empID`)) ORDER BY date_format(`att`.`date`,'%b %d, %Y') ASC ;

-- --------------------------------------------------------

--
-- Structure for view `view_employee_count`
--
DROP TABLE IF EXISTS `view_employee_count`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_employee_count`  AS SELECT count(0) AS `employeeCount` FROM `employee` ;

-- --------------------------------------------------------

--
-- Structure for view `view_emprank_grosspay`
--
DROP TABLE IF EXISTS `view_emprank_grosspay`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_emprank_grosspay`  AS SELECT concat(`emp`.`empLastname`,', ',`emp`.`empFirstname`,' ',if(`emp`.`empMiddlename` = 'NA','',`emp`.`empMiddlename`)) AS `empFullname`, sum(`es`.`grossPay` - (`es`.`sss` + `es`.`philHealth`)) AS `netSalary` FROM ((`employee_salary` `es` left join `employee` `emp` on(`emp`.`empID` = `es`.`empID`)) left join `pay_periods` `pd` on(`pd`.`payPeriodID` = `es`.`payPeriodID`)) WHERE `es`.`payPeriodID` = (select max(`pay_periods`.`payPeriodID`) from `pay_periods` limit 1) GROUP BY `es`.`empID` ORDER BY sum(`es`.`grossPay` - (`es`.`sss` + `es`.`philHealth`)) DESC LIMIT 0, 5 ;

-- --------------------------------------------------------

--
-- Structure for view `view_late_today`
--
DROP TABLE IF EXISTS `view_late_today`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_late_today`  AS SELECT count(0) AS `numberOfLates` FROM `attendance` WHERE `attendance`.`date` = curdate() AND `attendance`.`remarks` = 'Late' ;

-- --------------------------------------------------------

--
-- Structure for view `view_payroll_report`
--
DROP TABLE IF EXISTS `view_payroll_report`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_payroll_report`  AS SELECT `es`.`empID` AS `empID`, to_days(curdate()) - to_days(`pp`.`payRollTo`) AS `daysAgo`, `es`.`grossPay` AS `grossPay` FROM (`pay_periods` `pp` left join `employee_salary` `es` on(`es`.`payPeriodID` = `pp`.`payPeriodID`)) ORDER BY to_days(curdate()) - to_days(`pp`.`payRollTo`) ASC LIMIT 0, 5 ;

-- --------------------------------------------------------

--
-- Structure for view `view_pay_slip`
--
DROP TABLE IF EXISTS `view_pay_slip`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pay_slip`  AS SELECT `es`.`empID` AS `empID`, `es`.`empSalaryID` AS `empSalaryID`, concat(`pd`.`payRollFrom`,' - ',`pd`.`payRollTo`) AS `date`, concat(`emp`.`empLastname`,', ',`emp`.`empFirstname`,' ',if(`emp`.`empMiddlename` = 'NA','',`emp`.`empMiddlename`)) AS `empFullname`, `es`.`grossPay` AS `grossPay`, `es`.`sss` AS `sss`, `es`.`philHealth` AS `philHealth`, `es`.`grossPay`- (`es`.`sss` + `es`.`philHealth`) AS `netSalary` FROM ((`employee_salary` `es` left join `pay_periods` `pd` on(`es`.`payPeriodID` = `pd`.`payPeriodID`)) left join `employee` `emp` on(`es`.`empID` = `emp`.`empID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_previous_net_salary`
--
DROP TABLE IF EXISTS `view_previous_net_salary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_previous_net_salary`  AS SELECT `emp`.`empID` AS `empID`, concat(`pd`.`payRollFrom`,' - ',`pd`.`payRollTo`) AS `date`, concat(`emp`.`empLastname`,', ',`emp`.`empFirstname`,' ',if(`emp`.`empMiddlename` = 'NA','',`emp`.`empMiddlename`)) AS `empFullname`, `es`.`grossPay`- (`es`.`sss` + `es`.`philHealth`) AS `netSalary` FROM ((`employee_salary` `es` left join `employee` `emp` on(`emp`.`empID` = `es`.`empID`)) left join `pay_periods` `pd` on(`pd`.`payPeriodID` = `es`.`payPeriodID`)) WHERE `es`.`payPeriodID` = (select max(`pay_periods`.`payPeriodID`) from `pay_periods` where `pay_periods`.`isPosted` = 1 limit 1) ;

-- --------------------------------------------------------

--
-- Structure for view `view_total_per_period`
--
DROP TABLE IF EXISTS `view_total_per_period`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_total_per_period`  AS SELECT concat(`pd`.`payRollFrom`,' - ',`pd`.`payRollTo`) AS `DATE`, sum(`es`.`grossPay` - (`es`.`sss` + `es`.`philHealth`)) AS `totalNetPay` FROM (`employee_salary` `es` left join `pay_periods` `pd` on(`es`.`payPeriodID` = `pd`.`payPeriodID`)) GROUP BY `pd`.`payPeriodID` LIMIT 0, 5 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`adminAccountID`),
  ADD KEY `adminID` (`adminID`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendanceID`),
  ADD KEY `empID` (`empID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`empID`);

--
-- Indexes for table `employee_accounts`
--
ALTER TABLE `employee_accounts`
  ADD PRIMARY KEY (`empAccountID`),
  ADD KEY `empID` (`empID`);

--
-- Indexes for table `employee_salary`
--
ALTER TABLE `employee_salary`
  ADD PRIMARY KEY (`empSalaryID`),
  ADD KEY `payPeriodID` (`payPeriodID`),
  ADD KEY `empID` (`empID`);

--
-- Indexes for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  ADD PRIMARY KEY (`empScheduleID`),
  ADD KEY `empID` (`empID`);

--
-- Indexes for table `pay_periods`
--
ALTER TABLE `pay_periods`
  ADD PRIMARY KEY (`payPeriodID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `adminAccountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `empID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `employee_accounts`
--
ALTER TABLE `employee_accounts`
  MODIFY `empAccountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `employee_salary`
--
ALTER TABLE `employee_salary`
  MODIFY `empSalaryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  MODIFY `empScheduleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pay_periods`
--
ALTER TABLE `pay_periods`
  MODIFY `payPeriodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD CONSTRAINT `admin_accounts_ibfk_1` FOREIGN KEY (`adminID`) REFERENCES `admin` (`adminID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`empID`) REFERENCES `employee` (`empID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_accounts`
--
ALTER TABLE `employee_accounts`
  ADD CONSTRAINT `employee_accounts_ibfk_1` FOREIGN KEY (`empID`) REFERENCES `employee` (`empID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_salary`
--
ALTER TABLE `employee_salary`
  ADD CONSTRAINT `employee_salary_ibfk_1` FOREIGN KEY (`payPeriodID`) REFERENCES `pay_periods` (`payPeriodID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_salary_ibfk_2` FOREIGN KEY (`empID`) REFERENCES `employee` (`empID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  ADD CONSTRAINT `employee_schedules_ibfk_1` FOREIGN KEY (`empID`) REFERENCES `employee` (`empID`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- Database: `guideco2`
--
CREATE DATABASE IF NOT EXISTS `guideco2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `guideco2`;

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
  `picture` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `created_at`, `first_name`, `middle_name`, `last_name`, `position`, `picture`, `birthdate`, `sex`, `contact_number`, `address`) VALUES
(2, 'jpaulmar', 'john14manjac', 'johnpaulmarmanjac@gmail.com', '2024-07-18 13:02:12', 'john paulmar', 'lontoc', 'manjac', 'Developer/Creator', NULL, '2003-07-14', 'Male', '09304365359', 'sampaga balayan batangas'),
(3, 'pau', '12345', 'haha@gmail.com', '2024-10-27 07:12:29', 'Ferdinand Paulo', 'Felices', 'Sacdalan', 'Front End', NULL, '2002-01-12', 'Male', '345678922', 'Toong Tuy Batangas'),
(4, 'juspher', '1234', NULL, '2024-10-27 07:52:20', 'Juspher', NULL, 'Pedraza', 'Senior Developer', NULL, NULL, NULL, NULL, NULL),
(5, 'jay', '1234', 'jaybautista19@gmail.com', '2024-10-28 01:37:37', 'Jay', '', 'Bautista', 'Guidance Counselor', NULL, '1980-01-14', 'Male', '0966571618', 'Nasugbu, Batangas');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `victimFirstName`, `victimMiddleName`, `victimLastName`, `victimDOB`, `victimAge`, `victimSex`, `victimGrade`, `victimSection`, `victimAdviser`, `victimContact`, `victimAddress`, `motherName`, `motherOccupation`, `motherAddress`, `motherContact`, `fatherName`, `fatherOccupation`, `fatherAddress`, `fatherContact`, `complainantFirstName`, `complainantMiddleName`, `complainantLastName`, `relationshipToVictim`, `complainantContact`, `complainantAddress`, `complainedFirstName`, `complainedMiddleName`, `complainedLastName`, `complainedDOB`, `complainedAge`, `complainedSex`, `complainedDesignation`, `complainedGrade`, `complainedSection`, `complainedAdviser`, `complainedContact`, `complainedAddress`, `caseDetails`, `actionTaken`, `recommendations`, `teacher_id`) VALUES
(20, 'john paulmar', 'lontoc', 'manjac', '0000-00-00', 2024, 'Male', '12', 'Drucker', 'maria an busilig', NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'fritch', '', 'cortez', '0000-00-00', 0, '0', '', NULL, NULL, NULL, '', '', '', '', '', 46),
(21, 'john paulmar', 'lontoc', 'manjac', '0000-00-00', 2024, 'Male', '12', 'Drucker', 'maria an busilig', NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'maria an', '', 'busilig', '0000-00-00', 0, '0', '', NULL, NULL, NULL, '', '', '', '', '', 49),
(23, 'Michael', 'Estong', 'Jordan', '2005-09-10', 19, 'Male', '12', 'Drucker', 'maria an busilig', NULL, NULL, 'Rosena Jordan', 'House Wife', 'Tuy, Batangas', '094358537', 'Joe Jordan', 'Farmer', 'Tuy, Batangas', '09123456789', 'Romeo', 'Diliao', 'Reyes', 'Classmate', '12345645654', 'Tuy, Batangas', 'fritch', 'Marie', 'cortez', '0000-00-00', 1985, '39', 'Female', NULL, NULL, NULL, '09123456789', 'Tuy, Batangas', 'Nakipag Suntukan', 'Disciplinary action', 'Guide her Actions', 46),
(24, 'Tim', 'Cruz', 'Duncan', '2003-04-14', 21, 'Male', '12', 'Drucker', 'maria an busilig', NULL, NULL, 'Sarah Duncan', 'Engineer', 'Lian, Batangas', '1234567892', 'David Duncan', 'Teacher', 'Tuy', '0917-345-6789', 'Ann', 'Rose', 'Perez', 'Classmate', '43534643', 'Balayan Batangas', 'eron', 'Anne', 'pangilinan', '0000-00-00', 1990, '34', 'Female', NULL, NULL, NULL, '09123456780', 'Nasugbu, Batangas', 'Nagnakaw', 'Disciplinary Action', 'Makipag ayos kasama ang magulang', 48),
(25, 'Dirk', 'Gomez', 'Nowitzki', '2003-11-30', 20, 'Male', '12', 'Drucker', 'maria an busilig', NULL, NULL, 'Lisa Nowitzki', 'Designer', 'Tuy, Batangas', '1234567896', 'Daniel Nowitzki', 'Architect', 'Nasugbu', '0917-789-0123', 'Kleo', 'Patra', 'Meo', 'Classmate', '435346546', 'Lian, Batangas', 'maria teresa', 'Rosa', 'descallar', '0000-00-00', 1975, '48', 'Female', NULL, NULL, NULL, '09123456784', 'Lian, Batangas', 'Nakipag suntukan', 'Disciplinary Action', 'Disciplinary Action', 52),
(26, 'Shoto', 'Navarro', 'Todoroki', '2003-02-27', 21, 'Male', '12', 'Drucker', 'maria an busilig', NULL, NULL, 'Rachel Todoroki', 'Artist', 'Lian, Batangas', '1234567801', 'Shoto Todoroki', 'Police Officer', 'Tuy', '0917-234-5679', 'Selena', 'Gomez', 'Perez', 'Classmate', '123456', 'Balayan Batangas', 'mark jhun', 'Lloyd', 'atienza', '0000-00-00', 1987, '37', 'Male', NULL, NULL, NULL, '09123456789', 'Nasugbu, Batangas', 'Akyat Bakod', 'Disciplinary Action', 'Disciplinary Action', 57),
(27, 'Larry', 'Reyes', 'Bird', '2004-06-22', 20, 'Male', '12', 'Drucker', 'maria an busilig', NULL, NULL, 'Maria Bird', 'Teacher', 'Tuy, Batangas', '1234567890', 'Michael Bird', 'Engineer', 'Nasugbu', '0917-123-4567', '', '', '', '', '', '', 'eron', 'Anne', 'pangilinan', '0000-00-00', 1990, '34', 'Female', NULL, NULL, NULL, '09123456780', 'Nasugbu, Batangas', '', '', '', 48);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints_student`
--

INSERT INTO `complaints_student` (`id`, `victimFirstName`, `victimMiddleName`, `victimLastName`, `victimDOB`, `victimAge`, `victimSex`, `victimGrade`, `victimSection`, `victimAdviser`, `victimContact`, `victimAddress`, `motherName`, `motherOccupation`, `motherAddress`, `motherContact`, `fatherName`, `fatherOccupation`, `fatherAddress`, `fatherContact`, `complainantFirstName`, `complainantMiddleName`, `complainantLastName`, `relationshipToVictim`, `complainantContact`, `complainantAddress`, `complainedFirstName`, `complainedMiddleName`, `complainedLastName`, `complainedDOB`, `complainedAge`, `complainedSex`, `complainedGrade`, `complainedSection`, `complainedAdviser`, `complainedContact`, `complainedAddress`, `complainedMotherName`, `complainedMotherOccupation`, `complainedMotherAddress`, `complainedMotherContact`, `complainedFatherName`, `complainedFatherOccupation`, `complainedFatherAddress`, `complainedFatherContact`, `caseDetails`, `actionTaken`, `recommendations`, `reportedAt`, `student_id`) VALUES
(31, 'Jerry', 'Torres', 'West', '2006-05-15', 18, 'Male', '12', 'Drucker', 'maria an busilig', '09123456710', NULL, 'Betty West', 'Librarian', 'Lian, Batangas', '1234567898', 'Matthew West', 'Writer', 'Tuy', '0917-901-2345', 'Rose', 'Ann', 'Lorez', 'Classmate', '123456', 'Tuy, Batangas', 'Ochaco', 'Ramos', 'Uraraka', '2006-11-11', 17, 'Male', '12', 'Drucker', 'maria an busilig', '', NULL, 'Angela Uraraka', 'Social Worker', 'Tuy, Batangas', '1234567802', 'Ochaco Uraraka', 'Lawyer', 'Nasugbu', '0917-345-6780', 'Cutting Classes', 'Disciplinary Action', 'Disciplinary Action', '2024-10-27 17:47:33', 240270),
(32, 'Eijiro', 'Villanueva', 'Kirishima', '2006-10-12', 18, 'Male', '12', 'Drucker', 'maria an busilig', '09123456717', NULL, 'Susan Kirishima', 'Web Developer', 'Tuy, Batangas', '1234567805', 'Eijiro Kirishima', 'Construction Worker', 'Nasugbu', '0917-678-9013', 'Terra', 'Ann', 'Wally', 'Classmate', '123456', 'Lian, Batangas', 'Momo', 'Ortega', 'Yaoyorozu', '2004-08-16', 20, 'Male', '12', 'Drucker', 'maria an busilig', '', NULL, 'Deborah Yaoyorozu', 'Data Analyst', 'Nasugbu, Batangas', '1234567806', 'Mina Ashido', 'Artist', 'Lian', '0917-789-0124', 'Uniform', 'Disciplinary Action', 'Disciplinary Action', '2024-10-27 17:49:08', 240274),
(33, 'Tenya', 'Aquino', 'Iida', '2004-03-15', 20, 'Male', '12', 'Drucker', 'maria an busilig', '09123456715', NULL, 'Michelle Iida', 'Musician', 'Nasugbu, Batangas', '1234567803', 'Tenya Iida', 'Mechanic', 'Lian', '0917-456-7891', '', '', '', '', '', '', 'Alejandro', 'Alejandro', 'García', '2003-03-01', 21, 'Male', '12', 'fayol', 'fritz buenviaje', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '2024-10-27 17:50:20', 240297),
(34, 'Momo', 'Ortega', 'Yaoyorozu', '2004-08-16', 20, 'Male', '12', 'Drucker', 'maria an busilig', '09123456718', NULL, 'Deborah Yaoyorozu', 'Data Analyst', 'Nasugbu, Batangas', '1234567806', 'Mina Ashido', 'Artist', 'Lian', '0917-789-0124', '', '', '', '', '', '', 'Emma', 'Aiden', 'Garcia', '2006-01-01', 18, 'Male', '12', 'Drucker', 'maria an busilig', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '2024-10-27 17:50:40', 240282),
(35, 'Henry', 'Henry', 'Jackson', '2004-01-01', 20, 'Male', '12', 'Drucker', 'maria an busilig', '09123456791', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Harper', 'Harper', 'Thomas', '2005-10-01', 19, 'Male', '12', 'Drucker', 'maria an busilig', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '2024-10-27 17:50:49', 240292),
(36, 'William', 'James', 'Miller', '2005-02-01', 19, 'Male', '12', 'Drucker', 'maria an busilig', '09123456780', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Ramon', 'Villanueva', 'Fernandez', '2005-09-05', 19, 'Female', '12', 'aristotle', 'norecel gaa', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '2024-10-27 17:51:01', 240361);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fathers`
--

INSERT INTO `fathers` (`parent_id`, `student_id`, `name`, `contact_number`, `email`, `occupation`, `address`) VALUES
(24005, 240257, 'Joe Jordan', '09123456789', 'jordan.joe@example.com', 'Farmer', 'Tuy, Batangas'),
(24006, 240258, 'Michael Bird', '0917-123-4567', 'michael.bird@example.com', 'Engineer', 'Nasugbu'),
(24007, 240259, 'James Barkley', '0917-234-5678', 'james.barkley@example.com', 'Doctor', 'Lian'),
(24008, 240260, 'David Duncan', '0917-345-6789', 'david.duncan@example.com', 'Teacher', 'Tuy'),
(24009, 240261, 'Chris Malone', '0917-456-7890', 'chris.malone@example.com', 'Artist', 'Nasugbu'),
(24010, 240262, 'Kevin Chamberlain', '0917-567-8901', 'kevin.chamberlain@example.com', 'Scientist', 'Lian'),
(24011, 240263, 'Anthony Olajuwon', '0917-678-9012', 'anthony.olajuwon@example.com', 'Nurse', 'Tuy'),
(24012, 240264, 'Daniel Nowitzki', '0917-789-0123', 'daniel.nowitzki@example.com', 'Architect', 'Nasugbu'),
(24013, 240265, 'Brian Robertson', '0917-890-1234', 'brian.robertson@example.com', 'Musician', 'Lian'),
(24014, 240266, 'Matthew West', '0917-901-2345', 'matthew.west@example.com', 'Writer', 'Tuy'),
(24015, 240267, 'Jason Midoriya', '0917-012-3456', 'jason.midoriya@example.com', 'Chef', 'Nasugbu'),
(24016, 240268, 'Izuku Bakugo', '0917-123-4568', 'izuku.bakugo@example.com', 'Firefighter', 'Lian'),
(24017, 240269, 'Shoto Todoroki', '0917-234-5679', 'shoto.todoroki@example.com', 'Police Officer', 'Tuy'),
(24018, 240270, 'Ochaco Uraraka', '0917-345-6780', 'ochaco.uraraka@example.com', 'Lawyer', 'Nasugbu'),
(24019, 240271, 'Tenya Iida', '0917-456-7891', 'tenya.iida@example.com', 'Mechanic', 'Lian'),
(24020, 240272, 'Tsuyu Asui', '0917-567-8902', 'tsuyu.asui@example.com', 'Biologist', 'Tuy'),
(24021, 240273, 'Eijiro Kirishima', '0917-678-9013', 'eijiro.kirishima@example.com', 'Construction Worker', 'Nasugbu'),
(24022, 240274, 'Mina Ashido', '0917-789-0124', 'mina.ashido@example.com', 'Artist', 'Lian'),
(24023, 240275, 'Momo Yaoyorozu', '0917-890-1235', 'momo.yaoyorozu@example.com', 'Designer', 'Tuy'),
(24024, 240276, 'Fumikage Tokoyami', '0917-901-2346', 'fumikage.tokoyami@example.com', 'Photographer', 'Nasugbu'),
(240277, 240277, NULL, NULL, NULL, NULL, NULL),
(240278, 240278, NULL, NULL, NULL, NULL, NULL),
(240279, 240279, NULL, NULL, NULL, NULL, NULL),
(240280, 240280, NULL, NULL, NULL, NULL, NULL),
(240281, 240281, NULL, NULL, NULL, NULL, NULL),
(240282, 240282, NULL, NULL, NULL, NULL, NULL),
(240283, 240283, NULL, NULL, NULL, NULL, NULL),
(240284, 240284, NULL, NULL, NULL, NULL, NULL),
(240285, 240285, NULL, NULL, NULL, NULL, NULL),
(240286, 240286, NULL, NULL, NULL, NULL, NULL),
(240287, 240287, NULL, NULL, NULL, NULL, NULL),
(240288, 240288, NULL, NULL, NULL, NULL, NULL),
(240289, 240289, NULL, NULL, NULL, NULL, NULL),
(240290, 240290, NULL, NULL, NULL, NULL, NULL),
(240291, 240291, NULL, NULL, NULL, NULL, NULL),
(240292, 240292, NULL, NULL, NULL, NULL, NULL),
(240293, 240293, NULL, NULL, NULL, NULL, NULL),
(240294, 240294, NULL, NULL, NULL, NULL, NULL),
(240295, 240295, NULL, NULL, NULL, NULL, NULL),
(240296, 240296, NULL, NULL, NULL, NULL, NULL),
(240297, 240297, NULL, NULL, NULL, NULL, NULL),
(240298, 240298, NULL, NULL, NULL, NULL, NULL),
(240299, 240299, NULL, NULL, NULL, NULL, NULL),
(240300, 240300, NULL, NULL, NULL, NULL, NULL),
(240301, 240301, NULL, NULL, NULL, NULL, NULL),
(240302, 240302, NULL, NULL, NULL, NULL, NULL),
(240303, 240303, NULL, NULL, NULL, NULL, NULL),
(240304, 240304, NULL, NULL, NULL, NULL, NULL),
(240305, 240305, NULL, NULL, NULL, NULL, NULL),
(240306, 240306, NULL, NULL, NULL, NULL, NULL),
(240307, 240307, NULL, NULL, NULL, NULL, NULL),
(240308, 240308, NULL, NULL, NULL, NULL, NULL),
(240309, 240309, NULL, NULL, NULL, NULL, NULL),
(240310, 240310, NULL, NULL, NULL, NULL, NULL),
(240311, 240311, NULL, NULL, NULL, NULL, NULL),
(240312, 240312, NULL, NULL, NULL, NULL, NULL),
(240313, 240313, NULL, NULL, NULL, NULL, NULL),
(240314, 240314, NULL, NULL, NULL, NULL, NULL),
(240315, 240315, NULL, NULL, NULL, NULL, NULL),
(240316, 240316, NULL, NULL, NULL, NULL, NULL),
(240317, 240317, NULL, NULL, NULL, NULL, NULL),
(240318, 240318, NULL, NULL, NULL, NULL, NULL),
(240319, 240319, NULL, NULL, NULL, NULL, NULL),
(240320, 240320, NULL, NULL, NULL, NULL, NULL),
(240321, 240321, NULL, NULL, NULL, NULL, NULL),
(240322, 240322, NULL, NULL, NULL, NULL, NULL),
(240323, 240323, NULL, NULL, NULL, NULL, NULL),
(240324, 240324, NULL, NULL, NULL, NULL, NULL),
(240325, 240325, NULL, NULL, NULL, NULL, NULL),
(240326, 240326, NULL, NULL, NULL, NULL, NULL),
(240327, 240327, NULL, NULL, NULL, NULL, NULL),
(240328, 240328, NULL, NULL, NULL, NULL, NULL),
(240329, 240329, NULL, NULL, NULL, NULL, NULL),
(240330, 240330, NULL, NULL, NULL, NULL, NULL),
(240331, 240331, NULL, NULL, NULL, NULL, NULL),
(240332, 240332, NULL, NULL, NULL, NULL, NULL),
(240333, 240333, NULL, NULL, NULL, NULL, NULL),
(240334, 240334, NULL, NULL, NULL, NULL, NULL),
(240335, 240335, NULL, NULL, NULL, NULL, NULL),
(240336, 240336, NULL, NULL, NULL, NULL, NULL),
(240337, 240337, NULL, NULL, NULL, NULL, NULL),
(240338, 240338, NULL, NULL, NULL, NULL, NULL),
(240339, 240339, NULL, NULL, NULL, NULL, NULL),
(240340, 240340, NULL, NULL, NULL, NULL, NULL),
(240341, 240341, NULL, NULL, NULL, NULL, NULL),
(240342, 240342, NULL, NULL, NULL, NULL, NULL),
(240343, 240343, NULL, NULL, NULL, NULL, NULL),
(240344, 240344, NULL, NULL, NULL, NULL, NULL),
(240345, 240345, NULL, NULL, NULL, NULL, NULL),
(240346, 240346, NULL, NULL, NULL, NULL, NULL),
(240347, 240347, NULL, NULL, NULL, NULL, NULL),
(240348, 240348, NULL, NULL, NULL, NULL, NULL),
(240349, 240349, NULL, NULL, NULL, NULL, NULL),
(240350, 240350, NULL, NULL, NULL, NULL, NULL),
(240351, 240351, NULL, NULL, NULL, NULL, NULL),
(240352, 240352, NULL, NULL, NULL, NULL, NULL),
(240353, 240353, NULL, NULL, NULL, NULL, NULL),
(240354, 240354, NULL, NULL, NULL, NULL, NULL),
(240355, 240355, NULL, NULL, NULL, NULL, NULL),
(240356, 240356, NULL, NULL, NULL, NULL, NULL),
(240357, 240357, NULL, NULL, NULL, NULL, NULL),
(240358, 240358, NULL, NULL, NULL, NULL, NULL),
(240359, 240359, NULL, NULL, NULL, NULL, NULL),
(240360, 240360, NULL, NULL, NULL, NULL, NULL),
(240361, 240361, NULL, NULL, NULL, NULL, NULL),
(240362, 240362, NULL, NULL, NULL, NULL, NULL),
(240363, 240363, NULL, NULL, NULL, NULL, NULL),
(240364, 240364, NULL, NULL, NULL, NULL, NULL),
(240365, 240365, NULL, NULL, NULL, NULL, NULL),
(240366, 240366, NULL, NULL, NULL, NULL, NULL),
(240367, 240367, NULL, NULL, NULL, NULL, NULL),
(240368, 240368, NULL, NULL, NULL, NULL, NULL),
(240369, 240369, NULL, NULL, NULL, NULL, NULL),
(240370, 240370, NULL, NULL, NULL, NULL, NULL),
(240371, 240371, NULL, NULL, NULL, NULL, NULL),
(240372, 240372, NULL, NULL, NULL, NULL, NULL),
(240373, 240373, NULL, NULL, NULL, NULL, NULL),
(240374, 240374, NULL, NULL, NULL, NULL, NULL),
(240375, 240375, NULL, NULL, NULL, NULL, NULL),
(240376, 240376, NULL, NULL, NULL, NULL, NULL),
(240377, 240377, NULL, NULL, NULL, NULL, NULL),
(240378, 240378, NULL, NULL, NULL, NULL, NULL),
(240379, 240379, NULL, NULL, NULL, NULL, NULL),
(240380, 240380, NULL, NULL, NULL, NULL, NULL),
(240381, 240381, NULL, NULL, NULL, NULL, NULL),
(240382, 240382, NULL, NULL, NULL, NULL, NULL),
(240383, 240383, NULL, NULL, NULL, NULL, NULL),
(240384, 240384, NULL, NULL, NULL, NULL, NULL),
(240385, 240385, NULL, NULL, NULL, NULL, NULL),
(240386, 240386, NULL, NULL, NULL, NULL, NULL),
(240387, 240387, NULL, NULL, NULL, NULL, NULL),
(240388, 240388, NULL, NULL, NULL, NULL, NULL),
(240389, 240389, NULL, NULL, NULL, NULL, NULL),
(240390, 240390, NULL, NULL, NULL, NULL, NULL),
(240391, 240391, NULL, NULL, NULL, NULL, NULL),
(240392, 240392, NULL, NULL, NULL, NULL, NULL),
(240393, 240393, NULL, NULL, NULL, NULL, NULL),
(240394, 240394, NULL, NULL, NULL, NULL, NULL),
(240395, 240395, NULL, NULL, NULL, NULL, NULL),
(240396, 240396, NULL, NULL, NULL, NULL, NULL),
(240397, 240397, NULL, NULL, NULL, NULL, NULL),
(240398, 240398, NULL, NULL, NULL, NULL, NULL),
(240399, 240399, NULL, NULL, NULL, NULL, NULL),
(240400, 240400, NULL, NULL, NULL, NULL, NULL),
(240401, 240401, NULL, NULL, NULL, NULL, NULL),
(240402, 240402, NULL, NULL, NULL, NULL, NULL),
(240403, 240403, NULL, NULL, NULL, NULL, NULL),
(240404, 240404, NULL, NULL, NULL, NULL, NULL),
(240405, 240405, NULL, NULL, NULL, NULL, NULL),
(240406, 240406, NULL, NULL, NULL, NULL, NULL),
(240407, 240407, NULL, NULL, NULL, NULL, NULL),
(240408, 240408, NULL, NULL, NULL, NULL, NULL),
(240409, 240409, NULL, NULL, NULL, NULL, NULL),
(240410, 240410, NULL, NULL, NULL, NULL, NULL),
(240411, 240411, NULL, NULL, NULL, NULL, NULL),
(240412, 240412, NULL, NULL, NULL, NULL, NULL),
(240413, 240413, NULL, NULL, NULL, NULL, NULL),
(240414, 240414, NULL, NULL, NULL, NULL, NULL),
(240415, 240415, NULL, NULL, NULL, NULL, NULL),
(240416, 240416, NULL, NULL, NULL, NULL, NULL),
(240417, 240417, NULL, NULL, NULL, NULL, NULL),
(240418, 240418, NULL, NULL, NULL, NULL, NULL),
(240419, 240419, NULL, NULL, NULL, NULL, NULL),
(240420, 240420, NULL, NULL, NULL, NULL, NULL),
(240421, 240421, NULL, NULL, NULL, NULL, NULL),
(240422, 240422, NULL, NULL, NULL, NULL, NULL),
(240423, 240423, NULL, NULL, NULL, NULL, NULL),
(240424, 240424, NULL, NULL, NULL, NULL, NULL),
(240425, 240425, NULL, NULL, NULL, NULL, NULL),
(240426, 240426, NULL, NULL, NULL, NULL, NULL),
(240427, 240427, NULL, NULL, NULL, NULL, NULL),
(240428, 240428, NULL, NULL, NULL, NULL, NULL),
(240429, 240429, NULL, NULL, NULL, NULL, NULL),
(240430, 240430, NULL, NULL, NULL, NULL, NULL),
(240431, 240431, NULL, NULL, NULL, NULL, NULL),
(240432, 240432, NULL, NULL, NULL, NULL, NULL),
(240433, 240433, NULL, NULL, NULL, NULL, NULL),
(240434, 240434, NULL, NULL, NULL, NULL, NULL),
(240435, 240435, NULL, NULL, NULL, NULL, NULL),
(240436, 240436, NULL, NULL, NULL, NULL, NULL),
(240437, 240437, NULL, NULL, NULL, NULL, NULL),
(240438, 240438, NULL, NULL, NULL, NULL, NULL),
(240439, 240439, NULL, NULL, NULL, NULL, NULL),
(240440, 240440, NULL, NULL, NULL, NULL, NULL),
(240441, 240441, NULL, NULL, NULL, NULL, NULL),
(240442, 240442, NULL, NULL, NULL, NULL, NULL),
(240443, 240443, NULL, NULL, NULL, NULL, NULL),
(240444, 240444, NULL, NULL, NULL, NULL, NULL),
(240445, 240445, NULL, NULL, NULL, NULL, NULL),
(240446, 240446, NULL, NULL, NULL, NULL, NULL),
(240447, 240447, NULL, NULL, NULL, NULL, NULL),
(240448, 240448, NULL, NULL, NULL, NULL, NULL),
(240449, 240449, NULL, NULL, NULL, NULL, NULL),
(240450, 240450, NULL, NULL, NULL, NULL, NULL),
(240451, 240451, NULL, NULL, NULL, NULL, NULL),
(240452, 240452, NULL, NULL, NULL, NULL, NULL),
(240453, 240453, NULL, NULL, NULL, NULL, NULL),
(240454, 240454, NULL, NULL, NULL, NULL, NULL),
(240455, 240455, NULL, NULL, NULL, NULL, NULL),
(240456, 240456, NULL, NULL, NULL, NULL, NULL),
(240457, 240457, NULL, NULL, NULL, NULL, NULL),
(240458, 240458, NULL, NULL, NULL, NULL, NULL),
(240459, 240459, NULL, NULL, NULL, NULL, NULL),
(240460, 240460, NULL, NULL, NULL, NULL, NULL),
(240461, 240461, NULL, NULL, NULL, NULL, NULL),
(240462, 240462, NULL, NULL, NULL, NULL, NULL),
(240463, 240463, NULL, NULL, NULL, NULL, NULL),
(240464, 240464, NULL, NULL, NULL, NULL, NULL),
(240465, 240465, NULL, NULL, NULL, NULL, NULL),
(240466, 240466, NULL, NULL, NULL, NULL, NULL),
(240467, 240467, NULL, NULL, NULL, NULL, NULL),
(240468, 240468, NULL, NULL, NULL, NULL, NULL),
(240469, 240469, NULL, NULL, NULL, NULL, NULL),
(240470, 240470, NULL, NULL, NULL, NULL, NULL),
(240471, 240471, NULL, NULL, NULL, NULL, NULL),
(240472, 240472, NULL, NULL, NULL, NULL, NULL),
(240473, 240473, NULL, NULL, NULL, NULL, NULL),
(240474, 240474, NULL, NULL, NULL, NULL, NULL),
(240475, 240475, NULL, NULL, NULL, NULL, NULL),
(240476, 240476, NULL, NULL, NULL, NULL, NULL),
(240477, 240477, NULL, NULL, NULL, NULL, NULL),
(240478, 240478, NULL, NULL, NULL, NULL, NULL),
(240479, 240479, NULL, NULL, NULL, NULL, NULL),
(240480, 240480, NULL, NULL, NULL, NULL, NULL),
(240481, 240481, NULL, NULL, NULL, NULL, NULL),
(240482, 240482, NULL, NULL, NULL, NULL, NULL),
(240483, 240483, NULL, NULL, NULL, NULL, NULL),
(240484, 240484, NULL, NULL, NULL, NULL, NULL),
(240485, 240485, NULL, NULL, NULL, NULL, NULL),
(240486, 240486, NULL, NULL, NULL, NULL, NULL),
(240487, 240487, NULL, NULL, NULL, NULL, NULL),
(240488, 240488, NULL, NULL, NULL, NULL, NULL),
(240489, 240489, NULL, NULL, NULL, NULL, NULL),
(240490, 240490, NULL, NULL, NULL, NULL, NULL),
(240491, 240491, NULL, NULL, NULL, NULL, NULL),
(240492, 240492, NULL, NULL, NULL, NULL, NULL),
(240493, 240493, NULL, NULL, NULL, NULL, NULL),
(240494, 240494, NULL, NULL, NULL, NULL, NULL),
(240495, 240495, NULL, NULL, NULL, NULL, NULL),
(240496, 240496, NULL, NULL, NULL, NULL, NULL),
(240497, 240497, NULL, NULL, NULL, NULL, NULL),
(240498, 240498, NULL, NULL, NULL, NULL, NULL),
(240499, 240499, NULL, NULL, NULL, NULL, NULL),
(240500, 240500, NULL, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guards`
--

INSERT INTO `guards` (`id`, `username`, `password`, `first_name`, `middle_name`, `last_name`, `email`, `position`, `birthdate`, `sex`, `contact_number`, `address`) VALUES
(15, 'senior', '1234', 'Senior', 'Estong', 'Perez', 'Senior@gmail.com', 'Guard', '1975-01-17', 'Male', '09665171890', 'Nasugbu, Batangas');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mothers`
--

INSERT INTO `mothers` (`parent_id`, `student_id`, `name`, `contact_number`, `email`, `occupation`, `address`) VALUES
(24006, 240258, 'Maria Bird', '1234567890', NULL, 'Teacher', 'Tuy, Batangas'),
(24007, 240259, 'Linda Barkley', '1234567891', NULL, 'Nurse', 'Nasugbu, Batangas'),
(24008, 240260, 'Sarah Duncan', '1234567892', NULL, 'Engineer', 'Lian, Batangas'),
(24009, 240261, 'Karen Malone', '1234567893', NULL, 'Accountant', 'Tuy, Batangas'),
(24010, 240262, 'Jennifer Chamberlain', '1234567894', NULL, 'Doctor', 'Nasugbu, Batangas'),
(24011, 240263, 'Patricia Olajuwon', '1234567895', NULL, 'Chef', 'Lian, Batangas'),
(24012, 240264, 'Lisa Nowitzki', '1234567896', NULL, 'Designer', 'Tuy, Batangas'),
(24013, 240265, 'Nancy Robertson', '1234567897', NULL, 'Firefighter', 'Nasugbu, Batangas'),
(24014, 240266, 'Betty West', '1234567898', NULL, 'Librarian', 'Lian, Batangas'),
(24015, 240267, 'Emily Midoriya', '1234567899', NULL, 'Pharmacist', 'Tuy, Batangas'),
(24016, 240268, 'Jessica Bakugo', '1234567800', NULL, 'Veterinarian', 'Nasugbu, Batangas'),
(24017, 240269, 'Rachel Todoroki', '1234567801', NULL, 'Artist', 'Lian, Batangas'),
(24018, 240270, 'Angela Uraraka', '1234567802', NULL, 'Social Worker', 'Tuy, Batangas'),
(24019, 240271, 'Michelle Iida', '1234567803', NULL, 'Musician', 'Nasugbu, Batangas'),
(24020, 240272, 'Laura Asui', '1234567804', NULL, 'Writer', 'Lian, Batangas'),
(24021, 240273, 'Susan Kirishima', '1234567805', NULL, 'Web Developer', 'Tuy, Batangas'),
(24022, 240274, 'Deborah Yaoyorozu', '1234567806', NULL, 'Data Analyst', 'Nasugbu, Batangas'),
(24023, 240275, 'Christine Tokoyami', '1234567807', NULL, 'Photographer', 'Lian, Batangas'),
(24024, 240276, 'Samantha Kaminari', '1234567808', NULL, 'Sales Associate', 'Tuy, Batangas'),
(240257, NULL, 'Rosena Jordan', '094358537', 'rosena@gmail.com', 'House Wife', 'Tuy, Batangas'),
(240258, NULL, 'Maria Clara', '09123456789', 'maria.clara@gmail.com', 'Teacher', 'Balayan, Batangas'),
(240259, NULL, 'Juana de la Cruz', '09234567890', 'juana@gmail.com', 'Nurse', 'Nasugbu, Batangas'),
(240260, NULL, 'Ana Santos', '09345678901', 'ana.santos@gmail.com', 'Engineer', 'Lian, Batangas'),
(240261, NULL, 'Liza Reyes', '09456789012', 'liza.reyes@gmail.com', 'Accountant', 'Tuy, Batangas'),
(240262, NULL, 'Carmen Lopez', '09567890123', 'carmen.lopez@gmail.com', 'Chef', 'Balayan, Batangas'),
(240263, NULL, 'Sofia Lee', '09678901234', 'sofia.lee@gmail.com', 'Artist', 'Nasugbu, Batangas'),
(240264, NULL, 'Julia Kim', '09789012345', 'julia.kim@gmail.com', 'Scientist', 'Lian, Batangas'),
(240265, NULL, 'Rita Park', '09890123456', 'rita.park@gmail.com', 'Writer', 'Tuy, Batangas'),
(240266, NULL, 'Anna Maria', '09901234567', 'anna.maria@gmail.com', 'Pharmacist', 'Balayan, Batangas'),
(240267, NULL, 'Ella Stone', '09123456780', 'ella.stone@gmail.com', 'Designer', 'Nasugbu, Batangas'),
(240268, NULL, 'Nina Brown', '09234567801', 'nina.brown@gmail.com', 'Developer', 'Lian, Batangas'),
(240269, NULL, 'Olivia White', '09345678912', 'olivia.white@gmail.com', 'Sales Associate', 'Tuy, Batangas'),
(240270, NULL, 'Emma Watson', '09456789023', 'emma.watson@gmail.com', 'Marketing Manager', 'Balayan, Batangas'),
(240271, NULL, 'Sarah Connor', '09567890134', 'sarah.connor@gmail.com', 'Project Manager', 'Nasugbu, Batangas'),
(240272, NULL, 'Lucy Hale', '09678901245', 'lucy.hale@gmail.com', 'HR Specialist', 'Lian, Batangas'),
(240273, NULL, 'Mia Wong', '09789012356', 'mia.wong@gmail.com', 'Consultant', 'Tuy, Batangas'),
(240274, NULL, 'Chloe Zhang', '09890123467', 'chloe.zhang@gmail.com', 'Data Analyst', 'Balayan, Batangas'),
(240275, NULL, 'Ella Fitzgerald', '09123456791', 'ella.fitzgerald@gmail.com', 'Photographer', 'Nasugbu, Batangas'),
(240276, NULL, 'Maya Angelou', '09234567802', 'maya.angelou@gmail.com', 'Public Speaker', 'Lian, Batangas'),
(240277, 240277, NULL, NULL, NULL, NULL, NULL),
(240278, 240278, NULL, NULL, NULL, NULL, NULL),
(240279, 240279, NULL, NULL, NULL, NULL, NULL),
(240280, 240280, NULL, NULL, NULL, NULL, NULL),
(240281, 240281, NULL, NULL, NULL, NULL, NULL),
(240282, 240282, NULL, NULL, NULL, NULL, NULL),
(240283, 240283, NULL, NULL, NULL, NULL, NULL),
(240284, 240284, NULL, NULL, NULL, NULL, NULL),
(240285, 240285, NULL, NULL, NULL, NULL, NULL),
(240287, 240287, NULL, NULL, NULL, NULL, NULL),
(240288, 240288, NULL, NULL, NULL, NULL, NULL),
(240289, 240289, NULL, NULL, NULL, NULL, NULL),
(240290, 240290, NULL, NULL, NULL, NULL, NULL),
(240291, 240291, NULL, NULL, NULL, NULL, NULL),
(240292, 240292, NULL, NULL, NULL, NULL, NULL),
(240293, 240293, NULL, NULL, NULL, NULL, NULL),
(240294, 240294, NULL, NULL, NULL, NULL, NULL),
(240295, 240295, NULL, NULL, NULL, NULL, NULL),
(240296, 240296, NULL, NULL, NULL, NULL, NULL),
(240297, 240297, NULL, NULL, NULL, NULL, NULL),
(240298, 240298, NULL, NULL, NULL, NULL, NULL),
(240299, 240299, NULL, NULL, NULL, NULL, NULL),
(240300, 240300, NULL, NULL, NULL, NULL, NULL),
(240301, 240301, NULL, NULL, NULL, NULL, NULL),
(240302, 240302, NULL, NULL, NULL, NULL, NULL),
(240303, 240303, NULL, NULL, NULL, NULL, NULL),
(240304, 240304, NULL, NULL, NULL, NULL, NULL),
(240305, 240305, NULL, NULL, NULL, NULL, NULL),
(240306, 240306, NULL, NULL, NULL, NULL, NULL),
(240307, 240307, NULL, NULL, NULL, NULL, NULL),
(240308, 240308, NULL, NULL, NULL, NULL, NULL),
(240309, 240309, NULL, NULL, NULL, NULL, NULL),
(240310, 240310, NULL, NULL, NULL, NULL, NULL),
(240311, 240311, NULL, NULL, NULL, NULL, NULL),
(240312, 240312, NULL, NULL, NULL, NULL, NULL),
(240313, 240313, NULL, NULL, NULL, NULL, NULL),
(240314, 240314, NULL, NULL, NULL, NULL, NULL),
(240315, 240315, NULL, NULL, NULL, NULL, NULL),
(240316, 240316, NULL, NULL, NULL, NULL, NULL),
(240317, 240317, NULL, NULL, NULL, NULL, NULL),
(240318, 240318, NULL, NULL, NULL, NULL, NULL),
(240319, 240319, NULL, NULL, NULL, NULL, NULL),
(240320, 240320, NULL, NULL, NULL, NULL, NULL),
(240321, 240321, NULL, NULL, NULL, NULL, NULL),
(240322, 240322, NULL, NULL, NULL, NULL, NULL),
(240323, 240323, NULL, NULL, NULL, NULL, NULL),
(240324, 240324, NULL, NULL, NULL, NULL, NULL),
(240325, 240325, NULL, NULL, NULL, NULL, NULL),
(240326, 240326, NULL, NULL, NULL, NULL, NULL),
(240327, 240327, NULL, NULL, NULL, NULL, NULL),
(240328, 240328, NULL, NULL, NULL, NULL, NULL),
(240329, 240329, NULL, NULL, NULL, NULL, NULL),
(240330, 240330, NULL, NULL, NULL, NULL, NULL),
(240331, 240331, NULL, NULL, NULL, NULL, NULL),
(240332, 240332, NULL, NULL, NULL, NULL, NULL),
(240333, 240333, NULL, NULL, NULL, NULL, NULL),
(240334, 240334, NULL, NULL, NULL, NULL, NULL),
(240335, 240335, NULL, NULL, NULL, NULL, NULL),
(240336, 240336, NULL, NULL, NULL, NULL, NULL),
(240337, 240337, NULL, NULL, NULL, NULL, NULL),
(240338, 240338, NULL, NULL, NULL, NULL, NULL),
(240339, 240339, NULL, NULL, NULL, NULL, NULL),
(240340, 240340, NULL, NULL, NULL, NULL, NULL),
(240341, 240341, NULL, NULL, NULL, NULL, NULL),
(240342, 240342, NULL, NULL, NULL, NULL, NULL),
(240343, 240343, NULL, NULL, NULL, NULL, NULL),
(240344, 240344, NULL, NULL, NULL, NULL, NULL),
(240345, 240345, NULL, NULL, NULL, NULL, NULL),
(240346, 240346, NULL, NULL, NULL, NULL, NULL),
(240347, 240347, NULL, NULL, NULL, NULL, NULL),
(240348, 240348, NULL, NULL, NULL, NULL, NULL),
(240349, 240349, NULL, NULL, NULL, NULL, NULL),
(240350, 240350, NULL, NULL, NULL, NULL, NULL),
(240351, 240351, NULL, NULL, NULL, NULL, NULL),
(240352, 240352, NULL, NULL, NULL, NULL, NULL),
(240353, 240353, NULL, NULL, NULL, NULL, NULL),
(240354, 240354, NULL, NULL, NULL, NULL, NULL),
(240355, 240355, NULL, NULL, NULL, NULL, NULL),
(240356, 240356, NULL, NULL, NULL, NULL, NULL),
(240357, 240357, NULL, NULL, NULL, NULL, NULL),
(240358, 240358, NULL, NULL, NULL, NULL, NULL),
(240359, 240359, NULL, NULL, NULL, NULL, NULL),
(240360, 240360, NULL, NULL, NULL, NULL, NULL),
(240361, 240361, NULL, NULL, NULL, NULL, NULL),
(240362, 240362, NULL, NULL, NULL, NULL, NULL),
(240363, 240363, NULL, NULL, NULL, NULL, NULL),
(240364, 240364, NULL, NULL, NULL, NULL, NULL),
(240365, 240365, NULL, NULL, NULL, NULL, NULL),
(240366, 240366, NULL, NULL, NULL, NULL, NULL),
(240367, 240367, NULL, NULL, NULL, NULL, NULL),
(240368, 240368, NULL, NULL, NULL, NULL, NULL),
(240369, 240369, NULL, NULL, NULL, NULL, NULL),
(240370, 240370, NULL, NULL, NULL, NULL, NULL),
(240371, 240371, NULL, NULL, NULL, NULL, NULL),
(240372, 240372, NULL, NULL, NULL, NULL, NULL),
(240373, 240373, NULL, NULL, NULL, NULL, NULL),
(240374, 240374, NULL, NULL, NULL, NULL, NULL),
(240375, 240375, NULL, NULL, NULL, NULL, NULL),
(240376, 240376, NULL, NULL, NULL, NULL, NULL),
(240377, 240377, NULL, NULL, NULL, NULL, NULL),
(240378, 240378, NULL, NULL, NULL, NULL, NULL),
(240379, 240379, NULL, NULL, NULL, NULL, NULL),
(240380, 240380, NULL, NULL, NULL, NULL, NULL),
(240381, 240381, NULL, NULL, NULL, NULL, NULL),
(240382, 240382, NULL, NULL, NULL, NULL, NULL),
(240383, 240383, NULL, NULL, NULL, NULL, NULL),
(240384, 240384, NULL, NULL, NULL, NULL, NULL),
(240385, 240385, NULL, NULL, NULL, NULL, NULL),
(240386, 240386, NULL, NULL, NULL, NULL, NULL),
(240387, 240387, NULL, NULL, NULL, NULL, NULL),
(240388, 240388, NULL, NULL, NULL, NULL, NULL),
(240389, 240389, NULL, NULL, NULL, NULL, NULL),
(240390, 240390, NULL, NULL, NULL, NULL, NULL),
(240391, 240391, NULL, NULL, NULL, NULL, NULL),
(240392, 240392, NULL, NULL, NULL, NULL, NULL),
(240393, 240393, NULL, NULL, NULL, NULL, NULL),
(240394, 240394, NULL, NULL, NULL, NULL, NULL),
(240395, 240395, NULL, NULL, NULL, NULL, NULL),
(240396, 240396, NULL, NULL, NULL, NULL, NULL),
(240397, 240397, NULL, NULL, NULL, NULL, NULL),
(240398, 240398, NULL, NULL, NULL, NULL, NULL),
(240399, 240399, NULL, NULL, NULL, NULL, NULL),
(240400, 240400, NULL, NULL, NULL, NULL, NULL),
(240401, 240401, NULL, NULL, NULL, NULL, NULL),
(240402, 240402, NULL, NULL, NULL, NULL, NULL),
(240403, 240403, NULL, NULL, NULL, NULL, NULL),
(240404, 240404, NULL, NULL, NULL, NULL, NULL),
(240405, 240405, NULL, NULL, NULL, NULL, NULL),
(240406, 240406, NULL, NULL, NULL, NULL, NULL),
(240407, 240407, NULL, NULL, NULL, NULL, NULL),
(240408, 240408, NULL, NULL, NULL, NULL, NULL),
(240409, 240409, NULL, NULL, NULL, NULL, NULL),
(240410, 240410, NULL, NULL, NULL, NULL, NULL),
(240411, 240411, NULL, NULL, NULL, NULL, NULL),
(240412, 240412, NULL, NULL, NULL, NULL, NULL),
(240413, 240413, NULL, NULL, NULL, NULL, NULL),
(240414, 240414, NULL, NULL, NULL, NULL, NULL),
(240415, 240415, NULL, NULL, NULL, NULL, NULL),
(240416, 240416, NULL, NULL, NULL, NULL, NULL),
(240417, 240417, NULL, NULL, NULL, NULL, NULL),
(240418, 240418, NULL, NULL, NULL, NULL, NULL),
(240419, 240419, NULL, NULL, NULL, NULL, NULL),
(240420, 240420, NULL, NULL, NULL, NULL, NULL),
(240421, 240421, NULL, NULL, NULL, NULL, NULL),
(240422, 240422, NULL, NULL, NULL, NULL, NULL),
(240423, 240423, NULL, NULL, NULL, NULL, NULL),
(240424, 240424, NULL, NULL, NULL, NULL, NULL),
(240425, 240425, NULL, NULL, NULL, NULL, NULL),
(240426, 240426, NULL, NULL, NULL, NULL, NULL),
(240427, 240427, NULL, NULL, NULL, NULL, NULL),
(240428, 240428, NULL, NULL, NULL, NULL, NULL),
(240429, 240429, NULL, NULL, NULL, NULL, NULL),
(240430, 240430, NULL, NULL, NULL, NULL, NULL),
(240431, 240431, NULL, NULL, NULL, NULL, NULL),
(240432, 240432, NULL, NULL, NULL, NULL, NULL),
(240433, 240433, NULL, NULL, NULL, NULL, NULL),
(240434, 240434, NULL, NULL, NULL, NULL, NULL),
(240435, 240435, NULL, NULL, NULL, NULL, NULL),
(240436, 240436, NULL, NULL, NULL, NULL, NULL),
(240437, 240437, NULL, NULL, NULL, NULL, NULL),
(240438, 240438, NULL, NULL, NULL, NULL, NULL),
(240439, 240439, NULL, NULL, NULL, NULL, NULL),
(240440, 240440, NULL, NULL, NULL, NULL, NULL),
(240441, 240441, NULL, NULL, NULL, NULL, NULL),
(240442, 240442, NULL, NULL, NULL, NULL, NULL),
(240443, 240443, NULL, NULL, NULL, NULL, NULL),
(240444, 240444, NULL, NULL, NULL, NULL, NULL),
(240445, 240445, NULL, NULL, NULL, NULL, NULL),
(240446, 240446, NULL, NULL, NULL, NULL, NULL),
(240447, 240447, NULL, NULL, NULL, NULL, NULL),
(240448, 240448, NULL, NULL, NULL, NULL, NULL),
(240449, 240449, NULL, NULL, NULL, NULL, NULL),
(240450, 240450, NULL, NULL, NULL, NULL, NULL),
(240451, 240451, NULL, NULL, NULL, NULL, NULL),
(240452, 240452, NULL, NULL, NULL, NULL, NULL),
(240453, 240453, NULL, NULL, NULL, NULL, NULL),
(240454, 240454, NULL, NULL, NULL, NULL, NULL),
(240455, 240455, NULL, NULL, NULL, NULL, NULL),
(240456, 240456, NULL, NULL, NULL, NULL, NULL),
(240457, 240457, NULL, NULL, NULL, NULL, NULL),
(240458, 240458, NULL, NULL, NULL, NULL, NULL),
(240459, 240459, NULL, NULL, NULL, NULL, NULL),
(240460, 240460, NULL, NULL, NULL, NULL, NULL),
(240461, 240461, NULL, NULL, NULL, NULL, NULL),
(240462, 240462, NULL, NULL, NULL, NULL, NULL),
(240463, 240463, NULL, NULL, NULL, NULL, NULL),
(240464, 240464, NULL, NULL, NULL, NULL, NULL),
(240465, 240465, NULL, NULL, NULL, NULL, NULL),
(240466, 240466, NULL, NULL, NULL, NULL, NULL),
(240467, 240467, NULL, NULL, NULL, NULL, NULL),
(240468, 240468, NULL, NULL, NULL, NULL, NULL),
(240469, 240469, NULL, NULL, NULL, NULL, NULL),
(240470, 240470, NULL, NULL, NULL, NULL, NULL),
(240471, 240471, NULL, NULL, NULL, NULL, NULL),
(240472, 240472, NULL, NULL, NULL, NULL, NULL),
(240473, 240473, NULL, NULL, NULL, NULL, NULL),
(240474, 240474, NULL, NULL, NULL, NULL, NULL),
(240475, 240475, NULL, NULL, NULL, NULL, NULL),
(240476, 240476, NULL, NULL, NULL, NULL, NULL),
(240477, 240477, NULL, NULL, NULL, NULL, NULL),
(240478, 240478, NULL, NULL, NULL, NULL, NULL),
(240479, 240479, NULL, NULL, NULL, NULL, NULL),
(240480, 240480, NULL, NULL, NULL, NULL, NULL),
(240481, 240481, NULL, NULL, NULL, NULL, NULL),
(240482, 240482, NULL, NULL, NULL, NULL, NULL),
(240483, 240483, NULL, NULL, NULL, NULL, NULL),
(240484, 240484, NULL, NULL, NULL, NULL, NULL),
(240485, 240485, NULL, NULL, NULL, NULL, NULL),
(240486, 240486, NULL, NULL, NULL, NULL, NULL),
(240487, 240487, NULL, NULL, NULL, NULL, NULL),
(240488, 240488, NULL, NULL, NULL, NULL, NULL),
(240489, 240489, NULL, NULL, NULL, NULL, NULL),
(240490, 240490, NULL, NULL, NULL, NULL, NULL),
(240491, 240491, NULL, NULL, NULL, NULL, NULL),
(240492, 240492, NULL, NULL, NULL, NULL, NULL),
(240493, 240493, NULL, NULL, NULL, NULL, NULL),
(240494, 240494, NULL, NULL, NULL, NULL, NULL),
(240495, 240495, NULL, NULL, NULL, NULL, NULL),
(240496, 240496, NULL, NULL, NULL, NULL, NULL),
(240497, 240497, NULL, NULL, NULL, NULL, NULL),
(240498, 240498, NULL, NULL, NULL, NULL, NULL),
(240499, 240499, NULL, NULL, NULL, NULL, NULL),
(240500, 240500, NULL, NULL, NULL, NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `date` date NOT NULL,
  `time` time NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `admin_id`, `date`, `time`, `description`, `location`, `created_at`, `updated_at`) VALUES
(17, 5, '2024-10-04', '00:00:00', 'Larry Bird', '08:00', '2024-10-28 01:58:34', '2024-10-28 01:58:34'),
(18, 5, '2024-10-11', '00:00:00', 'meeting', '08:00', '2024-10-28 03:22:59', '2024-10-28 03:22:59');

-- --------------------------------------------------------

--
-- Table structure for table `school_year`
--

CREATE TABLE `school_year` (
  `id` int(11) NOT NULL,
  `year_start` int(11) NOT NULL,
  `year_end` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_year`
--

INSERT INTO `school_year` (`id`, `year_start`, `year_end`, `active`) VALUES
(1, 2023, 2024, 0),
(2, 2024, 2025, 0),
(3, 2025, 2026, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(120) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `strand_id` int(11) DEFAULT NULL,
  `grade_level` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`, `teacher_id`, `strand_id`, `grade_level`) VALUES
(30, 'Euclid', 46, 7, '12'),
(31, 'Pythagoras', 48, 7, '12'),
(32, 'Drucker', 49, 8, '12'),
(33, 'fayol', 50, 8, '12'),
(34, 'aristotle', 51, 9, '12'),
(35, 'confucius', 52, 9, '12'),
(36, 'Cokking - Keller', 57, 10, '12'),
(37, 'Cookery 12 - Cleopatra', 58, 10, '12'),
(38, 'Computer Programming - Lovelace', 59, 11, '12'),
(39, 'galileo', 60, 11, '11'),
(42, 'Archimedes', 62, 7, '11'),
(43, 'Descartes', 63, 7, '11'),
(44, 'Pacioli', 64, 8, '11'),
(45, 'Weber', 65, 8, '11'),
(46, 'Aphrodite', 66, 9, '11'),
(47, 'Artemis', 67, 9, '11'),
(48, 'Athena', 68, 9, '11'),
(49, 'Hera', 69, 9, '11'),
(50, 'Hermes', 70, 9, '11'),
(51, 'Venus', 71, 9, '11'),
(52, 'Cookery - Ramsay', 72, 10, '11'),
(53, 'Cookery - Goldman', 73, 10, '11'),
(54, 'Animation - Gates', 74, 11, '11'),
(55, 'Democritus', 53, 9, '12'),
(56, 'Freud', 54, 9, '12'),
(57, 'Locke', 55, 9, '12'),
(58, 'Plato', 56, 9, '12'),
(59, 'Rizal', 75, 12, '11');

-- --------------------------------------------------------

--
-- Table structure for table `section_assignment`
--

CREATE TABLE `section_assignment` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section_assignment`
--

INSERT INTO `section_assignment` (`id`, `student_id`, `teacher_id`, `section_id`, `school_year_id`) VALUES
(74, 240258, 49, 32, 1),
(75, 240259, 49, 32, 1),
(76, 240260, 49, 32, 1),
(77, 240261, 49, 32, 1),
(78, 240262, 49, 32, 1),
(79, 240263, 49, 32, 1),
(80, 240264, 49, 32, 1),
(81, 240265, 49, 32, 1),
(82, 240266, 49, 32, 1),
(83, 240267, 49, 32, 1),
(84, 240268, 49, 32, 1),
(85, 240269, 49, 32, 1),
(86, 240270, 49, 32, 1),
(87, 240271, 49, 32, 1),
(88, 240272, 49, 32, 1),
(89, 240273, 49, 32, 1),
(90, 240274, 49, 32, 1),
(91, 240275, 49, 32, 1),
(92, 240276, 49, 32, 1),
(93, 240277, 49, 32, 1),
(94, 240278, 49, 32, 1),
(95, 240279, 49, 32, 1),
(96, 240280, 49, 32, 1),
(97, 240281, 49, 32, 1),
(98, 240282, 49, 32, 1),
(99, 240283, 49, 32, 1),
(100, 240284, 49, 32, 1),
(101, 240285, 49, 32, 1),
(103, 240287, 49, 32, 1),
(104, 240288, 49, 32, 1),
(105, 240289, 49, 32, 1),
(106, 240290, 49, 32, 1),
(107, 240291, 49, 32, 1),
(108, 240292, 49, 32, 1),
(109, 240293, 49, 32, 1),
(110, 240294, 49, 32, 1),
(111, 240295, 49, 32, 1),
(112, 240296, 49, 32, 1),
(113, 240297, 50, 33, 1),
(114, 240298, 50, 33, 1),
(115, 240299, 50, 33, 1),
(116, 240300, 50, 33, 1),
(117, 240301, 50, 33, 1),
(118, 240302, 50, 33, 1),
(119, 240303, 50, 33, 1),
(120, 240304, 50, 33, 1),
(121, 240305, 50, 33, 1),
(122, 240306, 50, 33, 1),
(123, 240307, 50, 33, 1),
(124, 240308, 50, 33, 1),
(125, 240309, 50, 33, 1),
(126, 240310, 50, 33, 1),
(127, 240311, 50, 33, 1),
(128, 240312, 50, 33, 1),
(129, 240313, 50, 33, 1),
(130, 240314, 50, 33, 1),
(131, 240315, 50, 33, 1),
(132, 240316, 50, 33, 1),
(133, 240317, 64, 44, 2),
(134, 240318, 64, 44, 2),
(135, 240319, 64, 44, 2),
(136, 240320, 64, 44, 2),
(137, 240321, 64, 44, 2),
(138, 240322, 64, 44, 2),
(139, 240323, 64, 44, 2),
(140, 240324, 64, 44, 2),
(141, 240325, 64, 44, 2),
(142, 240326, 64, 44, 2),
(143, 240327, 64, 44, 2),
(144, 240328, 64, 44, 2),
(145, 240329, 64, 44, 2),
(146, 240330, 64, 44, 2),
(147, 240331, 64, 44, 2),
(148, 240332, 64, 44, 2),
(149, 240333, 64, 44, 2),
(150, 240334, 64, 44, 2),
(151, 240335, 64, 44, 2),
(152, 240336, 64, 44, 2),
(153, 240337, 65, 45, 2),
(154, 240338, 65, 45, 2),
(155, 240339, 65, 45, 2),
(156, 240340, 65, 45, 2),
(157, 240341, 65, 45, 2),
(158, 240342, 65, 45, 2),
(159, 240343, 65, 45, 2),
(160, 240344, 65, 45, 2),
(161, 240345, 65, 45, 2),
(162, 240346, 65, 45, 2),
(163, 240347, 65, 45, 2),
(164, 240348, 65, 45, 2),
(165, 240349, 65, 45, 2),
(166, 240350, 65, 45, 2),
(167, 240351, 65, 45, 2),
(168, 240352, 65, 45, 2),
(169, 240353, 65, 45, 2),
(170, 240354, 65, 45, 2),
(171, 240355, 65, 45, 2),
(172, 240356, 65, 45, 2),
(173, 240357, 51, 34, 1),
(174, 240358, 51, 34, 1),
(175, 240359, 51, 34, 1),
(176, 240360, 51, 34, 1),
(177, 240361, 51, 34, 1),
(178, 240362, 51, 34, 1),
(179, 240363, 51, 34, 1),
(180, 240364, 51, 34, 1),
(181, 240365, 51, 34, 1),
(182, 240366, 51, 34, 1),
(183, 240367, 51, 34, 1),
(184, 240368, 51, 34, 1),
(185, 240369, 51, 34, 1),
(186, 240370, 51, 34, 1),
(187, 240371, 51, 34, 1),
(188, 240372, 51, 34, 1),
(189, 240373, 51, 34, 1),
(190, 240374, 51, 34, 1),
(191, 240375, 51, 34, 1),
(192, 240376, 51, 34, 1),
(193, 240377, 52, 35, 1),
(194, 240378, 52, 35, 1),
(195, 240379, 52, 35, 1),
(196, 240380, 52, 35, 1),
(197, 240381, 52, 35, 1),
(198, 240382, 52, 35, 1),
(199, 240383, 52, 35, 1),
(200, 240384, 52, 35, 1),
(201, 240385, 52, 35, 1),
(202, 240386, 52, 35, 1),
(203, 240387, 52, 35, 1),
(204, 240388, 52, 35, 1),
(205, 240389, 52, 35, 1),
(206, 240390, 52, 35, 1),
(207, 240391, 52, 35, 1),
(208, 240392, 52, 35, 1),
(209, 240393, 52, 35, 1),
(210, 240394, 52, 35, 1),
(211, 240395, 52, 35, 1),
(212, 240396, 52, 35, 1),
(213, 240397, 66, 46, 2),
(214, 240398, 66, 46, 2),
(215, 240399, 66, 46, 2),
(216, 240400, 66, 46, 2),
(217, 240401, 66, 46, 2),
(218, 240402, 66, 46, 2),
(219, 240403, 66, 46, 2),
(220, 240404, 66, 46, 2),
(221, 240405, 66, 46, 2),
(222, 240406, 66, 46, 2),
(223, 240407, 66, 46, 2),
(224, 240408, 66, 46, 2),
(225, 240409, 66, 46, 2),
(226, 240410, 66, 46, 2),
(227, 240411, 66, 46, 2),
(228, 240412, 66, 46, 2),
(229, 240413, 66, 46, 2),
(230, 240414, 66, 46, 2),
(231, 240415, 66, 46, 2),
(232, 240416, 66, 46, 2),
(233, 240417, 67, 47, 2),
(234, 240418, 67, 47, 2),
(235, 240419, 67, 47, 2),
(236, 240420, 67, 47, 2),
(237, 240421, 67, 47, 2),
(238, 240422, 67, 47, 2),
(239, 240423, 67, 47, 2),
(240, 240424, 67, 47, 2),
(241, 240425, 67, 47, 2),
(242, 240426, 67, 47, 2),
(243, 240427, 67, 47, 2),
(244, 240428, 67, 47, 2),
(245, 240429, 67, 47, 2),
(246, 240430, 67, 47, 2),
(247, 240431, 67, 47, 2),
(248, 240432, 67, 47, 2),
(249, 240433, 67, 47, 2),
(250, 240434, 67, 47, 2),
(251, 240435, 67, 47, 2),
(252, 240436, 67, 47, 2),
(253, 240437, 68, 48, 2),
(254, 240438, 68, 48, 2),
(255, 240439, 68, 48, 2),
(256, 240440, 68, 48, 2),
(257, 240441, 68, 48, 2),
(258, 240442, 68, 48, 2),
(259, 240443, 68, 48, 2),
(260, 240444, 68, 48, 2),
(261, 240445, 68, 48, 2),
(262, 240446, 68, 48, 2),
(263, 240447, 68, 48, 2),
(264, 240448, 68, 48, 2),
(265, 240449, 68, 48, 2),
(266, 240450, 68, 48, 2),
(267, 240451, 68, 48, 2),
(268, 240452, 68, 48, 2),
(269, 240453, 68, 48, 2),
(270, 240454, 68, 48, 2),
(271, 240455, 68, 48, 2),
(272, 240456, 68, 48, 2),
(273, 240457, 69, 49, 2),
(274, 240458, 69, 49, 2),
(275, 240459, 69, 49, 2),
(276, 240460, 69, 49, 2),
(277, 240461, 69, 49, 2),
(278, 240462, 69, 49, 2),
(279, 240463, 69, 49, 2),
(280, 240464, 69, 49, 2),
(281, 240465, 69, 49, 2),
(282, 240466, 69, 49, 2),
(283, 240467, 69, 49, 2),
(284, 240468, 69, 49, 2),
(285, 240469, 69, 49, 2),
(286, 240470, 69, 49, 2),
(287, 240471, 69, 49, 2),
(288, 240472, 69, 49, 2),
(289, 240473, 69, 49, 2),
(290, 240474, 69, 49, 2),
(291, 240475, 69, 49, 2),
(292, 240476, 69, 49, 2),
(293, 240477, 70, 50, 2),
(294, 240478, 70, 50, 2),
(295, 240479, 70, 50, 2),
(296, 240480, 70, 50, 2),
(297, 240481, 70, 50, 2),
(298, 240482, 70, 50, 2),
(299, 240483, 70, 50, 2),
(300, 240484, 70, 50, 2),
(301, 240485, 70, 50, 2),
(302, 240486, 70, 50, 2),
(303, 240487, 70, 50, 2),
(304, 240488, 70, 50, 2),
(305, 240489, 70, 50, 2),
(306, 240490, 70, 50, 2),
(307, 240491, 70, 50, 2),
(308, 240492, 70, 50, 2),
(309, 240493, 70, 50, 2),
(310, 240494, 70, 50, 2),
(311, 240495, 70, 50, 2),
(312, 240496, 70, 50, 2),
(313, 240497, 49, 32, 1),
(314, 240498, 67, 47, 1),
(315, 240499, 67, 47, 1),
(316, 240500, 67, 47, 1);

-- --------------------------------------------------------

--
-- Table structure for table `strands`
--

CREATE TABLE `strands` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `strands`
--

INSERT INTO `strands` (`id`, `name`, `description`) VALUES
(7, 'STEM', 'Science, Technology, Engineering, and Mathematics'),
(8, 'ABM', 'Accountancy, Business, and Management'),
(9, 'HUMSS', 'Humanities and Social Sciences'),
(10, 'TVL-HE', 'Technical-Vocational-Livelihood - Home Economics'),
(11, 'TVL-ICT', 'Technical-Vocational-Livelihood - Information and Communications Technology'),
(12, 'BSIT', 'IT');

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
  `user_id` int(11) DEFAULT NULL,
  `school_year_id` int(11) DEFAULT NULL,
  `lrn` varchar(12) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `strand_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `middle_name`, `last_name`, `age`, `sex`, `section_id`, `contact_number`, `religion`, `birthdate`, `user_id`, `school_year_id`, `lrn`, `barangay`, `strand_id`) VALUES
(240258, 'Larry', 'Reyes', 'Bird', 19, 'Male', 32, '09123456702', 'Catholic', '2004-06-22', 240316, NULL, '', '', 0),
(240259, 'Charles', 'Santos', 'Barkley', 17, 'Male', 32, '09123456703', 'Catholic', '2006-12-05', 240317, NULL, '', '', 0),
(240260, 'Tim', 'Cruz', 'Duncan', 20, 'Male', 32, '09123456704', 'Catholic', '2003-04-14', 240318, NULL, '', '', 0),
(240261, 'Karl', 'Garcia', 'Malone', 18, 'Male', 32, '09123456705', 'Catholic', '2005-08-30', 240319, NULL, '', '', 0),
(240262, 'Wilt', 'Lopez', 'Chamberlain', 17, 'Male', 32, '09123456706', 'Catholic', '2006-03-22', 240320, NULL, '', '', 0),
(240263, 'Hakeem', 'Mendoza', 'Olajuwon', 19, 'Male', 32, '09123456707', 'Catholic', '2004-10-18', 240321, NULL, '', '', 0),
(240264, 'Dirk', 'Gomez', 'Nowitzki', 20, 'Male', 32, '09123456708', 'Catholic', '2003-11-30', 240322, NULL, '', '', 0),
(240265, 'Oscar', 'Castro', 'Robertson', 18, 'Male', 32, '09123456709', 'Catholic', '2005-01-25', 240323, NULL, '', '', 0),
(240266, 'Jerry', 'Torres', 'West', 17, 'Male', 32, '09123456710', 'Catholic', '2006-05-15', 240324, NULL, '', '', 0),
(240267, 'Izuku', 'Valdez', 'Midoriya', 19, 'Male', 32, '09123456711', 'Catholic', '2004-07-09', 240325, NULL, '', '', 0),
(240268, 'Katsuki', 'Silva', 'Bakugo', 18, 'Male', 32, '09123456712', 'Catholic', '2005-09-03', 240326, NULL, '', '', 0),
(240269, 'Shoto', 'Navarro', 'Todoroki', 20, 'Male', 32, '09123456713', 'Catholic', '2003-02-27', 240327, NULL, '', '', 0),
(240270, 'Ochaco', 'Ramos', 'Uraraka', 17, 'Male', 32, '09123456714', 'Catholic', '2006-11-11', 240328, NULL, '', '', 0),
(240271, 'Tenya', 'Aquino', 'Iida', 19, 'Male', 32, '09123456715', 'Catholic', '2004-03-15', 240329, NULL, '', '', 0),
(240272, 'Tsuyu', 'De Leon', 'Asui', 18, 'Male', 32, '09123456716', 'Catholic', '2005-07-24', 240330, NULL, '', '', 0),
(240273, 'Eijiro', 'Villanueva', 'Kirishima', 17, 'Male', 32, '09123456717', 'Catholic', '2006-10-12', 240331, NULL, '', '', 0),
(240274, 'Momo', 'Ortega', 'Yaoyorozu', 19, 'Male', 32, '09123456718', 'Catholic', '2004-08-16', 240332, NULL, '', '', 0),
(240275, 'Fumikage', 'Bautista', 'Tokoyami', 20, 'Male', 32, '09123456719', 'Catholic', '2003-01-03', 240333, NULL, '', '', 0),
(240276, 'Denki', 'Ponce', 'Kaminari', 18, 'Male', 32, '09123456720', 'Catholic', '2005-05-07', 240334, NULL, '', '', 0),
(240277, 'Ethan', 'Andrew', 'Smith', 18, 'Male', 32, '09123456804', 'Christianity', '2005-01-01', 240335, NULL, '', '', 0),
(240278, 'Sophia', 'Grace', 'Johnson', 19, 'Female', 32, '09123456805', 'Christianity', '2004-02-01', 240336, NULL, '', '', 0),
(240279, 'Michael', 'James', 'Williams', 20, 'Male', 32, '09123456806', 'Christianity', '2003-03-01', 240337, NULL, '', '', 0),
(240280, 'Olivia', 'Marie', 'Brown', 17, 'Female', 32, '09123456807', 'Christianity', '2006-04-01', 240338, NULL, '', '', 0),
(240281, 'Benjamin', 'Lee', 'Jones', 19, 'Male', 32, '09123456808', 'Christianity', '2004-05-01', 240339, NULL, '', '', 0),
(240282, 'Emma', 'Aiden', 'Garcia', 17, 'Male', 32, '09123456789', 'Christianity', '2006-01-01', 240340, NULL, '', '', 0),
(240283, 'William', 'James', 'Miller', 18, 'Male', 32, '09123456780', 'Christianity', '2005-02-01', 240341, NULL, '', '', 0),
(240284, 'Isabella', 'Isabella', 'Davis', 19, 'Male', 32, '09123456781', 'Christianity', '2004-03-01', 240342, NULL, '', '', 0),
(240285, 'Jacob', 'Jacob', 'Rodriguez', 20, 'Male', 32, '09123456782', 'Christianity', '2003-04-01', 240343, NULL, '', '', 0),
(240287, 'Daniel', 'Daniel', 'Hernandez', 19, 'Male', 32, '09123456783', 'Christianity', '2004-05-01', 240345, NULL, '', '', 0),
(240288, 'Charlotte', 'Charlotte', 'Lopez', 18, 'Male', 32, '09123456784', 'Christianity', '2005-06-01', 240346, NULL, '', '', 0),
(240289, 'James', 'James', 'Gonzalez', 19, 'Male', 32, '09123456785', 'Christianity', '2004-07-01', 240347, NULL, '', '', 0),
(240290, 'Amelia', 'Amelia', 'Wilson', 20, 'Male', 32, '09123456786', 'Christianity', '2003-08-01', 240348, NULL, '', '', 0),
(240291, 'Alexander', 'Alexander', 'Anderson', 19, 'Male', 32, '09123456787', 'Christianity', '2004-09-01', 240349, NULL, '', '', 0),
(240292, 'Harper', 'Harper', 'Thomas', 18, 'Male', 32, '09123456788', 'Christianity', '2005-10-01', 240350, NULL, '', '', 0),
(240293, 'Noah', 'Noah', 'Taylor', 19, 'Male', 32, '09123456789', 'Christianity', '2004-11-01', 240351, NULL, '', '', 0),
(240294, 'Emily', 'Emily', 'Moore', 20, 'Male', 32, '09123456790', 'Christianity', '2003-12-01', 240352, NULL, '', '', 0),
(240295, 'Henry', 'Henry', 'Jackson', 19, 'Male', 32, '09123456791', 'Christianity', '2004-01-01', 240353, NULL, '', '', 0),
(240296, 'Grace', 'Grace', 'Lee', 18, 'Male', 32, '09123456792', 'Christianity', '2005-02-01', 240354, NULL, '', '', 0),
(240297, 'Alejandro', 'Alejandro', 'García', 20, 'Male', 33, '09123456793', 'Christianity', '2003-03-01', 240355, NULL, '', '', 0),
(240298, 'Carmen', 'Carmen', 'Rodríguez', 19, 'Male', 33, '09123456794', 'Christianity', '2004-04-01', 240356, NULL, '', '', 0),
(240299, 'Diego', 'Diego', 'Martínez', 20, 'Male', 33, '09123456795', 'Christianity', '2003-05-01', 240357, NULL, '', '', 0),
(240300, 'Lucía', 'Lucía', 'Hernández', 19, 'Male', 33, '09123456796', 'Christianity', '2004-06-01', 240358, NULL, '', '', 0),
(240301, 'Mateo', 'Mateo', 'López', 20, 'Male', 33, '09123456797', 'Christianity', '2003-07-01', 240359, NULL, '', '', 0),
(240302, 'Sofia', 'Sofia', 'González', 18, 'Male', 33, '09123456798', 'Christianity', '2005-08-01', 240360, NULL, '', '', 0),
(240303, 'José', 'José', 'Pérez', 19, 'Male', 33, '09123456799', 'Christianity', '2004-09-01', 240361, NULL, '', '', 0),
(240304, 'Valentina', 'Valentina', 'Sánchez', 20, 'Male', 33, '09123456800', 'Christianity', '2003-10-01', 240362, NULL, '', '', 0),
(240305, 'Carlos', 'Carlos', 'Ramírez', 19, 'Male', 33, '09123456801', 'Christianity', '2004-11-01', 240363, NULL, '', '', 0),
(240306, 'Elena', 'Elena', 'Torres', 20, 'Male', 33, '09123456802', 'Christianity', '2003-12-01', 240364, NULL, '', '', 0),
(240307, 'Javier', 'Javier', 'Flores', 19, 'Male', 33, '09123456803', 'Christianity', '2004-01-01', 240365, NULL, '', '', 0),
(240308, 'Isabella', 'Sophia', 'Castillo', 17, 'Female', 33, '09123456809', 'Christianity', '2006-06-01', 240366, NULL, '', '', 0),
(240309, 'Luis', 'Alejandro', 'Morales', 18, 'Male', 33, '09123456810', 'Christianity', '2005-07-01', 240367, NULL, '', '', 0),
(240310, 'Natalia', 'Isabella', 'Rivera', 19, 'Female', 33, '09123456811', 'Christianity', '2004-08-01', 240368, NULL, '', '', 0),
(240311, 'Pablo', 'Daniel', 'Ortiz', 17, 'Male', 33, '09123456812', 'Christianity', '2006-09-01', 240369, NULL, '', '', 0),
(240312, 'Gabriela', 'Gabriel', 'Romero', 18, 'Female', 33, '09123456813', 'Christianity', '2005-10-01', 240370, NULL, '', '', 0),
(240313, 'Andrés', 'Sofia', 'Vargas', 19, 'Male', 33, '09123456814', 'Christianity', '2004-11-01', 240371, NULL, '', '', 0),
(240314, 'Martina', 'Luis', 'Mendoza', 17, 'Female', 33, '09123456815', 'Christianity', '2006-12-01', 240372, NULL, '', '', 0),
(240315, 'Santiago', 'María', 'Jiménez', 18, 'Male', 33, '09123456816', 'Christianity', '2005-01-01', 240373, NULL, '', '', 0),
(240316, 'Camila', 'Andrés', 'Silva', 19, 'Female', 33, '09123456817', 'Christianity', '2004-02-01', 240374, NULL, '', '', 0),
(240317, 'Ahmad', 'Putri', 'Pratama', 17, 'Male', 44, '09123456818', 'Islam', '2006-03-01', 240375, NULL, '', '', 0),
(240318, 'Siti', 'Aditya', 'Wijaya', 19, 'Male', 44, '09123456819', 'Islam', '2004-04-01', 240376, NULL, '', '', 0),
(240319, 'Budi', 'Sari', 'Santoso', 18, 'Female', 44, '09123456820', 'Islam', '2005-05-01', 240377, NULL, '', '', 0),
(240320, 'Dewi', 'Rahmat', 'Putri', 19, 'Male', 44, '09123456821', 'Islam', '2004-06-01', 240378, NULL, '', '', 0),
(240321, 'Agus', 'Lestari', 'Saputra', 17, 'Female', 44, '09123456822', 'Islam', '2006-07-01', 240379, NULL, '', '', 0),
(240322, 'Ratna', 'Purnama', 'Sari', 18, 'Male', 44, '09123456823', 'Islam', '2005-08-01', 240380, NULL, '', '', 0),
(240323, 'Rudi', 'Indah', 'Wahyudi', 19, 'Female', 44, '09123456824', 'Islam', '2004-09-01', 240381, NULL, '', '', 0),
(240324, 'Intan', 'Rizal', 'Utami', 17, 'Male', 44, '09123456825', 'Islam', '2006-10-01', 240382, NULL, '', '', 0),
(240325, 'Dedi', 'Ayu', 'Kurniawan', 18, 'Female', 44, '09123456826', 'Islam', '2005-11-01', 240383, NULL, '', '', 0),
(240326, 'Fitri', 'Mahendra', 'Lestari', 19, 'Male', 44, '09123456827', 'Islam', '2004-12-01', 240384, NULL, '', '', 0),
(240327, 'Rizki', 'Intan', 'Mahendra', 17, 'Female', 44, '09123456828', 'Islam', '2006-01-01', 240385, NULL, '', '', 0),
(240328, 'Maya', 'Yudi', 'Nugraha', 18, 'Male', 44, '09123456829', 'Islam', '2005-02-01', 240386, NULL, '', '', 0),
(240329, 'Hendra', 'Rina', 'Purnama', 19, 'Female', 44, '09123456830', 'Islam', '2004-03-01', 240387, NULL, '', '', 0),
(240330, 'Ayu', 'Hendra', 'Anggraini', 17, 'Male', 44, '09123456831', 'Islam', '2006-04-01', 240388, NULL, '', '', 0),
(240331, 'Yudi', 'Putri', 'Permana', 18, 'Female', 44, '09123456832', 'Islam', '2005-05-01', 240389, NULL, '', '', 0),
(240332, 'Lina', 'Rahmawati', 'Rahmawati', 19, 'Female', 44, '09123456833', 'Islam', '2004-06-01', 240390, NULL, '', '', 0),
(240333, 'Iwan', 'Wibowo', 'Wibowo', 17, 'Male', 44, '09123456789', 'Christian', '2005-05-01', 240391, NULL, '', '', 0),
(240334, 'Sari', 'Susanti', 'Susanti', 18, 'Female', 44, '09123456780', 'Muslim', '2004-06-15', 240392, NULL, '', '', 0),
(240335, 'Adi', 'Firmansyah', 'Firmansyah', 19, 'Male', 44, '09123456781', 'Hindu', '2003-07-20', 240393, NULL, '', '', 0),
(240336, 'Nia', 'Ramadhani', 'Ramadhani', 18, 'Female', 44, '09123456782', 'Buddhist', '2004-08-10', 240394, NULL, '', '', 0),
(240337, 'Carlos', 'Ortiz', 'Ortiz', 19, 'Male', 45, '09123456783', 'Christian', '2003-09-25', 240395, NULL, '', '', 0),
(240338, 'Ernesto', 'Castillo', 'Castillo', 18, 'Male', 45, '09123456784', 'Muslim', '2004-10-30', 240396, NULL, '', '', 0),
(240339, 'Armando', 'Vargas', 'Vargas', 17, 'Male', 45, '09123456785', 'Hindu', '2005-11-15', 240397, NULL, '', '', 0),
(240340, 'Domingo', 'Morales', 'Morales', 18, 'Male', 45, '09123456786', 'Buddhist', '2004-12-20', 240398, NULL, '', '', 0),
(240341, 'Esteban', 'Guerrero', 'Guerrero', 19, 'Male', 45, '09123456787', 'Christian', '2003-01-05', 240399, NULL, '', '', 0),
(240342, 'Felipe', 'Flores', 'Flores', 18, 'Male', 45, '09123456788', 'Muslim', '2004-02-14', 240400, NULL, '', '', 0),
(240343, 'Hernando', 'Peralta', 'Peralta', 19, 'Male', 45, '09123456789', 'Hindu', '2003-03-18', 240401, NULL, '', '', 0),
(240344, 'Isidro', 'Romero', 'Romero', 17, 'Male', 45, '09123456790', 'Buddhist', '2005-04-22', 240402, NULL, '', '', 0),
(240345, 'Jacinto', 'Roldan', 'Roldan', 18, 'Male', 45, '09123456791', 'Christian', '2004-05-30', 240403, NULL, '', '', 0),
(240346, 'Julio', 'Soriano', 'Soriano', 19, 'Male', 45, '09123456792', 'Muslim', '2003-06-16', 240404, NULL, '', '', 0),
(240347, 'Lorenzo', 'Aguirre', 'Aguirre', 18, 'Male', 45, '09123456793', 'Hindu', '2004-07-25', 240405, NULL, '', '', 0),
(240348, 'Marcelo', 'Barrios', 'Barrios', 19, 'Male', 45, '09123456794', 'Buddhist', '2003-08-11', 240406, NULL, '', '', 0),
(240349, 'Nicanor', 'Camacho', 'Camacho', 17, 'Male', 45, '09123456795', 'Christian', '2005-09-30', 240407, NULL, '', '', 0),
(240350, 'Omar', 'Dela Cruz', 'Dela Cruz', 18, 'Male', 45, '09123456796', 'Muslim', '2004-10-12', 240408, NULL, '', '', 0),
(240351, 'Pascual', 'Encarnacion', 'Encarnacion', 19, 'Male', 45, '09123456797', 'Hindu', '2003-11-29', 240409, NULL, '', '', 0),
(240352, 'Quirino', 'Ferrer', 'Ferrer', 17, 'Male', 45, '09123456798', 'Buddhist', '2005-12-05', 240410, NULL, '', '', 0),
(240353, 'Rafael', 'Garzon', 'Garzon', 18, 'Male', 45, '09123456799', 'Christian', '2004-01-16', 240411, NULL, '', '', 0),
(240354, 'Salvador', 'Hidalgo', 'Hidalgo', 19, 'Male', 45, '09123456001', 'Muslim', '2003-02-10', 240412, NULL, '', '', 0),
(240355, 'Teodoro', 'Ibarra', 'Ibarra', 17, 'Male', 45, '09123456002', 'Hindu', '2005-03-22', 240413, NULL, '', '', 0),
(240356, 'Urbano', 'Jimenez', 'Jimenez', 18, 'Male', 45, '09123456003', 'Buddhist', '2004-04-30', 240414, NULL, '', '', 0),
(240357, 'Jose', 'Rodriguez', 'Rodriguez', 19, 'Male', 34, '09123456004', 'Christian', '2003-05-15', 240415, NULL, '', '', 0),
(240358, 'Miguel', 'Santos', 'Santos', 17, 'Female', 34, '09123456005', 'Muslim', '2005-06-20', 240416, NULL, '', '', 0),
(240359, 'Pedro', 'Tiongson', 'Lopez', 18, 'Female', 34, '09123456006', 'Hindu', '2004-07-15', 240417, NULL, '', '', 0),
(240360, 'Tomas', 'Umali', 'Garcia', 19, 'Female', 34, '09123456007', 'Buddhist', '2003-08-30', 240418, NULL, '', '', 0),
(240361, 'Ramon', 'Villanueva', 'Fernandez', 17, 'Female', 34, '09123456008', 'Christian', '2005-09-05', 240419, NULL, '', '', 0),
(240362, 'Alberto', 'Wong', 'Mendoza', 18, 'Female', 34, '09123456009', 'Muslim', '2004-10-10', 240420, NULL, '', '', 0),
(240363, 'Emilio', 'Xiang', 'Gomez', 19, 'Female', 34, '09123456010', 'Hindu', '2003-11-22', 240421, NULL, '', '', 0),
(240364, 'Felix', 'Yap', 'Cruz', 17, 'Female', 34, '09123456011', 'Buddhist', '2005-12-12', 240422, NULL, '', '', 0),
(240365, 'Juanito', 'Zhao', 'Ramos', 18, 'Female', 34, '09123456012', 'Christian', '2004-01-01', 240423, NULL, '', '', 0),
(240366, 'Ricardo', 'Juan', 'Torres', 17, 'Male', 34, '09123456789', 'Catholic', '2006-05-15', 240424, NULL, '', '', 0),
(240367, 'Victorino', 'Luis', 'Diaz', 18, 'Male', 34, '09123456780', 'Islam', '2005-04-20', 240425, NULL, '', '', 0),
(240368, 'Antonio', 'Carlos', 'Reyes', 19, 'Male', 34, '09123456781', 'Christian', '2004-03-10', 240426, NULL, '', '', 0),
(240369, 'Manuel', 'Jose', 'Bautista', 17, 'Male', 34, '09123456782', 'Muslim', '2006-06-22', 240427, NULL, '', '', 0),
(240370, 'Fernando', 'Miguel', 'Salazar', 18, 'Male', 34, '09123456783', 'Catholic', '2005-07-30', 240428, NULL, '', '', 0),
(240371, 'Leoncio', 'Pablo', 'Martinez', 19, 'Male', 34, '09123456784', 'Islam', '2004-08-18', 240429, NULL, '', '', 0),
(240372, 'Gilberto', 'Rafael', 'Suarez', 17, 'Male', 34, '09123456785', 'Christian', '2006-09-25', 240430, NULL, '', '', 0),
(240373, 'Benito', 'Antonio', 'Alvarez', 18, 'Male', 34, '09123456786', 'Muslim', '2005-10-12', 240431, NULL, '', '', 0),
(240374, 'Mariano', 'Hector', 'Rivera', 19, 'Male', 34, '09123456787', 'Catholic', '2004-11-05', 240432, NULL, '', '', 0),
(240375, 'Luisito', 'Javier', 'Valdez', 17, 'Male', 34, '09123456788', 'Islam', '2006-12-20', 240433, NULL, '', '', 0),
(240376, 'Santiago', 'Eduardo', 'Navarro', 18, 'Male', 34, '09123456789', 'Christian', '2005-01-01', 240434, NULL, '', '', 0),
(240377, 'Valerio', 'Felipe', 'Lorenzo', 19, 'Male', 35, '09123456900', 'Muslim', '2004-02-28', 240435, NULL, '', '', 0),
(240378, 'Wenceslao', 'Ramon', 'Mojica', 17, 'Male', 35, '09123456901', 'Catholic', '2006-03-15', 240436, NULL, '', '', 0),
(240379, 'Xavier', 'Manuel', 'Narvaez', 18, 'Male', 35, '09123456902', 'Islam', '2005-04-20', 240437, NULL, '', '', 0),
(240380, 'Ysidro', 'Victor', 'Oliveros', 19, 'Male', 35, '09123456903', 'Christian', '2004-05-10', 240438, NULL, '', '', 0),
(240381, 'Zenon', 'Alberto', 'Panganiban', 17, 'Male', 35, '09123456904', 'Muslim', '2006-06-22', 240439, NULL, '', '', 0),
(240382, 'Andres', 'Jorge', 'Querubin', 18, 'Male', 35, '09123456905', 'Catholic', '2005-07-30', 240440, NULL, '', '', 0),
(240383, 'Benedicto', 'Pedro', 'Rafael', 18, 'Male', 35, '09123456789', 'Catholic', '2006-02-15', 240441, NULL, '', '', 0),
(240384, 'Crisostomo', 'Carlos', 'San Juan', 17, 'Male', 35, '09123456780', 'Islam', '2006-03-10', 240442, NULL, '', '', 0),
(240385, 'Dario', 'Miguel', 'Tamayo', 19, 'Male', 35, '09123456781', 'Christian', '2005-12-05', 240443, NULL, '', '', 0),
(240386, 'Elias', 'Alfredo', 'Urbina', 18, 'Male', 35, '09123456782', 'Muslim', '2006-01-22', 240444, NULL, '', '', 0),
(240387, 'Francisco', 'Luis', 'Villanueva', 17, 'Male', 35, '09123456783', 'Catholic', '2006-02-28', 240445, NULL, '', '', 0),
(240388, 'Guillermo', 'Jorge', 'Ximena', 18, 'Male', 35, '09123456784', 'Islam', '2005-11-12', 240446, NULL, '', '', 0),
(240389, 'Hilario', 'Eduardo', 'Yangco', 19, 'Male', 35, '09123456785', 'Christian', '2005-10-18', 240447, NULL, '', '', 0),
(240390, 'Inigo', 'Fernando', 'Zamora', 17, 'Male', 35, '09123456786', 'Muslim', '2006-04-01', 240448, NULL, '', '', 0),
(240391, 'Joaquin', 'Joaquin', 'Bacaltos', 18, 'Male', 35, '09123456787', 'Catholic', '2006-05-15', 240449, NULL, '', '', 0),
(240392, 'Karlos', 'Ricardo', 'Calderon', 19, 'Male', 35, '09123456788', 'Islam', '2005-06-21', 240450, NULL, '', '', 0),
(240393, 'Lito', 'Santiago', 'De la Rosa', 17, 'Male', 35, '09123456789', 'Christian', '2006-03-12', 240451, NULL, '', '', 0),
(240394, 'Martin', 'Alonzo', 'Escudero', 18, 'Male', 35, '09123456790', 'Muslim', '2006-07-30', 240452, NULL, '', '', 0),
(240395, 'Nestor', 'Emilio', 'Fortuna', 19, 'Male', 35, '09123456791', 'Catholic', '2005-08-05', 240453, NULL, '', '', 0),
(240396, 'Oswaldo', 'Roberto', 'Gatchalian', 17, 'Male', 35, '09123456792', 'Islam', '2006-09-14', 240454, NULL, '', '', 0),
(240397, 'Pancho', 'Fernando', 'Hernandez', 18, 'Male', 46, '09123456793', 'Christian', '2005-04-19', 240455, NULL, '', '', 0),
(240398, 'Ramonito', 'Leonardo', 'Ilustrisimo', 19, 'Male', 46, '09123456794', 'Muslim', '2005-05-25', 240456, NULL, '', '', 0),
(240399, 'Severino', 'Hector', 'Juarez', 17, 'Male', 46, '09123456795', 'Catholic', '2006-10-02', 240457, NULL, '', '', 0),
(240400, 'Tadeo', 'Diego', 'Kintanar', 18, 'Male', 46, '09123456796', 'Islam', '2006-11-15', 240458, NULL, '', '', 0),
(240401, 'Ulises', 'Alfredo', 'Laviste', 19, 'Male', 46, '09123456797', 'Christian', '2005-09-28', 240459, NULL, '', '', 0),
(240402, 'Victor', 'Pablo', 'Mangubat', 17, 'Male', 46, '09123456798', 'Muslim', '2006-12-22', 240460, NULL, '', '', 0),
(240403, 'Willy', 'Victor', 'Nabua', 18, 'Male', 46, '09123456799', 'Catholic', '2006-01-05', 240461, NULL, '', '', 0),
(240404, 'Xandro', 'Luis', 'Obalde', 19, 'Male', 46, '09123456800', 'Islam', '2005-03-18', 240462, NULL, '', '', 0),
(240405, 'Yoyong', 'Felipe', 'Pascua', 17, 'Male', 46, '09123456801', 'Christian', '2006-08-12', 240463, NULL, '', '', 0),
(240406, 'Zandro', 'Carlos', 'Rufino', 18, 'Male', 46, '09123456802', 'Muslim', '2006-06-30', 240464, NULL, '', '', 0),
(240407, 'Artemio', 'Jose', 'Santos', 19, 'Male', 46, '09123456803', 'Catholic', '2005-02-17', 240465, NULL, '', '', 0),
(240408, 'Bartolome', 'Lorenzo', 'Teves', 18, 'Male', 46, '09123456789', 'Catholic', '2006-06-15', 240466, NULL, '', '', 0),
(240409, 'Ciriaco', 'Mira', 'Urbano', 17, 'Female', 46, '09123456790', 'Catholic', '2006-05-10', 240467, NULL, '', '', 0),
(240410, 'Damian', 'Luis', 'Villareal', 19, 'Male', 46, '09123456791', 'Christian', '2005-04-20', 240468, NULL, '', '', 0),
(240411, 'Eulalio', 'Maria', 'Ysmael', 18, 'Female', 46, '09123456792', 'Catholic', '2006-03-25', 240469, NULL, '', '', 0),
(240412, 'Fulgencio', 'Juan', 'Zerna', 19, 'Male', 46, '09123456793', 'Islam', '2005-02-15', 240470, NULL, '', '', 0),
(240413, 'Genaro', 'Ana', 'Almario', 17, 'Female', 46, '09123456794', 'Catholic', '2006-01-05', 240471, NULL, '', '', 0),
(240414, 'Herminio', 'Raul', 'Bagasbas', 18, 'Male', 46, '09123456795', 'Christian', '2006-11-11', 240472, NULL, '', '', 0),
(240415, 'Ignacio', 'Emily', 'Cabrera', 19, 'Female', 46, '09123456796', 'Catholic', '2005-12-31', 240473, NULL, '', '', 0),
(240416, 'Jesus', 'David', 'Da Silva', 18, 'Male', 46, '09123456797', 'Muslim', '2006-07-20', 240474, NULL, '', '', 0),
(240417, 'Aaditya', 'Luis', 'Malhotra', 19, 'Male', 47, '09123456798', 'Catholic', '2005-10-10', 240475, NULL, '', '', 0),
(240418, 'Kiaan', 'Priya', 'Deshmukh', 17, 'Female', 47, '09123456799', 'Catholic', '2006-08-08', 240476, NULL, '', '', 0),
(240419, 'Ansh', 'Kiran', 'Mehta', 18, 'Male', 47, '09123456800', 'Islam', '2006-03-14', 240477, NULL, '', '', 0),
(240420, 'Karan', 'Anjali', 'Verma', 19, 'Female', 47, '09123456801', 'Christian', '2005-09-09', 240478, NULL, '', '', 0),
(240421, 'Vihaan', 'Rahul', 'Malhotra', 18, 'Male', 47, '09123456802', 'Catholic', '2006-02-22', 240479, NULL, '', '', 0),
(240422, 'Karan', 'Leela', 'Chowdhury', 19, 'Female', 47, '09123456803', 'Catholic', '2005-04-18', 240480, NULL, '', '', 0),
(240423, 'Arnav', 'Nikhil', 'Verma', 18, 'Male', 47, '09123456804', 'Muslim', '2006-05-12', 240481, NULL, '', '', 0),
(240424, 'Arnav', 'Rani', 'Sinha', 19, 'Female', 47, '09123456805', 'Christian', '2005-12-25', 240482, NULL, '', '', 0),
(240425, 'Rishi', 'Aarav', 'Rajput', 18, 'Male', 47, '09123456806', 'Catholic', '2006-06-30', 240483, NULL, '', '', 0),
(240426, 'Arjun', 'Sanya', 'Chopra', 19, 'Female', 47, '09123456807', 'Catholic', '2005-03-17', 240484, NULL, '', '', 0),
(240427, 'Arnav', 'Tarun', 'Reddy', 18, 'Male', 47, '09123456808', 'Muslim', '2006-04-29', 240485, NULL, '', '', 0),
(240428, 'Shaurya', 'Zara', 'Nair', 19, 'Female', 47, '09123456809', 'Christian', '2005-11-19', 240486, NULL, '', '', 0),
(240429, 'Arjun', 'Vivaan', 'Singh', 18, 'Male', 47, '09123456810', 'Catholic', '2006-08-04', 240487, NULL, '', '', 0),
(240430, 'Aditya', 'Kajal', 'Bajaj', 19, 'Female', 47, '09123456811', 'Catholic', '2005-09-15', 240488, NULL, '', '', 0),
(240431, 'Ansh', 'Anaya', 'Gupta', 18, 'Female', 47, '09123456812', 'Catholic', '2006-01-20', 240489, NULL, '', '', 0),
(240432, 'Aaditya', 'Vijay', 'Rajput', 19, 'Male', 47, '09123456813', 'Catholic', '2005-07-27', 240490, NULL, '', '', 0),
(240433, 'Ishaan', NULL, 'Gupta', NULL, 'Male', 47, NULL, NULL, NULL, 240491, NULL, '', '', 0),
(240434, 'Vihaan', NULL, 'Verma', NULL, 'Male', 47, NULL, NULL, NULL, 240492, NULL, '', '', 0),
(240435, 'Karan', NULL, 'Chowdhury', NULL, 'Male', 47, NULL, NULL, NULL, 240493, NULL, '', '', 0),
(240436, 'Krishna', NULL, 'Sinha', NULL, 'Male', 47, NULL, NULL, NULL, 240494, NULL, '', '', 0),
(240437, 'Shaurya', NULL, 'Sinha', NULL, 'Male', 48, NULL, NULL, NULL, 240495, NULL, '', '', 0),
(240438, 'Ayaan', NULL, 'Patel', NULL, 'Male', 48, NULL, NULL, NULL, 240496, NULL, '', '', 0),
(240439, 'Aarav', NULL, 'Chopra', NULL, 'Male', 48, NULL, NULL, NULL, 240497, NULL, '', '', 0),
(240440, 'Aarav', NULL, 'Bajaj', NULL, 'Male', 48, NULL, NULL, NULL, 240498, NULL, '', '', 0),
(240441, 'Ayaan', NULL, 'Sharma', NULL, 'Male', 48, NULL, NULL, NULL, 240499, NULL, '', '', 0),
(240442, 'Aarush', NULL, 'Nair', NULL, 'Male', 48, NULL, NULL, NULL, 240500, NULL, '', '', 0),
(240443, 'Vihaan', NULL, 'Chopra', NULL, 'Male', 48, NULL, NULL, NULL, 240501, NULL, '', '', 0),
(240444, 'Shaurya', NULL, 'Deshmukh', NULL, 'Male', 48, NULL, NULL, NULL, 240502, NULL, '', '', 0),
(240445, 'Ansh', NULL, 'Chopra', NULL, 'Male', 48, NULL, NULL, NULL, 240503, NULL, '', '', 0),
(240446, 'Aditya', NULL, 'Kumar', NULL, 'Male', 48, NULL, NULL, NULL, 240504, NULL, '', '', 0),
(240447, 'Karan', NULL, 'Chopra', NULL, 'Male', 48, NULL, NULL, NULL, 240505, NULL, '', '', 0),
(240448, 'Rishi', NULL, 'Chopra', NULL, 'Male', 48, NULL, NULL, NULL, 240506, NULL, '', '', 0),
(240449, 'Aarush', NULL, 'Kumar', NULL, 'Male', 48, NULL, NULL, NULL, 240507, NULL, '', '', 0),
(240450, 'Vivaan', NULL, 'Saxena', NULL, 'Male', 48, NULL, NULL, NULL, 240508, NULL, '', '', 0),
(240451, 'Aarav', NULL, 'Saxena', NULL, 'Male', 48, NULL, NULL, NULL, 240509, NULL, '', '', 0),
(240452, 'Aarav', NULL, 'Sinha', NULL, 'Male', 48, NULL, NULL, NULL, 240510, NULL, '', '', 0),
(240453, 'Ayaan', NULL, 'Sharma', NULL, 'Male', 48, NULL, NULL, NULL, 240511, NULL, '', '', 0),
(240454, 'Kiaan', NULL, 'Ahuja', NULL, 'Male', 48, NULL, NULL, NULL, 240512, NULL, '', '', 0),
(240455, 'Aaditya', NULL, 'Bansal', NULL, 'Male', 48, NULL, NULL, NULL, 240513, NULL, '', '', 0),
(240456, 'Shaurya', NULL, 'Malhotra', NULL, 'Male', 48, NULL, NULL, NULL, 240514, NULL, '', '', 0),
(240457, 'Ansh', NULL, 'Bansal', NULL, 'Male', 49, NULL, NULL, NULL, 240515, NULL, '', '', 0),
(240458, 'Shaurya', 'Raj', 'Saxena', 18, 'Male', 49, '09123457001', 'Catholic', '2006-02-14', 240516, NULL, '', '', 0),
(240459, 'Rishi', 'Karan', 'Deshmukh', 19, 'Female', 49, '09123457002', 'Catholic', '2005-03-10', 240517, NULL, '', '', 0),
(240460, 'Arnav', 'Ajay', 'Rajput', 17, 'Male', 49, '09123457003', 'Islam', '2006-11-21', 240518, NULL, '', '', 0),
(240461, 'Reyansh', 'Siddharth', 'Ahuja', 18, 'Female', 49, '09123457004', 'Christian', '2005-05-25', 240519, NULL, '', '', 0),
(240462, 'Arjun', 'Vikram', 'Deshmukh', 19, 'Male', 49, '09123457005', 'Catholic', '2005-09-12', 240520, NULL, '', '', 0),
(240463, 'Vihaan', 'Manish', 'Saxena', 18, 'Male', 49, '09123457006', 'Muslim', '2006-06-30', 240521, NULL, '', '', 0),
(240464, 'Krishna', 'Anil', 'Saxena', 17, 'Female', 49, '09123457007', 'Catholic', '2006-01-18', 240522, NULL, '', '', 0),
(240465, 'Rishi', 'Amit', 'Patel', 19, 'Male', 49, '09123457008', 'Catholic', '2006-07-09', 240523, NULL, '', '', 0),
(240466, 'Dhruv', 'Deepak', 'Borni', 17, 'Male', 49, '09123457009', 'Christian', '2006-12-05', 240524, NULL, '', '', 0),
(240467, 'Dhruv', 'Rohan', 'Joshi', 18, 'Female', 49, '09123457010', 'Islam', '2005-10-20', 240525, NULL, '', '', 0),
(240468, 'Arnav', 'Saurabh', 'Kumar', 19, 'Male', 49, '09123457011', 'Catholic', '2005-04-25', 240526, NULL, '', '', 0),
(240469, 'Karan', 'Mohit', 'Reddy', 18, 'Male', 49, '09123457012', 'Muslim', '2006-03-14', 240527, NULL, '', '', 0),
(240470, 'Kabir', 'Ravi', 'Sharma', 17, 'Female', 49, '09123457013', 'Christian', '2006-12-05', 240528, NULL, '', '', 0),
(240471, 'Aaditya', 'Gaurav', 'Verma', 19, 'Male', 49, '09123457014', 'Catholic', '2005-11-22', 240529, NULL, '', '', 0),
(240472, 'Aarav', 'Kunal', 'Sharma', 18, 'Female', 49, '09123457015', 'Catholic', '2006-09-18', 240530, NULL, '', '', 0),
(240473, 'Aditya', 'Shiv', 'Deshmukh', 19, 'Male', 49, '09123457016', 'Islam', '2006-10-16', 240531, NULL, '', '', 0),
(240474, 'Aarush', 'Aditya', 'Deshmukh', 18, 'Male', 49, '09123457017', 'Catholic', '2006-05-02', 240532, NULL, '', '', 0),
(240475, 'Sai', 'Suraj', 'Sharma', 17, 'Female', 49, '09123457018', 'Christian', '2006-08-29', 240533, NULL, '', '', 0),
(240476, 'Rishi', 'Rahul', 'Deshmukh', 19, 'Male', 49, '09123457019', 'Muslim', '2005-07-11', 240534, NULL, '', '', 0),
(240477, 'Sai', 'Karan', 'Chowdhury', 18, 'Male', 50, '09123457020', 'Catholic', '2006-10-16', 240535, NULL, '', '', 0),
(240478, 'Naloh', 'Vijay', 'Reddy', 19, 'Female', 50, '09123457021', 'Christian', '2005-12-14', 240536, NULL, '', '', 0),
(240479, 'Vihaan', 'Vinay', 'Reddy', 17, 'Male', 50, '09123457022', 'Catholic', '2006-04-27', 240537, NULL, '', '', 0),
(240480, 'Ishaan', 'Ritesh', 'Bajaj', 18, 'Female', 50, '09123457023', 'Muslim', '2006-03-05', 240538, NULL, '', '', 0),
(240481, 'Reyansh', 'Amit', 'Sinha', 19, 'Male', 50, '09123457024', 'Christian', '2005-10-30', 240539, NULL, '', '', 0),
(240482, 'Karan', 'Rohit', 'Kumar', 18, 'Female', 50, '09123457025', 'Catholic', '2006-11-18', 240540, NULL, '', '', 0),
(240483, 'Aaditya', 'Arjun', 'Sinha', 19, 'Male', 50, '09123457026', 'Islam', '2005-08-05', 240541, NULL, '', '', 0),
(240484, 'Kabir', 'Sanket', 'Verma', 17, 'Female', 50, '09123457027', 'Christian', '2006-02-23', 240542, NULL, '', '', 0),
(240485, 'Ansh', 'Vishal', 'Joshi', 19, 'Male', 50, '09123457028', 'Catholic', '2005-12-11', 240543, NULL, '', '', 0),
(240486, 'Ishaan', 'Ravi', 'Malhotra', 17, 'Male', 50, '09123456701', 'Catholic', '2006-05-15', 240544, NULL, '', '', 0),
(240487, 'Aaditya', 'Anil', 'Chopra', 18, 'Male', 50, '09123456702', 'Catholic', '2005-11-25', 240545, NULL, '', '', 0),
(240488, 'Aarush', 'Ajay', 'Saxena', 19, 'Male', 50, '09123456703', 'Christian', '2005-01-10', 240546, NULL, '', '', 0),
(240489, 'Kiaan', 'Vijay', 'Patel', 18, 'Male', 50, '09123456704', 'Catholic', '2006-04-20', 240547, NULL, '', '', 0),
(240490, 'Reyansh', 'Karan', 'Kumar', 19, 'Male', 50, '09123456705', 'Muslim', '2005-07-05', 240548, NULL, '', '', 0),
(240491, 'Aditya', 'Suresh', 'Bansal', 17, 'Male', 50, '09123456706', 'Catholic', '2006-09-30', 240549, NULL, '', '', 0),
(240492, 'Arjun', 'Rohan', 'Bansal', 18, 'Male', 50, '09123456707', 'Catholic', '2005-02-18', 240550, NULL, '', '', 0),
(240493, 'Arjun', 'Mohit', 'Sharma', 19, 'Male', 50, '09123456708', 'Muslim', '2005-03-12', 240551, NULL, '', '', 0),
(240494, 'Krishna', 'Deepak', 'Chopra', 18, 'Male', 50, '09123456709', 'Catholic', '2005-08-05', 240552, NULL, '', '', 0),
(240495, 'Rey', 'Kunal', 'Reddy', 19, 'Male', 50, '09123456710', 'Christian', '2005-06-15', 240553, NULL, '', '', 0),
(240496, 'Vihaan', 'Amit', 'Gupta', 17, 'Male', 50, '09123456711', 'Catholic', '2006-12-01', 240554, NULL, '', '', 0),
(240497, 'denver', NULL, 'alip', NULL, 'Male', 32, NULL, NULL, NULL, 240555, NULL, '', '', 0),
(240498, 'Juspher', NULL, 'Pedraza', NULL, 'Male', 47, NULL, NULL, NULL, 240556, NULL, '', '', 0),
(240499, 'Fedinand', NULL, 'Sacdalan', NULL, 'Male', 47, NULL, NULL, NULL, 240557, NULL, '', '', 0),
(240500, 'Paulmar', NULL, 'Manjac', NULL, 'Male', 47, NULL, NULL, NULL, 240558, NULL, '', '', 0);

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
  `address` varchar(255) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `username`, `password`, `first_name`, `middle_name`, `last_name`, `email`, `position`, `birthdate`, `sex`, `contact_number`, `address`, `section_id`) VALUES
(46, 'fritch', 'fritch', 'fritch', 'Marie', 'cortez', 'fritch@example.com', 'teacher', '1985-05-10', 'Female', '09123456789', 'Tuy, Batangas', NULL),
(48, 'eron', 'eron', 'eron', 'Anne', 'pangilinan', 'eron@example.com', 'teacher', '1990-03-15', 'Female', '09123456780', 'Nasugbu, Batangas', NULL),
(49, 'maria', 'maria', 'maria an', 'Lynn', 'busilig', 'maria@example.com', 'teacher', '1988-07-20', 'Female', '09123456781', 'Lian, Batangas', NULL),
(50, 'fritz', 'fritz', 'fritz', 'Luis', 'buenviaje', 'fritz@example.com', 'teacher', '1982-11-25', 'Male', '09123456782', 'Tuy, Batangas', NULL),
(51, 'nore', 'nore', 'norecel', 'Celeste', 'gaa', 'nore@example.com', 'teacher', '1980-02-10', 'Female', '09123456783', 'Nasugbu, Batangas', NULL),
(52, 'teresa', 'teresa', 'maria teresa', 'Rosa', 'descallar', 'teresa@example.com', 'teacher', '1975-12-05', 'Female', '09123456784', 'Lian, Batangas', NULL),
(53, 'cath', 'cath', 'catherene', 'Grace', 'veroya', 'cath@example.com', 'teacher', '1995-06-18', 'Female', '09123456785', 'Tuy, Batangas', NULL),
(54, 'diji', 'diji', 'dijinirah', 'Diane', 'guyagon', 'diji@example.com', 'teacher', '1986-04-22', 'Female', '09123456786', 'Nasugbu, Batangas', NULL),
(55, 'berna', 'berna', 'bernadette', 'Renee', 'digno', 'berna@example.com', 'teacher', '1989-09-12', 'Female', '09123456787', 'Lian, Batangas', NULL),
(56, 'ally', 'ally', 'allyson', 'Joy', 'montealegre', 'ally@example.com', 'teacher', '1992-08-30', 'Female', '09123456788', 'Tuy, Batangas', NULL),
(57, 'markj', 'markj', 'mark jhun', 'Lloyd', 'atienza', 'markj@example.com', 'teacher', '1987-01-14', 'Male', '09123456789', 'Nasugbu, Batangas', NULL),
(58, 'andria', 'andria', 'andria', 'Ava', 'zafra', 'andria@example.com', 'teacher', '1993-10-27', 'Female', '09123456790', 'Lian, Batangas', NULL),
(59, 'grace', 'grace', 'gracele', 'Lou', 'cabrera', 'grace@example.com', 'teacher', '1984-07-19', 'Female', '09123456791', 'Tuy, Batangas', NULL),
(60, 'lyze', 'lyze', 'lyzette', 'Fay', 'landicho', 'lyze@example.com', 'teacher', '1983-03-21', 'Female', '09123456792', 'Nasugbu, Batangas', NULL),
(62, 'Elsa', '$2y$10$tKrCS8VAYVItI/RKqHDJiOdZ4ZmS6W2.vobCbZD0GCKH.dvKVB7y6', 'Elsa', 'Camille', 'Capacia', 'elsa@example.com', 'teacher', '1991-05-15', 'Female', '09123456793', 'Lian, Batangas', NULL),
(63, 'Jerome', '$2y$10$kxNSOrBKh3/xwXnhjuScKutQbb1qlgVqlJsGekoApWcDmrXRNuAba', 'Jerome', 'Lee', 'Guevarra', 'jerome@example.com', 'teacher', '1988-11-02', 'Male', '09123456794', 'Tuy, Batangas', NULL),
(64, 'Melissa', '$2y$10$lNwN8VDb9SNpFvlv9kp5qO65DRFsMDQ2rtk48UJnsTWJW2giIFVJW', 'Melissa', 'Lynn', 'Mendoza', 'melissa@example.com', 'teacher', '1990-04-25', 'Female', '09123456795', 'Nasugbu, Batangas', NULL),
(65, 'Melody', '$2y$10$vaz6gW4DvkdiP6joFvkEnekh1HS/J/po3Z332Ed0WmGKf2tThl4l.', 'Melody', 'Ann', 'Acosta', 'melody@example.com', 'teacher', '1994-02-28', 'Female', '09123456796', 'Lian, Batangas', NULL),
(66, 'Mary', '$2y$10$vXeVyoHNDGeygic14Kt3Ceq.yOH9NFisNk5R6BKp2XcQfiqaQNICa', 'Mary Grace', 'Hope', 'Cabingan', 'mary@example.com', 'teacher', '1982-12-30', 'Female', '09123456797', 'Tuy, Batangas', NULL),
(67, 'April', '$2y$10$yp5YiyG/Rpzwp3bdGfcBJOaKeiQChs2OM5EulmtZUBkIzyhyV.2.S', 'April Catherine', 'Marie', 'Maniquez', 'april@example.com', 'teacher', '1995-01-05', 'Female', '09123456798', 'Nasugbu, Batangas', NULL),
(68, 'Karen', '$2y$10$dUG6WqC6RRdmzzFxXay8HOZpslN8wsfiVA6aF0G7VvRRuZStWtqs6', 'Karen Joy', 'Jade', 'Micarandayo', 'karen@example.com', 'teacher', '1986-08-18', 'Female', '09123456799', 'Lian, Batangas', NULL),
(69, 'Marjorie', '$2y$10$w40osK8kFpXn9a9Fy8PnXOgN1ZpZ8j9B.ASx3sDNE8e3CDWFdTSRm', 'Marjorie', 'Dawn', 'Jumarang', 'marjorie@example.com', 'teacher', '1981-09-09', 'Female', '09123456800', 'Tuy, Batangas', NULL),
(70, 'Jory', '$2y$10$yZXC0gA9oDtrfoTkUJAE/Oz6bpMcNBvjWW5xJ2fmb6eyI9ukwNH1K', 'Coleen Jory Anne', 'Ann', 'Alcoran', 'jory@example.com', 'teacher', '1992-06-29', 'Female', '09123456801', 'Nasugbu, Batangas', NULL),
(71, 'Maricel', '$2y$10$Vo2RYuxTk7LDf9PhKtoP9uuvRqFcQWfvaFSqd3jhElakVqwf/cCee', 'Maricel', 'Marie', 'Paredes', 'maricel@example.com', 'teacher', '1983-03-01', 'Female', '09123456802', 'Lian, Batangas', NULL),
(72, 'Lezlie', '$2y$10$AcOTT40NFv1Uk8IQLI452.Al3JQYm/NuyZOvu1SYyOiKexjVbeP..', 'Lezlie', 'Ann', 'Herandez', 'lezlie@example.com', 'teacher', '1984-02-14', 'Female', '09123456803', 'Tuy, Batangas', NULL),
(73, 'Rose', '$2y$10$Ni.M1FuMvUXYAGIKgG5NleDOQPzVm825j5R4o8xey9wQiddFtbB4W', 'April Rose', NULL, 'Subijana', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 'Lamei', '$2y$10$hT6X3uZdKuEHvGWxsKg1qeApOLGXInxPo5puInEtCxOzlgMX/iTAy', 'Lamei Ann', NULL, 'Butiong', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 'juspher', '$2y$10$3z1ro1PMTP3tZ1OvUYSK8uU4W.sR82dVIgStvbXicqo3f99lfD2Y2', 'juspher', NULL, 'pedraza', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(240316, 'larry1', 'user10139@example.com', '1234'),
(240317, 'charles', 'user10140@example.com', '1234'),
(240318, 'user10141', 'user10141@example.com', 'pass1021'),
(240319, 'user10142', 'user10142@example.com', 'pass1022'),
(240320, 'user10143', 'user10143@example.com', 'pass1023'),
(240321, 'user10144', 'user10144@example.com', 'pass1024'),
(240322, 'user10145', 'user10145@example.com', 'pass1025'),
(240323, 'user10146', 'user10146@example.com', 'pass1026'),
(240324, 'user10147', 'user10147@example.com', 'pass1027'),
(240325, 'user10148', 'user10148@example.com', 'pass1028'),
(240326, 'user10149', 'user10149@example.com', 'pass1029'),
(240327, 'user10150', 'user10150@example.com', 'pass1030'),
(240328, 'user10151', 'user10151@example.com', 'pass1031'),
(240329, 'user10152', 'user10152@example.com', 'pass1032'),
(240330, 'user10153', 'user10153@example.com', 'pass1033'),
(240331, 'user10154', 'user10154@example.com', 'pass1034'),
(240332, 'user10155', 'user10155@example.com', 'pass1035'),
(240333, 'user10156', 'user10156@example.com', 'pass1036'),
(240334, 'user10157', 'user10157@example.com', 'pass1037'),
(240335, '247001', '', '247001'),
(240336, '247002', '', '247002'),
(240337, '247003', '', '247003'),
(240338, '247004', '', '247004'),
(240339, '247005', '', '247005'),
(240340, '247006', '', '247006'),
(240341, '247007', '', '247007'),
(240342, '247008', '', '247008'),
(240343, '247009', '', '247009'),
(240344, '247010', '', '247010'),
(240345, '247011', '', '247011'),
(240346, '247012', '', '247012'),
(240347, '247013', '', '247013'),
(240348, '247014', '', '247014'),
(240349, '247015', '', '247015'),
(240350, '247016', '', '247016'),
(240351, '247017', '', '247017'),
(240352, '247018', '', '247018'),
(240353, '247019', '', '247019'),
(240354, '247020', '', '247020'),
(240355, '248001', '', '248001'),
(240356, '248002', '', '248002'),
(240357, '248003', '', '248003'),
(240358, '248004', '', '248004'),
(240359, '248005', '', '248005'),
(240360, '248006', '', '248006'),
(240361, '248007', '', '248007'),
(240362, '248008', '', '248008'),
(240363, '248009', '', '248009'),
(240364, '248010', '', '248010'),
(240365, '248011', '', '248011'),
(240366, '248012', '', '248012'),
(240367, '248013', '', '248013'),
(240368, '248014', '', '248014'),
(240369, '248015', '', '248015'),
(240370, '248016', '', '248016'),
(240371, '248017', '', '248017'),
(240372, '248018', '', '248018'),
(240373, '248019', '', '248019'),
(240374, '248020', '', '248020'),
(240375, '249001', '', '249001'),
(240376, '249002', '', '249002'),
(240377, '249003', '', '249003'),
(240378, '249004', '', '249004'),
(240379, '249005', '', '249005'),
(240380, '249006', '', '249006'),
(240381, '249007', '', '249007'),
(240382, '249008', '', '249008'),
(240383, '249009', '', '249009'),
(240384, '249010', '', '249010'),
(240385, '249011', '', '249011'),
(240386, '249012', '', '249012'),
(240387, '249013', '', '249013'),
(240388, '249014', '', '249014'),
(240389, '249015', '', '249015'),
(240390, '249016', '', '249016'),
(240391, '249017', '', '249017'),
(240392, '249018', '', '249018'),
(240393, '249019', '', '249019'),
(240394, '249020', '', '249020'),
(240395, '247101', '', '247101'),
(240396, '247102', '', '247102'),
(240397, '247103', '', '247103'),
(240398, '247104', '', '247104'),
(240399, '247105', '', '247105'),
(240400, '247106', '', '247106'),
(240401, '247107', '', '247107'),
(240402, '247108', '', '247108'),
(240403, '247109', '', '247109'),
(240404, '247110', '', '247110'),
(240405, '247111', '', '247111'),
(240406, '247112', '', '247112'),
(240407, '247113', '', '247113'),
(240408, '247114', '', '247114'),
(240409, '247115', '', '247115'),
(240410, '247116', '', '247116'),
(240411, '247117', '', '247117'),
(240412, '247118', '', '247118'),
(240413, '247119', '', '247119'),
(240414, '247120', '', '247120'),
(240415, '246101', '', '246101'),
(240416, '246102', '', '246102'),
(240417, '246103', '', '246103'),
(240418, '246104', '', '246104'),
(240419, '246105', '', '246105'),
(240420, '246106', '', '246106'),
(240421, '246107', '', '246107'),
(240422, '246108', '', '246108'),
(240423, '246109', '', '246109'),
(240424, '246110', '', '246110'),
(240425, '246111', '', '246111'),
(240426, '246112', '', '246112'),
(240427, '246113', '', '246113'),
(240428, '246114', '', '246114'),
(240429, '246115', '', '246115'),
(240430, '246116', '', '246116'),
(240431, '246117', '', '246117'),
(240432, '246118', '', '246118'),
(240433, '246119', '', '246119'),
(240434, '246120', '', '246120'),
(240435, '248101', '', '248101'),
(240436, '248102', '', '248102'),
(240437, '248103', '', '248103'),
(240438, '248104', '', '248104'),
(240439, '248105', '', '248105'),
(240440, '248106', '', '248106'),
(240441, '248107', '', '248107'),
(240442, '248108', '', '248108'),
(240443, '248109', '', '248109'),
(240444, '248110', '', '248110'),
(240445, '248111', '', '248111'),
(240446, '248112', '', '248112'),
(240447, '248113', '', '248113'),
(240448, '248114', '', '248114'),
(240449, '248115', '', '248115'),
(240450, '248116', '', '248116'),
(240451, '248117', '', '248117'),
(240452, '248118', '', '248118'),
(240453, '248119', '', '248119'),
(240454, '248120', '', '248120'),
(240455, '249101', '', '249101'),
(240456, '249102', '', '249102'),
(240457, '249103', '', '249103'),
(240458, '249104', '', '249104'),
(240459, '249105', '', '249105'),
(240460, '249106', '', '249106'),
(240461, '249107', '', '249107'),
(240462, '249108', '', '249108'),
(240463, '249109', '', '249109'),
(240464, '249110', '', '249110'),
(240465, '249111', '', '249111'),
(240466, '249112', '', '249112'),
(240467, '249113', '', '249113'),
(240468, '249114', '', '249114'),
(240469, '249115', '', '249115'),
(240470, '249116', '', '249116'),
(240471, '249117', '', '249117'),
(240472, '249118', '', '249118'),
(240473, '249119', '', '249119'),
(240474, '249120', '', '249120'),
(240475, '245251', '', '245251'),
(240476, '245252', '', '245252'),
(240477, '245253', '', '245253'),
(240478, '245254', '', '245254'),
(240479, '245255', '', '245255'),
(240480, '245256', '', '245256'),
(240481, '245257', '', '245257'),
(240482, '245258', '', '245258'),
(240483, '245259', '', '245259'),
(240484, '245260', '', '245260'),
(240485, '245261', '', '245261'),
(240486, '245262', '', '245262'),
(240487, '245263', '', '245263'),
(240488, '245264', '', '245264'),
(240489, '245265', '', '245265'),
(240490, '245266', '', '245266'),
(240491, '245267', '', '245267'),
(240492, '245268', '', '245268'),
(240493, '245269', '', '245269'),
(240494, '245270', '', '245270'),
(240495, '245231', '', '245231'),
(240496, '245232', '', '245232'),
(240497, '245233', '', '245233'),
(240498, '245234', '', '245234'),
(240499, '245235', '', '245235'),
(240500, '245236', '', '245236'),
(240501, '245237', '', '245237'),
(240502, '245238', '', '245238'),
(240503, '245239', '', '245239'),
(240504, '245240', '', '245240'),
(240505, '245241', '', '245241'),
(240506, '245242', '', '245242'),
(240507, '245243', '', '245243'),
(240508, '245244', '', '245244'),
(240509, '245245', '', '245245'),
(240510, '245246', '', '245246'),
(240511, '245247', '', '245247'),
(240512, '245248', '', '245248'),
(240513, '245249', '', '245249'),
(240514, '245250', '', '245250'),
(240515, '245111', '', '245111'),
(240516, '245112', '', '245112'),
(240517, '245113', '', '245113'),
(240518, '245114', '', '245114'),
(240519, '245115', '', '245115'),
(240520, '245116', '', '245116'),
(240521, '245117', '', '245117'),
(240522, '245118', '', '245118'),
(240523, '245119', '', '245119'),
(240524, '245120', '', '245120'),
(240525, '245121', '', '245121'),
(240526, '245122', '', '245122'),
(240527, '245123', '', '245123'),
(240528, '245124', '', '245124'),
(240529, '245125', '', '245125'),
(240530, '245126', '', '245126'),
(240531, '245127', '', '245127'),
(240532, '245128', '', '245128'),
(240533, '245129', '', '245129'),
(240534, '245130', '', '245130'),
(240535, '245291', '', '245291'),
(240536, '245292', '', '245292'),
(240537, '245293', '', '245293'),
(240538, '245294', '', '245294'),
(240539, '245295', '', '245295'),
(240540, '245296', '', '245296'),
(240541, '245297', '', '245297'),
(240542, '245298', '', '245298'),
(240543, '245299', '', '245299'),
(240544, '245300', '', '245300'),
(240545, '245301', '', '245301'),
(240546, '245302', '', '245302'),
(240547, '245303', '', '245303'),
(240548, '245304', '', '245304'),
(240549, '245305', '', '245305'),
(240550, '245306', '', '245306'),
(240551, '245307', '', '245307'),
(240552, '245308', '', '245308'),
(240553, '245309', '', '245309'),
(240554, '245310', '', '245310'),
(240555, 'denver', '', '1234'),
(240556, '247991', '', '247991'),
(240557, '247992', '', '247992'),
(240558, '247993', '', '247993');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`id`, `student_id`, `reported_at`, `guard_id`, `teacher_id`, `violation_id`) VALUES
(64, 240259, '2024-10-27 07:33:45', 15, NULL, 13),
(66, 240278, '2024-10-27 16:52:09', 15, NULL, 2),
(67, 240271, '2024-10-27 16:52:16', 15, NULL, 13),
(68, 240309, '2024-10-27 16:52:23', 15, NULL, 10),
(69, 240302, '2024-10-27 16:52:29', 15, NULL, 8),
(70, 240366, '2024-10-27 16:52:34', 15, NULL, 5),
(71, 240365, '2024-10-27 16:52:40', 15, NULL, 3),
(72, 240365, '2024-10-27 16:52:46', 15, NULL, 13),
(73, 240382, '2024-10-27 16:53:59', NULL, 48, 16),
(74, 240381, '2024-10-27 16:54:04', NULL, 48, 2),
(75, 240386, '2024-10-28 00:29:28', 15, NULL, 13),
(76, 240405, '2024-10-28 00:29:35', 15, NULL, 13),
(77, 240341, '2024-10-28 00:29:40', 15, NULL, 13),
(78, 240457, '2024-10-28 00:29:46', 15, NULL, 13),
(79, 240439, '2024-10-28 00:29:51', 15, NULL, 13),
(80, 240492, '2024-10-28 00:29:58', 15, NULL, 13),
(81, 240467, '2024-10-28 00:30:03', 15, NULL, 13),
(82, 240496, '2024-10-28 00:30:12', 15, NULL, 13),
(83, 240284, '2024-10-28 00:31:42', NULL, 48, 13),
(84, 240496, '2024-10-28 00:31:55', NULL, 48, 13),
(85, 240396, '2024-10-28 00:32:06', NULL, 48, 13),
(86, 240496, '2024-10-28 00:32:13', NULL, 48, 13),
(87, 240394, '2024-10-28 00:32:22', NULL, 48, 13),
(88, 240442, '2024-10-28 00:32:30', NULL, 48, 13),
(89, 240360, '2024-10-28 00:33:41', NULL, 56, 13),
(90, 240334, '2024-10-28 00:33:47', NULL, 56, 13),
(91, 240496, '2024-10-28 00:33:53', NULL, 56, 13),
(92, 240261, '2024-10-28 00:34:26', NULL, 56, 7),
(93, 240306, '2024-10-28 00:34:35', NULL, 56, 7),
(94, 240316, '2024-10-28 00:34:41', NULL, 56, 7),
(95, 240258, '2024-10-28 01:39:55', NULL, 48, 13),
(97, 240258, '2024-10-28 01:40:56', NULL, 48, 6),
(98, 240258, '2024-10-28 01:41:43', NULL, 48, 8),
(99, 240258, '2024-10-28 01:42:00', NULL, 48, 10),
(100, 240258, '2024-10-28 01:42:08', NULL, 48, 5),
(101, 240258, '2024-10-28 01:42:47', NULL, 48, 1),
(102, 240259, '2024-10-28 01:45:53', NULL, 48, 3),
(103, 240259, '2024-10-28 01:46:08', NULL, 48, 4),
(104, 240259, '2024-10-28 01:46:20', NULL, 48, 9),
(105, 240259, '2024-10-28 01:46:28', NULL, 48, 2),
(106, 240260, '2024-10-28 01:49:53', 15, NULL, 1),
(107, 240258, '2024-10-28 03:06:03', 15, NULL, 13),
(108, 240260, '2024-10-28 03:07:13', NULL, 48, 6);

-- --------------------------------------------------------

--
-- Table structure for table `violation_list`
--

CREATE TABLE `violation_list` (
  `id` int(11) NOT NULL,
  `violation_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `school_year`
--
ALTER TABLE `school_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `strand_id` (`strand_id`);

--
-- Indexes for table `section_assignment`
--
ALTER TABLE `section_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `school_year_id` (`school_year_id`),
  ADD KEY `section_assignment_ibfk_1` (`student_id`);

--
-- Indexes for table `strands`
--
ALTER TABLE `strands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_students_school_year` (`school_year_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_section` (`section_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `complaints_student`
--
ALTER TABLE `complaints_student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `fathers`
--
ALTER TABLE `fathers`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240501;

--
-- AUTO_INCREMENT for table `guards`
--
ALTER TABLE `guards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `mothers`
--
ALTER TABLE `mothers`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240501;

--
-- AUTO_INCREMENT for table `principal`
--
ALTER TABLE `principal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `school_year`
--
ALTER TABLE `school_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `section_assignment`
--
ALTER TABLE `section_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT for table `strands`
--
ALTER TABLE `strands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240501;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240559;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `violation_list`
--
ALTER TABLE `violation_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  ADD CONSTRAINT `fk_complaints_student_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mothers`
--
ALTER TABLE `mothers`
  ADD CONSTRAINT `mothers_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `fk_strand` FOREIGN KEY (`strand_id`) REFERENCES `strands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`strand_id`) REFERENCES `strands` (`id`),
  ADD CONSTRAINT `sections_ibfk_2` FOREIGN KEY (`strand_id`) REFERENCES `strands` (`id`);

--
-- Constraints for table `section_assignment`
--
ALTER TABLE `section_assignment`
  ADD CONSTRAINT `section_assignment_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_assignment_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`),
  ADD CONSTRAINT `section_assignment_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  ADD CONSTRAINT `section_assignment_ibfk_4` FOREIGN KEY (`school_year_id`) REFERENCES `school_year` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_school_year` FOREIGN KEY (`school_year_id`) REFERENCES `school_year` (`id`),
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `fk_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`);

--
-- Constraints for table `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `fk_guard` FOREIGN KEY (`guard_id`) REFERENCES `guards` (`id`),
  ADD CONSTRAINT `fk_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`),
  ADD CONSTRAINT `fk_violation_id` FOREIGN KEY (`violation_id`) REFERENCES `violation_list` (`id`),
  ADD CONSTRAINT `violations_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

--
-- Dumping data for table `pma__navigationhiding`
--

INSERT INTO `pma__navigationhiding` (`username`, `item_name`, `item_type`, `db_name`, `table_name`) VALUES
('root', 'procedures', 'group', 'attendance_database', '');

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"guideco2\",\"table\":\"admin\"},{\"db\":\"attendance_database\",\"table\":\"employee_accounts\"},{\"db\":\"attendance_database\",\"table\":\"employee\"},{\"db\":\"attendance_database\",\"table\":\"admin\"},{\"db\":\"attendance_database\",\"table\":\"admin_accounts\"},{\"db\":\"guideco2\",\"table\":\"users\"},{\"db\":\"guideco2\",\"table\":\"students\"},{\"db\":\"guideco2\",\"table\":\"principal\"},{\"db\":\"guideco2\",\"table\":\"teachers\"},{\"db\":\"guideco2\",\"table\":\"mothers\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

--
-- Dumping data for table `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'attendance_database', 'employee_accounts', '{\"sorted_col\":\"`empPassword` ASC\"}', '2024-11-07 04:03:07'),
('root', 'guideco2', 'admin', '{\"CREATE_TIME\":\"2024-10-27 13:17:31\",\"col_order\":[0,1,2,3,4,5,6,7,8,9,10,11,12,13],\"col_visib\":[1,1,1,1,1,1,1,1,1,1,1,1,1,1]}', '2024-10-27 06:11:33'),
('root', 'guideco2', 'fathers', '{\"sorted_col\":\"`parent_id` ASC\"}', '2024-10-27 14:55:54'),
('root', 'guideco2', 'teachers', '{\"CREATE_TIME\":\"2024-10-27 13:17:31\",\"col_order\":[0,1,3,2,4,5,6,7,8,9,10,11,12],\"col_visib\":[1,1,1,1,1,1,1,1,1,1,1,1,1]}', '2024-10-27 16:34:31');

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2024-11-08 12:08:08', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
