-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2025 at 10:23 PM
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
(11, 22, 28000, 'MUDIK', '2025-11-27', '2025-11-27 00:16:40', '2025-11-27 00:16:40');

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
(1, 75000, 1000, 300, 600, 5000, 600, 1000, '2025-11-28 00:15:30', '2025-11-30 17:03:34');

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
  `nama` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_bahan`
--

INSERT INTO `jenis_bahan` (`id`, `nama`, `gambar`, `created_at`, `updated_at`) VALUES
(1, 'BENZEMA', 'jenis_bahan/XCwRefEsQqfOjEv6BJbKemBpIi3uHUpASy8RVmwg.jpg', '2025-11-28 23:17:52', '2025-11-28 23:17:52'),
(2, 'Waffle', 'jenis_bahan/Ml3vHdSTeoFChZicr8m4U5NFKe8F9m7cGPZrAiZZ.jpg', '2025-11-28 23:26:32', '2025-11-28 23:26:32'),
(3, 'Brazil', 'jenis_bahan/2CAQnPD6eoO2jnO3A4ywQvuveNEDg1xoaEADLpBF.jpg', '2025-11-28 23:27:01', '2025-11-28 23:27:01'),
(4, 'Milano', 'jenis_bahan/ZRH0sbLcUaaQ4YqcFHnbx80S0ejkATeBhLS0egCK.jpg', '2025-11-28 23:27:29', '2025-11-28 23:27:29');

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
(3, 'Bawah Overdeck', 'jenis_bahan/SMDKuzr8pylxKTFf1OyxZA5zG5cSeSKWs2fu5WUp.jpg', '2025-11-28 23:35:11', '2025-11-28 23:35:11'),
(4, 'Karet Kansai', 'jenis_bahan/V4Hezlsnj44ViiXivSQpdXbLPiFRxwQREzT0BOtX.jpg', '2025-11-28 23:35:25', '2025-11-28 23:35:25');

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
(15, 'Pendek', 1, 3, '2025-11-26 22:46:01', '2025-11-26 22:46:01'),
(17, 'Hoodie', 1, 5, '2025-11-26 22:46:24', '2025-11-26 22:46:24'),
(18, 'Lekbong', 1, 2, '2025-11-26 22:46:35', '2025-11-26 22:46:35'),
(19, 'Sabelah', 20, 1, '2025-11-26 22:46:51', '2025-11-26 22:46:51'),
(20, 'Full Print', 20, 4, '2025-11-26 22:47:01', '2025-11-26 22:47:01'),
(21, 'yamaha', 1, 1, '2025-11-28 20:05:12', '2025-11-28 20:05:12');

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
(5, 'JERSEY MTB', '2025-11-28 01:13:07', '2025-11-28 01:13:07'),
(6, 'JERSEY BOLA', '2025-11-28 01:14:32', '2025-11-28 01:14:32'),
(7, 'JAKET', '2025-11-28 01:18:26', '2025-11-28 01:18:26'),
(8, 'JERSEY', '2025-11-28 01:21:32', '2025-11-28 01:21:32'),
(9, 'CELANA BOLA', '2025-11-28 01:24:21', '2025-11-28 01:24:21'),
(10, 'JERSEY ROAD BIKE', '2025-11-28 01:25:45', '2025-11-28 01:25:45'),
(11, 'JERSEY MANCING', '2025-11-28 01:28:21', '2025-11-28 01:28:21'),
(12, 'kao oblong', '2025-11-30 17:18:21', '2025-11-30 17:18:21'),
(13, 'jersey running', '2025-11-30 18:00:33', '2025-11-30 18:00:33');

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
(45, '2025_11_29_064839_add_spesifikasi_to_orders_table', 38);

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
  `keterangan` text NOT NULL DEFAULT 'gaada',
  -- removed unused spesifikasi columns: id_jenis_bahan, id_jenis_pola, id_jenis_kerah, id_jenis_jahitan
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `jenis_order_id`, `nama_job`, `slug`, `qty`, `hari`, `deadline`, `setting`, `print`, `press`, `cutting`, `jahit`, `finishing`, `packing`, `est`, `sisa_print`, `total_lembar_print`, `total_lembar_press`, `sisa_press`, `sisa_cutting`, `sisa_jahit`, `sisa_finishing`, `sisa_packing`, `created_at`, `updated_at`, `nama_konsumen`, `keterangan`) VALUES
(145, 15, 'kao oblong', 'kao-oblong', 148, 4.90, 4.90, 0, 0, 0, 0, 0, 0, 0, 4.90, 148, 0, 0, 148, 148, 148, 148, 148, '2025-11-30 17:18:21', '2025-11-30 17:18:21', 'ali tegal', 'Bahan: pe | Pola: Reglan | Kerah: O-Neck | Jahitan: Bawah Overdeck'),
(147, 15, 'JERSEY BOLA', 'jersey-bola-1', 18, 0.60, 0.60, 1, 18, 0, 0, 0, 0, 0, 0.60, 0, 54, 0, 18, 18, 18, 18, 18, '2025-11-30 17:55:13', '2025-11-30 18:38:59', 'manonjaya sorum', 'Bahan: BENZEMA | Pola: Reguler | Kerah: V-Neck | Jahitan: Samping Flatlock(nomor dar 2-19)'),
(148, 15, 'jersey running', 'jersey-running', 5, 0.20, 0.00, 1, 5, 5, 5, 5, 5, 5, 0.20, 0, 15, 15, 0, 0, 0, 0, 0, '2025-11-30 18:00:33', '2025-11-30 18:16:15', 'denim', 'Bahan: Milano | Pola: Reguler | Kerah: O-Neck | Jahitan: Pundak Rante'),
(149, 15, 'CELANA BOLA', 'celana-bola', 45, 1.50, 1.50, 0, 0, 0, 0, 0, 0, 0, 1.50, 45, 0, 0, 45, 45, 45, 45, 45, '2025-11-30 18:02:38', '2025-11-30 18:02:38', 'smp manon jaya', 'Bahan: BENZEMA | Pola: Reguler | Kerah: V-Neck | Jahitan: Samping Flatlock'),
(150, 14, 'JERSEY MTB', 'jersey-mtb', 1, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, 0.00, 1, 0, 0, 1, 1, 1, 1, 1, '2025-11-30 21:05:11', '2025-11-30 21:05:11', 'BARALA', 'Bahan: BENZEMA | Pola: Sambungan Pinggir | Kerah: V-Neck | Jahitan: Samping Flatlock');

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
(390, 148, 25, 'Setting', 1, '1', 'jersey running', '2025-11-30 18:11:15', '2025-11-30 18:11:15'),
(391, 147, 25, 'Setting', 1, '1', 'JERSEY BOLA', '2025-11-30 18:12:15', '2025-11-30 18:12:15'),
(392, 148, 20, 'Print', 5, 'no rijek', 'jersey running', '2025-11-30 18:13:23', '2025-11-30 18:13:23'),
(393, 148, 27, 'Press', 5, 'no rijek', 'jersey running', '2025-11-30 18:13:44', '2025-11-30 18:13:44'),
(394, 148, 21, 'Cutting', 5, 'no rijek', 'jersey running', '2025-11-30 18:14:07', '2025-11-30 18:14:07'),
(395, 148, 22, 'Jahit', 5, 'no rikek', 'jersey running', '2025-11-30 18:15:22', '2025-11-30 18:15:22'),
(396, 148, 26, 'Finishing', 5, 'mulus', 'jersey running', '2025-11-30 18:15:56', '2025-11-30 18:15:56'),
(397, 148, 23, 'Packing', 5, 'mulus', 'jersey running', '2025-11-30 18:16:15', '2025-11-30 18:16:15'),
(398, 147, 20, 'Print', 18, 'tanp rijek', 'JERSEY BOLA', '2025-11-30 18:38:59', '2025-11-30 18:38:59');

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
(1, 217, 7.20, 7.00, 23, 5, 5, 5, 5, 5, 2, 3, 194, 212, 212, 212, 212, 212, '2025-11-16 23:46:43', '2025-11-30 21:08:44');

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
(26, 'rafli', 'finising', '089789271892', 'tes', '2025-11-23 19:47:55', '2025-11-30 18:48:50'),
(27, 'UJENG', 'Press', '089789271892', 'cau', '2025-11-24 05:41:25', '2025-11-24 05:41:25'),
(28, 'Una', 'Finishing', '089789271892', 'j', '2025-11-30 18:49:42', '2025-11-30 18:49:42');

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
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `jenis_pola`
--
ALTER TABLE `jenis_pola`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `kategori_jenis_order`
--
ALTER TABLE `kategori_jenis_order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `order_histories`
--
ALTER TABLE `order_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=399;

--
-- AUTO_INCREMENT for table `order_totals`
--
ALTER TABLE `order_totals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pegawais`
--
ALTER TABLE `pegawais`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `jenis_order`
--
ALTER TABLE `jenis_order`
  ADD CONSTRAINT `jenis_order_id_kategori_jenis_order_foreign` FOREIGN KEY (`id_kategori_jenis_order`) REFERENCES `kategori_jenis_order` (`id`) ON DELETE SET NULL;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
