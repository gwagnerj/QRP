-- phpMyAdmin SQL Dump
-- version 4.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 16, 2018 at 06:15 PM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 7.1.11-1+ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `codexworld`
--

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `state_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0:Blocked, 1:Active'
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `city_name`, `state_id`, `status`) VALUES
(1, 'Sydney', 1, 1),
(2, 'Newcastle', 1, 1),
(3, 'Central Coast', 1, 1),
(4, 'Brisbane', 2, 1),
(5, 'Gold Coast', 2, 1),
(6, 'Townsville', 2, 1),
(7, 'Melbourne', 3, 1),
(8, 'Geelong', 3, 1),
(9, 'Bern', 4, 1),
(10, 'Geneve', 5, 1),
(11, 'Lausanne', 6, 1),
(12, 'Oviedo', 7, 1),
(13, 'Barcelona', 8, 1),
(14, 'Sabadell', 8, 1),
(15, 'Tarragona', 8, 1),
(16, 'Madrid', 9, 1),
(17, 'Fuenlabrada', 9, 1),
(18, 'Getafe', 9, 1),
(19, 'London', 10, 1),
(20, 'Liverpool', 10, 1),
(21, 'Manchester', 10, 1),
(22, 'Saint Helier', 11, 1),
(23, 'Glasgow', 12, 1),
(24, 'Edinburgh', 12, 1),
(25, 'Aberdeen', 12, 1),
(26, 'Jacksonville', 13, 1),
(27, 'Miami', 13, 1),
(28, 'Tampa', 13, 1),
(29, 'Atlanta', 14, 1),
(30, 'Columbus', 14, 1),
(31, 'Savannah', 14, 1),
(32, 'Overland Park', 15, 1),
(33, 'Kansas City', 15, 1),
(34, 'Topeka', 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0:Blocked, 1:Active'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `country_name`, `status`) VALUES
(1, 'Australia', 1),
(2, 'Switzerland', 1),
(3, 'Spain', 1),
(4, 'United Kingdom', 1),
(5, 'United States', 1);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE IF NOT EXISTS `states` (
  `state_id` int(11) NOT NULL,
  `state_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0:Blocked, 1:Active'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`state_id`, `state_name`, `country_id`, `status`) VALUES
(1, 'New South Wales', 1, 1),
(2, 'Queensland', 1, 1),
(3, 'Victoria', 1, 1),
(4, 'Bern', 2, 1),
(5, 'Geneve', 2, 1),
(6, 'Vaud', 2, 1),
(7, 'Asturia', 3, 1),
(8, 'Katalonia', 3, 1),
(9, 'Madrid', 3, 1),
(10, 'England', 4, 1),
(11, 'Jersey', 4, 1),
(12, 'Scotland', 4, 1),
(13, 'Florida', 5, 1),
(14, 'Georgia', 5, 1),
(15, 'Kansas', 5, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`state_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
