-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 09:04 PM
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
-- Database: `dbsertifikat`
--

-- --------------------------------------------------------

--
-- Table structure for table `sertifikat`
--

CREATE TABLE `sertifikat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_kth` varchar(100) DEFAULT NULL,
  `nomor_sertifikat` varchar(50) DEFAULT NULL,
  `nama_file` varchar(255) DEFAULT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sertifikat`
--

INSERT INTO `sertifikat` (`id`, `user_id`, `nama_kth`, `nomor_sertifikat`, `nama_file`, `tanggal_upload`, `tanggal_update`) VALUES
(26, 1, 'Pelatihan Lestari Abadi', '2025/w-123/04/04', '68335fb890912_1748197304.jpg', '2025-05-25 18:21:44', '2025-05-26 02:21:44'),
(27, 1, 'Pelatihan Lestari Alamku', '2025/w-123/04/04', '6833621bebfac_1748197915.jpg', '2025-05-25 18:31:55', '2025-05-26 02:31:55');

-- --------------------------------------------------------

--
-- Table structure for table `userss`
--

CREATE TABLE `userss` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kth') NOT NULL,
  `aktif` enum('Y','N') DEFAULT 'Y',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userss`
--

INSERT INTO `userss` (`id`, `nama`, `username`, `password`, `role`, `aktif`, `created_at`) VALUES
(1, 'Feni', 'feni0', '$2y$10$tt91YN1voYuDX4U2PSijOu6V9C1OQ74AFXdYRu9mVcJq5Xbsmw1oa', 'kth', 'Y', '2025-05-24 05:04:04'),
(9, 'Administrator', 'admin', '$2y$10$UVV57/Oici.Yq808WJcHv.gRBDLBxo.Z2rdCAvuPwEaGG2ZMtEViu', 'admin', 'Y', '2025-05-25 11:26:18'),
(10, 'Jerome Polin Sijabat', 'BangJer', '$2y$10$RnArWaOzffvhMQm9ncsw8earjAFwjYZI/O6yqSxtqcMJ/qbZZ.GI2', 'kth', 'Y', '2025-05-25 14:14:32'),
(12, 'Administrator', 'vivid', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Y', '2025-05-25 15:16:36'),
(13, 'Vivid Ockta Hasiana Tumanggor', 'dream_v', '$2y$10$k6fEf9XqIEaT3e5VZhq8eO0h.6emspu8INQ2Az97b7uhRcd8O2U/O', 'kth', 'Y', '2025-05-25 18:27:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userss`
--
ALTER TABLE `userss`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sertifikat`
--
ALTER TABLE `sertifikat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `userss`
--
ALTER TABLE `userss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
