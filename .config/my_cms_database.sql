-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 08, 2018 at 08:07 PM
-- Server version: 5.5.57-0+deb8u1
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_cms_database`
--
CREATE DATABASE IF NOT EXISTS `my_cms_database` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `my_cms_database`;

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

DROP TABLE IF EXISTS `app_settings`;
CREATE TABLE IF NOT EXISTS `app_settings` (
`setting_id` int(16) NOT NULL COMMENT 'Unique setting ID',
  `setting_name` varchar(128) NOT NULL COMMENT 'The Unique Name of the Setting',
  `setting_value` varchar(1024) NOT NULL COMMENT 'THe Value of the Setting',
  `last_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Lasted updated'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

DROP TABLE IF EXISTS `user_data`;
CREATE TABLE IF NOT EXISTS `user_data` (
`user_id` int(16) NOT NULL COMMENT 'Unique User ID',
  `username` varchar(24) NOT NULL COMMENT 'UserName',
  `md5_password` varchar(64) NOT NULL COMMENT 'MD5 Hashed PS',
  `user_email` varchar(128) NOT NULL COMMENT 'Unique email address',
  `admin_rights` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Has Admin Permissions',
  `account_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Account is Active'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Stores Users account data';

--
-- Dumping data for table `user_data`
--

INSERT INTO `user_data` (`user_id`, `username`, `md5_password`, `user_email`, `admin_rights`, `account_active`) VALUES
(10, 'admin', 'b17eccdc6c06bd8e15928d583503adf9', 'admin@example.com', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
 ADD PRIMARY KEY (`setting_id`), ADD UNIQUE KEY `setting_id` (`setting_id`,`setting_name`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
 ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `user_id` (`user_id`,`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
MODIFY `setting_id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Unique setting ID',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
MODIFY `user_id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Unique User ID',AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
