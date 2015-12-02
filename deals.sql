-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2015 at 08:23 AM
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
  `user_name` varchar(20) DEFAULT NULL,
  `comment_content` text CHARACTER SET latin1,
  `comment_date` date DEFAULT NULL,
  `comment_parent` int(11) DEFAULT NULL,
  `comment_report` int(11) DEFAULT NULL,
  `comment_status` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `deal_id`, `user_id`, `user_name`, `comment_content`, `comment_date`, `comment_parent`, `comment_report`, `comment_status`) VALUES
(1, 8, 3688, NULL, 'test comment for first time', '2015-11-17', 1234, 4321, 1),
(2, 8, 3688, NULL, 'International Data', '2016-09-07', 1234, 5678, 1),
(3, 1, 3688, NULL, 'International Datacccc updates', '2016-09-07', 1234, 5678, 1),
(4, 1, 3688, NULL, 'International Data', '2016-09-07', 1234, 5678, 1),
(5, 1, 3688, NULL, 'International Data', '2016-09-07', 1234, 5678, 1),
(6, 1, 3688, NULL, 'International Data', '2016-09-07', 1234, 5678, 1),
(7, 1, 3688, NULL, 'International Data', '2016-09-07', 1234, 5678, 1),
(8, 1, 3688, NULL, 'International Data', '2016-09-07', 1234, 5678, 1),
(9, 1, 3688, NULL, 'International Datacccc', '2016-09-07', 1234, 5678, 1),
(11, 8, 2345, 'kranthi', 'comment text asdf', '2015-11-17', 2356, 2, 1),
(12, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL);

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
  `deal_sub_category` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `deal`
--

