-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2018 at 08:31 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lqc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audits_completed_lqc`
--

CREATE TABLE `audits_completed_lqc` (
  `id` int(11) NOT NULL,
  `lot_no` varchar(50) NOT NULL,
  `audit_id` int(11) NOT NULL,
  `audit_date` date NOT NULL,
  `auditer_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `part_no` varchar(150) NOT NULL,
  `part_name` varchar(150) NOT NULL,
  `prod_lot_qty` mediumint(8) NOT NULL,
  `tot_count` smallint(4) NOT NULL,
  `ok_count` smallint(4) NOT NULL,
  `ng_count` smallint(4) NOT NULL,
  `sent_to_gerp` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audits_lqc`
--

CREATE TABLE `audits_lqc` (
  `id` int(11) NOT NULL,
  `lot_no` varchar(50) NOT NULL,
  `audit_date` date NOT NULL,
  `auditer_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `part_no` varchar(150) NOT NULL,
  `part_name` varchar(150) NOT NULL,
  `prod_lot_qty` mediumint(8) NOT NULL,
  `remaining_prod_lot_qty` int(20) DEFAULT NULL,
  `state` enum('registered','aborted','started','finished','completed','paired','skiped','removed') NOT NULL DEFAULT 'registered',
  `on_hold` tinyint(1) NOT NULL DEFAULT '0',
  `register_datetime` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `defect_codes`
--

CREATE TABLE `defect_codes` (
  `id` int(20) NOT NULL,
  `product_id` int(20) DEFAULT NULL,
  `part_id` int(20) DEFAULT NULL,
  `supplier_id` int(20) DEFAULT NULL,
  `defect_description` varchar(200) DEFAULT NULL,
  `defect_description_detail` varchar(200) DEFAULT NULL,
  `is_deleted` int(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lqc_audit_defect_code`
--

CREATE TABLE `lqc_audit_defect_code` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `defect_occured_ids` varchar(50) DEFAULT NULL,
  `audit_id` int(11) NOT NULL,
  `result` enum('OK','NG','NA') DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `product_id` int(20) DEFAULT NULL,
  `defect_occured` text,
  `remark` varchar(500) DEFAULT NULL,
  `serial_no` varchar(50) DEFAULT NULL,
  `retest_remark` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `page_hits`
--

CREATE TABLE `page_hits` (
  `id` int(11) NOT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `hit_count` int(30) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `part_id`
--

CREATE TABLE `part_id` (
  `id` int(10) NOT NULL,
  `product_id` int(10) DEFAULT NULL,
  `part_id` int(10) DEFAULT NULL,
  `part_no` varchar(20) DEFAULT NULL,
  `lqc_planned_lot` int(10) DEFAULT NULL,
  `lqc_remaining_lot` int(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `production_plans`
--

CREATE TABLE `production_plans` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `plan_date` date NOT NULL,
  `lot_size` mediumint(8) NOT NULL,
  `plan_status` enum('started','completed','hold','skiped') DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  `part_id` int(20) DEFAULT NULL,
  `supplier_id` int(20) DEFAULT NULL,
  `part_no` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qr_code_print`
--

CREATE TABLE `qr_code_print` (
  `id` int(20) NOT NULL,
  `product_id` int(20) DEFAULT NULL,
  `supplier_id` int(20) DEFAULT NULL,
  `qr_code_qty` int(20) DEFAULT NULL,
  `part_id` int(20) DEFAULT NULL,
  `qr_codes` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qr_print_history`
--

CREATE TABLE `qr_print_history` (
  `id` int(11) NOT NULL,
  `product_id` int(20) DEFAULT NULL,
  `part_id` int(10) DEFAULT NULL,
  `supplier_id` int(10) DEFAULT NULL,
  `print_date` datetime DEFAULT NULL,
  `reprint_remark` varchar(500) DEFAULT NULL,
  `print_remark` varchar(500) DEFAULT NULL,
  `printed_by` varchar(100) DEFAULT NULL,
  `qr_code` varchar(200) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `remaining_lot_history`
--

CREATE TABLE `remaining_lot_history` (
  `id` int(10) NOT NULL,
  `product_id` int(10) DEFAULT NULL,
  `part_id` int(10) DEFAULT NULL,
  `part_no` varchar(50) DEFAULT NULL,
  `lqc_remaining_lot` int(10) DEFAULT NULL,
  `lqc_planned_lot` int(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `product_id` varchar(50) DEFAULT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `user_type` enum('Admin','LG Inspector') NOT NULL,
  `reset_token` varchar(50) DEFAULT NULL,
  `reset_request_time` datetime DEFAULT NULL,
  `checklist_checked` date NOT NULL,
  `created` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `login_count` int(50) DEFAULT '0',
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audits_completed_lqc`
--
ALTER TABLE `audits_completed_lqc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_date` (`audit_date`,`auditer_id`,`part_no`,`part_name`);

--
-- Indexes for table `audits_lqc`
--
ALTER TABLE `audits_lqc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_date` (`audit_date`,`product_id`);

--
-- Indexes for table `defect_codes`
--
ALTER TABLE `defect_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lqc_audit_defect_code`
--
ALTER TABLE `lqc_audit_defect_code`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_id` (`audit_id`);

--
-- Indexes for table `page_hits`
--
ALTER TABLE `page_hits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_plans`
--
ALTER TABLE `production_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qr_code_print`
--
ALTER TABLE `qr_code_print`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qr_print_history`
--
ALTER TABLE `qr_print_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remaining_lot_history`
--
ALTER TABLE `remaining_lot_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audits_completed_lqc`
--
ALTER TABLE `audits_completed_lqc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `audits_lqc`
--
ALTER TABLE `audits_lqc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `defect_codes`
--
ALTER TABLE `defect_codes`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `lqc_audit_defect_code`
--
ALTER TABLE `lqc_audit_defect_code`
  MODIFY `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `page_hits`
--
ALTER TABLE `page_hits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `production_plans`
--
ALTER TABLE `production_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `qr_code_print`
--
ALTER TABLE `qr_code_print`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `qr_print_history`
--
ALTER TABLE `qr_print_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `remaining_lot_history`
--
ALTER TABLE `remaining_lot_history`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
