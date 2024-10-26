-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2024 at 01:56 PM
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
-- Database: `myschool`
--

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `staffId` varchar(250) DEFAULT NULL,
  `created_by` varchar(250) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_by` varchar(250) NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `password`, `firstname`, `lastname`, `staffId`, `created_by`, `created_on`, `modified_by`, `modified_on`, `status`) VALUES
(1, 'pavan@myschool.com', 'admin@123', 'Pavan', 'Kumar', 'MS001', 'admin', '2024-09-26 08:49:54', '', '2024-09-26 08:49:54', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `marks` float(10,2) NOT NULL,
  `studentId` varchar(250) DEFAULT NULL,
  `created_by` varchar(250) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_by` varchar(250) NOT NULL,
  `modified_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `name`, `subject`, `marks`, `studentId`, `created_by`, `created_on`, `modified_by`, `modified_on`, `status`) VALUES
(1, 'Pavan Kumar', 'English', 79.00, 'MS001', '', '2024-09-26 11:05:47', 'admin', '2024-09-27 16:05:26', 1),
(2, 'Ravi', 'Computers', 85.00, 'MS002', 'admin', '2024-09-26 11:35:46', 'admin', '2024-09-26 12:09:25', 1),
(3, 'Shashi', 'Maths', 90.00, 'MS003', 'admin', '2024-09-26 11:36:59', 'admin', '2024-09-26 12:09:25', 1),
(4, 'suraj', 'English', 100.00, 'MS004', 'admin', '2024-09-26 12:42:18', '', '2024-09-26 12:42:18', 1),
(5, 'bhagavath', 'English', 100.00, 'MS005', 'admin', '2024-09-26 12:42:36', 'admin', '2024-09-26 22:41:32', 1),
(6, 'kiran Kumar', 'English', 100.00, 'MS006', 'admin', '2024-09-26 12:42:59', 'admin', '2024-09-27 15:56:45', 1),
(7, 'nikhil', 'Computers', 80.00, 'MS007', 'admin', '2024-09-26 22:37:45', '', '2024-09-26 22:37:45', 1),
(8, 'kiran', 'Computers', 55.00, 'MS008', 'admin', '2024-09-27 15:56:25', '', '2024-09-27 15:56:25', 1),
(9, 'Raju', 'Computers', 70.00, 'MS009', 'admin', '2024-09-27 16:05:01', 'admin', '2024-09-27 16:06:23', 0),
(10, 'Shashi', 'Computers', 80.00, 'MS003', 'admin', '2024-10-14 07:51:59', 'admin', '2024-10-14 07:53:29', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
