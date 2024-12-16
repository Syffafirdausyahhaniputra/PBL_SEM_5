-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 16, 2024 at 03:40 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pbl_sem_5`
--

-- --------------------------------------------------------

--
-- Table structure for table `dosens`
--

CREATE TABLE `dosens` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosens`
--

INSERT INTO `dosens` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Zulfa Ulinnuha', 'Dosen Jurusan Teknologi Informasi', '2024-11-19 22:42:58', '2024-11-19 22:42:58'),
(2, 'Nama Dosen A', 'Bidang A', '2024-11-19 22:42:58', '2024-11-19 22:42:58'),
(3, 'Nama Dosen B', 'Bidang B', '2024-11-19 22:42:58', '2024-11-19 22:42:58'),
(4, 'Nama Dosen C', 'Bidang C', '2024-11-19 22:42:58', '2024-11-19 22:42:58');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2024_11_08_041404_create_m_role_table', 1),
(3, '2024_11_08_041730_create_m_bidang_table', 1),
(4, '2024_11_08_041855_create_m_matkul_table', 1),
(5, '2024_11_08_043115_create_m_user_table', 2),
(6, '2024_11_08_044100_create_m_dosen_table', 3),
(7, '2024_11_10_122913_create_m_jenis_table', 4),
(8, '2024_11_10_123115_create_m_vendor_table', 4),
(9, '2024_11_10_123532_create_t_sertifikasi_table', 5),
(10, '2024_11_10_125554_create_m_level_pelatihan_table', 5),
(11, '2024_11_10_125905_create_t_pelatihan_table', 6),
(12, '2024_11_10_130748_create_t_data_sertifikasi_table', 7),
(13, '2024_11_10_131420_create_t_data_pelatihan_table', 7),
(14, '2024_11_18_074932_create_bidangs_table', 8),
(15, '2024_11_20_043300_create_m_prodi_table', 8),
(16, '2024_11_20_044320_create_m_kompetensi_prodi_table', 9),
(17, '2024_11_20_054037_create_dosens_table', 10),
(20, '2024_11_21_041246_create_m_jabatan_table', 11),
(21, '2024_11_21_041337_create_m_golongan_table', 11),
(22, '2024_11_21_041709_add_jabatan_id_golongan_id_column_in_m_dosen_table', 11),
(23, '2024_11_21_044906_create_m_surat_tugas_table', 12),
(24, '2024_11_21_085612_create_rekomendasi_table', 13),
(25, '2024_11_30_142122_add_biaya_tanggal_akhir_column_in_t_sertifikasi_table', 14),
(26, '2024_11_30_142542_add_biaya_tanggal_akhir_column_in_t_pelatihan_table', 14),
(27, '2024_11_30_143409_add_sertifikat_surat_tugas_id_column_in_t_data_sertifikasi_table', 15),
(28, '2024_11_30_143852_add_sertifikat_surat_tugas_id_column_in_t_data_pelatihan_table', 15),
(29, '2024_11_30_144538_update_m_surat_tugas_table', 16),
(30, '2024_12_03_080251_create_m_dosen_bidang_table', 17),
(31, '2024_12_03_080307_create_m_dosen_matkul_table', 17),
(32, '2024_12_03_081225_remove_columns_bidang_id_mk_id_m_dosen_table', 18),
(33, '2024_12_07_114731_add_email_column_in_m_user_table', 19),
(34, '2024_12_11_090716_create_m_pangkat_table', 20),
(35, '2024_12_11_090939_add_pangkat_id_column_in_m_dosen_table', 21);

-- --------------------------------------------------------

--
-- Table structure for table `m_bidang`
--

