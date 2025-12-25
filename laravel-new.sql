-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 05:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `casbons`
--

CREATE TABLE `casbons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pegawai_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `casbons`
--

INSERT INTO `casbons` (`id`, `pegawai_id`, `jumlah`, `keterangan`, `tanggal`, `created_at`, `updated_at`) VALUES
(5, 19, 100, 'beli kuota', '2025-11-26', '2025-11-24 01:48:52', '2025-11-24 01:48:52'),
(6, 19, 300, 'pulsa', '2025-11-07', '2025-11-24 02:05:53', '2025-11-24 02:05:53'),
(7, 19, 5000, 'HYNG DAHAR', '2025-11-07', '2025-11-24 02:06:28', '2025-11-24 02:06:28'),
(8, 19, 60000, 'MUDIK', '2025-11-14', '2025-11-24 02:06:53', '2025-11-24 02:06:53'),
(9, 20, 1000000, 'beli kuota', '2025-11-21', '2025-11-25 19:55:41', '2025-11-25 19:55:41'),
(10, 21, 36000, 'MUDIK', '2025-11-27', '2025-11-27 00:00:20', '2025-11-27 00:00:20'),
(11, 22, 28000, 'MUDIK', '2025-11-27', '2025-11-27 00:16:40', '2025-11-27 00:16:40'),
(12, 22, 20000, 'meli beas', '2025-12-07', '2025-12-07 22:13:25', '2025-12-07 22:13:25');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `harga_jenis_pekerjaan`
--

CREATE TABLE `harga_jenis_pekerjaan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `harga_setting` int(11) NOT NULL,
  `harga_print` int(11) NOT NULL,
  `harga_press` int(11) NOT NULL,
  `harga_cutting` int(11) NOT NULL,
  `harga_jahit` int(11) NOT NULL,
  `harga_finishing` int(11) NOT NULL,
  `harga_packing` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `harga_jenis_pekerjaan`
--

INSERT INTO `harga_jenis_pekerjaan` (`id`, `harga_setting`, `harga_print`, `harga_press`, `harga_cutting`, `harga_jahit`, `harga_finishing`, `harga_packing`, `created_at`, `updated_at`) VALUES
(1, 50000, 1000, 300, 600, 5000, 600, 1000, '2025-11-28 00:15:30', '2025-12-01 18:29:23');

-- --------------------------------------------------------

--
-- Table structure for table `harga_jobs`
--

CREATE TABLE `harga_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_bahan`
--

CREATE TABLE `jenis_bahan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_kategori_jenis_order` bigint(20) UNSIGNED DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_bahan`
--

INSERT INTO `jenis_bahan` (`id`, `id_kategori_jenis_order`, `nama`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 1, 'BENZEMA', 'jenis_bahan/XCwRefEsQqfOjEv6BJbKemBpIi3uHUpASy8RVmwg.jpg', '2025-11-28 23:17:52', '2025-12-04 09:37:30'),
(2, NULL, 'Waffle', 'jenis_bahan/Ml3vHdSTeoFChZicr8m4U5NFKe8F9m7cGPZrAiZZ.jpg', '2025-11-28 23:26:32', '2025-11-28 23:26:32'),
(3, NULL, 'Brazil', 'jenis_bahan/2CAQnPD6eoO2jnO3A4ywQvuveNEDg1xoaEADLpBF.jpg', '2025-11-28 23:27:01', '2025-11-28 23:27:01'),
(4, NULL, 'Milano', 'jenis_bahan/ZRH0sbLcUaaQ4YqcFHnbx80S0ejkATeBhLS0egCK.jpg', '2025-11-28 23:27:29', '2025-11-28 23:27:29');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_jahitan`
--

CREATE TABLE `jenis_jahitan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_jahitan`
--