INSERT INTO `deal` (`id`, `user_id`, `deal_url`, `title`, `deal_price`, `deal_availablity`, `city_postcode`, `deal_category`, `deal_sub_category`, `discount`, `discount_code`, `detail`, `prize`, `period`, `deal_image`, `deal_image_url`, `tags`, `deal_rule`, `link_to_rule`, `apply_to`, `report`, `start_date`, `end_date`, `status`) VALUES
(2, '247', 'www.google.com', 'Diwali Deal', '200', 'International', '3333', 0, 0, '2', 'Dis330', 'This is for college students', 'dd', 'Daily', 'www.kranovation.com', 'www.kranovation.com', 'college', 'content', 'hello data', 'testing', 3, '0000-00-00', '0000-00-00', 1),
(4, '247', 'www.google.com', 'Diwali Deal', '200', 'International', '3333', 5678, 0, '2', 'Dis330', 'This is for college students', 'dd', 'Daily', 'www.kranovation.com', 'www.kranovation.com', 'college', 'content', 'hello data', 'testing', 3, '0000-00-00', '0000-00-00', 1),
(5, '247', 'www.google.com', 'Diwali Deal', '200', 'International', '3333', 2356, 0, '2', 'Dis330', 'This is for college students', 'dd', 'Daily', 'www.kranovation.com', 'www.kranovation.com', 'college', 'content', 'hello data', 'testing', 3, '0000-00-00', '0000-00-00', 1),
(6, '247', 'www.google.com', 'Diwali Deal', '200', 'International', '3333', 2356, 0, '2', 'Dis330', 'This is for college students', 'dd', 'Daily', 'www.kranovation.com', 'www.kranovation.com', 'college', 'content', 'hello data', 'testing', 3, '0000-00-00', '0000-00-00', 1),
(7, '247', 'www.google.com', 'Diwali Deal', '200', 'International', '3333', 2356, 0, '2', 'Dis330', 'This is for college students', 'dd', 'Daily', 'www.kranovation.com', 'www.kranovation.com', 'college', 'content', 'hello data', 'testing', 3, '0000-00-00', '0000-00-00', 1),
(8, '247', 'www.google.com', 'Diwali Deal', '200', 'International', '3333', 2356, 0, '2', 'Dis330', 'This is for college students', 'dd', 'Daily', 'www.kranovation.com', 'www.kranovation.com', 'college', 'content', 'hello data', 'testing', 3, '2015-12-04', '2016-08-04', 1),
(9, '247', 'www.google.com', 'Diwali Deal', '200', 'International', '3333', 2356, 0, '2', 'Dis330', 'This is for college students', 'dd', 'Daily', 'www.kranovation.com', 'www.kranovation.com', 'college', 'content', 'hello data', 'testing', 3, '2015-12-04', '2016-08-04', 1),
(10, '247', 'www.google.com', 'Diwali Deal', '200', 'International', '3333', 0, 3, '2', 'Dis330', 'This is for college students', 'dd', 'Daily', 'www.kranovation.com', 'www.kranovation.com', 'college', 'content', 'hello data', 'testing', 3, '2015-12-04', '2016-08-04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `deal_social_activity`
--

CREATE TABLE IF NOT EXISTS `deal_social_activity` (
  `id` int(11) NOT NULL,
  `deal_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_type` varchar(20) DEFAULT NULL,
  `activity_date` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `deal_social_activity`
--

INSERT INTO `deal_social_activity` (`id`, `deal_id`, `comment_id`, `user_id`, `activity_type`, `activity_date`) VALUES
(8, 1, 0, 3688, 'LIKE', '2016-09-07'),
(12, 1, 0, 36889, 'LIKE', '2016-09-07'),
(14, 10, 0, 36889, 'LIKE', '2016-09-07'),
(18, 10, 0, 3688, 'DISLIKE', '2016-09-07'),
(19, 8, NULL, 368, 'DISLIKE', '2016-09-07'),
(20, NULL, 2, 368, 'LIKE', '2016-09-07'),
(21, 8, NULL, 5555, 'LIKE', '2015-11-23'),
(24, NULL, 2, 5555, 'LIKE', '2015-11-23');

-- --------------------------------------------------------

--
-- Table structure for table `deal_sub_category`
--

CREATE TABLE IF NOT EXISTS `deal_sub_category` (
  `category_id` int(11) DEFAULT NULL,
  `sub_category_name` varchar(20) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `deal_sub_category`
--

INSERT INTO `deal_sub_category` (`category_id`, `sub_category_name`, `status`, `id`) VALUES
(2356, 'science', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `deal_type`
--

CREATE TABLE IF NOT EXISTS `deal_type` (
  `id` int(11) NOT NULL,
  `category_name` varchar(20) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `deal_type`
--

INSERT INTO `deal_type` (`id`, `category_name`, `status`) VALUES
(2356, 'Bangalore', 1);

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
  `avatar_url` varchar(255) DEFAULT NULL,
  `token` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `provider`, `identifier`, `email`, `password`, `first_name`, `last_name`, `avatar_url`, `token`) VALUES
(1, 'Local', '123456', 'kkd@kk.com', 'kranthi', 'kranthi', 'kumar', '', 'd9a46f1794b00554'),
(2, 'Local', '12345678', 'kk1@kk.com', 'kkk', 'kk', '', '', NULL),
(3, 'Local', '123455k', 'kk@kk.com', 'kranthi', 'kranthi', 'kumar', '', NULL),
(4, 'Local', '123455', 'kk@kk.com', 'kranthi', 'kranthi', 'kumar', '', '611fa840cbbf0cbb'),
(5, 'Local', '123', 'kranthi.dkk@gmail.com', 'hhh', 'hh', 'hh', '', NULL),
(6, 'Local', '123s', 'kranthi.dkk@gmail.com', 'hhh', 'hh', 'hh', '', NULL),
(7, 'Facebook', '10206739737029947', 'kranthi_dkk@yahoo.com', '', 'Kranthi', 'Kumar D', 'https://graph.facebook.com/10206739737029947/picture?width=150&height=150', '16f4c89274027fcb'),
(8, 'Local', '123123', 'ravi.pallikonda@gmail.com', '123', 'ravi', 'asd', '', '03e8a1e82dc84a29');

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `composite_social_activity` (`deal_id`,`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `deal`
--
ALTER TABLE `deal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `deal_social_activity`
--
ALTER TABLE `deal_social_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `deal_sub_category`
--
ALTER TABLE `deal_sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
