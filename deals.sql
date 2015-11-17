-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2015 at 01:48 PM
-- Server version: 5.6.26
-- PHP Version: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `deals`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL,
  `user_name` varchar(20) DEFAULT NULL,
  `user_pass` varchar(20) DEFAULT NULL,
  `user_email` varchar(20) DEFAULT NULL,
  `user_registered` date DEFAULT NULL,
  `user_status` int(11) DEFAULT NULL,
  `display_name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL,
  `deal_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_content` text CHARACTER SET latin1,
  `comment_date` date DEFAULT NULL,
  `comment_parent` int(11) DEFAULT NULL,
  `comment_report` int(11) DEFAULT NULL,
  `comment_status` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sub_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deal`
--

CREATE TABLE IF NOT EXISTS `deal` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `deal_url` varchar(20) DEFAULT NULL,
  `title` varchar(20) DEFAULT NULL,
  `deal_price` varchar(20) DEFAULT NULL,
  `deal_availablity` varchar(20) DEFAULT NULL,
  `city_postcode` varchar(20) DEFAULT NULL,
  `deal_category` int(11) DEFAULT NULL,
  `deal_sub_category` varchar(20) DEFAULT NULL,
  `discount` varchar(20) DEFAULT NULL,
  `discount_code` varchar(20) DEFAULT NULL,
  `detail` longtext CHARACTER SET latin1,
  `prize` varchar(20) DEFAULT NULL,
  `period` varchar(20) DEFAULT NULL,
  `deal_image` varchar(20) DEFAULT NULL,
  `deal_image_url` varchar(20) DEFAULT NULL,
  `tags` text CHARACTER SET latin1,
  `deal_rule` longtext CHARACTER SET latin1,
  `link_to_rule` varchar(20) DEFAULT NULL,
  `apply_to` varchar(20) DEFAULT NULL,
  `report` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deal_social_activity`
--

CREATE TABLE IF NOT EXISTS `deal_social_activity` (
  `id` int(11) NOT NULL,
  `deal_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_date` date DEFAULT NULL,
  `activity_content` text CHARACTER SET latin1,
  `activity_value` int(11) DEFAULT NULL,
  `activity_type` varchar(20) DEFAULT NULL,
  `activity_status` int(11) DEFAULT NULL,
  `activity_parent` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sub_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deal_sub_category`
--

CREATE TABLE IF NOT EXISTS `deal_sub_category` (
  `category_id` int(11) DEFAULT NULL,
  `sub_category_name` varchar(20) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deal_type`
--

CREATE TABLE IF NOT EXISTS `deal_type` (
  `id` int(11) NOT NULL DEFAULT '0',
  `category_name` varchar(20) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `provider` varchar(20) DEFAULT NULL,
  `identifier` varchar(30) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `provider`, `identifier`, `email`, `password`, `first_name`, `last_name`, `avatar_url`) VALUES
(1, 'Local', '123456', 'kkd@kk.com', 'kranthi', 'kranthi', 'kumar', ''),
(2, 'Local', '12345678', 'kk1@kk.com', 'kkk', 'kk', '', ''),
(3, 'Local', '123455k', 'kk@kk.com', 'kranthi', 'kranthi', 'kumar', ''),
(4, 'Local', '123455', 'kk@kk.com', 'kranthi', 'kranthi', 'kumar', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_composite_key` (`user_name`,`user_email`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deal`
--
ALTER TABLE `deal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deal_social_activity`
--
ALTER TABLE `deal_social_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deal_sub_category`
--
ALTER TABLE `deal_sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deal_type`
--
ALTER TABLE `deal_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `composite_key` (`provider`,`identifier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deal`
--
ALTER TABLE `deal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deal_social_activity`
--
ALTER TABLE `deal_social_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deal_sub_category`
--
ALTER TABLE `deal_sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
