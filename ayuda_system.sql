-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2026 at 05:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ayuda_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `id` int(11) NOT NULL,
  `barangay_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`id`, `barangay_name`, `created_at`) VALUES
(1, 'Banucal', '2026-02-25 10:48:55'),
(2, 'Bequi-Walin', '2026-02-25 10:48:55'),
(3, 'Bugui', '2026-02-25 10:48:55'),
(4, 'Calungbuyan', '2026-02-25 10:48:55'),
(5, 'Carcarabasa', '2026-02-25 10:48:55'),
(6, 'Labut', '2026-02-25 10:48:55'),
(7, 'Poblacion Norte (Namatting)', '2026-02-25 10:48:55'),
(8, 'Poblacion Sur (Demang)', '2026-02-25 10:48:55'),
(9, 'San Vicente (Kamatliwan)', '2026-02-25 10:48:55'),
(10, 'Suysuyan', '2026-02-25 10:48:55'),
(11, 'Tay-ac', '2026-02-25 10:48:55');

-- --------------------------------------------------------

--
-- Table structure for table `households`
--

CREATE TABLE `households` (
  `id` int(11) NOT NULL,
  `household_head` varchar(100) NOT NULL,
  `family_size` int(11) NOT NULL,
  `income` decimal(10,2) NOT NULL,
  `score` int(11) NOT NULL,
  `priority` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `disaster_impact` int(11) NOT NULL,
  `income_source` varchar(50) NOT NULL,
  `assistance_history` int(11) NOT NULL,
  `barangay` varchar(100) NOT NULL DEFAULT 'Calungbuyan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `households`
--

INSERT INTO `households` (`id`, `household_head`, `family_size`, `income`, `score`, `priority`, `created_at`, `disaster_impact`, `income_source`, `assistance_history`, `barangay`) VALUES
(1, 'Juan Dela Cruz', 5, 5000.00, 85, 'High', '2026-02-24 05:22:14', 0, '', 0, 'Calungbuyan'),
(2, 'Maria Santos', 3, 8000.00, 70, 'Medium', '2026-02-24 05:22:14', 0, '', 0, 'Calungbuyan'),
(3, 'Joshua Marasigan', 8, 4000.00, 55, 'Medium', '2026-02-24 05:22:14', 0, 'Stable', 0, 'Calungbuyan'),
(4, 'Ana Lopez', 2, 12000.00, 60, 'Low', '2026-02-24 05:22:14', 0, '', 0, 'Calungbuyan'),
(6, 'Francis Marasigan', 999, 1.00, 65, 'Medium', '2026-02-24 05:31:45', 0, '', 0, 'Calungbuyan'),
(7, 'Bryan Marasigan', 90, 99999.00, 57, 'Medium', '2026-02-24 05:53:54', 2, 'Stable', 12, 'Calungbuyan'),
(10, 'Vinico Sumbad', 5, 12345.00, 48, 'Low', '2026-02-24 06:48:17', 2, 'Stable', 0, 'Calungbuyan'),
(19, 'Angelbert James Ablan', 5, 10000.00, 48, 'Low', '2026-02-25 11:39:50', 1, 'Stable', 1, 'Bugui');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('MSWDO','Barangay') NOT NULL,
  `barangay` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `barangay`) VALUES
(1, 'mswdo', '$2y$10$c7totMs2z.hF/F8Rgf.bEu8nhPqokTXXL97PeF5fCx.iKMdI.RVBa', 'MSWDO', ''),
(2, 'banucal', '$2y$10$rNGhud7sMNuffMOJf95teeCQkjXgsR//OP6ftAf7tIjQevXl2M.8S', 'Barangay', 'Banucal'),
(3, 'bequi_walin', '$2y$10$0/A92rxD3hUUr8m151Ud8.BuU4beZ2nCbXXRvEv5cGbXKLpc2umBe', 'Barangay', 'Bequi-Walin'),
(4, 'bugui', '$2y$10$V1/OjSEHKJYnCjB45Rm15eQcYbb549tYlvav0HAu9VIWwL.NhyIBC', 'Barangay', 'Bugui'),
(5, 'calungbuyan', '$2y$10$iXk0HRONWuAqWrdDt5xuWeyLgDB42BtK8gq3Npey0qi7pIdfmZvH6', 'Barangay', 'Calungbuyan'),
(6, 'carcarabasa', '$2y$10$yxiM4WxW2gSuQKOaCWXkeufpzOWM6QWm1LJcO.xW6xebsDB7On.uC', 'Barangay', 'Carcarabasa'),
(7, 'labut', '$2y$10$s7yJxUFwIEPy5pmx7RPCfuyNSbkAIR18oALa/1PFTPN5B20WKXLVe', 'Barangay', 'Labut'),
(8, 'poblacion_norte', '$2y$10$lMAJpmeSbwISSJYqLJFmKulbNmQkp1x7DArBDo3nB64V/hgaTiqt2', 'Barangay', 'Poblacion Norte (Namatting)'),
(9, 'poblacion_sur', '$2y$10$x.e/VVCR2cotiDxRsFK2puaYIjAEiNImrDwPq/zBE8IdQ8kFHjsOC', 'Barangay', 'Poblacion Sur (Demang)'),
(10, 'san_vicente', '$2y$10$Z9s2b8VbCWpZs3D8LNxRUOGnCLfKkv2yqLoRXVg.zVXzhY0S1AgO.', 'Barangay', 'San Vicente (Kamatliwan)'),
(11, 'suysuyan', '$2y$10$EZex6/RwlR1Mq4dWL8QaGOXPoGMLnxRXFAweSg6abTXwvtmAaayyS', 'Barangay', 'Suysuyan'),
(12, 'tay_ac', '$2y$10$TYcxsSvevYfQFGxcm3hSTO7XpioQTVBcpy6xsMPbFmKSIiReAt3t2', 'Barangay', 'Tay-ac');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barangay_name` (`barangay_name`);

--
-- Indexes for table `households`
--
ALTER TABLE `households`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_household_head` (`household_head`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `households`
--
ALTER TABLE `households`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
