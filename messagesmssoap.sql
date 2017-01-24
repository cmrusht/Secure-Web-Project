-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2017 at 10:29 PM
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
  `message` varchar(50) DEFAULT NULL,
  `message_timestamp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`source`, `destination`, `received`, `bearer`, `message_hash`, `message_id`, `message`, `message_timestamp`) VALUES
('447817814149', '447817814149', '23/01/2017 12:36:05', 'SMS', '5h37xnk58d', 'ccl', '43', '2016-12-20 15:38:16'),
('447817814149', '447817814149', '23/01/2017 12:38:47', 'SMS', 'ePD7B0tVd3', 'ccl', 'Number test', '2016-12-20 15:38:16'),
('447817814149', '447817814149', '23/01/2017 12:43:00', 'SMS', 'ig7yGe7Muh', 'ccl', 'Date and time test', '23/01/2017 01:42:54pm'),
('447817814149', '447817814149', '23/01/2017 12:49:33', 'SMS', 'InL0KKDtnD', 'ccl', 'Test time 2', '23/01/2017 12:49:23'),
('447817814149', '447817814149', '24/01/2017 21:01:57', 'SMS', '1vdff8AamE', 'ccl', 'Hi new message', '24/01/2017 21:01:51');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
