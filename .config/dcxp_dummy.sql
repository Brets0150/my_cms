-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 14, 2018 at 01:26 AM
-- Server version: 5.5.58-0+deb8u1
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dcxp_dummy`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE IF NOT EXISTS `app_settings` (
`setting_id` int(16) NOT NULL COMMENT 'Unique setting ID',
  `setting_name` varchar(128) NOT NULL COMMENT 'The Unique Name of the Setting',
  `setting_value` varchar(1024) NOT NULL COMMENT 'THe Value of the Setting',
  `last_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Lasted updated'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`setting_id`, `setting_name`, `setting_value`, `last_changed`) VALUES
(2, 'ticketing_system_url', 'https://esupport.example.com/staff/Tickets/Ticket/View/', '2018-06-14 08:24:23');

-- --------------------------------------------------------

--
-- Table structure for table `cash_pool_table`
--

CREATE TABLE IF NOT EXISTS `cash_pool_table` (
`cash_pool_id` int(16) NOT NULL COMMENT 'Unique Cash Pool',
  `cash_pool_month` int(8) NOT NULL COMMENT 'Month of the Pool',
  `cash_pool_year` int(8) NOT NULL COMMENT 'Year of the Pool',
  `cash_pool_value` int(8) NOT NULL COMMENT '$$Cash Value'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
`job_id` int(16) NOT NULL COMMENT 'Unique Job ID',
  `job_name` varchar(128) NOT NULL COMMENT 'Name of the Job',
  `job_description` varchar(8192) NOT NULL COMMENT 'Description of the Job',
  `job_xp_value` int(16) NOT NULL COMMENT 'Default XP value',
  `job_active` tinyint(1) NOT NULL COMMENT 'Job Active Status'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='The different job that can be performed and there XP values';

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `xp_data`
--

CREATE TABLE IF NOT EXISTS `xp_data` (
`xp_id` int(16) NOT NULL COMMENT 'Unique XP ID',
  `user_id` int(16) NOT NULL COMMENT 'User Who Sumitted',
  `job_id` int(16) NOT NULL COMMENT 'The ID of the Job Done.',
  `ticket_number` int(24) NOT NULL COMMENT 'The ticket number the job was worked on.',
  `date_submitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date of User submission',
  `date_reviewed` date NOT NULL COMMENT 'Date Admin reviewed request.',
  `requested_xp` int(16) NOT NULL COMMENT 'Requested base XP.',
  `bonus_xp` int(16) NOT NULL COMMENT 'Request for Bonus XP',
  `bonus_reason` varchar(8192) NOT NULL COMMENT 'Reason for the Bonus XP',
  `reviewed_status` tinyint(1) NOT NULL COMMENT 'Admin has reviewed and accepted or rejected request.',
  `xp_accepted` tinyint(1) NOT NULL COMMENT 'The XP requested value was accepted by the admin.',
  `final_xp_score` int(16) NOT NULL COMMENT 'The total XP earned for the job.',
  `review_feedback` varchar(8192) NOT NULL COMMENT 'Admins feedback on the job, and the work preformed.'
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='Stores all users XP values and all other related info.';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
 ADD PRIMARY KEY (`setting_id`), ADD UNIQUE KEY `setting_id` (`setting_id`,`setting_name`);

--
-- Indexes for table `cash_pool_table`
--
ALTER TABLE `cash_pool_table`
 ADD PRIMARY KEY (`cash_pool_id`), ADD UNIQUE KEY `cash_pool_id` (`cash_pool_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
 ADD PRIMARY KEY (`job_id`), ADD UNIQUE KEY `job_id` (`job_id`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
 ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `user_id` (`user_id`,`user_email`);

--
-- Indexes for table `xp_data`
--
ALTER TABLE `xp_data`
 ADD PRIMARY KEY (`xp_id`), ADD UNIQUE KEY `xp_id` (`xp_id`), ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
MODIFY `setting_id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Unique setting ID',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `cash_pool_table`
--
ALTER TABLE `cash_pool_table`
MODIFY `cash_pool_id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Unique Cash Pool',AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
MODIFY `job_id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Unique Job ID',AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
MODIFY `user_id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Unique User ID',AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `xp_data`
--
ALTER TABLE `xp_data`
MODIFY `xp_id` int(16) NOT NULL AUTO_INCREMENT COMMENT 'Unique XP ID',AUTO_INCREMENT=28;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
