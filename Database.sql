-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2017 at 03:14 PM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `messagesmssoap`
--
CREATE DATABASE IF NOT EXISTS `messagesmssoap` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `messagesmssoap`;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `source` varchar(50) NOT NULL,
  `destination` varchar(50) NOT NULL,
  `received` varchar(50) NOT NULL,
  `bearer` varchar(50) NOT NULL,
  `message_hash` varchar(50) NOT NULL,
  `message_id` varchar(50) NOT NULL,
  `switch1` varchar(50) NOT NULL,
  `switch2` varchar(50) NOT NULL,
  `switch3` varchar(50) NOT NULL,
  `switch4` varchar(50) NOT NULL,
  `fan` varchar(50) NOT NULL,
  `heater` varchar(50) NOT NULL,
  `keypad` varchar(50) NOT NULL,
  `message` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`source`, `destination`, `received`, `bearer`, `message_hash`, `message_id`, `switch1`, `switch2`, `switch3`, `switch4`, `fan`, `heater`, `keypad`, `message`) VALUES
('447817814149', '447817814149', '25/01/2017 14:11:36', 'SMS', '8PW94', 'llc', 'on', 'on', 'on', 'on', 'Forward', '34', '3453', 'dfgdfg'),
('447817814149', '447817814149', '25/01/2017 14:12:47', 'SMS', 'OYSEp', 'llc', 'on', 'on', 'on', 'on', 'Forward', '23', '4632', 'Update 1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
