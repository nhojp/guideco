-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2024 at 01:39 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `capstone`
--

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(255) NOT NULL,
  `userid` int(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `age` varchar(255) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `contactnumber` int(255) NOT NULL,
  `religion` varchar(255) NOT NULL,
  `birthday` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `userid`, `firstname`, `middlename`, `lastname`, `age`, `sex`, `section`, `contactnumber`, `religion`, `birthday`) VALUES
(90, 1, 'John', 'A.', 'Doe', '18', 'M', 'A1', 2147483647, 'Catholic', '0000-00-00'),
(91, 2, 'Jane', 'B.', 'Smith', '17', 'F', 'A2', 2147483647, 'Muslim', '0000-00-00'),
(92, 3, 'Mike', 'C.', 'Johnson', '16', 'M', 'B1', 2147483647, 'Catholic', '2003-10-07'),
(93, 4, 'Mary', 'D.', 'Williams', '19', 'F', 'B2', 2147483647, 'Christian', '2004-05-04'),
(94, 5, 'Chris', 'E.', 'Brown', '20', 'M', 'C1', 2147483647, 'Catholic', '0000-00-00'),
(95, 6, 'Lisa', 'F.', 'Jones', '21', 'F', 'C2', 2147483647, 'Protestant', '0000-00-00'),
(96, 7, 'Alex', 'G.', 'Garcia', '22', 'M', 'D1', 2147483647, 'Catholic', '0000-00-00'),
(97, 8, 'Sarah', 'H.', 'Martinez', '23', 'F', 'D2', 2147483647, 'Muslim', '0000-00-00'),
(98, 9, 'David', 'I.', 'Rodriguez', '19', 'M', 'E1', 2147483647, 'Catholic', '0000-00-00'),
(99, 10, 'Emma', 'J.', 'Hernandez', '18', 'F', 'E2', 2147483647, 'Christian', '2010-12-05'),
(100, 11, 'Daniel', 'K.', 'Lee', '17', 'M', 'F1', 2147483647, 'Catholic', '2011-05-06'),
(101, 12, 'Olivia', 'L.', 'Kim', '16', 'F', 'F2', 2147483647, 'Protestant', '2012-01-07'),
(102, 13, 'James', 'M.', 'Chen', '20', 'M', 'G1', 2147483647, 'Catholic', '0000-00-00'),
(103, 14, 'Sophia', 'N.', 'Patel', '19', 'F', 'G2', 2147483647, 'Muslim', '0000-00-00'),
(104, 15, 'Benjamin', 'O.', 'Young', '21', 'M', 'H1', 2147483647, 'Catholic', '0000-00-00'),
(105, 16, 'Mia', 'P.', 'Allen', '22', 'F', 'H2', 2147483647, 'Christian', '2004-11-01'),
(106, 17, 'Elijah', 'Q.', 'Wright', '23', 'M', 'I1', 2147483647, 'Catholic', '0000-00-00'),
(107, 18, 'Isabella', 'R.', 'Scott', '19', 'F', 'I2', 2147483647, 'Protestant', '2006-05-04'),
(108, 19, 'Noah', 'S.', 'Green', '18', 'M', 'J1', 2147483647, 'Catholic', '2007-02-05'),
(109, 20, 'Ava', 'T.', 'Adams', '17', 'F', 'J2', 2147483647, 'Muslim', '0000-00-00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
