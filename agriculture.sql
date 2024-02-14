-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2024 at 09:28 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agriculture`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_chart`
--

CREATE TABLE `activity_chart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `crop_id` int(11) NOT NULL,
  `activity_time` varchar(15) NOT NULL,
  `activity_type` tinyint(4) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activity_chart`
--

INSERT INTO `activity_chart` (`id`, `user_id`, `crop_id`, `activity_time`, `activity_type`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(1, 6, 1, 'March', 0, 1, 0, '1677606099', '2023-02-28 22:41:39'),
(3, 6, 1, 'June', 1, 1, 0, '1678010210', '2023-03-05 14:56:50'),
(4, 6, 1, 'September', 2, 1, 0, '1678010301', '2023-03-05 14:58:21'),
(5, 6, 1, 'December', 3, 1, 0, '1678010310', '2023-03-05 14:58:30');

-- --------------------------------------------------------

--
-- Table structure for table `backups`
--

CREATE TABLE `backups` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `circle_id` int(11) DEFAULT NULL,
  `backup_file_path` longtext NOT NULL,
  `backup_file_type` varchar(15) NOT NULL,
  `backup_type` tinyint(4) NOT NULL,
  `backup_method` varchar(15) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `backup_meta`
--

CREATE TABLE `backup_meta` (
  `id` int(11) NOT NULL,
  `backup_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `circles`
--

CREATE TABLE `circles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `district` varchar(225) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `circles`
--

INSERT INTO `circles` (`id`, `user_id`, `name`, `district`, `parent_id`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(1, 6, 'Takht Bahi Extension', '9', NULL, 1, 0, '1675858993', '0000-00-00 00:00:00'),
(3, 7, 'Haripur Extension', '8', NULL, 1, 0, '1676658814', '0000-00-00 00:00:00'),
(4, 6, 'Tube Well Extension', '8', NULL, 1, 0, '1677778219', '0000-00-00 00:00:00'),
(5, 6, 'Toru Extension', '9', NULL, 1, 0, '1689099887', '0000-00-00 00:00:00'),
(6, 6, 'DI Khan Extension', '11', NULL, 1, 0, '1689101559', '0000-00-00 00:00:00'),
(7, 6, 'Mardan', '9', NULL, 1, 0, '1690177289', '0000-00-00 00:00:00'),
(8, 6, 'Hathian', '9', NULL, 1, 0, '1690177325', '0000-00-00 00:00:00'),
(9, 6, 'Ghari Kapura', '9', NULL, 1, 0, '1690177350', '0000-00-00 00:00:00'),
(10, 6, 'Char Guli', '9', NULL, 1, 0, '1690177398', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `circle_meta`
--

CREATE TABLE `circle_meta` (
  `id` int(11) NOT NULL,
  `circle_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `circle_meta`
--

INSERT INTO `circle_meta` (`id`, `circle_id`, `meta_key`, `meta_value`) VALUES
(1, 1, 'circle_phone_number', '03358359438'),
(2, 1, 'circle_manager_id', ''),
(3, 3, 'circle_phone_number', '03209973190'),
(4, 3, 'circle_manager_id', '8'),
(5, 4, 'circle_phone_number', '03124613593'),
(6, 4, 'circle_manager_id', '12'),
(7, 5, 'circle_phone_number', ''),
(8, 5, 'circle_manager_id', '12'),
(9, 6, 'circle_phone_number', ''),
(10, 6, 'circle_manager_id', '12'),
(11, 7, 'circle_phone_number', ''),
(12, 7, 'circle_manager_id', '12'),
(13, 8, 'circle_phone_number', ''),
(14, 8, 'circle_manager_id', '12'),
(15, 9, 'circle_phone_number', ''),
(16, 9, 'circle_manager_id', '12'),
(17, 10, 'circle_phone_number', ''),
(18, 10, 'circle_manager_id', '12');

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

CREATE TABLE `configurations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `type` varchar(225) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `configurations`
--

INSERT INTO `configurations` (`id`, `user_id`, `name`, `type`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(4, 6, 'Wheet', 'crop', 1, 0, '1676047970', '2023-02-10 21:52:50'),
(5, 6, 'Corn', 'crop', 1, 0, '1676047975', '2023-02-10 21:52:55'),
(7, 6, 'Approved', 'class', 1, 0, '1676050159', '2023-02-10 22:29:19'),
(8, 6, 'Haripur', 'district', 1, 0, '1676111073', '2023-02-11 15:24:33'),
(9, 6, 'Mardan', 'district', 1, 0, '1676111078', '2023-02-11 15:24:38'),
(10, 6, 'SA-2023', 'variety', 1, 0, '1676461902', '2023-02-15 16:51:42'),
(11, 6, 'DI Khan', 'district', 1, 0, '1677823338', '2023-03-03 11:02:18'),
(12, 6, 'Malakand (Fsc)', 'district', 1, 0, '1677823346', '2023-03-03 11:02:26'),
(13, 6, 'Chitral Upper', 'district', 1, 0, '1689135382', '2023-07-12 09:16:22'),
(14, 6, 'Dir Upper', 'district', 1, 0, '1689135388', '2023-07-12 09:16:28'),
(15, 6, 'Dir Lower', 'district', 1, 0, '1689135393', '2023-07-12 09:16:33'),
(16, 6, 'Swat Upper', 'district', 1, 0, '1689135400', '2023-07-12 09:16:40'),
(17, 6, 'Shangla', 'district', 1, 0, '1689135408', '2023-07-12 09:16:48'),
(18, 6, 'Charsadda (ADF)', 'district', 1, 0, '1689135421', '2023-07-12 09:17:01'),
(19, 6, 'Charsadda (Fsc)', 'district', 1, 0, '1689135428', '2023-07-12 09:17:08'),
(20, 6, 'Kohistan', 'district', 1, 0, '1689135434', '2023-07-12 09:17:14'),
(21, 6, 'Tour Ghar', 'district', 1, 0, '1689135445', '2023-07-12 09:17:25'),
(22, 6, 'Buttagram', 'district', 1, 0, '1689135456', '2023-07-12 09:17:36'),
(23, 6, 'Mansehra', 'district', 1, 0, '1689135470', '2023-07-12 09:17:50'),
(24, 6, 'Sawabi', 'district', 1, 0, '1689135479', '2023-07-12 09:17:59'),
(25, 6, 'Nowshera', 'district', 1, 0, '1689135486', '2023-07-12 09:18:06');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `extension_id` int(11) DEFAULT NULL,
  `farmer_cnic` varchar(225) NOT NULL,
  `farmer_name` varchar(225) NOT NULL,
  `farmer_mobile_number` varchar(225) NOT NULL,
  `farmer_address` longtext NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `user_id`, `extension_id`, `farmer_cnic`, `farmer_name`, `farmer_mobile_number`, `farmer_address`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(1, 7, 1, '1330234228883', 'Muhammad Saifullah Asif', '03124613593', 'Mirpur', 1, 0, '1676462574', '2023-02-15 17:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `finance`
--

CREATE TABLE `finance` (
  `id` int(11) NOT NULL,
  `circle_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `finance`
--

INSERT INTO `finance` (`id`, `circle_id`, `amount`, `parent_id`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(2, 1, 1204800, NULL, 1, 0, '1677313784', '2023-02-25 13:29:44'),
(3, 3, 344000, NULL, 1, 0, '1677351888', '2023-02-26 00:04:48'),
(4, 3, 0, NULL, 1, 0, '1685523517', '2023-05-31 13:58:37'),
(5, 3, 0, NULL, 1, 0, '1685523534', '2023-05-31 13:58:54');

-- --------------------------------------------------------

--
-- Table structure for table `fscrd_report`
--

CREATE TABLE `fscrd_report` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `circle_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `report_comment` text NOT NULL,
  `report_type` int(11) NOT NULL,
  `report_status` int(11) NOT NULL DEFAULT 0,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `delete_status` int(11) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fscrd_report`
--

INSERT INTO `fscrd_report` (`id`, `user_id`, `circle_id`, `stock_id`, `report_comment`, `report_type`, `report_status`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(1, 8, 1, 23, 'Spray', 1, 1, 1, 0, '1690477731', '2023-07-27 22:08:51'),
(2, 8, 1, 32, 'Clean Stock', 2, 1, 1, 0, '1690657363', '2023-07-30 00:02:43'),
(7, 8, 1, 43, 'Accepted', 3, 1, 1, 0, '1690739517', '2023-07-30 22:51:57');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `circle_id` int(11) DEFAULT NULL,
  `notify_user_id` varchar(11) DEFAULT NULL,
  `notify_circle_id` varchar(11) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `content` longtext NOT NULL,
  `notify_type` tinyint(4) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `seen_status` tinyint(4) NOT NULL DEFAULT 0,
  `seen_time` varchar(15) NOT NULL,
  `notify_time` varchar(15) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `circle_id`, `notify_user_id`, `notify_circle_id`, `activity_id`, `content`, `notify_type`, `parent_id`, `seen_status`, `seen_time`, `notify_time`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(31, 6, NULL, '0', '', NULL, 'Be Ready For Procuring Stock Of Wheet', 0, NULL, 0, '', '1677738600', 1, 0, '1677692159', '2023-03-01 22:35:59'),
(32, 6, NULL, '0', '', NULL, 'Be Ready Financially For Procuring Stock In This Month', 0, NULL, 0, '', '1677696885', 1, 0, '1677696885', '2023-03-01 23:54:45'),
(33, NULL, NULL, NULL, '0', 1, 'Dear Procurement Officer please be ready for Procuring the stock of <b>Wheet</b> in this month. Make your finance strong and ready the packing bags', 0, NULL, 0, '', '1678121905', 1, 0, '1678121905', '2023-03-06 21:58:25');

-- --------------------------------------------------------

--
-- Table structure for table `notification_seen`
--

CREATE TABLE `notification_seen` (
  `id` int(11) NOT NULL,
  `notify_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification_seen`
--

INSERT INTO `notification_seen` (`id`, `notify_id`, `user_id`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(2, 32, 6, 1, 0, '1677697227', '2023-03-02 00:00:27'),
(3, 31, 6, 1, 0, '1677739061', '2023-03-02 11:37:41'),
(4, 31, 7, 1, 0, '1678119891', '2023-03-06 21:24:51'),
(5, 33, 8, 1, 0, '1678123297', '2023-03-06 22:21:37');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `meta_key`, `meta_value`) VALUES
(1, 'admin_email', 'muhammadsaifullahasif@gmail.com'),
(2, 'backup_cc_email', 'msaifullah7243@gmail.com'),
(3, 'activity_notifications', '{\"activity_type_0\":\"Dear Procurement Officer please be ready for Procuring the stock of <b>{{stock_type}}</b> in this month. Make your finance strong and ready the packing bags\",\"activity_type_1\":\"Dear Procurement Officer please be ready for Fumigate the stock of <b>{{stock_type}}</b> in this month. Make your finance strong and ready the fumigation equipments\",\"activity_type_2\":\"Dear Procurement Officer please be ready for Cleaning the stock of <b>{{stock_type}}</b> in this month. Make your finance strong and ready the cleaning equipments\",\"activity_type_3\":\"Dear Procurement Officer please be ready for Supply of the stock of <b>{{stock_type}}</b> in this month.\"}'),
(4, 'smtp_configuration', '{\"email_address\":\"info@wirecoder.com\",\"email_password\":\"7S@!fullah\",\"email_host\":\"wirecoder.com\"}'),
(5, 'allow_application', '{\"global_allow\":0,\"selected_extensions\":[],\"blocked_extensions\":[],\"selected_provinces\":[],\"blocked_provinces\":[]}');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `circle_id` int(11) NOT NULL,
  `stock_source` varchar(225) NOT NULL,
  `supplier_info` varchar(225) NOT NULL,
  `smp_id` int(11) DEFAULT NULL,
  `activity_season` int(11) NOT NULL,
  `lot_number` varchar(225) NOT NULL,
  `crop` varchar(225) NOT NULL,
  `variety` varchar(225) NOT NULL,
  `class` varchar(225) NOT NULL,
  `stock_qty` varchar(225) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `stock_status` int(11) NOT NULL DEFAULT 0,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `user_id`, `circle_id`, `stock_source`, `supplier_info`, `smp_id`, `activity_season`, `lot_number`, `crop`, `variety`, `class`, `stock_qty`, `parent_id`, `stock_status`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(26, 6, 1, 'other_province', 'Punjab', NULL, 6, '724385524', '1', '1', '3', '9000', NULL, 0, 1, 0, '1688627961', '2023-07-06 12:19:21'),
(27, 8, 1, 'other_circle', '3', NULL, 6, '12345', '1', '3', '4', '5000', NULL, 0, 1, 0, '1688838311', '2023-07-08 22:45:11'),
(28, 8, 1, 'other_circle', '3', NULL, 6, '54321', '1', '1', '2', '10000', NULL, 0, 1, 0, '1688838340', '2023-07-08 22:45:40'),
(29, 8, 1, 'other_circle', '4', NULL, 6, '1234', '1', '8', '2', '8000', NULL, 0, 1, 0, '1689100067', '2023-07-11 23:27:47'),
(30, 8, 1, 'from_farmer', '1330234228883', 21, 7, '23938471928374', '1', '1', '2', '1500', NULL, 1, 1, 0, '1690205474', '2023-07-24 18:31:14'),
(32, 8, 1, 'from_farmer', '1330234228883', 23, 7, '723894729', '1', '1', '3', '1969.985', NULL, 1, 1, 0, '1690657363', '2023-07-30 00:02:43');

-- --------------------------------------------------------

--
-- Table structure for table `stock_activity_season`
--

CREATE TABLE `stock_activity_season` (
  `id` int(11) NOT NULL,
  `stock_crop_id` int(11) NOT NULL,
  `season_title` varchar(225) NOT NULL,
  `season_year` varchar(225) NOT NULL,
  `season_start_date` varchar(225) NOT NULL,
  `season_end_date` varchar(225) NOT NULL,
  `season_status` int(11) NOT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `delete_status` int(11) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_activity_season`
--

INSERT INTO `stock_activity_season` (`id`, `stock_crop_id`, `season_title`, `season_year`, `season_start_date`, `season_end_date`, `season_status`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(5, 1, '2022-2023', '2022', '2022-03-01', '2023-02-28', 0, 1, 0, '1685645212', '2023-06-01 23:46:52'),
(6, 1, '2023-2024', '2023', '2023-03-01', '2024-02-29', 0, 1, 0, '1685645375', '2023-06-01 23:49:35'),
(7, 1, '2024-2025', '2023', '2023-07-01', '2024-06-30', 1, 1, 0, '1690184012', '2023-07-24 12:33:32');

-- --------------------------------------------------------

--
-- Table structure for table `stock_class`
--

CREATE TABLE `stock_class` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_name` varchar(225) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_class`
--

INSERT INTO `stock_class` (`id`, `user_id`, `class_name`, `parent_id`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(1, 7, 'Basic', NULL, 1, 0, '1677088951', '2023-02-22 23:02:31'),
(2, 7, 'Pre Basic', NULL, 1, 0, '1677088956', '2023-02-22 23:02:36'),
(3, 7, 'Certified', NULL, 1, 0, '1677088960', '2023-02-22 23:02:40'),
(4, 7, 'Approved', NULL, 1, 0, '1677088970', '2023-02-22 23:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `stock_cleaning`
--

CREATE TABLE `stock_cleaning` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `circle_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `processing_qty` varchar(15) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_cleaning`
--

INSERT INTO `stock_cleaning` (`id`, `user_id`, `circle_id`, `parent_id`, `stock_id`, `processing_qty`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(37, 8, 1, 26, 37, '9000', 1, 0, '1688670340', '2023-07-07 00:05:40'),
(38, 8, 1, 27, 38, '5000', 1, 0, '1688838760', '2023-07-08 22:52:40'),
(39, 8, 1, 28, 39, '10000', 1, 0, '1688838917', '2023-07-08 22:55:17'),
(40, 8, 1, 30, 41, '1500', 1, 0, '1690207584', '2023-07-24 19:06:24'),
(41, 8, 1, 32, 43, '1969.985', 1, 0, '1690739517', '2023-07-30 22:51:57');

-- --------------------------------------------------------

--
-- Table structure for table `stock_cleaning_meta`
--

CREATE TABLE `stock_cleaning_meta` (
  `id` int(11) NOT NULL,
  `stock_cleaning_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_cleaning_meta`
--

INSERT INTO `stock_cleaning_meta` (`id`, `stock_cleaning_id`, `meta_key`, `meta_value`) VALUES
(429, 37, 'total_stock_qty', '9000'),
(430, 37, 'processing_qty', '9000'),
(431, 37, 'grade_1', '8100'),
(432, 37, 'small_grains', '150'),
(433, 37, 'gundi', '120'),
(434, 37, 'broken', '130'),
(435, 37, 'straw', '100'),
(436, 37, 'dust', '100'),
(437, 37, 'other', '100'),
(438, 38, 'total_stock_qty', '5000'),
(439, 38, 'processing_qty', '5000'),
(440, 38, 'grade_1', '4250'),
(441, 38, 'small_grains', '150'),
(442, 38, 'gundi', '150'),
(443, 38, 'broken', '120'),
(444, 38, 'straw', '100'),
(445, 38, 'dust', '100'),
(446, 38, 'other', '30'),
(447, 39, 'total_stock_qty', '10000'),
(448, 39, 'processing_qty', '10000'),
(449, 39, 'grade_1', '8900'),
(450, 39, 'small_grains', '450'),
(451, 39, 'gundi', '400'),
(452, 39, 'broken', '100'),
(453, 39, 'straw', '50'),
(454, 39, 'dust', '50'),
(455, 39, 'other', '50'),
(456, 40, 'total_stock_qty', '1500'),
(457, 40, 'processing_qty', '1500'),
(458, 40, 'grade_1', '1300'),
(459, 40, 'small_grains', '50'),
(460, 40, 'gundi', '30'),
(461, 40, 'broken', '50'),
(462, 40, 'straw', '65'),
(463, 40, 'dust', '5'),
(464, 40, 'other', ''),
(465, 41, 'total_stock_qty', '1969.985'),
(466, 41, 'processing_qty', '1969.985'),
(467, 41, 'grade_1', '1600'),
(468, 41, 'small_grains', '100'),
(469, 41, 'gundi', '100'),
(470, 41, 'broken', '100'),
(471, 41, 'straw', ''),
(472, 41, 'dust', '69.985'),
(473, 41, 'other', '');

-- --------------------------------------------------------

--
-- Table structure for table `stock_crop`
--

CREATE TABLE `stock_crop` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `crop` varchar(225) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` int(11) NOT NULL DEFAULT 1,
  `delete_status` int(11) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_crop`
--

INSERT INTO `stock_crop` (`id`, `user_id`, `crop`, `parent_id`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(1, 7, 'Wheat', NULL, 1, 0, '1677087854', '2023-02-22 22:44:14'),
(2, 7, 'Maize', NULL, 1, 0, '1677087860', '2023-02-22 22:44:20'),
(3, 7, 'Kidney Been', NULL, 1, 0, '1677087866', '2023-02-22 22:44:26');

-- --------------------------------------------------------

--
-- Table structure for table `stock_fumigation`
--

CREATE TABLE `stock_fumigation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `circle_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `processing_qty` varchar(15) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_fumigation`
--

INSERT INTO `stock_fumigation` (`id`, `user_id`, `circle_id`, `parent_id`, `stock_id`, `processing_qty`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(31, 8, 1, 26, 37, '9000', 1, 0, '1688669940', '2023-07-06 23:59:00'),
(32, 8, 1, 28, 39, '1900', 1, 0, '1690123808', '2023-07-23 19:50:08'),
(33, 8, 1, 29, 40, '500', 1, 0, '1690174872', '2023-07-24 10:01:12'),
(34, 8, 1, 30, 41, '1500', 1, 0, '1690207465', '2023-07-24 19:04:25'),
(35, 8, 1, 30, 41, '1300', 1, 0, '1690208511', '2023-07-24 19:21:51'),
(36, 8, 1, 32, 43, '1600', 1, 0, '1691255001', '2023-08-05 22:03:21');

-- --------------------------------------------------------

--
-- Table structure for table `stock_fumigation_meta`
--

CREATE TABLE `stock_fumigation_meta` (
  `id` int(11) NOT NULL,
  `stock_fumigation_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_fumigation_meta`
--

INSERT INTO `stock_fumigation_meta` (`id`, `stock_fumigation_id`, `meta_key`, `meta_value`) VALUES
(151, 31, 'total_stock_qty', '9000'),
(152, 31, 'processing_qty', '9000'),
(153, 32, 'total_stock_qty', '1900'),
(154, 32, 'processing_qty', '1900'),
(155, 33, 'total_stock_qty', '500'),
(156, 33, 'processing_qty', '500'),
(157, 34, 'total_stock_qty', '1500'),
(158, 34, 'processing_qty', '1500'),
(159, 35, 'total_stock_qty', '1300'),
(160, 35, 'processing_qty', '1300'),
(161, 36, 'total_stock_qty', '1600'),
(162, 36, 'processing_qty', '1600');

-- --------------------------------------------------------

--
-- Table structure for table `stock_meta`
--

CREATE TABLE `stock_meta` (
  `id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_meta`
--

INSERT INTO `stock_meta` (`id`, `stock_id`, `meta_key`, `meta_value`) VALUES
(35, 26, 'stock_price', '60'),
(36, 26, 'stock_qty_price', '540000'),
(37, 27, 'stock_price', '70'),
(38, 27, 'stock_qty_price', '350000'),
(39, 28, 'stock_price', '60'),
(40, 28, 'stock_qty_price', '600000'),
(41, 29, 'stock_price', '50'),
(42, 29, 'stock_qty_price', '400000'),
(43, 30, 'stock_price', '50'),
(44, 30, 'stock_qty_price', '75000'),
(47, 32, 'stock_price', '60'),
(48, 32, 'stock_qty_price', '118199.1');

-- --------------------------------------------------------

--
-- Table structure for table `stock_price`
--

CREATE TABLE `stock_price` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stock_crop_id` int(11) NOT NULL,
  `stock_class_id` int(11) NOT NULL,
  `stock_variety_id` int(11) DEFAULT NULL,
  `purchase_price` double NOT NULL,
  `sale_price` double NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_price`
--

INSERT INTO `stock_price` (`id`, `user_id`, `stock_crop_id`, `stock_class_id`, `stock_variety_id`, `purchase_price`, `sale_price`, `parent_id`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(2, 7, 1, 1, NULL, 40, 50, NULL, 1, 0, '1677089933', '2023-02-22 23:18:53'),
(3, 7, 1, 2, NULL, 50, 60, NULL, 1, 0, '1677090061', '2023-02-22 23:21:01'),
(4, 7, 1, 3, NULL, 60, 70, NULL, 1, 0, '1677090073', '2023-02-22 23:21:13'),
(5, 7, 1, 4, NULL, 70, 80, NULL, 1, 0, '1677090083', '2023-02-22 23:21:23'),
(6, 7, 2, 1, NULL, 1000, 1200, NULL, 1, 1, '1677430094', '2023-02-26 21:48:14'),
(7, 7, 2, 2, NULL, 1500, 1700, NULL, 1, 1, '1677430107', '2023-02-26 21:48:27'),
(8, 7, 2, 3, NULL, 2000, 2200, NULL, 1, 1, '1677430118', '2023-02-26 21:48:38'),
(9, 7, 2, 4, NULL, 2500, 2700, NULL, 1, 1, '1677430130', '2023-02-26 21:48:50'),
(10, 7, 3, 1, NULL, 3000, 4000, NULL, 1, 1, '1677430156', '2023-02-26 21:49:16'),
(11, 7, 3, 2, NULL, 3500, 4500, NULL, 1, 1, '1677430168', '2023-02-26 21:49:28'),
(12, 7, 3, 3, NULL, 4000, 5000, NULL, 1, 1, '1677430175', '2023-02-26 21:49:35'),
(13, 7, 3, 4, NULL, 4500, 5500, NULL, 1, 1, '1677430210', '2023-02-26 21:50:10');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transactions`
--

CREATE TABLE `stock_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `circle_id` int(11) NOT NULL,
  `stock_qty` varchar(15) NOT NULL,
  `stock_status` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `supply_status` int(11) NOT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_transactions`
--

INSERT INTO `stock_transactions` (`id`, `user_id`, `circle_id`, `stock_qty`, `stock_status`, `class`, `stock_id`, `parent_id`, `supply_status`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(37, 6, 1, '2000', 2, 3, 26, NULL, 0, 1, 0, '1688627961', '2023-07-06 12:19:21'),
(38, 8, 1, '0', 2, 4, 27, NULL, 0, 2, 0, '1688838311', '2023-07-08 22:45:11'),
(39, 8, 1, '300', 2, 2, 28, NULL, 0, 1, 0, '1688838340', '2023-07-08 22:45:40'),
(40, 8, 1, '500', 0, 2, 29, NULL, 0, 3, 0, '1689100067', '2023-07-11 23:27:47'),
(41, 8, 1, '1000', 2, 2, 30, NULL, 0, 1, 0, '1690205474', '2023-07-24 18:31:14'),
(43, 8, 1, '1600', 2, 3, 32, NULL, 1, 3, 0, '1690657363', '2023-07-30 00:02:43');

-- --------------------------------------------------------

--
-- Table structure for table `stock_variety`
--

CREATE TABLE `stock_variety` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stock_crop_id` int(11) NOT NULL,
  `variety` varchar(225) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stock_variety`
--

INSERT INTO `stock_variety` (`id`, `user_id`, `stock_crop_id`, `variety`, `parent_id`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(1, 7, 1, 'PS-2019', NULL, 1, 0, '1677088479', '2023-02-22 22:54:39'),
(2, 7, 2, 'SA-7243', NULL, 1, 0, '1677088488', '2023-02-22 22:54:48'),
(3, 6, 1, 'Khaista-2017', NULL, 1, 0, '1688802081', '2023-07-08 12:41:21'),
(4, 6, 1, 'Wadan-2017', NULL, 1, 0, '1688802089', '2023-07-08 12:41:29'),
(5, 6, 1, 'Paseena - 2017', NULL, 1, 0, '1688802102', '2023-07-08 12:41:42'),
(6, 6, 1, 'PS - 2015', NULL, 1, 0, '1688802109', '2023-07-08 12:41:49'),
(7, 6, 1, 'Akbar - 2019', NULL, 1, 0, '1688802116', '2023-07-08 12:41:56'),
(8, 6, 1, 'Gulzar - 2019', NULL, 1, 0, '1688802126', '2023-07-08 12:42:06'),
(9, 6, 1, 'Sawabi - 1', NULL, 1, 0, '1688802134', '2023-07-08 12:42:14');

-- --------------------------------------------------------

--
-- Table structure for table `supply`
--

CREATE TABLE `supply` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `circle_id` int(11) NOT NULL,
  `receive_source` varchar(225) NOT NULL,
  `receiver_detail` varchar(225) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `stock_qty` int(11) NOT NULL,
  `activity_season` int(11) NOT NULL,
  `receiver_info` varchar(225) NOT NULL,
  `receiver_time_created` varchar(15) NOT NULL,
  `receive_status` int(11) NOT NULL,
  `smp_status` int(11) NOT NULL DEFAULT 0,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supply`
--

INSERT INTO `supply` (`id`, `user_id`, `circle_id`, `receive_source`, `receiver_detail`, `parent_id`, `stock_id`, `stock_qty`, `activity_season`, `receiver_info`, `receiver_time_created`, `receive_status`, `smp_status`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(14, 8, 1, 'other_extension', '3', 26, 37, 4100, 7, '3', '1688755169', 1, 0, 1, 0, '1688755169', '2023-07-07 23:39:29'),
(15, 8, 1, 'other_extension', '5', 27, 38, 4000, 7, '5', '1689099985', 1, 0, 1, 0, '1689099985', '2023-07-11 23:26:25'),
(16, 8, 1, 'other_extension', '5', 29, 40, 5000, 7, '5', '1689100102', 1, 0, 1, 0, '1689100102', '2023-07-11 23:28:22'),
(17, 8, 1, 'other_extension', '4', 28, 39, 7000, 7, '4', '1689100879', 1, 0, 1, 0, '1689100879', '2023-07-11 23:41:19'),
(18, 8, 1, 'other_extension', '3', 29, 40, 2500, 7, '3', '1689100943', 1, 0, 1, 0, '1689100943', '2023-07-11 23:42:23'),
(19, 8, 1, 'other_extension', '6', 26, 37, 2000, 7, '6', '1689101606', 1, 0, 1, 0, '1689101606', '2023-07-11 23:53:26'),
(20, 8, 1, 'to_farmer', '1330234228883', 28, 39, 900, 7, '1330234228883', '1690181816', 1, 0, 1, 0, '1690181816', '2023-07-24 11:56:56'),
(21, 8, 1, 'to_farmer', '1330234228883', 28, 39, 700, 7, '1330234228883', '1690202977', 1, 0, 1, 0, '1690202977', '2023-07-24 17:49:37'),
(22, 8, 1, 'to_farmer', '1330234228883', 27, 38, 250, 7, '1330234228883', '1690220334', 1, 0, 1, 0, '1690220334', '2023-07-24 22:38:54'),
(23, 8, 1, 'to_farmer', '1330234228883', 30, 41, 300, 7, '1330234228883', '1690477731', 1, 1, 1, 0, '1690477731', '2023-07-27 22:08:51');

-- --------------------------------------------------------

--
-- Table structure for table `supply_meta`
--

CREATE TABLE `supply_meta` (
  `id` int(11) NOT NULL,
  `supply_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supply_meta`
--

INSERT INTO `supply_meta` (`id`, `supply_id`, `meta_key`, `meta_value`) VALUES
(79, 8, 'stock_lot_number', '1234'),
(80, 8, 'stock_type', '1'),
(81, 8, 'stock_variety', '1'),
(82, 8, 'stock_class', '1'),
(83, 8, 'stock_status', '0'),
(84, 8, 'stock_price', '$'),
(85, 8, 'driver_cnic', '1330234228883'),
(86, 8, 'driver_name', 'Muhammad Saifullah Asif'),
(87, 8, 'driver_mobile_number', '03124613593'),
(88, 8, 'driver_address', 'Mirpur'),
(89, 8, 'vehicle_number', 'SA-7243'),
(90, 8, 'labour_cost', '1000'),
(91, 8, 'packing_bag_cost', '0'),
(92, 8, 'miscellaneous_cost', '0'),
(126, 12, 'stock_lot_number', '1234'),
(127, 12, 'stock_type', '1'),
(128, 12, 'stock_variety', '1'),
(129, 12, 'stock_class', '1'),
(130, 12, 'stock_status', '0'),
(131, 12, 'stock_price', '2500'),
(132, 12, 'driver_cnic', '1330234228883'),
(133, 12, 'driver_name', 'Muhammad Saifullah Asif'),
(134, 12, 'driver_mobile_number', '03124613593'),
(135, 12, 'driver_address', 'Haripur'),
(136, 12, 'vehicle_number', 'SA-7243'),
(137, 12, 'labour_cost', '3000'),
(138, 12, 'packing_bag_cost', '1500'),
(139, 12, 'miscellaneous_cost', '0'),
(151, 14, 'stock_lot_number', '724385524'),
(152, 14, 'stock_type', '1'),
(153, 14, 'stock_variety', '1'),
(154, 14, 'stock_class', '3'),
(155, 14, 'stock_status', '2'),
(156, 14, 'stock_price', '70'),
(157, 14, 'driver_cnic', '1330234228883'),
(158, 14, 'driver_name', 'Muhammad Saifullah Asif'),
(159, 14, 'driver_mobile_number', '03124613593'),
(160, 14, 'driver_address', 'Mirpur'),
(161, 14, 'vehicle_number', 'SA-7243'),
(162, 15, 'stock_lot_number', '12345'),
(163, 15, 'stock_type', '1'),
(164, 15, 'stock_variety', '3'),
(165, 15, 'stock_class', '4'),
(166, 15, 'stock_status', '2'),
(167, 15, 'stock_price', '80'),
(168, 15, 'driver_cnic', '1330234228883'),
(169, 15, 'driver_name', 'Muhammad Saifullah Asif'),
(170, 15, 'driver_mobile_number', '03124613593'),
(171, 15, 'driver_address', 'Mirpur'),
(172, 15, 'vehicle_number', 'SA-7243'),
(173, 16, 'stock_lot_number', '1234'),
(174, 16, 'stock_type', '1'),
(175, 16, 'stock_variety', '8'),
(176, 16, 'stock_class', '2'),
(177, 16, 'stock_status', '0'),
(178, 16, 'stock_price', '60'),
(179, 16, 'driver_cnic', '1330234228883'),
(180, 16, 'driver_name', 'Muhammad Saifullah Asif'),
(181, 16, 'driver_mobile_number', '03124613593'),
(182, 16, 'driver_address', 'Mirpur'),
(183, 16, 'vehicle_number', 'SA-7243'),
(184, 17, 'stock_lot_number', '54321'),
(185, 17, 'stock_type', '1'),
(186, 17, 'stock_variety', '1'),
(187, 17, 'stock_class', '2'),
(188, 17, 'stock_status', '2'),
(189, 17, 'stock_price', '60'),
(190, 17, 'driver_cnic', '1330234228883'),
(191, 17, 'driver_name', 'Muhammad Saifullah Asif'),
(192, 17, 'driver_mobile_number', '03124613593'),
(193, 17, 'driver_address', 'Mirpur'),
(194, 17, 'vehicle_number', 'SA-7243'),
(195, 18, 'stock_lot_number', '1234'),
(196, 18, 'stock_type', '1'),
(197, 18, 'stock_variety', '8'),
(198, 18, 'stock_class', '2'),
(199, 18, 'stock_status', '0'),
(200, 18, 'stock_price', '60'),
(201, 18, 'driver_cnic', '1330234228883'),
(202, 18, 'driver_name', 'Muhammad Saifullah Asif'),
(203, 18, 'driver_mobile_number', '03124613593'),
(204, 18, 'driver_address', 'Mirpur'),
(205, 18, 'vehicle_number', 'SA-7243'),
(206, 19, 'stock_lot_number', '724385524'),
(207, 19, 'stock_type', '1'),
(208, 19, 'stock_variety', '1'),
(209, 19, 'stock_class', '3'),
(210, 19, 'stock_status', '2'),
(211, 19, 'stock_price', '70'),
(212, 19, 'driver_cnic', '1330234228883'),
(213, 19, 'driver_name', 'Muhammad Saifullah Asif'),
(214, 19, 'driver_mobile_number', '03124613593'),
(215, 19, 'driver_address', 'Mirpur'),
(216, 19, 'vehicle_number', 'SA-7243'),
(217, 20, 'area', '200'),
(218, 20, 'stock_lot_number', '54321'),
(219, 20, 'stock_type', '1'),
(220, 20, 'stock_variety', '1'),
(221, 20, 'stock_class', '2'),
(222, 20, 'stock_status', '2'),
(223, 20, 'stock_sale_price', ''),
(224, 20, 'fsrd_comments', 'SPRAY'),
(225, 21, 'area', ''),
(226, 21, 'stock_lot_number', '54321'),
(227, 21, 'stock_type', '1'),
(228, 21, 'stock_variety', '1'),
(229, 21, 'stock_class', '2'),
(230, 21, 'stock_status', '2'),
(231, 21, 'stock_sale_price', '42000'),
(232, 22, 'area', '500'),
(233, 22, 'stock_lot_number', '12345'),
(234, 22, 'stock_type', '1'),
(235, 22, 'stock_variety', '3'),
(236, 22, 'stock_class', '4'),
(237, 22, 'stock_status', '2'),
(238, 22, 'stock_sale_price', '20000'),
(239, 21, 'fsrd_comments', ''),
(240, 22, 'fsrd_comments', 'Spray'),
(241, 23, 'area', '300'),
(242, 23, 'area_longitude', ''),
(243, 23, 'area_latitude', ''),
(244, 23, 'stock_lot_number', '23938471928374'),
(245, 23, 'stock_crop', '1'),
(246, 23, 'stock_variety', '1'),
(247, 23, 'stock_class', '2'),
(248, 23, 'stock_status', '2'),
(249, 23, 'stock_sale_price', '18000');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `circle_id` int(11) NOT NULL,
  `finance_id` int(11) NOT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `ref_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `type` int(11) NOT NULL,
  `trans_flow` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_meta`
--

CREATE TABLE `transaction_meta` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_login` varchar(225) NOT NULL,
  `user_pass` varchar(225) NOT NULL,
  `display_name` varchar(225) NOT NULL,
  `circle_id` int(11) DEFAULT NULL,
  `district` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 0,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `active_status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_status` tinyint(4) NOT NULL DEFAULT 0,
  `time_created` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_login`, `user_pass`, `display_name`, `circle_id`, `district`, `parent_id`, `role`, `type`, `active_status`, `delete_status`, `time_created`, `date_created`) VALUES
(6, 'muhammadsaifullahasif@gmail.com', '$2y$10$IjwtuKVJxysJkW0gCpr3zuqDAZ8ik1UvbR2R17u1pavVJJAVvIGtW', 'Muhammad Saifullah Asif', NULL, NULL, NULL, 0, 0, 1, 0, '1675775721', '2023-02-07 18:15:21'),
(7, 'msaifullah7243@gmail.com', '$2y$10$IjwtuKVJxysJkW0gCpr3zuqDAZ8ik1UvbR2R17u1pavVJJAVvIGtW', 'Muhammad Saifullah', 0, 9, NULL, 0, 1, 1, 0, '1675853901', '2023-02-08 15:58:22'),
(8, 'asfand5840@gmail.com', '$2y$10$IjwtuKVJxysJkW0gCpr3zuqDAZ8ik1UvbR2R17u1pavVJJAVvIGtW', 'Asfandyar Ahmed Awan', 1, 9, NULL, 1, 0, 1, 0, '1676658730', '2023-02-17 23:32:10'),
(12, 'sheryar@gmail.com', '$2y$10$IjwtuKVJxysJkW0gCpr3zuqDAZ8ik1UvbR2R17u1pavVJJAVvIGtW', 'Sheryar Ahmed Awan', 10, 9, NULL, 1, 0, 1, 0, '1677781239', '2023-03-02 23:20:39'),
(14, 'abcdef@example.com', '$2y$10$IjwtuKVJxysJkW0gCpr3zuqDAZ8ik1UvbR2R17u1pavVJJAVvIGtW', 'ABC DEF', 7, 9, NULL, 1, 0, 1, 0, '1690179400', '2023-07-24 11:16:40');

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

CREATE TABLE `user_meta` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `meta_key` varchar(225) NOT NULL,
  `meta_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_meta`
--

INSERT INTO `user_meta` (`id`, `user_id`, `meta_key`, `meta_value`) VALUES
(41, 6, 'first_name', 'Muhammad'),
(42, 6, 'last_name', 'Saifullah Asif'),
(43, 6, 'phone_number', '03124613593'),
(44, 6, 'address', 'Mirpur'),
(45, 6, 'email_address', 'muhammadsaifullahasif@gmail.com'),
(46, 6, 'profile_image', '{\"image_name\":\"1675775721_1670426569615.jpg\",\"image_path\":\"http://localhost:8080/agriculture/media/users/1675775721_1670426569615.jpg\",\"image_size\":965791,\"image_type\":\"jpg\"}'),
(47, 6, 'user_role', ''),
(48, 6, 'session_tokens', ''),
(49, 7, 'first_name', 'Muhammad'),
(50, 7, 'last_name', 'Saifullah'),
(51, 7, 'phone_number', '03358359438'),
(52, 7, 'address', 'Mirpur'),
(53, 7, 'email_address', 'msaifullah7243@gmail.com'),
(54, 7, 'profile_image', '{\"image_name\":\"1675853901_1647847675944.jpg\",\"image_path\":\"http://localhost:8080/agriculture/media/users/1675853901_1647847675944.jpg\",\"image_size\":313549,\"image_type\":\"jpg\"}'),
(55, 7, 'user_role', ''),
(56, 7, 'session_tokens', ''),
(59, 8, 'first_name', 'Asfandyar'),
(60, 8, 'last_name', 'Ahmed Awan'),
(61, 8, 'phone_number', '03209973190'),
(62, 8, 'address', 'Haripur'),
(63, 8, 'email_address', 'asfand5840@gmail.com'),
(64, 8, 'profile_image', '{\"image_name\":\"\",\"image_path\":\"\",\"image_size\":\"\",\"image_type\":\"\"}'),
(65, 8, 'user_role', ''),
(66, 8, 'session_tokens', ''),
(91, 12, 'first_name', 'Sheryar'),
(92, 12, 'last_name', 'Ahmed Awan'),
(93, 12, 'phone_number', '03462209029'),
(94, 12, 'address', 'Haripur'),
(95, 12, 'email_address', 'sheryar@gmail.com'),
(96, 12, 'profile_image', '{\"image_name\":\"\",\"image_path\":\"\",\"image_size\":\"\",\"image_type\":\"\"}'),
(97, 12, 'user_role', ''),
(98, 12, 'session_tokens', ''),
(107, 14, 'first_name', 'ABC'),
(108, 14, 'last_name', 'DEF'),
(109, 14, 'phone_number', '03209973190'),
(110, 14, 'address', ''),
(111, 14, 'email_address', 'abcdef@example.com'),
(112, 14, 'profile_image', '{\"image_name\":\"\",\"image_path\":\"\",\"image_size\":\"\",\"image_type\":\"\"}'),
(113, 14, 'user_role', ''),
(114, 14, 'session_tokens', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_chart`
--
ALTER TABLE `activity_chart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `backups`
--
ALTER TABLE `backups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `backup_meta`
--
ALTER TABLE `backup_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `circles`
--
ALTER TABLE `circles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `circle_meta`
--
ALTER TABLE `circle_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `finance`
--
ALTER TABLE `finance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fscrd_report`
--
ALTER TABLE `fscrd_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_seen`
--
ALTER TABLE `notification_seen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_activity_season`
--
ALTER TABLE `stock_activity_season`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_class`
--
ALTER TABLE `stock_class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_cleaning`
--
ALTER TABLE `stock_cleaning`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_cleaning_meta`
--
ALTER TABLE `stock_cleaning_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_crop`
--
ALTER TABLE `stock_crop`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_fumigation`
--
ALTER TABLE `stock_fumigation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_fumigation_meta`
--
ALTER TABLE `stock_fumigation_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_meta`
--
ALTER TABLE `stock_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_price`
--
ALTER TABLE `stock_price`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_variety`
--
ALTER TABLE `stock_variety`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supply`
--
ALTER TABLE `supply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supply_meta`
--
ALTER TABLE `supply_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_meta`
--
ALTER TABLE `transaction_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_chart`
--
ALTER TABLE `activity_chart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `backups`
--
ALTER TABLE `backups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `backup_meta`
--
ALTER TABLE `backup_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `circles`
--
ALTER TABLE `circles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `circle_meta`
--
ALTER TABLE `circle_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `finance`
--
ALTER TABLE `finance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fscrd_report`
--
ALTER TABLE `fscrd_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `notification_seen`
--
ALTER TABLE `notification_seen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `stock_activity_season`
--
ALTER TABLE `stock_activity_season`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stock_class`
--
ALTER TABLE `stock_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_cleaning`
--
ALTER TABLE `stock_cleaning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `stock_cleaning_meta`
--
ALTER TABLE `stock_cleaning_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=474;

--
-- AUTO_INCREMENT for table `stock_crop`
--
ALTER TABLE `stock_crop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock_fumigation`
--
ALTER TABLE `stock_fumigation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `stock_fumigation_meta`
--
ALTER TABLE `stock_fumigation_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `stock_meta`
--
ALTER TABLE `stock_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `stock_price`
--
ALTER TABLE `stock_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `stock_variety`
--
ALTER TABLE `stock_variety`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `supply`
--
ALTER TABLE `supply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `supply_meta`
--
ALTER TABLE `supply_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `transaction_meta`
--
ALTER TABLE `transaction_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_meta`
--
ALTER TABLE `user_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
