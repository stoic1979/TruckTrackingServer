-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 08, 2017 at 12:23 PM
-- Server version: 5.6.32-78.1-log
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `weavebyt_trucktracking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(64) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`) VALUES
(1, 'truckmanager', 'Pb@2017', 'test@gmail.com'),
(2, 'truckguru', 'Pb@2017', 'truckguru@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE IF NOT EXISTS `driver` (
  `id` int(11) NOT NULL,
  `admin_id` bigint(20) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `fname` varchar(64) NOT NULL,
  `lname` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `from_loc` varchar(64) NOT NULL,
  `to_loc` varchar(64) NOT NULL,
  `vehicle_no` varchar(32) NOT NULL,
  `phone_no` varchar(32) NOT NULL,
  `license_no` varchar(32) NOT NULL,
  `aadhar_no` varchar(64) NOT NULL,
  `last_lat` double NOT NULL,
  `last_lng` double NOT NULL,
  `last_ts` bigint(20) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`id`, `admin_id`, `username`, `password`, `fname`, `lname`, `email`, `from_loc`, `to_loc`, `vehicle_no`, `phone_no`, `license_no`, `aadhar_no`, `last_lat`, `last_lng`, `last_ts`, `active`) VALUES
(1, 2, 'gopal', 'g123', '', '', '', '', '', '', '9988776655', '', '', 41.1, 2.2, 1234, 0),
(3, 1, 'shamsher', 's123', '', '', '', '', '', '', '9876543210', '', '', 45.45, 34.34, 4343, 0),
(4, 1, 'deepak', 'd123', '', '', '', '', '', '', '9898986666', '', '', 0, 0, 0, 0),
(5, 1, 'suraj', 's123', '', '', '', '', '', '', '8899776612', '', '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` bigint(20) NOT NULL,
  `did` int(11) NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `ts` bigint(20) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `did`, `lat`, `lng`, `ts`) VALUES
(2, 1, 11.11, 11.11, 345678),
(3, 1, 11.113, 11.114, 34567856),
(4, 3, 45.45, 34.34, 4343),
(5, 3, 45.45, 34.34, 4343),
(6, 3, 45.45, 34.34, 4343),
(7, 3, 45.45, 34.34, 4343),
(8, 3, 45.45, 34.34, 4343);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(64) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
