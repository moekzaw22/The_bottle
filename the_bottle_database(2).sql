-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2021 at 04:59 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `the_bottle_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_id` int(11) NOT NULL,
  `Product_name` varchar(200) NOT NULL,
  `Product_type` varchar(100) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_id`, `Product_name`, `Product_type`, `Quantity`, `Price`) VALUES
(1, 'Red label', 'Whiskey', 92, 23000),
(4, 'Black label', 'Whiskey', 44, 30000),
(6, 'GR Smooth', 'Whiskey', 58, 1300),
(7, 'Andaman Gold', 'Beer', 38, 2000);

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `purchaseid` int(11) NOT NULL,
  `Product_id` int(11) NOT NULL,
  `Buy_Quantity` int(11) NOT NULL,
  `totalprice` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`purchaseid`, `Product_id`, `Buy_Quantity`, `totalprice`, `Date`, `Time`, `status`) VALUES
(1, 6, 1, 1300, '2021-09-19', '08:04:48', 'Complete'),
(2, 1, 1, 23000, '2021-09-19', '08:04:53', 'Complete'),
(3, 1, 1, 23000, '2021-09-19', '08:04:56', ''),
(4, 6, 1, 1300, '2021-09-19', '08:04:59', ''),
(5, 1, 1, 23000, '2021-09-19', '08:28:26', ''),
(6, 1, 1, 23000, '2021-09-19', '08:52:52', 'Complete'),
(7, 4, 1, 30000, '2021-09-19', '08:53:02', 'Complete'),
(8, 1, 1, 23000, '2021-09-19', '09:05:42', 'Complete'),
(9, 4, 4, 120000, '2021-09-19', '09:05:46', 'Complete'),
(10, 1, 1, 23000, '2021-09-19', '09:07:13', 'Complete'),
(11, 1, 1, 23000, '2021-09-19', '09:07:31', 'Complete'),
(12, 1, 1, 23000, '2021-09-19', '09:08:26', 'Complete'),
(13, 4, 1, 30000, '2021-09-19', '09:09:24', 'Complete'),
(14, 7, 2, 4000, '2021-09-19', '09:19:17', 'Complete');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Product_id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`purchaseid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `purchaseid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