CREATE TABLE `m_bidang` (
  `bidang_id` bigint UNSIGNED NOT NULL,
  `bidang_kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bidang_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_bidang`
--

INSERT INTO `m_bidang` (`bidang_id`, `bidang_kode`, `bidang_nama`, `created_at`, `updated_at`) VALUES
(1, 'IT', 'Teknologi Informasi', '2024-11-10 13:39:41', '2024-11-10 13:39:41'),
(2, 'CLC', 'Cloud Computing', '2024-11-20 05:17:10', '2024-11-20 05:17:10'),
(3, 'DTM', 'Data Mining', '2024-11-20 05:17:26', '2024-11-20 05:17:26'),
(4, 'MPS', 'Manajemen Pemasaran', '2024-11-20 05:17:44', '2024-11-20 05:17:44'),
(5, '5', 'Algoritma Evolusioner', '2024-12-03 03:13:11', NULL),
(6, '6', 'Analisis Data', '2024-12-03 03:13:11', NULL),
(7, '7', 'Aplikasi Permainan', '2024-12-03 03:13:11', NULL),
(8, '8', 'Artificial Intelligence', '2024-12-03 03:13:11', NULL),
(9, '9', 'Attention Based RNN', '2024-12-03 03:13:11', NULL),
(10, '10', 'Augmented Reality (AR)', '2024-12-03 03:13:11', NULL),
(11, '11', 'Big Data', '2024-12-03 03:13:11', NULL),
(12, '12', 'Clustering', '2024-12-03 03:13:11', NULL),
(13, '13', 'Cognitive Artificial Intelligence', '2024-12-03 03:13:11', NULL),
(14, '14', 'Data Analysis', '2024-12-03 03:13:11', NULL),
(15, '15', 'Data Mining', '2024-12-03 03:13:11', NULL),
(16, '16', 'Data Science', '2024-12-03 03:13:11', NULL),
(17, '17', 'Data Warehouse', '2024-12-03 03:13:11', NULL),
(18, '18', 'Decision Support System', '2024-12-03 03:13:11', NULL),
(19, '19', 'Deep Learning', '2024-12-03 03:13:11', NULL),
(20, '20', 'Defense Technology', '2024-12-03 03:13:11', NULL),
(21, '21', 'Enterprise Resource Planning (ERP)', '2024-12-03 03:13:11', NULL),
(22, '22', 'Fake News Detection', '2024-12-03 03:13:11', NULL),
(23, '23', 'Game', '2024-12-03 03:13:11', NULL),
(24, '24', 'Geographic Information System (GIS)', '2024-12-03 03:13:11', NULL),
(25, '25', 'Human Computer Interaction (HCI)', '2024-12-03 03:13:11', NULL),
(26, '26', 'Image Processing', '2024-12-03 03:13:11', NULL),
(27, '27', 'Information Fusion', '2024-12-03 03:13:11', NULL),
(28, '28', 'Information Retrieval', '2024-12-03 03:13:11', NULL),
(29, '29', 'Infrastruktur Server dan Jaringan', '2024-12-03 03:13:11', NULL),
(30, '30', 'Internet of Things (IOT)', '2024-12-03 03:13:11', NULL),
(31, '31', 'keamanan Informasi Jaringan', '2024-12-03 03:13:11', NULL),
(32, '32', 'Keamanan Jaringan', '2024-12-03 03:13:11', NULL),
(33, '33', 'Kecerdasan Buatan', '2024-12-03 03:13:11', NULL),
(34, '34', 'Kecerdasan Komputasional', '2024-12-03 03:13:11', NULL),
(35, '35', 'Klasifikasi', '2024-12-03 03:13:11', NULL),
(36, '36', 'Komputasi Awan', '2024-12-03 03:13:11', NULL),
(37, '37', 'Komputasi Berbasis Jaringan', '2024-12-03 03:13:11', NULL),
(38, '38', 'Large Language Model (LLM)', '2024-12-03 03:13:11', NULL),
(39, '39', 'Learning Engineering', '2024-12-03 03:13:11', NULL),
(40, '40', 'Learning Engineering Technology (LET)', '2024-12-03 03:13:11', NULL),
(41, '41', 'Machine Learning', '2024-12-03 03:13:11', NULL),
(42, '42', 'Mobile Application', '2024-12-03 03:13:11', NULL),
(43, '43', 'Multimedia', '2024-12-03 03:13:11', NULL),
(44, '44', 'Natural Language Processing (NLP)', '2024-12-03 03:13:11', NULL),
(45, '45', 'Optical Character Recognition (OCR)', '2024-12-03 03:13:11', NULL),
(46, '46', 'Optimasi Basis Data', '2024-12-03 03:13:11', NULL),
(47, '47', 'Pattern Recognition', '2024-12-03 03:13:11', NULL),
(48, '48', 'Pembelajaran Mesin', '2024-12-03 03:13:11', NULL),
(49, '49', 'Pengembangan Teknologi Mobile', '2024-12-03 03:13:11', NULL),
(50, '50', 'Pengolahan Citra', '2024-12-03 03:13:11', NULL),
(51, '51', 'Quality Assurance', '2024-12-03 03:13:11', NULL),
(52, '52', 'Recommender System', '2024-12-03 03:13:11', NULL),
(53, '53', 'Reinforcement Learning ', '2024-12-03 03:13:11', NULL),
(54, '54', 'Rekayasa Perangkat Lunak', '2024-12-03 03:13:11', NULL),
(55, '55', 'Semantic Analysis', '2024-12-03 03:13:11', NULL),
(56, '56', 'Sentiment Analysis', '2024-12-03 03:13:11', NULL),
(57, '57', 'Sintactic Analysis', '2024-12-03 03:13:11', NULL),
(58, '58', 'Sistem Cerdas', '2024-12-03 03:13:11', NULL),
(59, '59', 'Sistem Informasi', '2024-12-03 03:13:11', NULL),
(60, '60', 'Sistem Pendukung Keputusan (SPK)', '2024-12-03 03:13:11', NULL),
(61, '61', 'Sistem Prediksi', '2024-12-03 03:13:11', NULL),
(62, '62', 'Sistem Rekomendasi', '2024-12-03 03:13:11', NULL),
(63, '63', 'Software Engineering', '2024-12-03 03:13:11', NULL),
(64, '64', 'Surveillance Information Systems', '2024-12-03 03:13:11', NULL),
(65, '65', 'Tata kelola Teknologi Informasi', '2024-12-03 03:13:11', NULL),
(66, '66', 'Technology Enhanced Learning', '2024-12-03 03:13:11', NULL),
(67, '67', 'Teknologi Jaringan', '2024-12-03 03:13:11', NULL),
(68, '68', 'Teknologi Media', '2024-12-03 03:13:11', NULL),
(69, '69', 'Text Mining', '2024-12-03 03:13:11', NULL),
(70, '70', 'Text Processing.', '2024-12-03 03:13:11', NULL),
(71, '71', 'Text Summarization', '2024-12-03 03:13:11', NULL),
(72, '72', 'Topic Modelling', '2024-12-03 03:13:11', NULL),
(73, '73', 'UI/UX', '2024-12-03 03:13:11', NULL),
(74, '74', 'UMKM', '2024-12-03 03:13:11', NULL),
(75, '75', 'Virtual Reality (VR)', '2024-12-03 03:13:11', NULL),
(76, '76', 'Visualisasi', '2024-12-03 03:13:11', NULL),
(77, '77', 'Visualisasi Data', '2024-12-03 03:13:11', NULL),
(78, '78', 'Wireless Technology', '2024-12-03 03:13:11', NULL),
(79, '79', 'Biometrics', '2024-12-03 03:13:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_dosen`
--

CREATE TABLE `m_dosen` (
  `dosen_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `pangkat_id` bigint UNSIGNED DEFAULT NULL,
  `golongan_id` bigint UNSIGNED DEFAULT NULL,
  `jabatan_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_dosen`
--

INSERT INTO `m_dosen` (`dosen_id`, `user_id`, `pangkat_id`, `golongan_id`, `jabatan_id`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 4, 2, '2024-11-10 13:55:38', '2024-12-15 19:42:52'),
(2, 4, NULL, NULL, NULL, '2024-11-10 13:56:44', '2024-11-10 13:56:44'),
(3, 5, NULL, NULL, NULL, '2024-11-20 01:00:16', '2024-11-20 01:00:16'),
(4, 6, NULL, NULL, NULL, '2024-11-20 01:00:16', '2024-11-20 01:00:16'),
(7, 6, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(8, 167, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(9, 169, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(10, 170, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(11, 172, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(12, 173, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(13, 175, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(14, 177, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(15, 179, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(16, 180, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(17, 181, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(18, 182, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(19, 183, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(20, 184, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(21, 185, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(22, 186, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(23, 187, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(24, 188, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(25, 189, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(26, 190, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(27, 191, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(28, 192, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(29, 193, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(30, 194, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(31, 195, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(32, 197, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(33, 198, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(34, 199, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(35, 200, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(36, 201, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(37, 202, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(38, 204, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(39, 205, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(40, 206, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(41, 207, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(42, 208, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(43, 212, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(44, 213, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(45, 214, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(46, 215, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(47, 216, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(48, 217, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(49, 218, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(50, 219, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(51, 220, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(52, 221, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(53, 222, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(54, 224, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(55, 225, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(56, 226, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(57, 227, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(58, 229, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(59, 231, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(60, 233, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(61, 235, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(62, 236, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(63, 237, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(64, 238, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(65, 240, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(66, 242, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51'),
(67, 243, NULL, NULL, NULL, '2024-12-03 04:31:51', '2024-12-03 04:31:51');

-- --------------------------------------------------------

--
-- Table structure for table `m_dosen_bidang`
--

CREATE TABLE `m_dosen_bidang` (
  `dosen_bidang_id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `bidang_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_dosen_bidang`
--

INSERT INTO `m_dosen_bidang` (`dosen_bidang_id`, `dosen_id`, `bidang_id`, `created_at`, `updated_at`) VALUES
(1, 1, 4, NULL, NULL),
(2, 1, 1, NULL, NULL),
(3, 1, 2, NULL, NULL),
(4, 2, 1, NULL, NULL),
(5, 2, 3, NULL, NULL),
(6, 3, 4, NULL, NULL),
(7, 3, 3, NULL, NULL),
(8, 4, 2, NULL, NULL),
(9, 4, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_dosen_matkul`
--

CREATE TABLE `m_dosen_matkul` (
  `dosen_matkul_id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `mk_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_dosen_matkul`
--

INSERT INTO `m_dosen_matkul` (`dosen_matkul_id`, `dosen_id`, `mk_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 2, 1, NULL, NULL),
(5, 2, 3, NULL, NULL),
(6, 3, 2, NULL, NULL),
(7, 3, 3, NULL, NULL),
(8, 4, 1, NULL, NULL),
(9, 4, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_golongan`
--

CREATE TABLE `m_golongan` (
  `golongan_id` bigint UNSIGNED NOT NULL,
  `golongan_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_golongan`
--

INSERT INTO `m_golongan` (`golongan_id`, `golongan_nama`, `created_at`, `updated_at`) VALUES
(1, 'III-A', NULL, NULL),
(2, 'III-B', NULL, NULL),
(3, 'III-C', NULL, NULL),
(4, 'IV-A', NULL, NULL),
(5, 'IV-B', NULL, NULL),
(6, 'IV-C', NULL, NULL),
(7, 'IV-D', NULL, NULL),
(8, 'IV-E', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_jabatan`
--

CREATE TABLE `m_jabatan` (
  `jabatan_id` bigint UNSIGNED NOT NULL,
  `jabatan_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_jabatan`
--

INSERT INTO `m_jabatan` (`jabatan_id`, `jabatan_nama`, `created_at`, `updated_at`) VALUES
(1, 'Tenaga Pengajar', NULL, NULL),
(2, 'Asisten Ahli', NULL, NULL),
(3, 'Lektor', NULL, NULL),
(4, 'Lektor Kepala', NULL, NULL),
(5, 'Guru Besar', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_jenis`
--

CREATE TABLE `m_jenis` (
  `jenis_id` bigint UNSIGNED NOT NULL,
  `jenis_kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_jenis`
--

INSERT INTO `m_jenis` (`jenis_id`, `jenis_kode`, `jenis_nama`, `created_at`, `updated_at`) VALUES
(1, 'PROF', 'Profesi', '2024-11-10 13:40:52', '2024-11-10 13:40:52'),
(2, 'AHLI', 'Keahlian', '2024-11-10 13:40:52', '2024-11-10 13:40:52');

-- --------------------------------------------------------

--
-- Table structure for table `m_kompetensi_prodi`
--

CREATE TABLE `m_kompetensi_prodi` (
  `kompetensi_prodi_id` bigint UNSIGNED NOT NULL,
  `prodi_id` bigint UNSIGNED NOT NULL,
  `bidang_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_kompetensi_prodi`
--

INSERT INTO `m_kompetensi_prodi` (`kompetensi_prodi_id`, `prodi_id`, `bidang_id`, `created_at`, `updated_at`) VALUES
(10, 2, 1, '2024-11-25 23:30:15', '2024-11-25 23:30:15'),
(11, 2, 2, '2024-11-25 23:30:31', '2024-11-25 23:30:31'),
(12, 3, 3, '2024-11-25 23:31:19', '2024-11-25 23:31:19'),
(13, 3, 4, '2024-11-25 23:31:19', '2024-11-25 23:31:19'),
(15, 2, 3, '2024-11-25 23:36:36', '2024-11-25 23:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `m_level_pelatihan`
--

CREATE TABLE `m_level_pelatihan` (
  `level_id` bigint UNSIGNED NOT NULL,
  `level_kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_level_pelatihan`
--

INSERT INTO `m_level_pelatihan` (`level_id`, `level_kode`, `level_nama`, `created_at`, `updated_at`) VALUES
(1, 'NAS', 'Nasional', '2024-11-10 13:41:51', '2024-11-10 13:41:51'),
(2, 'INTER', 'Internasional', '2024-11-10 13:41:51', '2024-11-10 13:41:51');

-- --------------------------------------------------------

--
-- Table structure for table `m_matkul`
--

CREATE TABLE `m_matkul` (
  `mk_id` bigint UNSIGNED NOT NULL,
  `mk_kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mk_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_matkul`
--

INSERT INTO `m_matkul` (`mk_id`, `mk_kode`, `mk_nama`, `created_at`, `updated_at`) VALUES
(1, 'SO', 'Sistem Operasi', '2024-11-10 13:44:25', '2024-11-10 13:44:25'),
(2, 'PWEB', 'Pemrograman Web', '2024-11-10 13:44:25', '2024-11-10 13:44:25'),
(3, 'PMOB', 'Pemrograman Mobile', '2024-11-10 13:45:23', '2024-11-10 13:45:23'),
(4, 'BDL', 'Basis Data Lanjut', '2024-12-03 03:49:54', NULL),
(5, 'PCL', 'Pancasila', '2024-12-03 03:49:54', NULL),
(6, 'TD', 'Teknik Dokumentasi', '2024-12-03 03:49:54', NULL),
(7, 'IKO', 'Ilmu Komunikasi Dan Organisasi', '2024-12-03 03:49:54', NULL),
(8, 'AKP', 'Aplikasi Komputer Perkantoran', '2024-12-03 03:49:54', NULL),
(9, 'BI1', 'Bahasa Inggris 1', '2024-12-03 03:49:54', NULL),
(10, 'KTI', 'Konsep Teknologi Informasi', '2024-12-03 03:49:54', NULL),
(11, 'MD', 'Matematika Diskrit', '2024-12-03 03:49:54', NULL),
(12, 'K3', 'Keselamatan Dan Kesehatan Kerja', '2024-12-03 03:49:54', NULL),
(13, 'DP', 'Dasar Pemrograman', '2024-12-03 03:49:54', NULL),
(14, 'PDP', 'Praktikum Dasar Pemrograman', '2024-12-03 03:49:54', NULL),
(15, 'DA', 'Desain Antarmuka', '2024-12-03 03:49:54', NULL),
(16, 'ADBO', 'Analisis Dan Desain Berorientasi Objek', '2024-12-03 03:49:54', NULL),
(17, 'KB', 'Kecerdasan Buatan', '2024-12-03 03:49:54', NULL),
(18, 'DPW', 'Desain & Pemrograman Web', '2024-12-03 03:49:54', NULL),
(19, 'M3', 'Matematika 3', '2024-12-03 03:49:54', NULL),
(20, 'PBO', 'Pemrograman Berbasis Objek', '2024-12-03 03:49:54', NULL),
(21, 'PPBO', 'Praktikum Pemrograman Berbasis Objek', '2024-12-03 03:49:54', NULL),
(22, 'SMK', 'Sistem Manajemen Kualitas', '2024-12-03 03:49:54', NULL),
(23, 'AG', 'Agama', '2024-12-03 03:49:54', NULL),
(24, 'KW', 'Kewarganegaraan', '2024-12-03 03:49:54', NULL),
(25, 'BI2', 'Bahasa Inggris 2', '2024-12-03 03:49:54', NULL),
(26, 'RPL', 'Rekayasa Perangkat Lunak', '2024-12-03 03:49:54', NULL),
(27, 'AL', 'Aljabar Linier', '2024-12-03 03:49:54', NULL),
(28, 'BD', 'Basis Data', '2024-12-03 03:49:54', NULL),
(29, 'PBD', 'Praktikum Basis Data', '2024-12-03 03:49:54', NULL),
(30, 'ASD', 'Algoritma Dan Struktur Data', '2024-12-03 03:49:54', NULL),
(31, 'PASD', 'Praktikum Algoritma Dan Struktur Data', '2024-12-03 03:49:54', NULL),
(32, 'CTPS', 'Critical Thinking dan Problem Solving', '2024-12-03 03:49:54', NULL),
(33, 'P1', 'Proyek 1', '2024-12-03 03:49:54', NULL),
(34, 'SK', 'Statistik Komputasi', '2024-12-03 03:49:54', NULL),
(35, 'BI', 'Business Intelligence', '2024-12-03 03:49:54', NULL),
(36, 'PWL', 'Pemrograman Web Lanjut', '2024-12-03 03:49:54', NULL),
(37, 'JK', 'Jaringan Komputer', '2024-12-03 03:49:54', NULL),
(38, 'PJK', 'Praktikum Jaringan Komputer', '2024-12-03 03:49:54', NULL),
(39, 'MP', 'Manajemen Proyek', '2024-12-03 03:49:54', NULL),
(40, 'TECH', 'Technopreneurship', '2024-12-03 03:49:54', NULL),
(41, 'P2', 'Proyek 2', '2024-12-03 03:49:54', NULL),
(42, 'PME', 'Pembelajaran Mesin', '2024-12-03 03:49:54', NULL),
(43, 'PPL', 'Pengujian Perangkat Lunak', '2024-12-03 03:49:54', NULL),
(44, 'BIN', 'Bahasa Indonesia', '2024-12-03 03:49:54', NULL),
(45, 'SIM', 'Sistem Informasi Manajemen', '2024-12-03 03:49:54', NULL),
(46, 'KA', 'Komputasi Awan', '2024-12-03 03:49:54', NULL),
(47, 'PBF', 'Pemrograman Berbasis Framework', '2024-12-03 03:49:54', NULL),
(48, 'BIPK', 'Bahasa Inggris Persiapan Kerja', '2024-12-03 03:49:54', NULL),
(49, 'IOT', 'Internet Of Things', '2024-12-03 03:49:54', NULL),
(50, 'BDA', 'Big Data', '2024-12-03 03:49:54', NULL),
(51, 'SPK', 'Sistem Pendukung Keputusan', '2024-12-03 03:49:54', NULL),
(52, 'PCVK', 'Pengolahan Citra Dan Visi Komputer', '2024-12-03 03:49:54', NULL),
(53, 'PPK', 'Perancangan Produk Kreatif', '2024-12-03 03:49:54', NULL),
(54, 'KBIT', 'Kepemimpinan Bidang IT', '2024-12-03 03:49:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_pangkat`
--

CREATE TABLE `m_pangkat` (
  `pangkat_id` bigint UNSIGNED NOT NULL,
  `pangkat_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_pangkat`
--

INSERT INTO `m_pangkat` (`pangkat_id`, `pangkat_nama`, `created_at`, `updated_at`) VALUES
(1, 'Penata Muda', NULL, NULL),
(2, 'Penata Muda Tk.1', NULL, NULL),
(3, 'Penata', NULL, NULL),
(4, 'Penata Tk.1', NULL, NULL),
(5, 'Pembina', NULL, NULL),
(6, 'Pembina Tk.1', NULL, NULL),
(7, 'Pembina Utama', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_prodi`
--

CREATE TABLE `m_prodi` (
  `prodi_id` bigint UNSIGNED NOT NULL,
  `prodi_kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prodi_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_prodi`
--

INSERT INTO `m_prodi` (`prodi_id`, `prodi_kode`, `prodi_nama`, `created_at`, `updated_at`) VALUES
(2, 'TI', 'D4 Teknik Informatika', '2024-11-20 01:23:46', '2024-11-20 01:23:46'),
(3, 'D4SIB', 'D4 Sistem Informasi Bisnis', '2024-11-20 05:18:19', '2024-11-20 05:18:19'),
(4, 'D2PPLS', 'D2 Pengembangan Piranti Lunak Situs', '2024-11-26 03:19:15', '2024-11-26 03:19:15');

-- --------------------------------------------------------

--
-- Table structure for table `m_role`
--

CREATE TABLE `m_role` (
  `role_id` bigint UNSIGNED NOT NULL,
  `role_kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_role`
--

INSERT INTO `m_role` (`role_id`, `role_kode`, `role_nama`, `created_at`, `updated_at`) VALUES
(1, 'ADMN', 'Admin', '2024-11-08 05:38:19', '2024-11-08 05:38:19'),
(2, 'LEAD', 'Pimpinan', '2024-11-08 05:38:19', NULL),
(3, 'DOSN', 'Dosen', '2024-11-08 05:38:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_surat_tugas`
--

CREATE TABLE `m_surat_tugas` (
  `surat_tugas_id` bigint UNSIGNED NOT NULL,
  `nomor_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_surat_tugas`
--

INSERT INTO `m_surat_tugas` (`surat_tugas_id`, `nomor_surat`, `nama_surat`, `status`, `created_at`, `updated_at`) VALUES
(1, 'null', '1734259410_document (1).pdf', 'Proses', '2024-12-15 03:43:30', '2024-12-15 03:43:30'),
(2, 'null', '1734259686_document (1).pdf', 'Proses', '2024-12-15 03:48:06', '2024-12-15 03:48:06'),
(3, 'null', '1734259976_document (1).pdf', 'Proses', '2024-12-15 03:52:56', '2024-12-15 03:52:56'),
(4, 'null', '1734260095_document (1).pdf', 'Proses', '2024-12-15 03:54:55', '2024-12-15 03:54:55'),
(5, 'null', '1734260118_document (1).pdf', 'Proses', '2024-12-15 03:55:18', '2024-12-15 03:55:18'),
(6, 'null', '1734260658_ANALISIS_PREDIKSI_PENJUALAN_DENGAN_METODE_REGRESI_.pdf', 'Proses', '2024-12-15 04:04:18', '2024-12-15 04:04:18'),
(7, 'null', '1734262443_ANALISIS_PREDIKSI_PENJUALAN_DENGAN_METODE_REGRESI_.pdf', 'Proses', '2024-12-15 04:34:03', '2024-12-15 04:34:03'),
(8, 'null', '1734262453_ANALISIS_PREDIKSI_PENJUALAN_DENGAN_METODE_REGRESI_.pdf', 'Proses', '2024-12-15 04:34:13', '2024-12-15 04:34:13'),
(9, 'null', '1734262514_ANALISIS_PREDIKSI_PENJUALAN_DENGAN_METODE_REGRESI_.pdf', 'Proses', '2024-12-15 04:35:14', '2024-12-15 04:35:14'),
(10, 'null', '1734263757_Manual Book Mobile.pdf', 'Proses', '2024-12-15 04:55:57', '2024-12-15 04:55:57'),
(11, 'null', '1734264945_document (1).pdf', 'Proses', '2024-12-15 05:15:45', '2024-12-15 05:15:45'),
(12, 'null', '1734280293_surat_tugas_Pengembangan Aplikasi Mobile.docx', 'Proses', '2024-12-15 09:31:33', '2024-12-15 09:31:33'),
(13, 'null', '1734280302_surat_tugas_Pengembangan Aplikasi Mobile.docx', 'Proses', '2024-12-15 09:31:42', '2024-12-15 09:31:42'),
(14, 'null', '1734280938_surat_tugas_Pengembangan Aplikasi Mobile.docx', 'Proses', '2024-12-15 09:42:18', '2024-12-15 09:42:18'),
(15, 'null', '1734281398_surat_tugas_Pengembangan Aplikasi Mobile.docx', 'Proses', '2024-12-15 09:49:58', '2024-12-15 09:49:58'),
(16, 'null', '1734281436_surat_tugas_Pengembangan Aplikasi Mobile.docx', 'Proses', '2024-12-15 09:50:36', '2024-12-15 09:50:36'),
(17, 'pelatihan_1', '1734283792_surat_tugas_Pengembangan Aplikasi Mobile.docx', 'Proses', '2024-12-15 10:29:52', '2024-12-15 10:29:52'),
(18, 'pelatihan_1', '1734283949_surat_tugas_Pengembangan Aplikasi Mobile.docx', 'Proses', '2024-12-15 10:32:29', '2024-12-15 10:32:29'),
(19, 'pelatihan_1', '1734284005_surat_tugas_Pengembangan Aplikasi Mobile.docx', 'Proses', '2024-12-15 10:33:25', '2024-12-15 10:33:25'),
(20, 'pelatihan_5', '1734284226_document (1).pdf', 'Proses', '2024-12-15 10:37:06', '2024-12-15 10:37:06'),
(21, 'pelatihan_5', '1734284263_2995-7321-1-PB (2).pdf', 'Proses', '2024-12-15 10:37:43', '2024-12-15 10:37:43'),
(22, 'sertifikasi_1', '1734286568_surat_tugas_Pengembangan Aplikasi Mobile.docx', 'Proses', '2024-12-15 11:16:08', '2024-12-15 11:16:08'),
(23, 'sertifikasi_2', '1734286803_surat_tugas_1734021484.docx', 'Proses', '2024-12-15 11:20:03', '2024-12-15 11:20:03'),
(24, 'sertifikasi_4', '1734288625_document (1).pdf', 'Proses', '2024-12-15 11:50:25', '2024-12-15 11:50:25');

-- --------------------------------------------------------

--
-- Table structure for table `m_user`
--

CREATE TABLE `m_user` (
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_user`
--

INSERT INTO `m_user` (`user_id`, `role_id`, `username`, `nama`, `nip`, `avatar`, `password`, `email`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin1', 'administrator', '1234567891', '1734301139WhatsAppImage2024-10-23at16.32.33_bf74374d.jpg', '$2y$12$xpMk6Iroffl7klOxNPflEuX1rlQOAVISLVk5iq7EFkwhh/hNS1s0q', 'administrator@gmail.com', '2024-11-07 23:22:21', '2024-12-15 15:18:59'),
(2, 2, 'lead01', 'Syffa F', '1234567899', '1733796354a1.jpg', '$2y$12$jbSg3KiNxOyL1/ceO9VUvu1iwsE3lQKxkKXRc./q0ysxxNqHIXETu', 'syffafirdausyah@gmail.com', '2024-11-08 00:50:53', '2024-12-12 04:56:38'),
(3, 3, 'dosen01', 'Muhammad Taufiq Abdus Salam', '0303030303', '1733199508dosen01.jpg', '$2y$12$PZD3kr8MnveiujhhR9JnIuiqaKQJic5qpfa6DoVHiYdNv7G0BmYH.', 'dosen1@gmail.com', '2024-11-10 06:53:35', '2024-12-15 19:52:53'),
(4, 3, 'dosen02', 'Solikhin', '0404040404', NULL, '$2y$12$z1JULD9nwrnC7RK5Vn4IK.cQQ.QAEeOd2xETpsclmCJLjSY3UbHDu', NULL, '2024-11-10 06:55:29', '2024-11-16 20:24:52'),
(5, 3, 'dosen03', 'Suhatta', '2626262626', NULL, '$2y$12$qsP/FgclNDa2MGXoVVTDCeJUFywsmM/H9k/wU.8ozI5RqBf29f15.', NULL, '2024-11-16 20:24:33', '2024-11-16 20:24:33'),
(6, 3, 'dosen04', 'M. Isroqi Gelby F.', '1818181818', NULL, '$2y$12$1IIikdW7lOu5X1W.2C5jUuC0rrD.vdyjM1mfSOHwBtGPUWrR9U286', NULL, '2024-11-16 20:37:01', NULL),
(167, 3, 'Ade Ismail ', 'Ade Ismail, S.Kom., M.TI', '404079101', NULL, '$2y$12$X8eCwvTFlBHSYK7/KKWUo.bDxr//dZq3z5aU4nnykixH0OsRBUgv6', NULL, '2024-12-03 04:31:36', NULL),
(168, 3, 'Agung Nugroho Pramud', 'Agung Nugroho Pramudhita, S.T., M.T.', '10028903', NULL, '$2y$12$G7.UjYkFPDvkeSTYjzezCO2TC.B5JgzMpYO5Uo5y8WFRB0NBG98AS', NULL, '2024-12-03 04:31:36', NULL),
(169, 3, 'Ahmadi Yuli Ananta', 'Ahmadi Yuli Ananta, ST., M.M.', '5078102', NULL, '$2y$12$jD.Cw85vZBMpBeBRZuNK2uuvB7pb86SmR2TDqV2fZ6AlPxd7C3926', NULL, '2024-12-03 04:31:36', NULL),
(170, 3, 'Annisa Puspa Kirana', 'Annisa Puspa Kirana, S.Kom., M.Kom', '23018906', NULL, '$2y$12$tpT4WKowqnNwhZu9PALMMOERFTRveCPSb6nERnQxx2eIfUkVleQpq', NULL, '2024-12-03 04:31:36', NULL),
(171, 3, 'Annisa Taufika Firda', 'Annisa Taufika Firdausi, ST., MT.', '14128704', NULL, '$2y$12$nmwcbJyrEYOZr8MiuxGM..bxeQJPuOaDYfw6AdK4bCYVi63wq7lxa', NULL, '2024-12-03 04:31:36', NULL),
(172, 3, 'Anugrah Nur Rahmanto', 'Anugrah Nur Rahmanto, S.Sn., M.Ds.', '30129101', NULL, '$2y$12$qeI7wm6LeKmRNgvtwmOvnu50/1IR6uNzfPws9W332ar.bn5sKDur.', NULL, '2024-12-03 04:31:37', NULL),
(173, 3, 'Ariadi Retno Ririd', 'Ariadi Retno Ririd, S.Kom., M.Kom.', '10088101', NULL, '$2y$12$ktYOaVWghL.R3ar/9qMAjeXJOCWsf76uWgaYU.0nYUzfR.uRiGyMa', NULL, '2024-12-03 04:31:37', NULL),
(174, 3, 'Arie Rachmad Syulist', 'Arie Rachmad Syulistyo, S.Kom., M.Kom', '24088701', NULL, '$2y$12$72JsIsl9DT2fDBN48Spkne.rI0WIfBItZ5WCjamyB8CatSZM0zvaC', NULL, '2024-12-03 04:31:37', NULL),
(175, 3, 'Arief Prasetyo', 'Arief Prasetyo, S.Kom., M.Kom.', '13037905', NULL, '$2y$12$lZYled//Dvdw7sPnTiNJKu2dv8mlGrfMxXc6hVm0HYKRr96pyX6oa', NULL, '2024-12-03 04:31:37', NULL),
(176, 3, 'Astrifidha Rahma Ama', 'Astrifidha Rahma Amalia,S.Pd., M.Pd.', '21059405', NULL, '$2y$12$aqTED4x5eTa4ik2xanFq5eMORY.WJf.Fr/lPGbnjBiMx/Mw3K5ENu', NULL, '2024-12-03 04:31:37', NULL),
(177, 3, 'Atiqah Nurul Asri', 'Atiqah Nurul Asri, S.Pd., M.Pd.', '25067607', NULL, '$2y$12$NyHjZbwG5ktgAV0fnDfDYODPA/FnhzFttdIcOJTGkzPjSXNUdBCda', NULL, '2024-12-03 04:31:38', NULL),
(178, 3, 'Bagas Satya Dian Nug', 'Bagas Satya Dian Nugraha, ST., MT.', '16069009', NULL, '$2y$12$cEkrPFPwOeEa9r2cfKjJ0eTQU2LEihyJUyqu4AeXBEbV82yN8zTXy', NULL, '2024-12-03 04:31:38', NULL),
(179, 3, 'Banni Satria Andoko ', 'Banni Satria Andoko, S. Kom., M.MSI., Dr. Eng.', '9088107', NULL, '$2y$12$WLjjtLVBljhEf6bOYWd02OFgrmQKhOlj3OwJjXm/nXGWNwlj7Gm5O', NULL, '2024-12-03 04:31:38', NULL),
(180, 3, 'Budi Harijanto ', 'Budi Harijanto, ST., M.MKom.', '5016211', NULL, '$2y$12$qyuVI0matv0.46NBn/.85u71qU.s2wMWCPdD8vlgVgJkgTcQTIUTS', NULL, '2024-12-03 04:31:38', NULL),
(181, 3, 'Cahya Rahmad   ', 'Cahya Rahmad, ST., M.Kom., Prof., Dr. Eng. ', '2027214', NULL, '$2y$12$JTGzaiNeFwlD4k3PAcKp9OJmHcx2lnrXOaC4pesXWcxeA/wldaiDC', NULL, '2024-12-03 04:31:38', NULL),
(182, 3, 'Candra Bella Vista', 'Candra Bella Vista, S.Kom., MT.', '17129402', NULL, '$2y$12$ocMrZpJezTl5SvXLCQx6sOA07ynv2g9ticRQ80gIlai2KKQ2rn8lK', NULL, '2024-12-03 04:31:39', NULL),
(183, 3, 'Deddy Kusbianto ', 'Deddy Kusbianto PA, Ir., M.Mkom.', '28116204', NULL, '$2y$12$7LUDdKbu2qu1SB8p1VBTIuhZ/W4igz2rEP1imMpyhQiBjeQaLv3UC', NULL, '2024-12-03 04:31:39', NULL),
(184, 3, 'Dhebys Suryani', 'Dhebys Suryani, S.Kom., MT', '9118305', NULL, '$2y$12$BkhaiI7J7b7OHa/sZiMgUegF/WviwQxaOOqaWFtTsZH1XMrnWh4ZG', NULL, '2024-12-03 04:31:39', NULL),
(185, 3, 'Dian Hanifudin Subhi', 'Dian Hanifudin Subhi, S.Kom., M.Kom.', '10068807', NULL, '$2y$12$op1.rEEoyXY1ZyLEIDPKA.QpCpKJ7cGxGnaDru3GXRBd.MhD6ONx2', NULL, '2024-12-03 04:31:39', NULL),
(186, 3, 'Dika Rizky Yunianto', 'Dika Rizky Yunianto, S.Kom, M.Kom', '6069202', NULL, '$2y$12$ovsrueFkTKRjoxS/VNw/5.hy2SrNdVGuZa7kNlad.DpcGMI0Vd7TS', NULL, '2024-12-03 04:31:39', NULL),
(187, 3, 'Dimas Wahyu Wibowo', 'Dimas Wahyu Wibowo, ST., MT.', '9108402', NULL, '$2y$12$x7kyQaMoYyd7BGQ1nWe2GuTCfjEhK3yaluwKN7rADR40u7Ve5cg5y', NULL, '2024-12-03 04:31:40', NULL),
(188, 3, 'Eka Larasati Amalia ', 'Eka Larasati Amalia, S.ST., MT.', '11078803', NULL, '$2y$12$UL4m/aTV9I0IlV4QMvetSegIWYS5NiW2dw/NjDFatfW0Ea9cnDMKC', NULL, '2024-12-03 04:31:40', NULL),
(189, 3, 'Ekojono', 'Ekojono, ST., M.Kom.', '8125911', NULL, '$2y$12$gvjcOENXhTiVWyhILLmZbOZEqziZvxIzr4wwVOyCA0UzbFmSjldkG', NULL, '2024-12-03 04:31:40', NULL),
(190, 3, 'Elok Nur Hamdana ', 'Elok Nur Hamdana, S.T., M.T', '702108601', NULL, '$2y$12$N0VdFK4o8KCcmxDAMIk.bOIopZ1orX9Y8BnnDGNVPDdT7i9hH7cSO', NULL, '2024-12-03 04:31:40', NULL),
(191, 3, 'Ely Setyo Astuti', 'Ely Setyo Astuti, ST., MT., Dr.', '15057606', NULL, '$2y$12$xW8ombPFNWYSS1HeXJGav.p7kmhzT8qidFaKQlX4nhSyyv3X6KnR6', NULL, '2024-12-03 04:31:41', NULL),
(192, 3, 'Endah Septa Sintiya', 'Endah Septa Sintiya,S.Pd., M.Kom', '31019404', NULL, '$2y$12$SfHZVoVfXyr6N1mY77CGFOtWOZ0U1ag.bOiQlvF7.sNpJbw9pW6EG', NULL, '2024-12-03 04:31:41', NULL),
(193, 3, 'Erfan Rohadi', 'Erfan Rohadi, ST., M.Eng., Ph.D.', '23017206', NULL, '$2y$12$GxvTymrc2vprH4jNpRinieXSdkPtiD5n99GHb2O80h66NoO9eAoFK', NULL, '2024-12-03 04:31:41', NULL),
(194, 3, 'Faiz Ushbah Mubarok', 'Faiz Ushbah Mubarok, S.Pd., M.Pd.', '5059303', NULL, '$2y$12$uXo3uY4D93WKAEy18CgRkufgEB1PsjQrdsKPMwRp1EBWWEdKp.H2C', NULL, '2024-12-03 04:31:41', NULL),
(195, 3, 'Farid Angga Pribadi', 'Farid Angga Pribadi, S.Kom.,M.Kom', '7108905', NULL, '$2y$12$UyvKKdMaCwudAM1HYCnuP.dBoBa1BdrPIPzZASE6vOgOO8SXpVFne', NULL, '2024-12-03 04:31:41', NULL),
(196, 3, 'Gunawan Budi Prasety', 'Gunawan Budi Prasetyo, ST., MMT., Ph.D.', '24047706', NULL, '$2y$12$.QlNb08MCorl9vOwA7ZYyOmVqrwctdBsqXHcfS6x1wvpSUxPI8mHS', NULL, '2024-12-03 04:31:42', NULL),
(197, 3, 'Habibie Ed Dien', 'Habibie Ed Dien, S.Kom., M.T.', '12049209', NULL, '$2y$12$ZPVAot8E.3pTphaL0XjARe8prEPnvhlkS7nk68t0FXIAflHJ3AOkG', NULL, '2024-12-03 04:31:42', NULL),
(198, 3, 'Hendra Pradibta ', 'Hendra Pradibta, SE., M.Sc.', '21058301', NULL, '$2y$12$YTK6KgNsJkvwNHFCa64QhOoIioDUyxJBPNI.tIjh4.1yFk0GRsEH.', NULL, '2024-12-03 04:31:42', NULL),
(199, 3, 'Ika Kusumaning Putri', 'Ika Kusumaning Putri, S.Kom., MT.', '14109103', NULL, '$2y$12$OxmpYCnS6cWQp40K8lfy0uadXGXs35d6VJ31AwCIG/U54ikTeTPBS', NULL, '2024-12-03 04:31:42', NULL),
(200, 3, 'Imam Fahrur Rozi', 'Imam Fahrur Rozi, ST., MT.', '10068402', NULL, '$2y$12$bEd4VR3QrU.bzS1oj8A6WOYPe5BEEv0k7HK4q0134slTiKIGCzs9G', NULL, '2024-12-03 04:31:42', NULL),
(201, 3, 'Indra Dharma Wijaya ', 'Indra Dharma Wijaya, ST., M.MT.', '10057308', NULL, '$2y$12$jNKs7AAhdkLSLq8oB2Q1Te3oyiPKUTqiBbvK4q2uVnADR9aXD7q4q', NULL, '2024-12-03 04:31:43', NULL),
(202, 3, 'Irsyad Arif Mashudi', 'Irsyad Arif Mashudi, S.Kom., M.Kom', '701028901', NULL, '$2y$12$BRO8dshBQiHOYvbZnYQ.EuHQq9lT65gC6M6yz3aJWctVEP0Lk5izS', NULL, '2024-12-03 04:31:43', NULL),
(203, 3, 'Kadek Suarjuna Batub', 'Kadek Suarjuna Batubulan, S.Kom, MT', '720039003', NULL, '$2y$12$oJn9o/or5jqOnKVF95oge.e5idSSlJqW3cPTjO6VPMSaGLOrFJB9q', NULL, '2024-12-03 04:31:43', NULL),
(204, 3, 'Luqman Affandi ', 'Luqman Affandi, S.Kom., MMSI', '730118201', NULL, '$2y$12$x/JbpSy1j4JTkTbfW3Dv5OZPoPUidOPqtNdtde/AiE0rCSJU20GTq', NULL, '2024-12-03 04:31:43', NULL),
(205, 3, 'M Hasyim Ratsanjani', 'M. Hasyim Ratsanjani, S.Kom., M.Kom', '5039007', NULL, '$2y$12$ATeVWF1PjKJLx8WEujdF7ubag4iPqUWCl8Gdsn5beCYlh/Bh3Xdi6', NULL, '2024-12-03 04:31:43', NULL),
(206, 3, 'Mamluatul Hani\'ah', 'Mamluatul Hani\'ah, S.Kom., M.Kom', '6029003', NULL, '$2y$12$ftYvDLf.HsRnJs.eqKYrjOqj6GFUmlP3qwkP3T8Yh.EexhhdfL9DC', NULL, '2024-12-03 04:31:44', NULL),
(207, 3, 'Meyti Eka Apriyani ', 'Meyti Eka Apriyani ST., MT.', '1024048703', NULL, '$2y$12$VoxAGAq6MvYIsrb1xr4/t.bWINCKG8fcobkD0oOvwcZzPWxE7uSVS', NULL, '2024-12-03 04:31:44', NULL),
(208, 3, 'Milyun Ni\'ma Shoumi', 'Milyun Ni\'ma Shoumi, S.Kom., M.Kom', '7058812', NULL, '$2y$12$n8T.X0DguvIA5bQ1eQc1Pea7MIC8AIewOT4ss3/pgCVQGajeENdk2', NULL, '2024-12-03 04:31:44', NULL),
(209, 3, 'Moch Zawaruddin Abdu', 'Moch. Zawaruddin Abdullah, S.ST., M.Kom', '10028906', NULL, '$2y$12$/Vw8aCfdoNUxbaQwV7mzlusxEwVVzVslcjxoS5zErWqn8NCMWsCR2', NULL, '2024-12-03 04:31:44', NULL),
(210, 3, 'Muhammad Afif Hendra', 'Muhammad Afif Hendrawan.,S.Kom., MT', '28119106', NULL, '$2y$12$Q1MIIm3OA0mt3Sz/EseHu.Uz1WnU7uxAdju.ThXInvSoSr5Thurs.', NULL, '2024-12-03 04:31:44', NULL),
(211, 3, 'Muhammad Shulhan Kha', 'Muhammad Shulhan Khairy, S.Kom, M.Kom', '17059201', NULL, '$2y$12$7T.s8LefPKfLayicUYnXkOAqXZLZBrslPBpHR7fATkUj347hAdggq', NULL, '2024-12-03 04:31:45', NULL),
(212, 3, 'Mungki Astiningrum', 'Mungki Astiningrum, ST., M.Kom.', '30107702', NULL, '$2y$12$cXQkE7wqmdJrSw/pzGhUJONbOX6G.2jw0G5.woi7JzE3GGMI6/mnq', NULL, '2024-12-03 04:31:45', NULL),
(213, 3, 'Mustika Mentari', 'Mustika Mentari, S.Kom., M.Kom', '7068804', NULL, '$2y$12$xIwADYj7QX0yy0lLcjOkKeyvHLJH7STWCgZ1O8r9HofiBgXiXeJ4u', NULL, '2024-12-03 04:31:45', NULL),
(214, 3, 'Noprianto', 'Noprianto, S.Kom., M.Eng', '511088901', NULL, '$2y$12$89AdED2GkioahQDlC00sRe9pmTf9m8pKibBRxktWg64e6DvAGgELO', NULL, '2024-12-03 04:31:45', NULL),
(215, 3, 'Pramana Yoga Saputra', 'Pramana Yoga Saputra, S.Kom., MMT.', '4058805', NULL, '$2y$12$a0Ij5Q/3yzM8f6tv8vNn9uQ5e2uQlazDmhTVSnCXC./ItLZXNPlby', NULL, '2024-12-03 04:31:45', NULL),
(216, 3, 'Putra Prima ', 'Putra Prima A., ST., M.Kom.', '3118602', NULL, '$2y$12$b0.1m9EGqAIud55MgqXVZ./nuJOfoJMf4dtqAWSWLTF6jGlx9skDi', NULL, '2024-12-03 04:31:46', NULL),
(217, 3, 'Rakhmat Arianto ', 'Rakhmat Arianto, S.ST., M.Kom., Dr.', '308018702', NULL, '$2y$12$Z.Gw071X715kzL92Ckz.GOES/ad/TlVRCoNLOBdVuRXaxXTLgjJ4S', NULL, '2024-12-03 04:31:46', NULL),
(218, 3, 'Retno Damayanti', 'Retno Damayanti, S.Pd., M.T.', '4108907', NULL, '$2y$12$4/yCL4ygQCCkv1dKWnqYpeumH/AVOy5ipNo6znz45pIAidhvtVJGS', NULL, '2024-12-03 04:31:46', NULL),
(219, 3, 'Ridwan Rismanto ', 'Ridwan Rismanto, SST., M.Kom.', '18038602', NULL, '$2y$12$DpMuThsLV0uicEH.ulhWFOOKgo7QkOhk1His7DxmW/bYRT.7MBGJm', NULL, '2024-12-03 04:31:46', NULL),
(220, 3, 'Rokhimatul Wakhidah', 'Rokhimatul Wakhidah, S.Pd., M.T.', '19038905', NULL, '$2y$12$hmKGzxecFOQkn0yNDMRVOemarRM1QzClqqawN80fj/hiDf1Dz8H/.', NULL, '2024-12-03 04:31:46', NULL),
(221, 3, 'Rosa Andrie Asmara ', 'Rosa Andrie Asmara, ST., MT., Dr. Eng.', '10108003', NULL, '$2y$12$DbqdDYTyatuH.QVQKHaMdOKfLQmuWIiuF9.0tjZFhDdRhYJNDcdcu', NULL, '2024-12-03 04:31:47', NULL),
(222, 3, 'Rudy Ariyanto ', 'Rudy Ariyanto, ST., M.Cs.', '10117109', NULL, '$2y$12$kl0HvH/xhzprXtWovtPLPO8k2vTHBE6sWTxLzfZFFU4e7LxBjf1xG', NULL, '2024-12-03 04:31:47', NULL),
(223, 3, 'Septian Enggar Sukma', 'Septian Enggar Sukmana, S.Pd., M.T', '601098901', NULL, '$2y$12$85d1R./95uSjgd6MO6s5P.tHa.Y5uQKDPQ.UlQhP/X5kZu0WhC/aW', NULL, '2024-12-03 04:31:47', NULL),
(224, 3, 'Sofyan Noor Arief ', 'Sofyan Noor Arief, S.ST., M.Kom.', '13088904', NULL, '$2y$12$EAnDHXCNKB07Mq74KFLXIOC/D/9gyB3NNmb/5JiJRqpkpoAcTPVVq', NULL, '2024-12-03 04:31:47', NULL),
(225, 3, 'Triana Fatmawati', 'Triana Fatmawati,S.T., M.T.', '4314058001', NULL, '$2y$12$PVr6lxxkmclL3a99objOCe8eT9dalrOndsUrju7Wt7DH6PMMjZweC', NULL, '2024-12-03 04:31:47', NULL),
(226, 3, 'Ulla Delfana Rosiani', 'Ulla Delfana Rosiani, ST., MT., Dr.', '27037801', NULL, '$2y$12$H8dQkt2nYoRHCoudmWehsepNs/K3fLnPQ2XQktbnF3PVwklLQ5S6W', NULL, '2024-12-03 04:31:48', NULL),
(227, 3, 'Usman Nurhasan', 'Usman Nurhasan, S.Kom., MT.', '23098604', NULL, '$2y$12$9SH4shYIMOW/wOcFqTnSt.Mlh8z8Vnl0aK16eLXEHCsPhW6A7lngG', NULL, '2024-12-03 04:31:48', NULL),
(228, 3, 'Vipkas Al Hadid Fird', 'Vipkas Al Hadid Firdaus, ST,. MT', '5059104', NULL, '$2y$12$TrRIY9bFNb26jnQ3hhZi1ezux.Sr.oOd3Z13kghoPn9yZRSfJuvPC', NULL, '2024-12-03 04:31:48', NULL),
(229, 3, 'Vit Zuraida', 'Vit Zuraida,S.Kom., M.Kom.', '9018910', NULL, '$2y$12$fPePAq08l1i7gKHzB40oyOoCqHnVgWe9cuG5w/S5tYEjOxpBTCtbW', NULL, '2024-12-03 04:31:48', NULL),
(230, 3, 'Vivi Nur Wijayaningr', 'Vivi Nur Wijayaningrum, S.Kom, M.Kom', '11089303', NULL, '$2y$12$9vV2x6xHhm8kxEQepojFwuk6Xc1fa07b5JWlLtfIXRLCCXrxqhum6', NULL, '2024-12-03 04:31:48', NULL),
(231, 3, 'Vivin Ayu Lestari', 'Vivin Ayu Lestari, S.Pd., M.Kom.', '21069102', NULL, '$2y$12$4wOTMhftVzc.l/oXYkM42OI4XxSeYHfxqiCPHUhjG3iMa1/yxK/KS', NULL, '2024-12-03 04:31:49', NULL),
(232, 3, 'Widaningsih Condrowa', 'Widaningsih Condrowardhani, SH, MH.', '18038104', NULL, '$2y$12$wizYQHlMvvKCambDsivnBuH38zaJuLaNM7DcPp8R02a8yMFgMOPCS', NULL, '2024-12-03 04:31:49', NULL),
(233, 3, 'Wilda Imama Sabilla', 'Wilda Imama Sabilla, S.Kom., M.Kom', '29089201', NULL, '$2y$12$2nEw6VbB3L5122qn4n2zXebLFpHoBhxaO3J5R2tjUu.YBLjahafC.', NULL, '2024-12-03 04:31:49', NULL),
(234, 3, 'Yan Watequlis Syaifu', 'Yan Watequlis Syaifuddin, ST., M.MT., Ph. D.', '5018104', NULL, '$2y$12$OJmaYTrKtpn8XHm0VVRMBeoJpjF9S2eBnL4jFEZ62ut1rlOcURuUi', NULL, '2024-12-03 04:31:49', NULL),
(235, 3, 'Yoppy Yunhasnawa', 'Yoppy Yunhasnawa, S.ST., M.Sc.', '21068905', NULL, '$2y$12$D48KYonhAFRkc5fdeO3Ss.XVfYE3kffv1L.LYLGtJvINzMNnYAtcu', NULL, '2024-12-03 04:31:49', NULL),
(236, 3, 'Yuri Ariyanto', 'Yuri Ariyanto, S.Kom., M.Kom.', '16078008', NULL, '$2y$12$XCFsnPsx3KsnYHlDaxE4FOsjEMWtKSaPv7YLIQp2bp5KSCijYLNbO', NULL, '2024-12-03 04:31:50', NULL),
(237, 3, 'Mutrofin Rozaq', 'Mutrofin Rozaq,S.Pd., M.Pd.', '0028108704)', NULL, '$2y$12$QipJc2iuMMjYMFmNVxylhO20bc.iCEg3kfjMRrFDgwyb/aOypZcjO', NULL, '2024-12-03 04:31:50', NULL),
(238, 3, 'Very Sugiarto', 'Very Sugiarto,S.Pd., M.Kom', '3028706', NULL, '$2y$12$qua2rDyyMT22QjV4I70PFe522B7z36R68dYhzohZdP/kocswcCG.e', NULL, '2024-12-03 04:31:50', NULL),
(239, 3, 'Adevian Fairuz Prata', 'Adevian Fairuz Pratama,S.S.T, M.Eng.', '8945260022', NULL, '$2y$12$vybM4nVtLyy.6EHuTP1eiuGHxANL5xS1l7CAmfAOcBylA6wZDa7Du', NULL, '2024-12-03 04:31:50', NULL),
(240, 3, 'Farida Ulfa', 'Farida Ulfa, S.Pd., M.Pd.', '14048005', NULL, '$2y$12$TbZ.wk2N2U3WO/q5RtgWNePcVob.YLfqMSzkiSwT9BIh/lAMOGu4C', NULL, '2024-12-03 04:31:50', NULL),
(241, 3, 'Muhammad Unggul Pame', 'Muhammad Unggul Pamenang, S.St., M.T.', '23089102', NULL, '$2y$12$iBP1xXa69xMFXy0Oh6ntSu3LAEruujWHn16AizkI04nnmckD53hQ2', NULL, '2024-12-03 04:31:51', NULL),
(242, 3, 'Robby Anggriawan ', 'Robby Anggriawan SE., ME.', '8992920021', NULL, '$2y$12$E1N8aKoEvUyyA1ULUyLdSecwcwbsBoDXD6CmLih821UA0cYrX37Hi', NULL, '2024-12-03 04:31:51', NULL),
(243, 3, 'Satrio Binusa ', 'Satrio Binusa S, SS, M.Pd', '26108904', NULL, '$2y$12$Au8b5Wmt08gat/32cXonluHTSkBKcTOMA5.eR9JIH1WUbuAhF9s.u', NULL, '2024-12-03 04:31:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_vendor`
--

CREATE TABLE `m_vendor` (
  `vendor_id` bigint UNSIGNED NOT NULL,
  `vendor_nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_kota` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_no_telf` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_alamat_web` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_vendor`
--

INSERT INTO `m_vendor` (`vendor_id`, `vendor_nama`, `vendor_alamat`, `vendor_kota`, `vendor_no_telf`, `vendor_alamat_web`, `created_at`, `updated_at`) VALUES
(1, 'Badan Nasional Sertifikasi Profesi', 'Kav. 52 Jl Letnan Jenderal MT Haryono 12770 Jakarta Selatan Jakarta Raya ', 'Jakarta', '+62217992685', 'https://bnsp.go.id/', '2024-11-10 13:46:27', '2024-11-10 13:46:27');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekomendasi`
--

CREATE TABLE `rekomendasi` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_data_pelatihan`
--

CREATE TABLE `t_data_pelatihan` (
  `data_pelatihan_id` bigint UNSIGNED NOT NULL,
  `pelatihan_id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `surat_tugas_id` bigint UNSIGNED DEFAULT NULL,
  `sertifikat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_data_pelatihan`
--

INSERT INTO `t_data_pelatihan` (`data_pelatihan_id`, `pelatihan_id`, `dosen_id`, `surat_tugas_id`, `sertifikat`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 19, NULL, '2024-11-10 15:10:10', '2024-12-15 10:33:25'),
(2, 1, 2, 19, NULL, '2024-11-10 15:10:10', '2024-12-15 10:33:25'),
(3, 2, 3, NULL, NULL, '2024-11-26 07:29:29', '2024-11-26 07:29:29'),
(4, 2, 4, NULL, NULL, '2024-11-26 07:29:29', '2024-11-26 07:29:29'),
(5, 3, 16, NULL, NULL, '2024-12-11 06:18:41', '2024-12-11 06:18:41'),
(6, 3, 18, NULL, NULL, '2024-12-11 06:18:41', '2024-12-11 06:18:41'),
(7, 4, 13, NULL, NULL, '2024-12-11 06:37:58', '2024-12-11 06:37:58'),
(8, 4, 13, NULL, NULL, '2024-12-11 06:37:58', '2024-12-11 06:37:58'),
(9, 5, 13, 21, NULL, '2024-12-11 06:43:42', '2024-12-15 10:37:43'),
(10, 5, 13, 21, NULL, '2024-12-11 06:43:42', '2024-12-15 10:37:43'),
(11, 6, 20, NULL, NULL, '2024-12-12 06:40:06', '2024-12-12 06:40:06'),
(12, 6, 18, NULL, NULL, '2024-12-12 06:40:06', '2024-12-12 06:40:06'),
(13, 7, 4, NULL, '1734017450_[JS01]Konsep Dasar_Sofi Lailatul_SIB3E_23.docx', '2024-12-12 08:30:50', '2024-12-12 08:30:50'),
(14, 8, 4, NULL, '1734017556_Maritza Ulfa Huriyah_Notulen Industri_Kecbis.pdf', '2024-12-12 08:32:36', '2024-12-12 08:32:36'),
(15, 9, 1, NULL, '1734036117_Maritza Ulfa Huriyah_Notulen Industri_Kecbis.pdf', '2024-12-12 13:41:57', '2024-12-12 13:41:57'),
(16, 10, 1, NULL, '1734036222_Internship_Maritza Ulfa Huriyah_System Analyst.pdf', '2024-12-12 13:43:42', '2024-12-12 13:43:42'),
(17, 19, 3, NULL, NULL, '2024-12-14 03:22:19', '2024-12-14 03:22:19'),
(18, 20, 3, NULL, NULL, '2024-12-14 03:26:04', '2024-12-14 03:26:04'),
(19, 21, 3, NULL, NULL, '2024-12-14 03:28:12', '2024-12-14 03:28:12'),
(20, 22, 1, NULL, '1734183857_Manual Book Mobile.pdf', '2024-12-14 06:44:17', '2024-12-14 06:44:17'),
(21, 23, 3, NULL, NULL, '2024-12-14 06:51:02', '2024-12-14 06:51:02'),
(24, 26, 1, NULL, '1734256163_document (1).pdf', '2024-12-15 02:49:23', '2024-12-15 02:49:23'),
(25, 27, 1, NULL, NULL, '2024-12-15 10:54:50', '2024-12-15 10:54:50'),
(26, 27, 3, NULL, NULL, '2024-12-15 10:54:50', '2024-12-15 10:54:50');

-- --------------------------------------------------------

--
-- Table structure for table `t_data_sertifikasi`
--

CREATE TABLE `t_data_sertifikasi` (
  `data_sertif_id` bigint UNSIGNED NOT NULL,
  `sertif_id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `surat_tugas_id` bigint UNSIGNED DEFAULT NULL,
  `sertifikat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_data_sertifikasi`
--

INSERT INTO `t_data_sertifikasi` (`data_sertif_id`, `sertif_id`, `dosen_id`, `surat_tugas_id`, `sertifikat`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 22, NULL, '2024-11-10 14:09:07', '2024-12-15 11:16:08'),
(2, 2, 2, 23, NULL, '2024-11-10 14:09:07', '2024-12-15 11:20:03'),
(3, 3, 3, NULL, '1733995632_Maritza Ulfa Huriyah_Notulen Industri_Kecbis.pdf', '2024-12-12 02:27:12', '2024-12-12 02:27:12'),
(4, 4, 1, 24, '1734039039_106179-1727-400685-1-10-20240109 (1).pdf', '2024-12-12 14:30:39', '2024-12-15 11:50:25'),
(5, 5, 1, NULL, '1734039838_Maritza Ulfa Huriyah_Notulen Industri_Kecbis.pdf', '2024-12-12 14:43:58', '2024-12-12 14:43:58'),
(6, 6, 1, NULL, '1734183780_Manual Book Mobile (1).pdf', '2024-12-14 06:43:00', '2024-12-14 06:43:00'),
(7, 7, 1, NULL, '1734183781_Manual Book Mobile (1).pdf', '2024-12-14 06:43:01', '2024-12-14 06:43:01'),
(8, 8, 1, NULL, '1734183902_Manual Book Mobile (1).pdf', '2024-12-14 06:45:02', '2024-12-14 06:45:02');

-- --------------------------------------------------------

--
-- Table structure for table `t_pelatihan`
--

CREATE TABLE `t_pelatihan` (
  `pelatihan_id` bigint UNSIGNED NOT NULL,
  `level_id` bigint UNSIGNED NOT NULL,
  `bidang_id` bigint UNSIGNED NOT NULL,
  `mk_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `nama_pelatihan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `biaya` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `tanggal_akhir` date DEFAULT NULL,
  `kuota` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_pelatihan`
--

INSERT INTO `t_pelatihan` (`pelatihan_id`, `level_id`, `bidang_id`, `mk_id`, `vendor_id`, `nama_pelatihan`, `biaya`, `tanggal`, `tanggal_akhir`, `kuota`, `lokasi`, `periode`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 1, 'Pelatihan Pengembangan Web', '10000000', '2024-11-14', '2024-12-20', '10', 'Jakarta', '2024', 'Proses', 'Penunjukan', '2024-11-10 14:01:54', '2024-12-15 02:49:58'),
(2, 2, 1, 3, 1, 'Pelatihan Pengembangan Mobile', '15000000', '2024-11-30', NULL, '10', 'Jl. Bromo', '2024', 'Proses', 'Menunggu Validasi', '2024-11-26 07:26:25', '2024-11-26 07:26:25'),
(3, 1, 3, 10, 1, 'ajsdiwd', '10000', '2024-12-12', '2024-12-25', '2', 'masdkasd', '2024', 'Proses', 'Menunggu Validasi', '2024-12-11 06:18:41', '2024-12-11 06:18:41'),
(4, 2, 4, 5, 1, 'qwedqwdqw', '1000', '2024-12-11', '2024-12-12', '2', 'sdsd', '2024', 'Proses', 'Menunggu Validasi', '2024-12-11 06:37:58', '2024-12-11 06:37:58'),
(5, 1, 4, 12, 1, 'qwedqwdqw', '1000', '2024-12-12', '2024-12-20', '2', 'sdsd', '2024', 'Proses', 'Menunggu validasi', '2024-12-11 06:43:42', '2024-12-11 06:43:42'),
(6, 1, 8, 13, 1, 'hgdbd', '65378', '2024-12-13', '2024-12-20', '2', 'hbx', '2020', 'Proses', 'Menunggu validasi', '2024-12-12 06:40:06', '2024-12-12 06:40:06'),
(7, 1, 1, 2, 1, 'Coba', NULL, '2024-12-11', '2024-12-11', '1', 'Polinema', '2024', 'Proses', 'Mandiri', '2024-12-12 08:30:50', '2024-12-12 08:30:50'),
(8, 1, 4, 15, 1, 'bnd', NULL, '2024-12-13', '2024-12-26', '1', 'nd c', '2022', 'Proses', 'sudah divalidasi', '2024-12-12 08:32:36', '2024-12-12 08:32:36'),
(9, 1, 1, 2, 1, 'sdf', NULL, '2024-12-13', '2024-12-26', '1', 'zsx', '234', 'Proses', 'Menunggu Validasi', '2024-12-12 13:41:57', '2024-12-12 13:41:57'),
(10, 1, 2, 3, 1, 'feccf', NULL, '2024-12-14', '2024-12-15', '1', 'cdc', '2332', 'Selesai', 'Mandiri', '2024-12-12 13:43:42', '2024-12-12 13:43:42'),
(19, 2, 6, 7, 1, 'asd', '10000', '2024-12-16', NULL, '3', 'malang', '2024', 'Selesai', 'Selesai', '2024-12-14 03:22:19', '2024-12-14 03:22:19'),
(20, 2, 7, 4, 1, 'oppp', '10000', '2024-12-17', NULL, '2', 'malang', '2024', 'Selesai', 'mandiri', '2024-12-14 03:26:04', '2024-12-14 03:26:04'),
(21, 2, 12, 10, 1, 'hbdsf', '7438', '2024-12-22', '2024-12-25', '3', 'dhbs', '22', 'Selesai', 'edcs', '2024-12-14 03:28:12', '2024-12-14 03:28:12'),
(22, 1, 5, 14, 1, 'hgh', NULL, '2024-12-20', '2024-12-30', '1', 'uhb', '2022', 'Selesai', 'Mandiri', '2024-12-14 06:44:17', '2024-12-14 06:44:17'),
(23, 2, 13, 12, 1, 'bhg', '67', '2024-12-08', '2024-12-23', '9', 'hnj', '500', 'Proses', 'Mandiri', '2024-12-14 06:51:02', '2024-12-14 06:51:02'),
(26, 1, 2, 13, 1, 'reerdg', NULL, '2024-12-16', '2024-12-27', '1', 'drgdr', '2133', 'Proses', 'Menunggu Validasi', '2024-12-15 02:49:23', '2024-12-15 02:49:23'),
(27, 2, 2, 3, 1, 'Coba123', '100000', '2024-12-17', '2024-12-18', '2', 'Polinema', '2024', 'Proses', 'Menunggu validasi', '2024-12-15 10:54:50', '2024-12-15 10:54:50');

-- --------------------------------------------------------

--
-- Table structure for table `t_sertifikasi`
--

CREATE TABLE `t_sertifikasi` (
  `sertif_id` bigint UNSIGNED NOT NULL,
  `jenis_id` bigint UNSIGNED NOT NULL,
  `bidang_id` bigint UNSIGNED NOT NULL,
  `mk_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `nama_sertif` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `biaya` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `tanggal_akhir` date DEFAULT NULL,
  `masa_berlaku` date NOT NULL,
  `periode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_sertifikasi`
--

INSERT INTO `t_sertifikasi` (`sertif_id`, `jenis_id`, `bidang_id`, `mk_id`, `vendor_id`, `nama_sertif`, `biaya`, `tanggal`, `tanggal_akhir`, `masa_berlaku`, `periode`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 1, 'Pengembangan web', '20000000', '2024-11-14', NULL, '2025-11-14', '2024', 'Proses', 'Penunjukan', '2024-11-10 13:58:10', '2024-11-10 13:58:10'),
(2, 1, 1, 3, 1, 'Pengembangan Aplikasi Mobile', '30000000', '2024-11-20', NULL, '2025-11-20', '2024', 'Proses', 'Penunjukan', '2024-11-10 14:00:02', '2024-11-10 14:00:02'),
(3, 1, 3, 3, 1, 'fghb', NULL, '2024-12-20', NULL, '2024-12-29', '2024', 'Proses', 'Mandiri', '2024-12-12 02:27:12', '2024-12-12 02:27:12'),
(4, 1, 1, 6, 1, 'jmhgf', NULL, '2024-12-13', NULL, '2024-12-14', '2024', 'Selesai', 'Mandiri', '2024-12-12 14:30:39', '2024-12-12 14:30:39'),
(5, 1, 6, 12, 1, 'hbjd', NULL, '2024-12-12', NULL, '2024-12-20', '213', 'Selesai', 'Mandiri', '2024-12-12 14:43:58', '2024-12-12 14:43:58'),
(6, 1, 3, 12, 1, 'aaaaaaaaaa', NULL, '2024-12-09', NULL, '2024-12-17', '2024', 'Selesai', 'Mandiri', '2024-12-14 06:43:00', '2024-12-14 06:43:00'),
(7, 1, 3, 12, 1, 'aaaaaaaaaa', NULL, '2024-12-09', NULL, '2024-12-17', '2024', 'Selesai', 'Mandiri', '2024-12-14 06:43:01', '2024-12-14 06:43:01'),
(8, 1, 2, 13, 1, 'jhjn', NULL, '2024-12-26', NULL, '2024-12-31', '2022', 'Selesai', 'Mandiri', '2024-12-14 06:45:02', '2024-12-14 06:45:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dosens`
--
ALTER TABLE `dosens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_bidang`
--
ALTER TABLE `m_bidang`
  ADD PRIMARY KEY (`bidang_id`),
  ADD UNIQUE KEY `m_bidang_bidang_kode_unique` (`bidang_kode`);

--
-- Indexes for table `m_dosen`
--
ALTER TABLE `m_dosen`
  ADD PRIMARY KEY (`dosen_id`),
  ADD KEY `m_dosen_user_id_index` (`user_id`),
  ADD KEY `m_dosen_golongan_id_foreign` (`golongan_id`),
  ADD KEY `m_dosen_jabatan_id_foreign` (`jabatan_id`),
  ADD KEY `m_dosen_pangkat_id_foreign` (`pangkat_id`);

--
-- Indexes for table `m_dosen_bidang`
--
ALTER TABLE `m_dosen_bidang`
  ADD PRIMARY KEY (`dosen_bidang_id`),
  ADD KEY `m_dosen_bidang_dosen_id_index` (`dosen_id`),
  ADD KEY `m_dosen_bidang_bidang_id_index` (`bidang_id`);

--
-- Indexes for table `m_dosen_matkul`
--
ALTER TABLE `m_dosen_matkul`
  ADD PRIMARY KEY (`dosen_matkul_id`),
  ADD KEY `m_dosen_matkul_dosen_id_index` (`dosen_id`),
  ADD KEY `m_dosen_matkul_mk_id_index` (`mk_id`);

--
-- Indexes for table `m_golongan`
--
ALTER TABLE `m_golongan`
  ADD PRIMARY KEY (`golongan_id`);

--
-- Indexes for table `m_jabatan`
--
ALTER TABLE `m_jabatan`
  ADD PRIMARY KEY (`jabatan_id`);

--
-- Indexes for table `m_jenis`
--
ALTER TABLE `m_jenis`
  ADD PRIMARY KEY (`jenis_id`),
  ADD UNIQUE KEY `m_jenis_jenis_kode_unique` (`jenis_kode`);

--
-- Indexes for table `m_kompetensi_prodi`
--
ALTER TABLE `m_kompetensi_prodi`
  ADD PRIMARY KEY (`kompetensi_prodi_id`),
  ADD UNIQUE KEY `prodi_id` (`prodi_id`,`bidang_id`),
  ADD KEY `m_kompetensi_prodi_prodi_id_index` (`prodi_id`),
  ADD KEY `m_kompetensi_prodi_bidang_id_index` (`bidang_id`);

--
-- Indexes for table `m_level_pelatihan`
--
ALTER TABLE `m_level_pelatihan`
  ADD PRIMARY KEY (`level_id`),
  ADD UNIQUE KEY `m_level_pelatihan_level_kode_unique` (`level_kode`);

--
-- Indexes for table `m_matkul`
--
ALTER TABLE `m_matkul`
  ADD PRIMARY KEY (`mk_id`),
  ADD UNIQUE KEY `m_matkul_mk_kode_unique` (`mk_kode`);

--
-- Indexes for table `m_pangkat`
--
ALTER TABLE `m_pangkat`
  ADD PRIMARY KEY (`pangkat_id`);

--
-- Indexes for table `m_prodi`
--
ALTER TABLE `m_prodi`
  ADD PRIMARY KEY (`prodi_id`),
  ADD UNIQUE KEY `m_prodi_prodi_kode_unique` (`prodi_kode`);

--
-- Indexes for table `m_role`
--
ALTER TABLE `m_role`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `m_role_role_kode_unique` (`role_kode`);

--
-- Indexes for table `m_surat_tugas`
--
ALTER TABLE `m_surat_tugas`
  ADD PRIMARY KEY (`surat_tugas_id`);

--
-- Indexes for table `m_user`
--
ALTER TABLE `m_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `m_user_username_unique` (`username`),
  ADD KEY `m_user_role_id_index` (`role_id`);

--
-- Indexes for table `m_vendor`
--
ALTER TABLE `m_vendor`
  ADD PRIMARY KEY (`vendor_id`),
  ADD UNIQUE KEY `m_vendor_vendor_nama_unique` (`vendor_nama`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `rekomendasi`
--
ALTER TABLE `rekomendasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_data_pelatihan`
--
ALTER TABLE `t_data_pelatihan`
  ADD PRIMARY KEY (`data_pelatihan_id`),
  ADD KEY `t_data_pelatihan_pelatihan_id_index` (`pelatihan_id`),
  ADD KEY `t_data_pelatihan_dosen_id_index` (`dosen_id`),
  ADD KEY `t_data_pelatihan_surat_tugas_id_foreign` (`surat_tugas_id`);

--
-- Indexes for table `t_data_sertifikasi`
--
ALTER TABLE `t_data_sertifikasi`
  ADD PRIMARY KEY (`data_sertif_id`),
  ADD KEY `t_data_sertifikasi_sertif_id_index` (`sertif_id`),
  ADD KEY `t_data_sertifikasi_dosen_id_index` (`dosen_id`),
  ADD KEY `t_data_sertifikasi_surat_tugas_id_foreign` (`surat_tugas_id`);

--
-- Indexes for table `t_pelatihan`
--
ALTER TABLE `t_pelatihan`
  ADD PRIMARY KEY (`pelatihan_id`),
  ADD KEY `t_pelatihan_level_id_index` (`level_id`),
  ADD KEY `t_pelatihan_bidang_id_index` (`bidang_id`),
  ADD KEY `t_pelatihan_mk_id_index` (`mk_id`),
  ADD KEY `t_pelatihan_vendor_id_index` (`vendor_id`);

--
-- Indexes for table `t_sertifikasi`
--
ALTER TABLE `t_sertifikasi`
  ADD PRIMARY KEY (`sertif_id`),
  ADD KEY `t_sertifikasi_jenis_id_index` (`jenis_id`),
  ADD KEY `t_sertifikasi_bidang_id_index` (`bidang_id`),
  ADD KEY `t_sertifikasi_mk_id_index` (`mk_id`),
  ADD KEY `t_sertifikasi_vendor_id_index` (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dosens`
--
ALTER TABLE `dosens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `m_bidang`
--
ALTER TABLE `m_bidang`
  MODIFY `bidang_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `m_dosen`
--
ALTER TABLE `m_dosen`
  MODIFY `dosen_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `m_dosen_bidang`
--
ALTER TABLE `m_dosen_bidang`
  MODIFY `dosen_bidang_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `m_dosen_matkul`
--
ALTER TABLE `m_dosen_matkul`
  MODIFY `dosen_matkul_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `m_golongan`
--
ALTER TABLE `m_golongan`
  MODIFY `golongan_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `m_jabatan`
--
ALTER TABLE `m_jabatan`
  MODIFY `jabatan_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `m_jenis`
--
ALTER TABLE `m_jenis`
  MODIFY `jenis_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_kompetensi_prodi`
--
ALTER TABLE `m_kompetensi_prodi`
  MODIFY `kompetensi_prodi_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `m_level_pelatihan`
--
ALTER TABLE `m_level_pelatihan`
  MODIFY `level_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_matkul`
--
ALTER TABLE `m_matkul`
  MODIFY `mk_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `m_pangkat`
--
ALTER TABLE `m_pangkat`
  MODIFY `pangkat_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `m_prodi`
--
ALTER TABLE `m_prodi`
  MODIFY `prodi_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `m_role`
--
ALTER TABLE `m_role`
  MODIFY `role_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_surat_tugas`
--
ALTER TABLE `m_surat_tugas`
  MODIFY `surat_tugas_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `m_user`
--
ALTER TABLE `m_user`
  MODIFY `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT for table `m_vendor`
--
ALTER TABLE `m_vendor`
  MODIFY `vendor_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekomendasi`
--
ALTER TABLE `rekomendasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_data_pelatihan`
--
ALTER TABLE `t_data_pelatihan`
  MODIFY `data_pelatihan_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `t_data_sertifikasi`
--
ALTER TABLE `t_data_sertifikasi`
  MODIFY `data_sertif_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `t_pelatihan`
--
ALTER TABLE `t_pelatihan`
  MODIFY `pelatihan_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `t_sertifikasi`
--
ALTER TABLE `t_sertifikasi`
  MODIFY `sertif_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `m_dosen`
--
ALTER TABLE `m_dosen`
  ADD CONSTRAINT `m_dosen_golongan_id_foreign` FOREIGN KEY (`golongan_id`) REFERENCES `m_golongan` (`golongan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `m_dosen_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `m_jabatan` (`jabatan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `m_dosen_pangkat_id_foreign` FOREIGN KEY (`pangkat_id`) REFERENCES `m_pangkat` (`pangkat_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `m_dosen_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`user_id`);

--
-- Constraints for table `m_dosen_bidang`
--
ALTER TABLE `m_dosen_bidang`
  ADD CONSTRAINT `m_dosen_bidang_bidang_id_foreign` FOREIGN KEY (`bidang_id`) REFERENCES `m_bidang` (`bidang_id`),
  ADD CONSTRAINT `m_dosen_bidang_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `m_dosen` (`dosen_id`);

--
-- Constraints for table `m_dosen_matkul`
--
ALTER TABLE `m_dosen_matkul`
  ADD CONSTRAINT `m_dosen_matkul_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `m_dosen` (`dosen_id`),
  ADD CONSTRAINT `m_dosen_matkul_mk_id_foreign` FOREIGN KEY (`mk_id`) REFERENCES `m_matkul` (`mk_id`);

--
-- Constraints for table `m_kompetensi_prodi`
--
ALTER TABLE `m_kompetensi_prodi`
  ADD CONSTRAINT `m_kompetensi_prodi_bidang_id_foreign` FOREIGN KEY (`bidang_id`) REFERENCES `m_bidang` (`bidang_id`),
  ADD CONSTRAINT `m_kompetensi_prodi_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `m_prodi` (`prodi_id`);

--
-- Constraints for table `m_user`
--
ALTER TABLE `m_user`
  ADD CONSTRAINT `m_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `m_role` (`role_id`);

--
-- Constraints for table `t_data_pelatihan`
--
ALTER TABLE `t_data_pelatihan`
  ADD CONSTRAINT `fk_surat_tugas` FOREIGN KEY (`surat_tugas_id`) REFERENCES `m_surat_tugas` (`surat_tugas_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `t_data_pelatihan_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `m_dosen` (`dosen_id`),
  ADD CONSTRAINT `t_data_pelatihan_pelatihan_id_foreign` FOREIGN KEY (`pelatihan_id`) REFERENCES `t_pelatihan` (`pelatihan_id`),
  ADD CONSTRAINT `t_data_pelatihan_surat_tugas_id_foreign` FOREIGN KEY (`surat_tugas_id`) REFERENCES `m_surat_tugas` (`surat_tugas_id`) ON DELETE CASCADE;

--
-- Constraints for table `t_data_sertifikasi`
--
ALTER TABLE `t_data_sertifikasi`
  ADD CONSTRAINT `t_data_sertifikasi_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `m_dosen` (`dosen_id`),
  ADD CONSTRAINT `t_data_sertifikasi_sertif_id_foreign` FOREIGN KEY (`sertif_id`) REFERENCES `t_sertifikasi` (`sertif_id`),
  ADD CONSTRAINT `t_data_sertifikasi_surat_tugas_id_foreign` FOREIGN KEY (`surat_tugas_id`) REFERENCES `m_surat_tugas` (`surat_tugas_id`) ON DELETE CASCADE;

--
-- Constraints for table `t_pelatihan`
--
ALTER TABLE `t_pelatihan`
  ADD CONSTRAINT `t_pelatihan_bidang_id_foreign` FOREIGN KEY (`bidang_id`) REFERENCES `m_bidang` (`bidang_id`),
  ADD CONSTRAINT `t_pelatihan_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `m_level_pelatihan` (`level_id`),
  ADD CONSTRAINT `t_pelatihan_mk_id_foreign` FOREIGN KEY (`mk_id`) REFERENCES `m_matkul` (`mk_id`),
  ADD CONSTRAINT `t_pelatihan_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `m_vendor` (`vendor_id`);

--
-- Constraints for table `t_sertifikasi`
--
ALTER TABLE `t_sertifikasi`
  ADD CONSTRAINT `t_sertifikasi_bidang_id_foreign` FOREIGN KEY (`bidang_id`) REFERENCES `m_bidang` (`bidang_id`),
  ADD CONSTRAINT `t_sertifikasi_jenis_id_foreign` FOREIGN KEY (`jenis_id`) REFERENCES `m_jenis` (`jenis_id`),
  ADD CONSTRAINT `t_sertifikasi_mk_id_foreign` FOREIGN KEY (`mk_id`) REFERENCES `m_matkul` (`mk_id`),
  ADD CONSTRAINT `t_sertifikasi_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `m_vendor` (`vendor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