INSERT INTO `jenis_jahitan` (`id`, `nama`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Pundak Rante', 'jenis_bahan/4xLubm88qGtJV1hkJTXJ9zNt3XKI291mL5HYBK3d.jpg', '2025-11-28 23:34:08', '2025-11-30 23:55:50'),
(2, 'Samping Flatlock', 'jenis_bahan/xbcOsr7ZXXqM5h5LoTLgeaJrdCEiDMLYzftS4xm0.jpg', '2025-11-28 23:34:43', '2025-11-28 23:34:43'),
(3, 'Bawah lengkung', 'jenis_bahan/SMDKuzr8pylxKTFf1OyxZA5zG5cSeSKWs2fu5WUp.jpg', '2025-11-28 23:35:11', '2025-12-01 18:11:32'),
(4, 'bawah lurus', 'jenis_bahan/V4Hezlsnj44ViiXivSQpdXbLPiFRxwQREzT0BOtX.jpg', '2025-11-28 23:35:25', '2025-12-01 18:11:11');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_kerah`
--

CREATE TABLE `jenis_kerah` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_kerah`
--

INSERT INTO `jenis_kerah` (`id`, `nama`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'V-Neck', 'jenis_bahan/Bwm7EBGa2TknKvLbvSXJOI4dgWYBzzr8MiEjC9zS.jpg', '2025-11-28 23:32:05', '2025-11-28 23:32:05'),
(2, 'O-Neck', 'jenis_bahan/BDDcqWtJgaXyP6m95eMesnxTKP9XMqzUtWlIPz85.jpg', '2025-11-28 23:32:31', '2025-11-28 23:32:31'),
(3, 'V-DOUBLE', 'jenis_bahan/sGgvhMEXe1VMiLUU8UZJ6O4ADbat2ONnZyACtbdW.jpg', '2025-11-28 23:33:01', '2025-11-28 23:33:01'),
(4, 'Polo', 'jenis_bahan/SlLr3S7ZlEDNYoBn1AdPE2Utzocg1eXzyFVcCMX5.jpg', '2025-11-28 23:33:22', '2025-11-28 23:33:22');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_order`
--

CREATE TABLE `jenis_order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_jenis` varchar(255) NOT NULL,
  `id_kategori_jenis_order` bigint(20) UNSIGNED DEFAULT NULL,
  `nilai` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_order`
--

INSERT INTO `jenis_order` (`id`, `nama_jenis`, `id_kategori_jenis_order`, `nilai`, `created_at`, `updated_at`) VALUES
(14, 'Panjang', 1, 4, '2025-11-26 22:45:38', '2025-11-28 11:06:50'),
(17, 'Hoodie', 1, 5, '2025-11-26 22:46:24', '2025-11-26 22:46:24'),
(18, 'Lekbong', 1, 2, '2025-11-26 22:46:35', '2025-11-26 22:46:35'),
(19, 'Sabelah', 20, 1, '2025-11-26 22:46:51', '2025-11-26 22:46:51'),
(20, 'Full Print', 20, 4, '2025-11-26 22:47:01', '2025-11-26 22:47:01'),
(21, 'yamaha', 1, 1, '2025-11-28 20:05:12', '2025-11-28 20:05:12'),
(23, 'kolor', 20, 2, '2025-12-01 19:00:49', '2025-12-01 19:00:49'),
(24, 'trening', 20, 4, '2025-12-01 19:01:14', '2025-12-01 19:01:14'),
(26, 'pendek', 1, 3, '2025-12-03 22:17:22', '2025-12-03 22:17:22');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pola`
--

CREATE TABLE `jenis_pola` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_pola`
--

INSERT INTO `jenis_pola` (`id`, `nama`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'Reguler', 'jenis_bahan/MaOxzwUyU5w0aKxfEvQpUwbhBjA0GSmqVPHOTLmR.jpg', '2025-11-28 23:28:42', '2025-11-28 23:28:42'),
(2, 'Reglan', 'jenis_bahan/KpavU58B1i8XjqD9hKRZW4shwbPUHHQveEhq18bF.jpg', '2025-11-28 23:30:37', '2025-11-28 23:30:37'),
(3, 'Sambungan Pinggir', 'jenis_bahan/g4gMWVoROakIeR7sPyJ8oOli8mePQgmOp5FLUUIP.jpg', '2025-11-28 23:31:24', '2025-11-28 23:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_spek`
--

CREATE TABLE `jenis_spek` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_jenis_spek` varchar(255) NOT NULL,
  `id_kategori_jenis_order` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_spek`
--

INSERT INTO `jenis_spek` (`id`, `nama_jenis_spek`, `id_kategori_jenis_order`, `created_at`, `updated_at`) VALUES
(1, 'Jenis Bahan', 1, '2025-12-04 10:45:03', '2025-12-04 10:45:03'),
(2, 'JENIS POLA', 1, '2025-12-04 10:49:11', '2025-12-04 10:49:11'),
(3, 'Jenis Kerah', 1, '2025-12-04 10:49:32', '2025-12-04 10:49:32'),
(4, 'Jenis Jahitan', 1, '2025-12-04 10:50:36', '2025-12-04 10:50:36'),
(5, 'Kolor keren', 20, '2025-12-04 11:04:04', '2025-12-04 11:04:04');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_spek_detail`
--

CREATE TABLE `jenis_spek_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_jenis_spek_detail` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `id_jenis_order` bigint(20) UNSIGNED DEFAULT NULL,
  `id_jenis_spek` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_spek_detail`
--

INSERT INTO `jenis_spek_detail` (`id`, `nama_jenis_spek_detail`, `gambar`, `id_jenis_order`, `id_jenis_spek`, `created_at`, `updated_at`) VALUES
(1, 'Benzema', 'jenis_spek_detail/QunICYL8hOsI6BaUHUyllruSTxgI9ZpmeuT2bqK6.jpg', NULL, 1, '2025-12-04 13:19:32', '2025-12-04 13:19:43'),
(2, 'Reguler', 'jenis_spek_detail/cpwzmPb7RUuful0FZk3cFOme2eSkaxilROEh4kMC.jpg', NULL, 2, '2025-12-04 13:20:21', '2025-12-04 13:20:21'),
(3, 'Brazil', 'jenis_spek_detail/v9oZOWJxatr8LTYpEiJ1bVScssP8lHj60XVma3Q9.jpg', NULL, 1, '2025-12-04 13:30:29', '2025-12-04 13:30:29'),
(4, 'Waffle', 'jenis_spek_detail/FCLPRfidU1r0cIeO4iQHqFYnDXQj9hdQ2sg7cyVW.jpg', NULL, 1, '2025-12-04 13:32:04', '2025-12-04 13:32:04'),
(5, 'Milano', 'jenis_spek_detail/FBM8wVSSTCYlPg8xL9Niu1ONTdA4X2gGYsNdGwPl.jpg', NULL, 1, '2025-12-04 13:32:27', '2025-12-04 13:32:27'),
(6, 'Reglan', 'jenis_spek_detail/NgFWWyIiuWLUaGMjmjwVXiA8FujTR5VdcV5KWqig.jpg', NULL, 2, '2025-12-04 13:33:02', '2025-12-04 13:33:02'),
(7, 'Sambungan Pinggir', 'jenis_spek_detail/EPsre3TjfxQ2BjPZBhMKgW9YjTg4tS2sr4RWerS6.jpg', NULL, 2, '2025-12-04 13:33:32', '2025-12-04 13:33:32'),
(8, 'V-Neck', 'jenis_spek_detail/qID6AxR2RmYLGAUsddyfiodpKovGR2CxEAH5jsHo.jpg', NULL, 3, '2025-12-04 13:33:58', '2025-12-04 13:33:58'),
(9, 'O-Neck', 'jenis_spek_detail/uMWfG5NRVbG5YfcWz6hGL8LNmq3w93PdVibKauwh.jpg', NULL, 3, '2025-12-04 13:34:26', '2025-12-04 13:34:26'),
(10, 'V-Double', 'jenis_spek_detail/nQHg7tlGormde0owc6rgCQwvv8fzpjIywh9yMHDp.jpg', NULL, 3, '2025-12-04 13:34:50', '2025-12-04 13:34:50'),
(11, 'Polo', 'jenis_spek_detail/Bfg83A9JKT8DTUwFVPkhlrBFsJY8Zyy3awSIqv9u.jpg', NULL, 3, '2025-12-04 13:35:14', '2025-12-04 13:35:14'),
(12, 'Pundak Rante', 'jenis_spek_detail/bGupElTVOtyGjqHqJTs6hK7ajL0tGGW763rqSzyr.jpg', NULL, 4, '2025-12-04 13:35:51', '2025-12-04 13:35:51'),
(13, 'Samping Flatlock', 'jenis_spek_detail/n0yQ7DgvTOCnF6t74oPqGcgTMUp4RvGM8DTuDJOI.jpg', NULL, 4, '2025-12-04 13:36:15', '2025-12-04 13:36:15'),
(14, 'Bawah Lengkung', 'jenis_spek_detail/IRMhGYYBpawSfY34hjn3V59S5Nf4oHHkUJvqJevc.jpg', NULL, 4, '2025-12-04 13:36:50', '2025-12-04 13:36:50'),
(15, 'Bawah Lurus', 'jenis_spek_detail/Zxq5UOk4hDxSe4JzNsSx8x05EPHgqzmZmwBY3UhO.jpg', NULL, 4, '2025-12-04 13:37:08', '2025-12-04 13:37:08'),
(16, 'Bawahan Keren', 'jenis_spek_detail/M2VGAjHhA0H6n1dxGuCMAYvTnsVS05IqiTmUOVz6.jpg', NULL, 5, '2025-12-04 13:37:52', '2025-12-04 13:37:52');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_spek_detail_jenis_order`
--

CREATE TABLE `jenis_spek_detail_jenis_order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jenis_spek_detail_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_order_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_spek_detail_jenis_order`
--

INSERT INTO `jenis_spek_detail_jenis_order` (`id`, `jenis_spek_detail_id`, `jenis_order_id`, `created_at`, `updated_at`) VALUES
(1, 3, 14, NULL, NULL),
(2, 3, 18, NULL, NULL),
(3, 3, 21, NULL, NULL),
(4, 3, 17, NULL, NULL),
(5, 3, 26, NULL, NULL),
(7, 1, 17, NULL, NULL),
(8, 1, 18, NULL, NULL),
(9, 1, 21, NULL, NULL),
(10, 1, 26, NULL, NULL),
(11, 4, 14, NULL, NULL),
(12, 4, 17, NULL, NULL),
(13, 4, 18, NULL, NULL),
(14, 4, 21, NULL, NULL),
(15, 4, 26, NULL, NULL),
(16, 5, 14, NULL, NULL),
(17, 5, 17, NULL, NULL),
(18, 5, 18, NULL, NULL),
(19, 5, 21, NULL, NULL),
(20, 5, 26, NULL, NULL),
(21, 6, 14, NULL, NULL),
(22, 6, 17, NULL, NULL),
(23, 6, 18, NULL, NULL),
(24, 6, 21, NULL, NULL),
(25, 6, 26, NULL, NULL),
(26, 7, 14, NULL, NULL),
(27, 7, 17, NULL, NULL),
(28, 7, 18, NULL, NULL),
(29, 7, 21, NULL, NULL),
(30, 7, 26, NULL, NULL),
(31, 8, 14, NULL, NULL),
(32, 8, 17, NULL, NULL),
(33, 8, 18, NULL, NULL),
(34, 8, 21, NULL, NULL),
(35, 8, 26, NULL, NULL),
(36, 9, 14, NULL, NULL),
(37, 9, 17, NULL, NULL),
(38, 9, 18, NULL, NULL),
(39, 9, 21, NULL, NULL),
(40, 9, 26, NULL, NULL),
(41, 10, 14, NULL, NULL),
(42, 10, 17, NULL, NULL),
(43, 10, 18, NULL, NULL),
(44, 10, 21, NULL, NULL),
(45, 10, 26, NULL, NULL),
(46, 11, 14, NULL, NULL),
(47, 11, 17, NULL, NULL),
(48, 11, 18, NULL, NULL),
(49, 11, 21, NULL, NULL),
(50, 11, 26, NULL, NULL),
(51, 2, 14, NULL, NULL),
(52, 2, 17, NULL, NULL),
(53, 2, 18, NULL, NULL),
(54, 2, 21, NULL, NULL),
(55, 2, 26, NULL, NULL),
(56, 12, 14, NULL, NULL),
(57, 12, 17, NULL, NULL),
(58, 12, 18, NULL, NULL),
(59, 12, 21, NULL, NULL),
(60, 12, 26, NULL, NULL),
(61, 13, 14, NULL, NULL),
(62, 13, 17, NULL, NULL),
(63, 13, 18, NULL, NULL),
(64, 13, 21, NULL, NULL),
(65, 13, 26, NULL, NULL),
(66, 14, 14, NULL, NULL),
(67, 14, 17, NULL, NULL),
(68, 14, 18, NULL, NULL),
(69, 14, 21, NULL, NULL),
(70, 14, 26, NULL, NULL),
(71, 15, 14, NULL, NULL),
(72, 15, 17, NULL, NULL),
(73, 15, 18, NULL, NULL),
(74, 15, 21, NULL, NULL),
(75, 15, 26, NULL, NULL),
(76, 16, 19, NULL, NULL),
(77, 16, 20, NULL, NULL),
(78, 16, 23, NULL, NULL),
(79, 16, 24, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_job` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `nama_job`, `created_at`, `updated_at`) VALUES
(5, 'JERSEY MTB', '2025-11-28 01:13:07', '2025-12-03 14:09:28'),
(6, 'JERSEY BOLA', '2025-11-28 01:14:32', '2025-11-28 01:14:32'),
(7, 'JAKET', '2025-11-28 01:18:26', '2025-11-28 01:18:26'),
(8, 'JERSEY', '2025-11-28 01:21:32', '2025-11-28 01:21:32'),
(9, 'CELANA BOLA', '2025-11-28 01:24:21', '2025-11-28 01:24:21'),
(10, 'JERSEY ROAD BIKE', '2025-11-28 01:25:45', '2025-11-28 01:25:45'),
(11, 'JERSEY MANCING', '2025-11-28 01:28:21', '2025-11-28 01:28:21'),
(12, 'kaos oblong', '2025-11-30 17:18:21', '2025-12-03 22:16:57'),
(13, 'jersey running', '2025-11-30 18:00:33', '2025-11-30 18:00:33'),
(16, 'JOB Tester', '2025-12-03 14:09:38', '2025-12-03 14:09:38'),
(17, 'jersey volly', '2025-12-03 22:26:14', '2025-12-03 22:26:14'),
(18, 'jersey testing', '2025-12-04 15:02:07', '2025-12-04 15:02:07'),
(19, 'baseball', '2025-12-06 02:18:51', '2025-12-06 02:18:51');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_jenis_order`
--

CREATE TABLE `kategori_jenis_order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori_jenis_order`
--

INSERT INTO `kategori_jenis_order` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Atasan', '2025-11-26 22:33:59', '2025-11-26 22:33:59'),
(20, 'Bawahan', '2025-11-26 22:38:32', '2025-11-26 22:38:32');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_11_17_050034_create_orders_table', 1),
(6, '2025_11_17_055733_add_totals_to_orders_table', 2),
(7, '2025_11_17_063552_remove_totals_from_orders_table', 3),
(10, '2025_11_17_063709_remove_totals_setting_from_orders_table', 4),
(11, '2025_11_17_072646_remove_slug_from_orders_table', 5),
(12, '2025_11_17_073254_remove_slug_from_orders_table', 6),
(13, '2025_11_17_073447_add_slug_to_orders_table', 7),
(14, '2025_11_17_073823_remove_slug_from_orders_table', 8),
(15, '2025_11_17_073931_add_slug_to_orders_table', 9),
(16, '2025_11_18_094728_create_pegawais_table', 10),
(17, '2025_11_18_100842_update_alamat_column_on_pegawais_table', 11),
(18, '2025_11_18_112145_add_konsumen_keterangan_on_orders_table', 12),
(19, '2025_11_18_143403_create_order_histories_table', 13),
(20, '2025_11_18_164005_change_hari_deadline_to_double_in_orders_table', 14),
(21, '2025_11_20_204809_create_harga_jobs_table', 15),
(22, '2025_11_20_210639_create_step_price_table', 16),
(23, '2025_11_20_223326_create_casbons_table', 17),
(24, '2025_11_21_064102_add_finishing_packing_to_orders_table', 18),
(26, '2025_11_21_064517_add_finishing_packing_to_order_totals_table', 19),
(27, '2025_11_23_025415_rename_telepon_to_posisi_in_pegawais_table', 20),
(28, '2025_11_25_160208_change_setting_to_boolean_in_orders_table', 21),
(29, '2025_11_25_162745_add_total_sisa_setting_to_order_totals_table', 22),
(30, '2025_11_26_152020_create_jenis_order_table', 23),
(31, '2025_11_26_152240_add_jenis_order_id_to_orders_table', 24),
(32, '2025_11_26_170643_add_total_lembar_print_to_orders_table', 25),
(33, '2025_11_26_172023_add_total_lembar_print_to_order_histories_table', 26),
(34, '2025_11_27_051123_create_kategori_jenis_order_table', 27),
(35, '2025_11_27_051602_add_id_kategori_jenis_order_to_jenis_order_table', 28),
(36, '2025_11_27_062531_create_jobs_table', 29),
(37, '2025_11_28_040345_add_total_lembar_press_to_orders_table', 30),
(38, '2025_11_28_041040_remove_total_lembar_print_from_order_histories_table', 31),
(39, '2025_11_28_063937_create_harga_jenis_pekerjaan_table', 32),
(40, '2025_11_28_131355_add_username_to_users_table', 33),
(41, '2025_11_29_055231_create_jenis_bahan_table', 34),
(42, '2025_11_29_055359_create_jenis_pola_table', 35),
(43, '2025_11_29_055427_create_jenis_kerah_table', 36),
(44, '2025_11_29_055445_create_jenis_jahitan_table', 37),
(45, '2025_11_29_064839_add_spesifikasi_to_orders_table', 38),
(46, '2025_12_02_074932_create_size_table', 39),
(47, '2025_12_04_163115_add_id_kategori_jenis_order_to_jenis_bahan_table', 40),
(48, '2025_12_04_172423_create_jenis_spek_table', 41),
(49, '2025_12_04_172816_create_jenis_spek_detail_table', 42),
(51, '2025_12_04_202323_add_jenis_order_to_jenis_spek_detail_table', 43),
(52, '2025_12_04_202400_create_jenis_spek_detail_jenis_order_table', 43),
(53, '2025_12_04_215614_drop_columns_on_orders_table', 44),
(54, '2025_12_05_000000_create_order_spesifikasi_table', 45),
(55, '2025_12_05_074939_create_pelanggan_table', 46),
(56, '2025_12_05_083554_create_pelanggan_orders_table', 47);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jenis_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_job` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `hari` decimal(8,2) NOT NULL,
  `deadline` decimal(8,2) NOT NULL,
  `setting` tinyint(1) NOT NULL DEFAULT 0,
  `print` int(11) NOT NULL DEFAULT 0,
  `press` int(11) NOT NULL DEFAULT 0,
  `cutting` int(11) NOT NULL DEFAULT 0,
  `jahit` int(11) NOT NULL DEFAULT 0,
  `finishing` int(11) NOT NULL DEFAULT 0,
  `packing` int(11) NOT NULL DEFAULT 0,
  `est` double(8,2) NOT NULL DEFAULT 0.00,
  `sisa_print` int(11) NOT NULL DEFAULT 0,
  `total_lembar_print` int(11) NOT NULL DEFAULT 0 COMMENT 'Total lembar print order',
  `total_lembar_press` int(11) NOT NULL DEFAULT 0,
  `sisa_press` int(11) NOT NULL DEFAULT 0,
  `sisa_cutting` int(11) NOT NULL DEFAULT 0,
  `sisa_jahit` int(11) NOT NULL DEFAULT 0,
  `sisa_finishing` int(11) NOT NULL DEFAULT 0,
  `sisa_packing` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nama_konsumen` varchar(255) NOT NULL DEFAULT 'rafli',
  `keterangan` text NOT NULL DEFAULT 'gaada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `jenis_order_id`, `nama_job`, `slug`, `qty`, `hari`, `deadline`, `setting`, `print`, `press`, `cutting`, `jahit`, `finishing`, `packing`, `est`, `sisa_print`, `total_lembar_print`, `total_lembar_press`, `sisa_press`, `sisa_cutting`, `sisa_jahit`, `sisa_finishing`, `sisa_packing`, `created_at`, `updated_at`, `nama_konsumen`, `keterangan`) VALUES
(195, 14, 'jersey running', 'jersey-running-1', 5, 0.20, 0.00, 1, 5, 5, 5, 5, 5, 5, 0.20, 0, 20, 20, 0, 0, 0, 0, 0, '2025-12-05 19:09:56', '2025-12-08 21:43:09', 'denim', 'Jenis Bahan: Brazil | JENIS POLA: Reguler | Jenis Kerah: O-Neck | Jenis Jahitan: Samping Flatlock'),
(196, 26, 'jersey volly', 'jersey-volly-1', 2, 0.10, 0.00, 1, 2, 2, 2, 2, 2, 2, 0.10, 0, 6, 6, 0, 0, 0, 0, 0, '2025-12-05 19:12:12', '2025-12-08 21:43:21', 'bxr tessa', 'Jenis Bahan: Benzema | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Pundak Rante'),
(197, 26, 'JERSEY BOLA', 'jersey-bola-5', 15, 0.50, 0.00, 1, 15, 15, 15, 15, 15, 15, 0.50, 0, 45, 45, 0, 0, 0, 0, 0, '2025-12-05 19:13:36', '2025-12-08 21:43:38', 'manbaul', 'Jenis Bahan: Benzema | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Pundak Rante'),
(198, 14, 'JERSEY MTB', 'jersey-mtb-6', 4, 0.10, 0.00, 1, 4, 4, 4, 4, 4, 4, 0.10, 0, 16, 16, 0, 0, 0, 0, 0, '2025-12-05 19:17:34', '2025-12-09 02:09:57', 'vina jupeng', 'Jenis Bahan: Waffle | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(199, 26, 'JERSEY MTB', 'jersey-mtb-7', 1, 0.00, 0.00, 1, 1, 1, 1, 1, 1, 1, 0.00, 0, 3, 3, 0, 0, 0, 0, 0, '2025-12-05 19:18:07', '2025-12-09 02:09:36', 'vina jupeng', 'Jenis Bahan: Waffle | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(200, 14, 'JERSEY MTB', 'jersey-mtb-8', 16, 0.50, 0.00, 1, 16, 16, 16, 16, 16, 16, 0.50, 0, 64, 64, 0, 0, 0, 0, 0, '2025-12-05 19:21:12', '2025-12-09 02:09:08', 'vina medal', 'Jenis Bahan: Waffle | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(201, 14, 'JERSEY MTB', 'jersey-mtb-9', 2, 0.10, 0.00, 1, 2, 2, 2, 2, 2, 2, 0.10, 0, 8, 8, 0, 0, 0, 0, 0, '2025-12-05 19:22:01', '2025-12-09 02:08:41', 'vina ngalungsar', 'Jenis Bahan: Waffle | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(202, 26, 'JERSEY MTB', 'jersey-mtb-10', 5, 0.20, 0.00, 1, 5, 5, 5, 5, 5, 5, 0.20, 0, 15, 15, 0, 0, 0, 0, 0, '2025-12-05 19:22:25', '2025-12-09 02:08:30', 'vina ngalungsar', 'Jenis Bahan: Waffle | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(203, 26, 'JERSEY BOLA', 'jersey-bola-6', 46, 1.50, 0.00, 1, 46, 46, 46, 46, 46, 46, 1.50, 0, 138, 138, 0, 0, 0, 0, 0, '2025-12-05 19:23:24', '2025-12-09 02:08:15', 'syifa class asixx manonjaya', 'Jenis Bahan: Waffle | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(204, 20, 'CELANA BOLA', 'celana-bola-2', 46, 1.50, 0.00, 1, 46, 46, 46, 46, 46, 46, 1.50, 0, 184, 184, 0, 0, 0, 0, 0, '2025-12-05 19:23:58', '2025-12-09 02:08:01', 'syifa class asixx manonjaya', 'Kolor keren: Bawahan Keren'),
(205, 26, 'JERSEY BOLA', 'jersey-bola-7', 6, 0.20, 0.00, 1, 6, 6, 6, 6, 6, 6, 0.20, 0, 18, 18, 0, 0, 0, 0, 0, '2025-12-05 19:26:33', '2025-12-05 22:51:53', 'adi ssb galuh', 'Jenis Bahan: Benzema | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(206, 20, 'CELANA BOLA', 'celana-bola-3', 6, 0.20, 0.00, 1, 6, 6, 6, 6, 6, 6, 0.20, 0, 24, 24, 0, 0, 0, 0, 0, '2025-12-05 19:27:07', '2025-12-05 22:49:33', 'adi ssb galuh', 'Kolor keren: Bawahan Keren'),
(207, 14, 'JERSEY BOLA', 'jersey-bola-8', 1, 0.00, 0.00, 1, 1, 1, 1, 1, 1, 1, 0.00, 0, 4, 4, 0, 0, 0, 0, 0, '2025-12-05 19:28:25', '2025-12-10 18:58:42', 'adi galuh asg', 'Jenis Bahan: Waffle | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(208, 26, 'baseball', 'baseball', 2, 0.10, 0.00, 1, 2, 2, 2, 2, 2, 2, 0.10, 0, 6, 6, 0, 0, 0, 0, 0, '2025-12-06 02:18:51', '2025-12-10 18:58:34', 'alves 88', 'Jenis Bahan: Benzema | JENIS POLA: Reguler | Jenis Jahitan: Samping Flatlock'),
(209, 26, 'JERSEY MTB', 'jersey-mtb-11', 1, 0.00, 0.00, 1, 1, 1, 1, 1, 1, 1, 0.00, 0, 3, 3, 0, 0, 0, 0, 0, '2025-12-06 02:20:51', '2025-12-09 02:07:32', 'yanto mcc', 'Jenis Bahan: Benzema | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(210, 26, 'JERSEY BOLA', 'jersey-bola', 20, 0.70, 0.00, 1, 20, 20, 20, 20, 20, 20, 0.70, 0, 60, 60, 0, 0, 0, 0, 0, '2025-12-07 22:16:58', '2025-12-10 18:58:25', 'manonjaya fositiv 5', 'Jenis Bahan: Benzema | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(211, 20, 'CELANA BOLA', 'celana-bola', 20, 0.70, 0.00, 1, 20, 20, 20, 20, 20, 20, 0.70, 0, 80, 80, 0, 0, 0, 0, 0, '2025-12-07 22:42:45', '2025-12-10 18:57:39', 'manonjaya fositiv 5', 'Kolor keren: Bawahan Keren'),
(212, 26, 'JERSEY BOLA', 'jersey-bola-1', 1, 0.00, 0.00, 1, 1, 1, 1, 1, 1, 1, 0.00, 0, 3, 3, 0, 0, 0, 0, 0, '2025-12-07 22:44:00', '2025-12-09 02:07:18', 'manbaul susulan', 'Jenis Bahan: Benzema | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(213, 26, 'JERSEY BOLA', 'jersey-bola-2', 3, 0.10, 0.00, 1, 3, 3, 3, 3, 3, 3, 0.10, 0, 9, 9, 0, 0, 0, 0, 0, '2025-12-07 22:44:54', '2025-12-10 18:58:16', 'adi ssb galuh susulan', 'Jenis Bahan: Benzema | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(214, 20, 'CELANA BOLA', 'celana-bola-1', 3, 0.10, 0.00, 1, 3, 3, 3, 3, 3, 3, 0.10, 0, 12, 12, 0, 0, 0, 0, 0, '2025-12-07 22:45:37', '2025-12-10 18:56:56', 'adi ssb galuh susulan', 'Kolor keren: Bawahan Keren'),
(215, 14, 'JERSEY MTB', 'jersey-mtb', 1, 0.00, 0.00, 1, 1, 1, 1, 1, 1, 1, 0.00, 0, 4, 4, 0, 0, 0, 0, 0, '2025-12-08 00:30:45', '2025-12-09 02:07:04', 'gunzalo', 'Jenis Bahan: Brazil | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock'),
(216, 14, 'JERSEY MTB', 'jersey-mtb-1', 2, 0.10, 0.10, 0, 0, 0, 0, 0, 0, 0, 0.10, 2, 0, 0, 2, 2, 2, 2, 2, '2025-12-11 03:17:15', '2025-12-11 03:17:15', 'nyacas bike', 'Jenis Bahan: Waffle | JENIS POLA: Reguler | Jenis Kerah: V-Neck | Jenis Jahitan: Samping Flatlock');

-- --------------------------------------------------------

--
-- Table structure for table `order_histories`
--

CREATE TABLE `order_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `pegawai_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_pekerjaan` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `nama_job_snapshot` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_histories`
--

INSERT INTO `order_histories` (`id`, `order_id`, `pegawai_id`, `jenis_pekerjaan`, `qty`, `keterangan`, `nama_job_snapshot`, `created_at`, `updated_at`) VALUES
(417, 195, 25, 'Setting', 1, '1', 'jersey running', '2025-12-05 22:39:37', '2025-12-05 22:39:37'),
(419, 206, 25, 'Setting', 1, '1', 'CELANA BOLA', '2025-12-05 22:43:07', '2025-12-05 22:43:07'),
(420, 205, 25, 'Setting', 1, '1', 'JERSEY BOLA', '2025-12-05 22:43:36', '2025-12-05 22:43:36'),
(421, 206, 20, 'Print', 6, 'mulus', 'CELANA BOLA', '2025-12-05 22:44:20', '2025-12-05 22:44:20'),
(422, 205, 20, 'Print', 6, NULL, 'JERSEY BOLA', '2025-12-05 22:44:56', '2025-12-05 22:44:56'),
(423, 195, 20, 'Print', 5, 'rijek 1 udah di cetak ulang', 'jersey running', '2025-12-05 22:46:00', '2025-12-05 22:46:00'),
(425, 206, 27, 'Press', 6, 'no rijek', 'CELANA BOLA', '2025-12-05 22:47:48', '2025-12-05 22:47:48'),
(426, 206, 21, 'Cutting', 6, 'yes', 'CELANA BOLA', '2025-12-05 22:48:12', '2025-12-05 22:48:12'),
(427, 206, 22, 'Jahit', 6, 'yes', 'CELANA BOLA', '2025-12-05 22:48:34', '2025-12-05 22:48:34'),
(428, 206, 28, 'Finishing', 6, 'yes', 'CELANA BOLA', '2025-12-05 22:48:55', '2025-12-05 22:48:55'),
(429, 206, 23, 'Packing', 6, 'y', 'CELANA BOLA', '2025-12-05 22:49:33', '2025-12-05 22:49:33'),
(430, 205, 19, 'Press', 6, 'y', 'JERSEY BOLA', '2025-12-05 22:50:17', '2025-12-05 22:50:17'),
(431, 205, 21, 'Cutting', 6, 'ya', 'JERSEY BOLA', '2025-12-05 22:50:44', '2025-12-05 22:50:44'),
(432, 205, 22, 'Jahit', 6, 'kurang kerah 1', 'JERSEY BOLA', '2025-12-05 22:51:09', '2025-12-05 22:51:09'),
(433, 205, 28, 'Finishing', 6, NULL, 'JERSEY BOLA', '2025-12-05 22:51:42', '2025-12-05 22:51:42'),
(434, 205, 23, 'Packing', 6, NULL, 'JERSEY BOLA', '2025-12-05 22:51:53', '2025-12-05 22:51:53'),
(435, 209, 25, 'Setting', 1, 'selesai', 'JERSEY MTB', '2025-12-06 02:23:04', '2025-12-06 02:23:04'),
(436, 208, 25, 'Setting', 1, 'selesai', 'baseball', '2025-12-06 02:23:26', '2025-12-06 02:23:26'),
(437, 207, 25, 'Setting', 1, 'selesai', 'JERSEY BOLA', '2025-12-06 02:23:43', '2025-12-06 02:23:43'),
(438, 197, 25, 'Setting', 1, 'selesai', 'JERSEY BOLA', '2025-12-06 02:26:49', '2025-12-06 02:26:49'),
(439, 196, 25, 'Setting', 1, 'selesai', 'jersey volly', '2025-12-06 02:27:04', '2025-12-06 02:27:04'),
(440, 198, 25, 'Setting', 1, 'selesai', 'JERSEY MTB', '2025-12-06 02:27:18', '2025-12-06 02:27:18'),
(441, 199, 25, 'Setting', 1, 'selesai', 'JERSEY MTB', '2025-12-06 02:27:50', '2025-12-06 02:27:50'),
(442, 200, 25, 'Setting', 1, 'selesai', 'JERSEY MTB', '2025-12-06 02:28:04', '2025-12-06 02:28:04'),
(443, 204, 25, 'Setting', 1, 'beres', 'CELANA BOLA', '2025-12-06 02:28:51', '2025-12-06 02:28:51'),
(444, 203, 25, 'Setting', 1, 'beres', 'JERSEY BOLA', '2025-12-06 02:29:04', '2025-12-06 02:29:04'),
(445, 202, 25, 'Setting', 1, 'beres', 'JERSEY MTB', '2025-12-06 02:29:20', '2025-12-06 02:29:20'),
(446, 201, 25, 'Setting', 1, 'beres', 'JERSEY MTB', '2025-12-06 02:29:36', '2025-12-06 02:29:36'),
(447, 196, 20, 'Print', 2, 'beres', 'jersey volly', '2025-12-06 02:30:17', '2025-12-06 02:30:17'),
(448, 197, 20, 'Print', 15, 'beres', 'JERSEY BOLA', '2025-12-06 02:30:30', '2025-12-06 02:30:30'),
(449, 198, 20, 'Print', 4, 'beres', 'JERSEY MTB', '2025-12-06 02:30:46', '2025-12-06 02:30:46'),
(450, 199, 20, 'Print', 1, 'beres', 'JERSEY MTB', '2025-12-06 02:30:59', '2025-12-06 02:30:59'),
(451, 200, 20, 'Print', 16, 'beres', 'JERSEY MTB', '2025-12-06 02:31:12', '2025-12-06 02:31:12'),
(452, 201, 20, 'Print', 2, 'beres', 'JERSEY MTB', '2025-12-06 02:31:22', '2025-12-06 02:31:22'),
(453, 202, 20, 'Print', 5, 'beres', 'JERSEY MTB', '2025-12-06 02:31:35', '2025-12-06 02:31:35'),
(454, 203, 20, 'Print', 46, 'beres', 'JERSEY BOLA', '2025-12-06 02:31:49', '2025-12-06 02:31:49'),
(455, 204, 20, 'Print', 46, 'beres', 'CELANA BOLA', '2025-12-06 02:32:01', '2025-12-06 02:32:01'),
(456, 207, 20, 'Print', 1, 'beres', 'JERSEY BOLA', '2025-12-06 02:32:15', '2025-12-06 02:32:15'),
(457, 208, 20, 'Print', 2, 'beres', 'baseball', '2025-12-06 02:32:24', '2025-12-06 02:32:24'),
(458, 209, 20, 'Print', 1, 'beres', 'JERSEY MTB', '2025-12-06 02:32:33', '2025-12-06 02:32:33'),
(459, 204, 27, 'Press', 46, 'beres', 'CELANA BOLA', '2025-12-07 21:36:36', '2025-12-07 21:36:36'),
(460, 203, 27, 'Press', 46, 'kolor', 'JERSEY BOLA', '2025-12-07 21:37:22', '2025-12-07 21:37:22'),
(461, 202, 27, 'Press', 5, 'beres', 'JERSEY MTB', '2025-12-07 21:39:12', '2025-12-07 21:39:12'),
(462, 207, 27, 'Press', 1, NULL, 'JERSEY BOLA', '2025-12-07 21:44:31', '2025-12-07 21:44:31'),
(463, 208, 27, 'Press', 2, 'b', 'baseball', '2025-12-07 21:46:19', '2025-12-07 21:46:19'),
(464, 209, 27, 'Press', 1, 'y', 'JERSEY MTB', '2025-12-07 21:46:45', '2025-12-07 21:46:45'),
(465, 196, 27, 'Press', 2, 'ok', 'jersey volly', '2025-12-07 21:47:30', '2025-12-07 21:47:30'),
(466, 201, 19, 'Press', 2, 'ok', 'JERSEY MTB', '2025-12-07 21:48:47', '2025-12-07 21:48:47'),
(467, 200, 19, 'Press', 16, 'ok', 'JERSEY MTB', '2025-12-07 21:49:09', '2025-12-07 21:49:09'),
(468, 199, 19, 'Press', 1, 'y', 'JERSEY MTB', '2025-12-07 21:49:35', '2025-12-07 21:49:35'),
(469, 198, 19, 'Press', 4, 'y', 'JERSEY MTB', '2025-12-07 21:49:57', '2025-12-07 21:49:57'),
(470, 197, 19, 'Press', 15, 'y', 'JERSEY BOLA', '2025-12-07 21:50:20', '2025-12-07 21:50:20'),
(471, 195, 19, 'Press', 5, 'y', 'jersey running', '2025-12-07 21:51:08', '2025-12-07 21:51:08'),
(472, 209, 21, 'Cutting', 1, 'b', 'JERSEY MTB', '2025-12-07 21:52:07', '2025-12-07 21:52:07'),
(473, 208, 21, 'Cutting', 2, 'b', 'baseball', '2025-12-07 21:53:55', '2025-12-07 21:53:55'),
(474, 207, 21, 'Cutting', 1, NULL, 'JERSEY BOLA', '2025-12-07 21:56:29', '2025-12-07 21:56:29'),
(475, 204, 21, 'Cutting', 46, 'b', 'CELANA BOLA', '2025-12-07 21:57:33', '2025-12-07 21:57:33'),
(476, 203, 21, 'Cutting', 46, 'b', 'JERSEY BOLA', '2025-12-07 21:58:04', '2025-12-07 21:58:04'),
(477, 202, 21, 'Cutting', 5, 'b', 'JERSEY MTB', '2025-12-07 21:58:26', '2025-12-07 21:58:26'),
(478, 201, 21, 'Cutting', 2, 'b', 'JERSEY MTB', '2025-12-07 21:59:03', '2025-12-07 21:59:03'),
(479, 200, 21, 'Cutting', 16, 'b', 'JERSEY MTB', '2025-12-07 21:59:21', '2025-12-07 21:59:21'),
(480, 199, 21, 'Cutting', 1, 'b', 'JERSEY MTB', '2025-12-07 21:59:46', '2025-12-07 21:59:46'),
(481, 198, 21, 'Cutting', 4, 'b', 'JERSEY MTB', '2025-12-07 22:00:09', '2025-12-07 22:00:09'),
(482, 197, 21, 'Cutting', 15, 'b', 'JERSEY BOLA', '2025-12-07 22:00:32', '2025-12-07 22:00:32'),
(483, 196, 21, 'Cutting', 2, 'b', 'jersey volly', '2025-12-07 22:01:34', '2025-12-07 22:01:34'),
(484, 195, 21, 'Cutting', 5, 'b', 'jersey running', '2025-12-07 22:03:01', '2025-12-07 22:03:01'),
(485, 208, 22, 'Jahit', 2, 'b', 'baseball', '2025-12-07 22:03:38', '2025-12-07 22:03:38'),
(486, 197, 22, 'Jahit', 15, 'b', 'JERSEY BOLA', '2025-12-07 22:07:02', '2025-12-07 22:07:02'),
(487, 196, 22, 'Jahit', 2, 'b', 'jersey volly', '2025-12-07 22:07:46', '2025-12-07 22:07:46'),
(488, 195, 22, 'Jahit', 5, 'b', 'jersey running', '2025-12-07 22:08:15', '2025-12-07 22:08:15'),
(489, 200, 22, 'Jahit', 16, 'revisi samping', 'JERSEY MTB', '2025-12-07 22:11:30', '2025-12-07 22:11:30'),
(490, 210, 25, 'Setting', 1, NULL, 'JERSEY BOLA', '2025-12-07 22:41:25', '2025-12-07 22:41:25'),
(491, 210, 20, 'Print', 20, 'beres', 'JERSEY BOLA', '2025-12-07 22:46:17', '2025-12-07 22:46:17'),
(492, 214, 25, 'Setting', 1, NULL, 'CELANA BOLA', '2025-12-07 22:46:59', '2025-12-07 22:46:59'),
(493, 213, 25, 'Setting', 1, NULL, 'JERSEY BOLA', '2025-12-07 22:47:06', '2025-12-07 22:47:06'),
(494, 212, 25, 'Setting', 1, NULL, 'JERSEY BOLA', '2025-12-07 22:47:21', '2025-12-07 22:47:21'),
(495, 211, 25, 'Setting', 1, NULL, 'CELANA BOLA', '2025-12-07 22:47:31', '2025-12-07 22:47:31'),
(496, 214, 20, 'Print', 3, NULL, 'CELANA BOLA', '2025-12-07 22:47:42', '2025-12-07 22:47:42'),
(497, 213, 20, 'Print', 3, NULL, 'JERSEY BOLA', '2025-12-07 22:47:51', '2025-12-07 22:47:51'),
(498, 212, 20, 'Print', 1, NULL, 'JERSEY BOLA', '2025-12-07 22:47:57', '2025-12-07 22:47:57'),
(499, 211, 20, 'Print', 20, NULL, 'CELANA BOLA', '2025-12-07 22:48:06', '2025-12-07 22:48:06'),
(500, 215, 25, 'Setting', 1, NULL, 'JERSEY MTB', '2025-12-08 00:31:14', '2025-12-08 00:31:14'),
(501, 215, 20, 'Print', 1, NULL, 'JERSEY MTB', '2025-12-08 00:31:26', '2025-12-08 00:31:26'),
(502, 215, 27, 'Press', 1, 'DONE', 'JERSEY MTB', '2025-12-08 21:27:31', '2025-12-08 21:27:31'),
(503, 212, 27, 'Press', 1, 'DONE', 'JERSEY BOLA', '2025-12-08 21:28:28', '2025-12-08 21:28:28'),
(504, 211, 27, 'Press', 20, 'DONE', 'CELANA BOLA', '2025-12-08 21:29:30', '2025-12-08 21:29:30'),
(505, 210, 27, 'Press', 20, 'BERES', 'JERSEY BOLA', '2025-12-08 21:29:49', '2025-12-08 21:29:49'),
(506, 215, 21, 'Cutting', 1, 'B', 'JERSEY MTB', '2025-12-08 21:31:42', '2025-12-08 21:31:42'),
(507, 212, 21, 'Cutting', 1, 'B', 'JERSEY BOLA', '2025-12-08 21:31:56', '2025-12-08 21:31:56'),
(508, 211, 21, 'Cutting', 20, 'B', 'CELANA BOLA', '2025-12-08 21:32:18', '2025-12-08 21:32:18'),
(509, 210, 21, 'Cutting', 20, 'B', 'JERSEY BOLA', '2025-12-08 21:32:32', '2025-12-08 21:32:32'),
(510, 198, 22, 'Jahit', 4, 'B', 'JERSEY MTB', '2025-12-08 21:33:48', '2025-12-08 21:33:48'),
(511, 199, 22, 'Jahit', 1, 'B', 'JERSEY MTB', '2025-12-08 21:34:07', '2025-12-08 21:34:07'),
(512, 203, 22, 'Jahit', 46, 'B', 'JERSEY BOLA', '2025-12-08 21:34:39', '2025-12-08 21:34:39'),
(513, 204, 22, 'Jahit', 46, 'B', 'CELANA BOLA', '2025-12-08 21:35:06', '2025-12-08 21:35:06'),
(514, 209, 22, 'Jahit', 1, 'B', 'JERSEY MTB', '2025-12-08 21:35:34', '2025-12-08 21:35:34'),
(515, 195, 28, 'Finishing', 5, 'B', 'jersey running', '2025-12-08 21:36:27', '2025-12-08 21:36:27'),
(516, 196, 28, 'Finishing', 2, 'B', 'jersey volly', '2025-12-08 21:36:41', '2025-12-08 21:36:41'),
(517, 197, 28, 'Finishing', 15, 'B', 'JERSEY BOLA', '2025-12-08 21:36:56', '2025-12-08 21:36:56'),
(518, 198, 28, 'Finishing', 4, 'B', 'JERSEY MTB', '2025-12-08 21:37:17', '2025-12-08 21:37:17'),
(519, 199, 28, 'Finishing', 1, 'B', 'JERSEY MTB', '2025-12-08 21:37:28', '2025-12-08 21:37:28'),
(520, 209, 28, 'Finishing', 1, 'B', 'JERSEY MTB', '2025-12-08 21:41:00', '2025-12-08 21:41:00'),
(521, 208, 28, 'Finishing', 2, 'B', 'baseball', '2025-12-08 21:41:22', '2025-12-08 21:41:22'),
(522, 204, 28, 'Finishing', 46, 'B', 'CELANA BOLA', '2025-12-08 21:41:45', '2025-12-08 21:41:45'),
(523, 203, 28, 'Finishing', 46, 'B', 'JERSEY BOLA', '2025-12-08 21:42:01', '2025-12-08 21:42:01'),
(524, 200, 28, 'Finishing', 16, 'B', 'JERSEY MTB', '2025-12-08 21:42:21', '2025-12-08 21:42:21'),
(525, 195, 23, 'Packing', 5, 'B', 'jersey running', '2025-12-08 21:43:09', '2025-12-08 21:43:09'),
(526, 196, 23, 'Packing', 2, 'B', 'jersey volly', '2025-12-08 21:43:21', '2025-12-08 21:43:21'),
(527, 197, 23, 'Packing', 15, 'B', 'JERSEY BOLA', '2025-12-08 21:43:38', '2025-12-08 21:43:38'),
(528, 214, 27, 'Press', 3, 'DONE', 'CELANA BOLA', '2025-12-09 02:03:03', '2025-12-09 02:03:03'),
(529, 213, 27, 'Press', 3, 'DONE', 'JERSEY BOLA', '2025-12-09 02:03:18', '2025-12-09 02:03:18'),
(530, 214, 21, 'Cutting', 3, 'B', 'CELANA BOLA', '2025-12-09 02:03:34', '2025-12-09 02:03:34'),
(531, 213, 21, 'Cutting', 3, 'B', 'JERSEY BOLA', '2025-12-09 02:03:48', '2025-12-09 02:03:48'),
(532, 202, 22, 'Jahit', 5, 'B', 'JERSEY MTB', '2025-12-09 02:04:15', '2025-12-09 02:04:15'),
(533, 201, 22, 'Jahit', 2, 'B', 'JERSEY MTB', '2025-12-09 02:04:29', '2025-12-09 02:04:29'),
(534, 215, 22, 'Jahit', 1, 'B', 'JERSEY MTB', '2025-12-09 02:04:52', '2025-12-09 02:04:52'),
(535, 212, 22, 'Jahit', 1, 'B', 'JERSEY BOLA', '2025-12-09 02:05:07', '2025-12-09 02:05:07'),
(536, 215, 28, 'Finishing', 1, 'B', 'JERSEY MTB', '2025-12-09 02:05:53', '2025-12-09 02:05:53'),
(537, 212, 28, 'Finishing', 1, 'B', 'JERSEY BOLA', '2025-12-09 02:06:07', '2025-12-09 02:06:07'),
(538, 202, 28, 'Finishing', 5, 'B', 'JERSEY MTB', '2025-12-09 02:06:28', '2025-12-09 02:06:28'),
(539, 201, 28, 'Finishing', 2, 'B', 'JERSEY MTB', '2025-12-09 02:06:41', '2025-12-09 02:06:41'),
(540, 215, 23, 'Packing', 1, 'B', 'JERSEY MTB', '2025-12-09 02:07:04', '2025-12-09 02:07:04'),
(541, 212, 23, 'Packing', 1, 'B', 'JERSEY BOLA', '2025-12-09 02:07:18', '2025-12-09 02:07:18'),
(542, 209, 23, 'Packing', 1, 'B', 'JERSEY MTB', '2025-12-09 02:07:32', '2025-12-09 02:07:32'),
(543, 204, 23, 'Packing', 46, 'B', 'CELANA BOLA', '2025-12-09 02:08:01', '2025-12-09 02:08:01'),
(544, 203, 23, 'Packing', 46, 'B', 'JERSEY BOLA', '2025-12-09 02:08:15', '2025-12-09 02:08:15'),
(545, 202, 23, 'Packing', 5, 'B', 'JERSEY MTB', '2025-12-09 02:08:30', '2025-12-09 02:08:30'),
(546, 201, 23, 'Packing', 2, 'B', 'JERSEY MTB', '2025-12-09 02:08:41', '2025-12-09 02:08:41'),
(547, 200, 23, 'Packing', 16, 'B', 'JERSEY MTB', '2025-12-09 02:09:08', '2025-12-09 02:09:08'),
(548, 199, 23, 'Packing', 1, 'B', 'JERSEY MTB', '2025-12-09 02:09:36', '2025-12-09 02:09:36'),
(549, 198, 23, 'Packing', 4, 'B', 'JERSEY MTB', '2025-12-09 02:09:57', '2025-12-09 02:09:57'),
(550, 210, 22, 'Jahit', 20, NULL, 'JERSEY BOLA', '2025-12-09 20:14:50', '2025-12-09 20:14:50'),
(551, 211, 22, 'Jahit', 20, NULL, 'CELANA BOLA', '2025-12-09 20:15:01', '2025-12-09 20:15:01'),
(552, 213, 22, 'Jahit', 3, NULL, 'JERSEY BOLA', '2025-12-09 20:15:11', '2025-12-09 20:15:11'),
(553, 214, 22, 'Jahit', 3, NULL, 'CELANA BOLA', '2025-12-09 20:15:21', '2025-12-09 20:15:21'),
(554, 207, 22, 'Jahit', 1, NULL, 'JERSEY BOLA', '2025-12-10 18:52:27', '2025-12-10 18:52:27'),
(555, 207, 28, 'Finishing', 1, NULL, 'JERSEY BOLA', '2025-12-10 18:52:40', '2025-12-10 18:52:40'),
(556, 214, 28, 'Finishing', 3, NULL, 'CELANA BOLA', '2025-12-10 18:53:01', '2025-12-10 18:53:01'),
(557, 213, 28, 'Finishing', 3, NULL, 'JERSEY BOLA', '2025-12-10 18:53:25', '2025-12-10 18:53:25'),
(558, 211, 28, 'Finishing', 20, NULL, 'CELANA BOLA', '2025-12-10 18:54:19', '2025-12-10 18:54:19'),
(559, 210, 28, 'Finishing', 20, NULL, 'JERSEY BOLA', '2025-12-10 18:54:28', '2025-12-10 18:54:28'),
(560, 214, 23, 'Packing', 3, NULL, 'CELANA BOLA', '2025-12-10 18:56:56', '2025-12-10 18:56:56'),
(561, 211, 23, 'Packing', 20, NULL, 'CELANA BOLA', '2025-12-10 18:57:39', '2025-12-10 18:57:39'),
(562, 213, 23, 'Packing', 3, NULL, 'JERSEY BOLA', '2025-12-10 18:58:16', '2025-12-10 18:58:16'),
(563, 210, 23, 'Packing', 20, NULL, 'JERSEY BOLA', '2025-12-10 18:58:25', '2025-12-10 18:58:25'),
(564, 208, 23, 'Packing', 2, NULL, 'baseball', '2025-12-10 18:58:34', '2025-12-10 18:58:34'),
(565, 207, 23, 'Packing', 1, NULL, 'JERSEY BOLA', '2025-12-10 18:58:42', '2025-12-10 18:58:42');

-- --------------------------------------------------------

--
-- Table structure for table `order_spesifikasi`
--

CREATE TABLE `order_spesifikasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_spek_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_spek_detail_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_spesifikasi`
--

INSERT INTO `order_spesifikasi` (`id`, `order_id`, `jenis_spek_id`, `jenis_spek_detail_id`, `created_at`, `updated_at`) VALUES
(18, 195, 1, 3, '2025-12-05 19:09:56', '2025-12-05 19:09:56'),
(19, 195, 2, 2, '2025-12-05 19:09:56', '2025-12-05 19:09:56'),
(20, 195, 3, 9, '2025-12-05 19:09:56', '2025-12-05 19:09:56'),
(21, 195, 4, 13, '2025-12-05 19:09:56', '2025-12-05 19:09:56'),
(22, 196, 1, 1, '2025-12-05 19:12:12', '2025-12-05 19:12:12'),
(23, 196, 2, 2, '2025-12-05 19:12:12', '2025-12-05 19:12:12'),
(24, 196, 3, 8, '2025-12-05 19:12:12', '2025-12-05 19:12:12'),
(25, 196, 4, 12, '2025-12-05 19:12:12', '2025-12-05 19:12:12'),
(26, 197, 1, 1, '2025-12-05 19:13:36', '2025-12-05 19:13:36'),
(27, 197, 2, 2, '2025-12-05 19:13:36', '2025-12-05 19:13:36'),
(28, 197, 3, 8, '2025-12-05 19:13:36', '2025-12-05 19:13:36'),
(29, 197, 4, 12, '2025-12-05 19:13:36', '2025-12-05 19:13:36'),
(30, 198, 1, 4, '2025-12-05 19:17:34', '2025-12-05 19:17:34'),
(31, 198, 2, 2, '2025-12-05 19:17:34', '2025-12-05 19:17:34'),
(32, 198, 3, 8, '2025-12-05 19:17:34', '2025-12-05 19:17:34'),
(33, 198, 4, 13, '2025-12-05 19:17:34', '2025-12-05 19:17:34'),
(34, 199, 1, 4, '2025-12-05 19:18:07', '2025-12-05 19:18:07'),
(35, 199, 2, 2, '2025-12-05 19:18:07', '2025-12-05 19:18:07'),
(36, 199, 3, 8, '2025-12-05 19:18:07', '2025-12-05 19:18:07'),
(37, 199, 4, 13, '2025-12-05 19:18:07', '2025-12-05 19:18:07'),
(38, 200, 1, 4, '2025-12-05 19:21:12', '2025-12-05 19:21:12'),
(39, 200, 2, 2, '2025-12-05 19:21:12', '2025-12-05 19:21:12'),
(40, 200, 3, 8, '2025-12-05 19:21:12', '2025-12-05 19:21:12'),
(41, 200, 4, 13, '2025-12-05 19:21:12', '2025-12-05 19:21:12'),
(42, 201, 1, 4, '2025-12-05 19:22:01', '2025-12-05 19:22:01'),
(43, 201, 2, 2, '2025-12-05 19:22:01', '2025-12-05 19:22:01'),
(44, 201, 3, 8, '2025-12-05 19:22:01', '2025-12-05 19:22:01'),
(45, 201, 4, 13, '2025-12-05 19:22:01', '2025-12-05 19:22:01'),
(46, 202, 1, 4, '2025-12-05 19:22:25', '2025-12-05 19:22:25'),
(47, 202, 2, 2, '2025-12-05 19:22:25', '2025-12-05 19:22:25'),
(48, 202, 3, 8, '2025-12-05 19:22:25', '2025-12-05 19:22:25'),
(49, 202, 4, 13, '2025-12-05 19:22:25', '2025-12-05 19:22:25'),
(50, 203, 1, 4, '2025-12-05 19:23:24', '2025-12-05 19:23:24'),
(51, 203, 2, 2, '2025-12-05 19:23:24', '2025-12-05 19:23:24'),
(52, 203, 3, 8, '2025-12-05 19:23:24', '2025-12-05 19:23:24'),
(53, 203, 4, 13, '2025-12-05 19:23:24', '2025-12-05 19:23:24'),
(54, 204, 5, 16, '2025-12-05 19:23:58', '2025-12-05 19:23:58'),
(55, 205, 1, 1, '2025-12-05 19:26:33', '2025-12-05 19:26:33'),
(56, 205, 2, 2, '2025-12-05 19:26:33', '2025-12-05 19:26:33'),
(57, 205, 3, 8, '2025-12-05 19:26:33', '2025-12-05 19:26:33'),
(58, 205, 4, 13, '2025-12-05 19:26:33', '2025-12-05 19:26:33'),
(59, 206, 5, 16, '2025-12-05 19:27:07', '2025-12-05 19:27:07'),
(60, 207, 1, 4, '2025-12-05 19:28:25', '2025-12-05 19:28:25'),
(61, 207, 2, 2, '2025-12-05 19:28:25', '2025-12-05 19:28:25'),
(62, 207, 3, 8, '2025-12-05 19:28:25', '2025-12-05 19:28:25'),
(63, 207, 4, 13, '2025-12-05 19:28:25', '2025-12-05 19:28:25'),
(64, 208, 1, 1, '2025-12-06 02:18:51', '2025-12-06 02:18:51'),
(65, 208, 2, 2, '2025-12-06 02:18:51', '2025-12-06 02:18:51'),
(66, 208, 4, 13, '2025-12-06 02:18:51', '2025-12-06 02:18:51'),
(67, 210, 1, 1, '2025-12-07 22:16:58', '2025-12-07 22:16:58'),
(68, 210, 2, 2, '2025-12-07 22:16:58', '2025-12-07 22:16:58'),
(69, 210, 3, 8, '2025-12-07 22:16:58', '2025-12-07 22:16:58'),
(70, 210, 4, 13, '2025-12-07 22:16:58', '2025-12-07 22:16:58'),
(71, 211, 5, 16, '2025-12-07 22:42:45', '2025-12-07 22:42:45'),
(72, 213, 1, 1, '2025-12-07 22:44:54', '2025-12-07 22:44:54'),
(73, 213, 2, 2, '2025-12-07 22:44:54', '2025-12-07 22:44:54'),
(74, 213, 3, 8, '2025-12-07 22:44:54', '2025-12-07 22:44:54'),
(75, 213, 4, 13, '2025-12-07 22:44:54', '2025-12-07 22:44:54'),
(76, 214, 5, 16, '2025-12-07 22:45:37', '2025-12-07 22:45:37'),
(77, 215, 1, 3, '2025-12-08 00:30:45', '2025-12-08 00:30:45'),
(78, 215, 2, 2, '2025-12-08 00:30:45', '2025-12-08 00:30:45'),
(79, 215, 3, 8, '2025-12-08 00:30:45', '2025-12-08 00:30:45'),
(80, 215, 4, 13, '2025-12-08 00:30:45', '2025-12-08 00:30:45'),
(81, 216, 1, 4, '2025-12-11 03:17:15', '2025-12-11 03:17:15'),
(82, 216, 2, 2, '2025-12-11 03:17:15', '2025-12-11 03:17:15'),
(83, 216, 3, 8, '2025-12-11 03:17:15', '2025-12-11 03:17:15'),
(84, 216, 4, 13, '2025-12-11 03:17:15', '2025-12-11 03:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `order_totals`
--

CREATE TABLE `order_totals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `total_qty` int(11) NOT NULL DEFAULT 0,
  `total_hari` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_deadline` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_print` int(11) NOT NULL DEFAULT 0,
  `total_press` int(11) NOT NULL DEFAULT 0,
  `total_cutting` int(11) NOT NULL DEFAULT 0,
  `total_jahit` int(11) NOT NULL DEFAULT 0,
  `total_finishing` int(11) NOT NULL DEFAULT 0,
  `total_packing` int(11) NOT NULL DEFAULT 0,
  `total_setting` int(11) NOT NULL DEFAULT 0,
  `total_sisa_setting` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `total_sisa_print` int(11) NOT NULL DEFAULT 0,
  `total_sisa_press` int(11) NOT NULL DEFAULT 0,
  `total_sisa_cutting` int(11) NOT NULL DEFAULT 0,
  `total_sisa_jahit` int(11) NOT NULL DEFAULT 0,
  `total_sisa_finishing` int(11) NOT NULL DEFAULT 0,
  `total_sisa_packing` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_totals`
--

INSERT INTO `order_totals` (`id`, `total_qty`, `total_hari`, `total_deadline`, `total_print`, `total_press`, `total_cutting`, `total_jahit`, `total_finishing`, `total_packing`, `total_setting`, `total_sisa_setting`, `total_sisa_print`, `total_sisa_press`, `total_sisa_cutting`, `total_sisa_jahit`, `total_sisa_finishing`, `total_sisa_packing`, `created_at`, `updated_at`) VALUES
(1, 208, 6.90, 0.10, 206, 206, 206, 206, 206, 206, 21, 1, 2, 2, 2, 2, 2, 2, '2025-11-16 23:46:43', '2025-12-15 07:39:46');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawais`
--

CREATE TABLE `pegawais` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `posisi` varchar(255) DEFAULT NULL,
  `rekening` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pegawais`
--

INSERT INTO `pegawais` (`id`, `nama`, `posisi`, `rekening`, `alamat`, `created_at`, `updated_at`) VALUES
(19, 'ARIP', 'Press', '089789271892', 'Nangela', '2025-11-23 19:00:37', '2025-11-24 22:05:56'),
(20, 'Ridwan', 'Print', '32434232423', 'NANGELA', '2025-11-23 19:01:31', '2025-11-23 19:01:31'),
(21, 'Aldi', 'Cutting', '089789271892', 'NANGELA', '2025-11-23 19:01:54', '2025-11-23 19:01:54'),
(22, 'AEP', 'Jahit', '32434232423', 'WETAN', '2025-11-23 19:02:14', '2025-11-23 19:02:14'),
(23, 'Aldi 2', 'Packing', '089789271892', 'tes', '2025-11-23 19:02:30', '2025-11-23 19:02:30'),
(25, 'Baden', 'Setting', '089789271892', 'Kulon', '2025-11-23 19:04:21', '2025-11-23 19:04:21'),
(27, 'UJENG', 'Press', '089789271892', 'cau', '2025-11-24 05:41:25', '2025-11-24 05:41:25'),
(28, 'una', 'Finishing', '089789271892', 'j', '2025-11-30 18:49:42', '2025-12-02 02:20:05'),
(29, 'bedog', 'Jahit', '089789271892', 'y', '2025-12-02 02:21:19', '2025-12-02 02:21:19');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `alamat`, `no_hp`, `created_at`, `updated_at`) VALUES
(2, 'Barala', NULL, NULL, '2025-12-05 01:34:11', '2025-12-05 01:34:11'),
(5, 'denim', 'o22222', 'tasikmalay', '2025-12-05 18:39:59', '2025-12-05 18:39:59'),
(6, 'bxr tessa', NULL, NULL, '2025-12-05 19:12:12', '2025-12-05 19:12:12'),
(7, 'manbaul', NULL, NULL, '2025-12-05 19:13:36', '2025-12-05 19:13:36'),
(8, 'vina jupeng', NULL, NULL, '2025-12-05 19:17:34', '2025-12-05 19:17:34'),
(9, 'vina medal', NULL, NULL, '2025-12-05 19:21:12', '2025-12-05 19:21:12'),
(10, 'vina ngalungsar', NULL, NULL, '2025-12-05 19:22:01', '2025-12-05 19:22:01'),
(11, 'syifa class asixx manonjaya', NULL, NULL, '2025-12-05 19:23:24', '2025-12-05 19:23:24'),
(12, 'adi ssb galuh', NULL, NULL, '2025-12-05 19:26:33', '2025-12-05 19:26:33'),
(13, 'adi galuh asg', NULL, NULL, '2025-12-05 19:28:25', '2025-12-05 19:28:25'),
(14, 'alves 88', NULL, NULL, '2025-12-06 02:18:51', '2025-12-06 02:18:51'),
(15, 'yanto mcc', NULL, NULL, '2025-12-06 02:20:51', '2025-12-06 02:20:51'),
(16, 'manonjaya fositiv 5', NULL, NULL, '2025-12-07 22:16:58', '2025-12-07 22:16:58'),
(17, 'manbaul susulan', NULL, NULL, '2025-12-07 22:44:00', '2025-12-07 22:44:00'),
(18, 'adi ssb galuh susulan', NULL, NULL, '2025-12-07 22:44:54', '2025-12-07 22:44:54'),
(19, 'gunzalo', NULL, NULL, '2025-12-08 00:30:45', '2025-12-08 00:30:45'),
(20, 'nyacas bike', NULL, NULL, '2025-12-11 03:17:15', '2025-12-11 03:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan_orders`
--

CREATE TABLE `pelanggan_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pelanggan_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan_orders`
--

INSERT INTO `pelanggan_orders` (`id`, `pelanggan_id`, `order_id`, `created_at`, `updated_at`) VALUES
(5, 5, 195, '2025-12-05 19:09:56', '2025-12-05 19:09:56'),
(6, 6, 196, '2025-12-05 19:12:12', '2025-12-05 19:12:12'),
(7, 7, 197, '2025-12-05 19:13:36', '2025-12-05 19:13:36'),
(8, 8, 198, '2025-12-05 19:17:34', '2025-12-05 19:17:34'),
(9, 8, 199, '2025-12-05 19:18:07', '2025-12-05 19:18:07'),
(10, 9, 200, '2025-12-05 19:21:12', '2025-12-05 19:21:12'),
(11, 10, 201, '2025-12-05 19:22:01', '2025-12-05 19:22:01'),
(12, 10, 202, '2025-12-05 19:22:25', '2025-12-05 19:22:25'),
(13, 11, 203, '2025-12-05 19:23:24', '2025-12-05 19:23:24'),
(14, 11, 204, '2025-12-05 19:23:58', '2025-12-05 19:23:58'),
(15, 12, 205, '2025-12-05 19:26:33', '2025-12-05 19:26:33'),
(16, 12, 206, '2025-12-05 19:27:07', '2025-12-05 19:27:07'),
(17, 13, 207, '2025-12-05 19:28:25', '2025-12-05 19:28:25'),
(18, 14, 208, '2025-12-06 02:18:51', '2025-12-06 02:18:51'),
(19, 15, 209, '2025-12-06 02:20:51', '2025-12-06 02:20:51'),
(20, 16, 210, '2025-12-07 22:16:58', '2025-12-07 22:16:58'),
(21, 16, 211, '2025-12-07 22:42:45', '2025-12-07 22:42:45'),
(22, 17, 212, '2025-12-07 22:44:00', '2025-12-07 22:44:00'),
(23, 18, 213, '2025-12-07 22:44:54', '2025-12-07 22:44:54'),
(24, 18, 214, '2025-12-07 22:45:37', '2025-12-07 22:45:37'),
(25, 19, 215, '2025-12-08 00:30:45', '2025-12-08 00:30:45'),
(26, 20, 216, '2025-12-11 03:17:15', '2025-12-11 03:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `xs` varchar(255) NOT NULL,
  `s` varchar(255) NOT NULL,
  `m` varchar(255) NOT NULL,
  `l` varchar(255) NOT NULL,
  `xl` varchar(255) NOT NULL,
  `2xl` varchar(255) NOT NULL,
  `3xl` varchar(255) NOT NULL,
  `4xl` varchar(255) NOT NULL,
  `5xl` varchar(255) NOT NULL,
  `6xl` varchar(255) NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `xs`, `s`, `m`, `l`, `xl`, `2xl`, `3xl`, `4xl`, `5xl`, `6xl`, `order_id`, `created_at`, `updated_at`) VALUES
(28, '0', '0', '2', '3', '0', '0', '0', '0', '0', '0', 195, '2025-12-05 19:09:56', '2025-12-05 19:09:56'),
(29, '0', '0', '2', '0', '0', '0', '0', '0', '0', '0', 196, '2025-12-05 19:12:12', '2025-12-05 19:12:12'),
(30, '0', '0', '5', '6', '4', '0', '0', '0', '0', '0', 197, '2025-12-05 19:13:36', '2025-12-05 19:13:36'),
(31, '0', '1', '2', '1', '0', '0', '0', '0', '0', '0', 198, '2025-12-05 19:17:34', '2025-12-05 19:17:34'),
(32, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', 199, '2025-12-05 19:18:07', '2025-12-05 19:18:07'),
(33, '0', '0', '5', '4', '4', '3', '0', '0', '0', '0', 200, '2025-12-05 19:21:12', '2025-12-05 19:21:12'),
(34, '0', '0', '2', '0', '0', '0', '0', '0', '0', '0', 201, '2025-12-05 19:22:01', '2025-12-05 19:22:01'),
(35, '0', '0', '0', '2', '3', '0', '0', '0', '0', '0', 202, '2025-12-05 19:22:25', '2025-12-05 19:22:25'),
(36, '0', '0', '31', '11', '4', '0', '0', '0', '0', '0', 203, '2025-12-05 19:23:24', '2025-12-05 19:23:24'),
(37, '0', '0', '31', '11', '4', '0', '0', '0', '0', '0', 204, '2025-12-05 19:23:58', '2025-12-05 19:23:58'),
(38, '0', '0', '1', '3', '0', '2', '0', '0', '0', '0', 205, '2025-12-05 19:26:33', '2025-12-05 19:26:33'),
(39, '0', '0', '1', '3', '0', '2', '0', '0', '0', '0', 206, '2025-12-05 19:27:07', '2025-12-05 19:27:07'),
(40, '0', '0', '0', '0', '1', '0', '0', '0', '0', '0', 207, '2025-12-05 19:28:25', '2025-12-05 19:28:25'),
(41, '0', '0', '0', '2', '0', '0', '0', '0', '0', '0', 208, '2025-12-06 02:18:51', '2025-12-06 02:18:51'),
(42, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', 209, '2025-12-06 02:20:51', '2025-12-06 02:20:51'),
(43, '2', '0', '6', '6', '6', '0', '0', '0', '0', '0', 210, '2025-12-07 22:16:58', '2025-12-07 22:16:58'),
(44, '2', '0', '6', '6', '6', '0', '0', '0', '0', '0', 211, '2025-12-07 22:42:45', '2025-12-07 22:42:45'),
(45, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', 212, '2025-12-07 22:44:00', '2025-12-07 22:44:00'),
(46, '0', '0', '1', '2', '0', '0', '0', '0', '0', '0', 213, '2025-12-07 22:44:54', '2025-12-07 22:44:54'),
(47, '0', '0', '1', '2', '0', '0', '0', '0', '0', '0', 214, '2025-12-07 22:45:37', '2025-12-07 22:45:37'),
(48, '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', 215, '2025-12-08 00:30:45', '2025-12-08 00:30:45'),
(49, '0', '2', '0', '0', '0', '0', '0', '0', '0', '0', 216, '2025-12-11 03:17:15', '2025-12-11 03:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `step_price`
--

CREATE TABLE `step_price` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_step` varchar(255) NOT NULL,
  `harga_step` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `step_price`
--

INSERT INTO `step_price` (`id`, `nama_step`, `harga_step`, `created_at`, `updated_at`) VALUES
(1, 'Print', 3000, '2025-11-20 14:12:48', '2025-11-20 14:20:39'),
(2, 'Press', 5000, '2025-11-20 14:17:03', '2025-11-20 14:17:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'bugevile', 'Administrator', 'admin@local.test', NULL, '$2y$12$iXUgazxIh.JfKUTqETvuMes9wwyZFfPpITa.Xhk7qKmRtluM0lsSu', NULL, '2025-11-28 11:28:43', '2025-11-28 11:28:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `casbons`
--
ALTER TABLE `casbons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `casbons_pegawai_id_foreign` (`pegawai_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `harga_jenis_pekerjaan`
--
ALTER TABLE `harga_jenis_pekerjaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `harga_jobs`
--
ALTER TABLE `harga_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `harga_jobs_job_id_foreign` (`job_id`);

--
-- Indexes for table `jenis_bahan`
--
ALTER TABLE `jenis_bahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenis_bahan_id_kategori_jenis_order_foreign` (`id_kategori_jenis_order`);

--
-- Indexes for table `jenis_jahitan`
--
ALTER TABLE `jenis_jahitan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_kerah`
--
ALTER TABLE `jenis_kerah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_order`
--
ALTER TABLE `jenis_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenis_order_id_kategori_jenis_order_foreign` (`id_kategori_jenis_order`);

--
-- Indexes for table `jenis_pola`
--
ALTER TABLE `jenis_pola`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_spek`
--
ALTER TABLE `jenis_spek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenis_spek_id_kategori_jenis_order_foreign` (`id_kategori_jenis_order`);

--
-- Indexes for table `jenis_spek_detail`
--
ALTER TABLE `jenis_spek_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenis_spek_detail_id_jenis_spek_foreign` (`id_jenis_spek`),
  ADD KEY `jenis_spek_detail_id_jenis_order_foreign` (`id_jenis_order`);

--
-- Indexes for table `jenis_spek_detail_jenis_order`
--
ALTER TABLE `jenis_spek_detail_jenis_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jenis_spek_detail_jenis_order_jenis_spek_detail_id_foreign` (`jenis_spek_detail_id`),
  ADD KEY `jenis_spek_detail_jenis_order_jenis_order_id_foreign` (`jenis_order_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_jenis_order`
--
ALTER TABLE `kategori_jenis_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_slug_unique` (`slug`),
  ADD KEY `orders_jenis_order_id_foreign` (`jenis_order_id`);

--
-- Indexes for table `order_histories`
--
ALTER TABLE `order_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_histories_order_id_foreign` (`order_id`),
  ADD KEY `order_histories_pegawai_id_foreign` (`pegawai_id`);

--
-- Indexes for table `order_spesifikasi`
--
ALTER TABLE `order_spesifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_spesifikasi_order_id_foreign` (`order_id`),
  ADD KEY `order_spesifikasi_jenis_spek_id_foreign` (`jenis_spek_id`),
  ADD KEY `order_spesifikasi_jenis_spek_detail_id_foreign` (`jenis_spek_detail_id`);

--
-- Indexes for table `order_totals`
--
ALTER TABLE `order_totals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pegawais`
--
ALTER TABLE `pegawais`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelanggan_orders`
--
ALTER TABLE `pelanggan_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pelanggan_orders_pelanggan_id_order_id_unique` (`pelanggan_id`,`order_id`),
  ADD KEY `pelanggan_orders_order_id_foreign` (`order_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `size_order_id_foreign` (`order_id`);

--
-- Indexes for table `step_price`
--
ALTER TABLE `step_price`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `casbons`
--
ALTER TABLE `casbons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `harga_jenis_pekerjaan`
--
ALTER TABLE `harga_jenis_pekerjaan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `harga_jobs`
--
ALTER TABLE `harga_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_bahan`
--
ALTER TABLE `jenis_bahan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jenis_jahitan`
--
ALTER TABLE `jenis_jahitan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jenis_kerah`
--
ALTER TABLE `jenis_kerah`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jenis_order`
--
ALTER TABLE `jenis_order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `jenis_pola`
--
ALTER TABLE `jenis_pola`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jenis_spek`
--
ALTER TABLE `jenis_spek`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jenis_spek_detail`
--
ALTER TABLE `jenis_spek_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `jenis_spek_detail_jenis_order`
--
ALTER TABLE `jenis_spek_detail_jenis_order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `kategori_jenis_order`
--
ALTER TABLE `kategori_jenis_order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `order_histories`
--
ALTER TABLE `order_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=566;

--
-- AUTO_INCREMENT for table `order_spesifikasi`
--
ALTER TABLE `order_spesifikasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `order_totals`
--
ALTER TABLE `order_totals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pegawais`
--
ALTER TABLE `pegawais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pelanggan_orders`
--
ALTER TABLE `pelanggan_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `step_price`
--
ALTER TABLE `step_price`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `casbons`
--
ALTER TABLE `casbons`
  ADD CONSTRAINT `casbons_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawais` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `harga_jobs`
--
ALTER TABLE `harga_jobs`
  ADD CONSTRAINT `harga_jobs_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jenis_bahan`
--
ALTER TABLE `jenis_bahan`
  ADD CONSTRAINT `jenis_bahan_id_kategori_jenis_order_foreign` FOREIGN KEY (`id_kategori_jenis_order`) REFERENCES `kategori_jenis_order` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `jenis_order`
--
ALTER TABLE `jenis_order`
  ADD CONSTRAINT `jenis_order_id_kategori_jenis_order_foreign` FOREIGN KEY (`id_kategori_jenis_order`) REFERENCES `kategori_jenis_order` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jenis_spek`
--
ALTER TABLE `jenis_spek`
  ADD CONSTRAINT `jenis_spek_id_kategori_jenis_order_foreign` FOREIGN KEY (`id_kategori_jenis_order`) REFERENCES `kategori_jenis_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jenis_spek_detail`
--
ALTER TABLE `jenis_spek_detail`
  ADD CONSTRAINT `jenis_spek_detail_id_jenis_order_foreign` FOREIGN KEY (`id_jenis_order`) REFERENCES `jenis_order` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `jenis_spek_detail_id_jenis_spek_foreign` FOREIGN KEY (`id_jenis_spek`) REFERENCES `jenis_spek` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jenis_spek_detail_jenis_order`
--
ALTER TABLE `jenis_spek_detail_jenis_order`
  ADD CONSTRAINT `jenis_spek_detail_jenis_order_jenis_order_id_foreign` FOREIGN KEY (`jenis_order_id`) REFERENCES `jenis_order` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jenis_spek_detail_jenis_order_jenis_spek_detail_id_foreign` FOREIGN KEY (`jenis_spek_detail_id`) REFERENCES `jenis_spek_detail` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_jenis_order_id_foreign` FOREIGN KEY (`jenis_order_id`) REFERENCES `jenis_order` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_histories`
--
ALTER TABLE `order_histories`
  ADD CONSTRAINT `order_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_histories_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawais` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_spesifikasi`
--
ALTER TABLE `order_spesifikasi`
  ADD CONSTRAINT `order_spesifikasi_jenis_spek_detail_id_foreign` FOREIGN KEY (`jenis_spek_detail_id`) REFERENCES `jenis_spek_detail` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_spesifikasi_jenis_spek_id_foreign` FOREIGN KEY (`jenis_spek_id`) REFERENCES `jenis_spek` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_spesifikasi_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pelanggan_orders`
--
ALTER TABLE `pelanggan_orders`
  ADD CONSTRAINT `pelanggan_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pelanggan_orders_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sizes`
--
ALTER TABLE `sizes`
  ADD CONSTRAINT `size_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
