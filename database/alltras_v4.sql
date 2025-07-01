-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 11:17 AM
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
-- Database: `alltras`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `ICNo` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PhoneNo` varchar(15) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `Name`, `ICNo`, `Email`, `PhoneNo`, `Password`, `Role`, `reset_token`, `token_expiry`) VALUES
(1, 'pbrCSM', '1', 'pbrCSM@customs.gov.my', '1', '$2y$10$MjWikRmK1FBDP3samrqJoOZCwGjA5fhQJuRShbLXMCgS5Wtdjj/7S', 'PBR CSM', NULL, NULL),
(2, 'psCSM', '2', 'psCSM@customs.gov.my', '2', '$2y$10$kMDBslkN03Y5i8hHF9UkNenkP7AuLicWIrpnq8yzk2QJE8F7.Vywy', 'Pegawai Sulit CSM', NULL, NULL),
(3, 'pCSM', '3', 'pCSM@customs.gov.my', '3', '$2y$10$pE/TVawoVVWqYYOnoEw/GeYNRbXJl/MF4PDQnQxdHU5bBlB5VBhhS', 'Pengesah CSM', NULL, NULL),
(4, 'semakHQ', '4', 'semakHQ@customs.gov.my', '4', '$2y$10$d5e8AYW4ex76vBOq681cf.AdZAGBJZVytEuOHoibqiQj7QCr/ZT8W', 'Penyemak HQ', NULL, NULL),
(5, 'sahHQ', '5', 'sahHQ@customs.gov.my', '5', '$2y$10$ntX14ioOVMe2lEn2m2C0..GbDgFD3e0hIwwertHvpC7WvXZiiMt2G', 'Pengesah HQ', NULL, NULL),
(6, 'lulusHQ', '6', 'lulusHQ@customs.gov.my', '6', '$2y$10$2Mr7XidPUqMszZCPjnLFVeDS.yDrSev4dz2oUo0fr/Y4hCfBnOkP6', 'Pelulus HQ', NULL, NULL),
(7, 'pbWANG', '7', 'pbWANG@customs.gov.my', '7', '$2y$10$NBy9uMiDUrHI4tO4rTunIu/Pkpbvl61Qsi6pzEMM.bJ5rpeiDOfVe', 'Penyemak Baki Kewangan', NULL, NULL),
(8, 'sahWANG', '8', 'sahWANG@customs.gov.my', '8', '$2y$10$RjaV5VprrXOtHcOawZSJK.ClMCAlaiiLgrMoi1GR50IS7sz5GYv1e', 'Pengesah Kewangan', NULL, NULL),
(9, 'pkWANG', '9', 'pkWANG@customs.gov.my', '9', '$2y$10$7YAptkizNe677pQU1u44J.zyoSOxuFqeI4E9yc3dLZL06ds25PSNa', 'Penyedia Kemudahan Kewangan', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `adminrole`
--

CREATE TABLE `adminrole` (
  `ID` int(11) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminrole`
--

INSERT INTO `adminrole` (`ID`, `role`) VALUES
(1, 'PBR CSM'),
(2, 'Pegawai Sulit CSM'),
(3, 'Pengesah CSM'),
(4, 'Penyemak HQ'),
(5, 'Pengesah HQ'),
(6, 'Pelulus HQ'),
(7, 'Penyemak Baki Kewangan'),
(8, 'Pengesah Kewangan'),
(9, 'Penyedia Kemudahan Kewangan');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `wilayah_asal_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `file_origin` enum('pemohon','csm1','csm2','hq','kewangan') DEFAULT 'pemohon',
  `file_origin_id` varchar(20) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `wilayah_asal_id`, `file_name`, `file_path`, `file_type`, `file_size`, `description`, `file_origin`, `file_origin_id`, `upload_date`) VALUES
(1, 10, 'Doc1.pdf', '../../../uploads/permohonan/10/685ba5615b18c_10_Dokumen_Pegawai.pdf', 'application/pdf', 162098, 'Dokumen Pegawai', 'pemohon', '010201100753', '2025-06-25 07:29:37'),
(2, 10, 'IC.pdf', '../../../uploads/permohonan/10/685ba5615d24c_10_Lampiran_II.pdf', 'application/pdf', 150885, 'Lampiran II', 'pemohon', '010201100753', '2025-06-25 07:29:37'),
(3, 10, 'KAWALAN DOKUMEN.pdf', '../../../uploads/permohonan/10/685ba5615ef75_10_Dokumen_Pasangan.pdf', 'application/pdf', 59717, 'Dokumen Pasangan', 'pemohon', '010201100753', '2025-06-25 07:29:37'),
(4, 10, 'Mini Transkrip Degree.pdf', '../../../uploads/permohonan/10/685ba56160a10_10_Sijil_Perkahwinan.pdf', 'application/pdf', 332316, 'Sijil Perkahwinan', 'pemohon', '010201100753', '2025-06-25 07:29:37'),
(5, 10, 'MQS UiTM.pdf', '../../../uploads/permohonan/10/685ba5616236d_10_Dokumen_Pengikut_1.pdf', 'application/pdf', 351388, 'Dokumen Pengikut 1', 'pemohon', '010201100753', '2025-06-25 07:29:37'),
(6, 10, 'PERANAN.pdf', '../../../uploads/permohonan/10/685ba56163cf9_10_Dokumen_Pengikut_2.pdf', 'application/pdf', 78913, 'Dokumen Pengikut 2', 'pemohon', '010201100753', '2025-06-25 07:29:37'),
(7, 10, 'Software Requirement Specification.pdf', '../../../uploads/permohonan/10/685ba561658c2_10_Dokumen_Sokongan_1.pdf', 'application/pdf', 198188, 'Dokumen Sokongan 1', 'pemohon', '010201100753', '2025-06-25 07:29:37'),
(8, 10, 'Student Task Manager.pdf', '../../../uploads/permohonan/10/685ba561671f5_10_Dokumen_Sokongan_2.pdf', 'application/pdf', 1516496, 'Dokumen Sokongan 2', 'pemohon', '010201100753', '2025-06-25 07:29:37'),
(9, 10, 'TEST DOCUMENT.pdf', '../../../uploads/permohonan/10/685ba5616901e_10_Dokumen_Sokongan_3.pdf', 'application/pdf', 15512, 'Dokumen Sokongan 3', 'pemohon', '010201100753', '2025-06-25 07:29:37'),
(10, 11, 'new_InternReport_merged.pdf', '../../../uploads/permohonan/11/685ba639274e7_11_Dokumen_Pegawai.pdf', 'application/pdf', 384190, 'Dokumen Pegawai', 'pemohon', '111111111111', '2025-06-25 07:33:13'),
(11, 11, 'DLI04 - Format Report 2025.pdf', '../../../uploads/permohonan/11/685ba63928d3f_11_Lampiran_II.pdf', 'application/pdf', 404333, 'Lampiran II', 'pemohon', '111111111111', '2025-06-25 07:33:13'),
(12, 11, 'new_InternReport_pagenumber.pdf', '../../../uploads/permohonan/11/685ba6392a0b7_11_Dokumen_Sokongan_1.pdf', 'application/pdf', 286245, 'Dokumen Sokongan 1', 'pemohon', '111111111111', '2025-06-25 07:33:13'),
(13, 11, 'LOG BOOK FSKM 2025.pdf', '../../../uploads/permohonan/11/685ba6392b0fe_11_Dokumen_Sokongan_2.pdf', 'application/pdf', 542294, 'Dokumen Sokongan 2', 'pemohon', '111111111111', '2025-06-25 07:33:13'),
(14, 12, 'hhhhhh.pdf', '../../../uploads/permohonan/12/685ba719af71c_12_Dokumen_Pegawai.pdf', 'application/pdf', 312836, 'Dokumen Pegawai', 'pemohon', '222222222222', '2025-06-25 07:36:57'),
(15, 12, 'ilovepdf_merged.pdf', '../../../uploads/permohonan/12/685ba719b0ad8_12_Lampiran_II.pdf', 'application/pdf', 4729832, 'Lampiran II', 'pemohon', '222222222222', '2025-06-25 07:36:57'),
(16, 12, '202505_KEBENARAN MASUK LEWAT_ASEAN.pdf', '../../../uploads/permohonan/12/685ba719b1a8b_12_Dokumen_Pasangan.pdf', 'application/pdf', 148765, 'Dokumen Pasangan', 'pemohon', '222222222222', '2025-06-25 07:36:57'),
(17, 12, 'SHARP BP-70C55_20250610_110804.pdf', '../../../uploads/permohonan/12/685ba719b28b1_12_Sijil_Perkahwinan.pdf', 'application/pdf', 696353, 'Sijil Perkahwinan', 'pemohon', '222222222222', '2025-06-25 07:36:57'),
(18, 12, 'LAPORAN_KEHADIRAN_KAKITANGAN_INDIVIDU_5.pdf', '../../../uploads/permohonan/12/685ba719b36fb_12_Dokumen_Pengikut_1.pdf', 'application/pdf', 3161164, 'Dokumen Pengikut 1', 'pemohon', '222222222222', '2025-06-25 07:36:57'),
(19, 12, '162478-386543_20250531.pdf', '../../../uploads/permohonan/12/685ba719b50bd_12_Dokumen_Sokongan_1.pdf', 'application/pdf', 165053, 'Dokumen Sokongan 1', 'pemohon', '222222222222', '2025-06-25 07:36:57'),
(20, 12, 'Resume Minimal (1) (1).pdf', '../../../uploads/permohonan/12/685ba719b5ff1_12_Dokumen_Sokongan_2.pdf', 'application/pdf', 485329, 'Dokumen Sokongan 2', 'pemohon', '222222222222', '2025-06-25 07:36:57'),
(21, 13, 'SalinanIC.pdf', '../../../uploads/permohonan/13/685ba85e2d37f_13_Dokumen_Pegawai.pdf', 'application/pdf', 113493, 'Dokumen Pegawai', 'pemohon', '333333333333', '2025-06-25 07:42:22'),
(22, 13, 'Statement Bulan 5.pdf', '../../../uploads/permohonan/13/685ba85e2eb01_13_Lampiran_II.pdf', 'application/pdf', 226773, 'Lampiran II', 'pemohon', '333333333333', '2025-06-25 07:42:22'),
(23, 13, 'Tuntutan Bulan 3.pdf', '../../../uploads/permohonan/13/685ba85e2faab_13_Dokumen_Pasangan.pdf', 'application/pdf', 35496, 'Dokumen Pasangan', 'pemohon', '333333333333', '2025-06-25 07:42:22'),
(24, 13, 'Tuntutan Bulan 4.pdf', '../../../uploads/permohonan/13/685ba85e30783_13_Sijil_Perkahwinan.pdf', 'application/pdf', 35284, 'Sijil Perkahwinan', 'pemohon', '333333333333', '2025-06-25 07:42:22'),
(25, 13, 'Tuntutan Bulan 5.pdf', '../../../uploads/permohonan/13/685ba85e31aa9_13_Dokumen_Pengikut_1.pdf', 'application/pdf', 210645, 'Dokumen Pengikut 1', 'pemohon', '333333333333', '2025-06-25 07:42:22'),
(26, 13, 'Full Log Book 2022755933.pdf', '../../../uploads/permohonan/13/685ba85e33350_13_Dokumen_Sokongan_1.pdf', 'application/pdf', 11290848, 'Dokumen Sokongan 1', 'pemohon', '333333333333', '2025-06-25 07:42:22'),
(27, 13, 'Full Report 2022755933.pdf', '../../../uploads/permohonan/13/685ba85e3467e_13_Dokumen_Sokongan_2.pdf', 'application/pdf', 394460, 'Dokumen Sokongan 2', 'pemohon', '333333333333', '2025-06-25 07:42:22'),
(28, 14, 'Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', '../../../uploads/permohonan/14/685bb0527fb18_14_Dokumen_Pegawai.pdf', 'application/pdf', 18586301, 'Dokumen Pegawai', 'pemohon', '920307085064', '2025-06-25 08:16:18'),
(29, 14, 'Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', '../../../uploads/permohonan/14/685bb05281b85_14_Lampiran_II.pdf', 'application/pdf', 18586301, 'Lampiran II', 'pemohon', '920307085064', '2025-06-25 08:16:18'),
(30, 14, 'Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', '../../../uploads/permohonan/14/685bb05283432_14_Dokumen_Pasangan.pdf', 'application/pdf', 18586301, 'Dokumen Pasangan', 'pemohon', '920307085064', '2025-06-25 08:16:18'),
(31, 14, 'Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', '../../../uploads/permohonan/14/685bb0528526f_14_Sijil_Perkahwinan.pdf', 'application/pdf', 18586301, 'Sijil Perkahwinan', 'pemohon', '920307085064', '2025-06-25 08:16:18'),
(32, 14, 'Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', '../../../uploads/permohonan/14/685bb05286079_14_Dokumen_Pengikut_1.pdf', 'application/pdf', 18586301, 'Dokumen Pengikut 1', 'pemohon', '920307085064', '2025-06-25 08:16:18'),
(33, 14, 'Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', '../../../uploads/permohonan/14/685bb05286c58_14_Dokumen_Sokongan_1.pdf', 'application/pdf', 18586301, 'Dokumen Sokongan 1', 'pemohon', '920307085064', '2025-06-25 08:16:18'),
(34, 14, 'Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', '../../../uploads/permohonan/14/685bb05287644_14_Dokumen_Sokongan_2.pdf', 'application/pdf', 18586301, 'Dokumen Sokongan 2', 'pemohon', '920307085064', '2025-06-25 08:16:18'),
(35, 14, 'kelulusan hehe.pdf', '../../../uploads/hq/685bb47255c46_14_Surat_Kelulusan.pdf', 'application/pdf', 327499, 'Surat Kelulusan', 'hq', '4', '2025-06-25 08:33:54'),
(36, 14, 'Resume_Najmi_Khairuzzaman.pdf', '../../../uploads/kewangan/685bb6d04ef41_14_E-tiket.pdf', 'application/pdf', 501109, 'E-tiket', 'kewangan', '9', '2025-06-25 08:44:00'),
(37, 15, 'Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', '../../../uploads/permohonan/15/685bbb83641d8_15_Dokumen_Pegawai.pdf', 'application/pdf', 18586301, 'Dokumen Pegawai', 'pemohon', '920307085064', '2025-06-25 09:04:03'),
(38, 15, 'Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', '../../../uploads/permohonan/15/685bbb8366021_15_Lampiran_II.pdf', 'application/pdf', 18586301, 'Lampiran II', 'pemohon', '920307085064', '2025-06-25 09:04:03'),
(39, 15, 'Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', '../../../uploads/permohonan/15/685bbb8367bff_15_Dokumen_Pasangan.pdf', 'application/pdf', 18586301, 'Dokumen Pasangan', 'pemohon', '920307085064', '2025-06-25 09:04:03'),
(40, 15, 'Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', '../../../uploads/permohonan/15/685bbb83698fe_15_Sijil_Perkahwinan.pdf', 'application/pdf', 18586301, 'Sijil Perkahwinan', 'pemohon', '920307085064', '2025-06-25 09:04:03');

-- --------------------------------------------------------

--
-- Table structure for table `document_logs`
--

CREATE TABLE `document_logs` (
  `id` int(11) NOT NULL,
  `tarikh` date NOT NULL,
  `namaAdmin` varchar(255) NOT NULL,
  `peranan` varchar(255) NOT NULL,
  `tindakan` varchar(255) NOT NULL,
  `catatan` varchar(255) NOT NULL,
  `wilayah_asal_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_logs`
--

INSERT INTO `document_logs` (`id`, `tarikh`, `namaAdmin`, `peranan`, `tindakan`, `catatan`, `wilayah_asal_id`) VALUES
(1, '2025-06-25', 'pbrCSM', 'PBR CSM', 'Diterima', '', 14),
(2, '2025-06-25', 'psCSM', 'Pegawai Sulit CSM', 'Telah diisi markah prestasi', '-', 14),
(3, '2025-06-25', 'pCSM', 'Pengesah CSM', 'Sah/Perakuan I', '', 14),
(4, '2025-06-25', 'semakHQ', 'Penyemak HQ', 'Diterima', '', 14),
(5, '2025-06-25', 'sahHQ', 'Pengesah HQ', 'Disahkan', '', 14),
(6, '2025-06-25', 'lulusHQ', 'Pelulus HQ', 'Diluluskan', '', 14),
(7, '2025-06-25', 'semakHQ', 'Penyemak HQ', 'Telah muat naik surat kelulusan', '-', 14),
(8, '2025-06-25', 'pbrCSM', 'PBR CSM', 'Telah direkodkan di dalam buku log', '-', 14),
(9, '2025-06-25', 'pCSM', 'Pengesah CSM', 'Sah/Perakuan II', '', 14),
(10, '2025-06-25', 'pbWANG', 'Penyemak Baki Kewangan', 'Telah disemak baki', '-', 14),
(11, '2025-06-25', 'sahWANG', 'Pengesah Kewangan', 'Disahkan', '', 14),
(12, '2025-06-25', 'pkWANG', 'Penyedia Kemudahan Kewangan', 'Telah muat naik e-tiket', '-', 14),
(13, '2025-06-25', 'SUPER ADMIN CTM', 'Super Admin', 'Dibatalkan', 'haha takleh balik', 13);

-- --------------------------------------------------------

--
-- Table structure for table `organisasi`
--

CREATE TABLE `organisasi` (
  `id` int(11) NOT NULL,
  `nama_cawangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organisasi`
--

INSERT INTO `organisasi` (`id`, `nama_cawangan`) VALUES
(1, 'CAWANGAN VERIFIKASI & PROFILING'),
(2, 'CAWANGAN AUDIT IMPORT & SST I'),
(3, 'CAWANGAN AUDIT IMPORT & SST II'),
(4, 'CAWANGAN AUDIT IMPORT & SST III'),
(5, 'CAWANGAN FROD PERDAGANGAN'),
(6, 'CAWANGAN FROD DOKUMENTASI'),
(7, 'CAWANGAN OPERASI'),
(8, 'CAWANGAN KHIDMAT SOKONGAN'),
(9, 'CAWANGAN AKAUN BELUM TERIMA'),
(10, 'CAWANGAN KRIMINAL'),
(11, 'CAWANGAN SIVIL'),
(12, 'CAWANGAN KONSULTASI'),
(13, 'CAWANGAN KEMUDAHAN'),
(14, 'UNIT PENDAFTARAN'),
(15, 'UNIT PEMBATALAN'),
(16, 'UNIT PENGUATKUASAAN PENDAFTARAN'),
(17, 'CAWANGAN PENYATA'),
(18, 'CAWANGAN PUNGUTAN'),
(19, 'CAWANGAN ANALISA, PROFILING & OPERASI'),
(20, 'CAWANGAN REMISI'),
(21, 'CAWANGAN PENILAIAN'),
(22, 'CAWANGAN KLASIFIKASI'),
(23, 'CAWANGAN PERAKUANAN HASIL'),
(24, 'CAWANGAN PENGGUNDANGAN'),
(25, 'CAWANGAN PERINDUSTRIAN'),
(26, 'CAWANGAN EKSAIS'),
(27, 'CAWANGAN IMPORT & EKSPORT'),
(28, 'CAWANGAN ZON PERINDUSTRIAN BEBAS (ZPB SUNGAI WAY)'),
(29, 'CAWANGAN ZON PERINDUSTRIAN BEBAS (ZPB HULU KELANG)'),
(30, 'CAWANGAN KEWANGAN'),
(31, 'CAWANGAN PEROLEHAN'),
(32, 'CAWANGAN SUMBER MANUSIA'),
(33, 'CAWANGAN PENTADBIRAN AM'),
(34, 'CAWANGAN INTEGRITI'),
(35, 'CAWANGAN LATIHAN DAN KORPORAT'),
(36, 'CAWANGAN TEKNOLOGI MAKLUMAT');

-- --------------------------------------------------------

--
-- Table structure for table `superadmin`
--

CREATE TABLE `superadmin` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `ICNo` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PhoneNo` varchar(15) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `superadmin`
--

INSERT INTO `superadmin` (`ID`, `Name`, `ICNo`, `Email`, `PhoneNo`, `Password`, `reset_token`, `token_expiry`) VALUES
(3, 'SUPER ADMIN CTM', '010100110100000101000100010011010100100101001110', 'yunonajmi@gmail.com', '0179813005', '$2y$10$YzpbkIXuvGyqYURU1C9gW.F149qUSSBwrEW8r9aBfO2m4TeiK/faq', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `event_type` enum('login','logout','document_upload','document_download','document_delete','status_change','data_update','data_create','data_delete','error') NOT NULL,
  `user_type` enum('admin','superAdmin','user') NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `affected_table` varchar(50) DEFAULT NULL,
  `affected_record_id` int(11) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `event_type`, `user_type`, `user_id`, `action`, `description`, `affected_table`, `affected_record_id`, `old_value`, `new_value`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 'login', 'superAdmin', '01010011010000010100', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 03:27:09'),
(2, 'login', 'user', '010201100753', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:27:50'),
(3, 'document_upload', 'user', '010201100753', 'Document upload', 'Document: Doc1.pdf', 'documents', 1, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:29:37'),
(4, 'document_upload', 'user', '010201100753', 'Document upload', 'Document: IC.pdf', 'documents', 2, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:29:37'),
(5, 'document_upload', 'user', '010201100753', 'Document upload', 'Document: KAWALAN DOKUMEN.pdf', 'documents', 3, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:29:37'),
(6, 'document_upload', 'user', '010201100753', 'Document upload', 'Document: Mini Transkrip Degree.pdf', 'documents', 4, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:29:37'),
(7, 'document_upload', 'user', '010201100753', 'Document upload', 'Document: MQS UiTM.pdf', 'documents', 5, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:29:37'),
(8, 'document_upload', 'user', '010201100753', 'Document upload', 'Document: PERANAN.pdf', 'documents', 6, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:29:37'),
(9, 'document_upload', 'user', '010201100753', 'Document upload', 'Document: Software Requirement Specification.pdf', 'documents', 7, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:29:37'),
(10, 'document_upload', 'user', '010201100753', 'Document upload', 'Document: Student Task Manager.pdf', 'documents', 8, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:29:37'),
(11, 'document_upload', 'user', '010201100753', 'Document upload', 'Document: TEST DOCUMENT.pdf', 'documents', 9, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:29:37'),
(12, 'logout', 'user', '010201100753', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:30:47'),
(13, 'login', 'user', '111111111111', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:31:51'),
(14, 'document_upload', 'user', '111111111111', 'Document upload', 'Document: new_InternReport_merged.pdf', 'documents', 10, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:33:13'),
(15, 'document_upload', 'user', '111111111111', 'Document upload', 'Document: DLI04 - Format Report 2025.pdf', 'documents', 11, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:33:13'),
(16, 'document_upload', 'user', '111111111111', 'Document upload', 'Document: new_InternReport_pagenumber.pdf', 'documents', 12, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:33:13'),
(17, 'document_upload', 'user', '111111111111', 'Document upload', 'Document: LOG BOOK FSKM 2025.pdf', 'documents', 13, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:33:13'),
(18, 'logout', 'user', '111111111111', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:34:16'),
(19, 'login', 'user', '222222222222', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:35:04'),
(20, 'document_upload', 'user', '222222222222', 'Document upload', 'Document: hhhhhh.pdf', 'documents', 14, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:36:57'),
(21, 'document_upload', 'user', '222222222222', 'Document upload', 'Document: ilovepdf_merged.pdf', 'documents', 15, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:36:57'),
(22, 'document_upload', 'user', '222222222222', 'Document upload', 'Document: 202505_KEBENARAN MASUK LEWAT_ASEAN.pdf', 'documents', 16, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:36:57'),
(23, 'document_upload', 'user', '222222222222', 'Document upload', 'Document: SHARP BP-70C55_20250610_110804.pdf', 'documents', 17, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:36:57'),
(24, 'document_upload', 'user', '222222222222', 'Document upload', 'Document: LAPORAN_KEHADIRAN_KAKITANGAN_INDIVIDU_5.pdf', 'documents', 18, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:36:57'),
(25, 'document_upload', 'user', '222222222222', 'Document upload', 'Document: 162478-386543_20250531.pdf', 'documents', 19, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:36:57'),
(26, 'document_upload', 'user', '222222222222', 'Document upload', 'Document: Resume Minimal (1) (1).pdf', 'documents', 20, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:36:57'),
(27, 'logout', 'user', '222222222222', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:37:22'),
(28, 'login', 'user', '333333333333', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:38:54'),
(29, 'document_upload', 'user', '333333333333', 'Document upload', 'Document: SalinanIC.pdf', 'documents', 21, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:42:22'),
(30, 'document_upload', 'user', '333333333333', 'Document upload', 'Document: Statement Bulan 5.pdf', 'documents', 22, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:42:22'),
(31, 'document_upload', 'user', '333333333333', 'Document upload', 'Document: Tuntutan Bulan 3.pdf', 'documents', 23, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:42:22'),
(32, 'document_upload', 'user', '333333333333', 'Document upload', 'Document: Tuntutan Bulan 4.pdf', 'documents', 24, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:42:22'),
(33, 'document_upload', 'user', '333333333333', 'Document upload', 'Document: Tuntutan Bulan 5.pdf', 'documents', 25, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:42:22'),
(34, 'document_upload', 'user', '333333333333', 'Document upload', 'Document: Full Log Book 2022755933.pdf', 'documents', 26, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:42:22'),
(35, 'document_upload', 'user', '333333333333', 'Document upload', 'Document: Full Report 2022755933.pdf', 'documents', 27, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 07:42:22'),
(36, 'login', 'user', '920307085064', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 07:59:34'),
(37, 'document_upload', 'user', '920307085064', 'Document upload', 'Document: Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', 'documents', 28, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 08:16:18'),
(38, 'document_upload', 'user', '920307085064', 'Document upload', 'Document: Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', 'documents', 29, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 08:16:18'),
(39, 'document_upload', 'user', '920307085064', 'Document upload', 'Document: Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', 'documents', 30, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 08:16:18'),
(40, 'document_upload', 'user', '920307085064', 'Document upload', 'Document: Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', 'documents', 31, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 08:16:18'),
(41, 'document_upload', 'user', '920307085064', 'Document upload', 'Document: Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', 'documents', 32, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 08:16:18'),
(42, 'document_upload', 'user', '920307085064', 'Document upload', 'Document: Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', 'documents', 33, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 08:16:18'),
(43, 'document_upload', 'user', '920307085064', 'Document upload', 'Document: Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', 'documents', 34, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 08:16:18'),
(44, 'logout', 'user', '333333333333', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:19:25'),
(45, 'login', 'admin', '1', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:21:20'),
(46, 'logout', 'admin', '1', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:23:11'),
(47, 'login', 'admin', '2', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:23:44'),
(48, 'logout', 'admin', '2', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:29:04'),
(49, 'login', 'admin', '3', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:29:34'),
(50, 'status_change', 'admin', '3', 'Application Status Change', 'CSM Pengesah updated application status', 'wilayah_asal', 14, 'Menunggu pengesahan pengesah CSM', 'Menunggu pengesahan penyemak1 HQ', '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:30:23'),
(51, 'logout', 'admin', '3', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:30:34'),
(52, 'login', 'admin', '4', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:30:54'),
(53, 'status_change', 'admin', '4', 'Application Status Change', 'HQ Penyemak updated application status', 'wilayah_asal', 14, 'Menunggu pengesahan penyemak1 HQ', 'Menunggu pengesahan pengesah HQ', '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:31:08'),
(54, 'logout', 'admin', '4', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:31:18'),
(55, 'login', 'admin', '5', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:31:35'),
(56, 'status_change', 'admin', '5', 'Application Status Change', 'HQ Pengesah updated application status', 'wilayah_asal', 14, 'Menunggu pengesahan pengesah HQ', 'Menunggu Pengesahan Pelulus HQ', '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:32:02'),
(57, 'logout', 'admin', '5', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:32:12'),
(58, 'login', 'admin', '6', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:32:20'),
(59, 'status_change', 'admin', '6', 'Application Status Change', 'HQ Pelulus updated application status', 'wilayah_asal', 14, 'Menunggu Pengesahan Pelulus HQ', 'Menunggu pengesahan penyemak2 HQ', '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:32:30'),
(60, 'logout', 'admin', '6', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:32:39'),
(61, 'login', 'admin', '4', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:32:46'),
(62, 'logout', 'admin', '4', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:34:02'),
(63, 'login', 'admin', '7', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:34:18'),
(64, 'logout', 'admin', '7', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:34:25'),
(65, 'login', 'admin', '1', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:34:31'),
(66, 'logout', 'admin', '1', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:35:02'),
(67, 'login', 'admin', '3', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:35:37'),
(68, 'status_change', 'admin', '3', 'Application Status Change', 'CSM Pengesah2 updated application status', 'wilayah_asal', 14, 'Menunggu pengesahan pengesah2 CSM', 'Menunggu pengesahan penyemak baki kewangan', '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:36:47'),
(69, 'logout', 'admin', '3', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:37:07'),
(70, 'login', 'admin', '7', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:37:17'),
(71, 'logout', 'admin', '7', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:38:26'),
(72, 'login', 'admin', '8', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:39:40'),
(73, 'status_change', 'admin', '8', 'Application Status Change', 'Kewangan Pengesah updated application status', 'wilayah_asal', 14, 'Menunggu pengesahan pengesah kewangan', 'Menunggu pengesahan penyedia kemudahan kewangan', '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:40:03'),
(74, 'logout', 'admin', '8', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:40:12'),
(75, 'login', 'admin', '9', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:40:28'),
(76, 'logout', 'admin', '9', 'User Logout', 'User logged out', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:46:46'),
(77, 'login', 'superAdmin', '01010011010000010100', 'User Login', 'Successful login attempt', NULL, NULL, NULL, NULL, '10.13.101.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', '2025-06-25 08:47:00'),
(78, 'document_upload', 'user', '920307085064', 'Document upload', 'Document: Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', 'documents', 37, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 09:04:03'),
(79, 'document_upload', 'user', '920307085064', 'Document upload', 'Document: Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', 'documents', 38, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 09:04:03'),
(80, 'document_upload', 'user', '920307085064', 'Document upload', 'Document: Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', 'documents', 39, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 09:04:03'),
(81, 'document_upload', 'user', '920307085064', 'Document upload', 'Document: Carta Organisasi Senarai Nama dan Jawatan Pegawai Kastam JKDM_17.02.2025_.pdf', 'documents', 40, NULL, NULL, '10.13.101.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', '2025-06-25 09:04:03');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama_first` varchar(50) NOT NULL,
  `nama_last` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `kp` varchar(20) NOT NULL,
  `bahagian` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama_first`, `nama_last`, `email`, `phone`, `kp`, `bahagian`, `password`, `created_at`, `updated_at`, `reset_token`, `token_expiry`) VALUES
(5, 'WAN MUHAMMAD NAJMI', 'BIN WAN KHAIRUZZAMAN', 'yunonajmi@gmail.com', '0179813005', '010201100753', 'CAWANGAN KEWANGAN', '$2y$10$vAWCTcamT3CNGd2/KucAyul2SrWN0CfTCQcevVey0PytBEGIMeiZW', '2025-06-25 07:20:24', '2025-06-25 07:20:24', NULL, NULL),
(6, 'NAJWA', 'KHAIRUZZAMAN', 'najmigamer@gmail.com', '0179813005', '111111111111', 'CAWANGAN KRIMINAL', '$2y$10$Wh1rjy2HbHOjhm8k4wBjxOEuT05YpwV.raQm/r0yw6PxpThmokMF6', '2025-06-25 07:31:46', '2025-06-25 07:31:46', NULL, NULL),
(7, 'NADZRIN', 'KHAIRUZZAMAN', 'wm.najmi.k@gmail.com', '0179813005', '222222222222', 'CAWANGAN PEROLEHAN', '$2y$10$08aald.e5neZ8X1Ny7uV9O07O.ZjJUg2XDnqXRODBu1mrZkaNGrs2', '2025-06-25 07:34:58', '2025-06-25 07:34:58', NULL, NULL),
(8, 'NAZIM', 'KHAIRUZZAMAN', '2022755933@student.uitm.edu.my', '0179813005', '333333333333', 'CAWANGAN LATIHAN DAN KORPORAT', '$2y$10$yl6eBwRf5/z/cICfubXmZeJ6RhtwaAyzfd2oAAyR9MK1UFeLcCQAi', '2025-06-25 07:38:49', '2025-06-25 07:38:49', NULL, NULL),
(9, 'FARAH FARHANA', 'HANIFAH', 'farhana.hanifah@customs.gov.my', '0162290230', '920307085064', 'CAWANGAN KEWANGAN', '$2y$10$Lo4fAR/rj26v2N2Tbk9WIebwKfykkFy86.PJ/TZozOrLu82RhdErC', '2025-06-25 07:59:24', '2025-06-25 07:59:24', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wilayah_asal`
--

CREATE TABLE `wilayah_asal` (
  `id` int(11) NOT NULL,
  `user_kp` varchar(20) NOT NULL,
  `jawatan_gred` varchar(100) NOT NULL,
  `email_penyelia` varchar(100) NOT NULL,
  `alamat_menetap_1` varchar(100) NOT NULL,
  `alamat_menetap_2` varchar(100) DEFAULT NULL,
  `poskod_menetap` varchar(10) NOT NULL,
  `bandar_menetap` varchar(50) NOT NULL,
  `negeri_menetap` varchar(50) NOT NULL,
  `alamat_berkhidmat_1` varchar(100) NOT NULL,
  `alamat_berkhidmat_2` varchar(100) DEFAULT NULL,
  `poskod_berkhidmat` varchar(10) NOT NULL,
  `bandar_berkhidmat` varchar(50) NOT NULL,
  `negeri_berkhidmat` varchar(50) NOT NULL,
  `tarikh_lapor_diri` date NOT NULL,
  `tarikh_terakhir_kemudahan` date DEFAULT NULL,
  `nama_first_pasangan` varchar(50) DEFAULT NULL,
  `nama_last_pasangan` varchar(50) DEFAULT NULL,
  `no_kp_pasangan` varchar(20) DEFAULT NULL,
  `alamat_berkhidmat_1_pasangan` varchar(100) DEFAULT NULL,
  `alamat_berkhidmat_2_pasangan` varchar(100) DEFAULT NULL,
  `poskod_berkhidmat_pasangan` varchar(10) DEFAULT NULL,
  `bandar_berkhidmat_pasangan` varchar(50) DEFAULT NULL,
  `negeri_berkhidmat_pasangan` varchar(50) DEFAULT NULL,
  `wilayah_menetap_pasangan` varchar(50) DEFAULT NULL,
  `nama_bapa` varchar(50) DEFAULT NULL,
  `no_kp_bapa` varchar(20) DEFAULT NULL,
  `wilayah_menetap_bapa` varchar(50) DEFAULT NULL,
  `alamat_menetap_1_bapa` varchar(100) DEFAULT NULL,
  `alamat_menetap_2_bapa` varchar(100) DEFAULT NULL,
  `poskod_menetap_bapa` varchar(10) DEFAULT NULL,
  `bandar_menetap_bapa` varchar(50) DEFAULT NULL,
  `negeri_menetap_bapa` varchar(50) DEFAULT NULL,
  `ibu_negeri_bandar_dituju_bapa` varchar(50) DEFAULT NULL,
  `nama_ibu` varchar(50) DEFAULT NULL,
  `no_kp_ibu` varchar(20) DEFAULT NULL,
  `wilayah_menetap_ibu` varchar(50) DEFAULT NULL,
  `alamat_menetap_1_ibu` varchar(100) DEFAULT NULL,
  `alamat_menetap_2_ibu` varchar(100) DEFAULT NULL,
  `poskod_menetap_ibu` varchar(10) DEFAULT NULL,
  `bandar_menetap_ibu` varchar(50) DEFAULT NULL,
  `negeri_menetap_ibu` varchar(50) DEFAULT NULL,
  `ibu_negeri_bandar_dituju_ibu` varchar(50) DEFAULT NULL,
  `jenis_permohonan` enum('diri_sendiri','keluarga') NOT NULL,
  `tarikh_penerbangan_pergi` date NOT NULL,
  `tarikh_penerbangan_balik` date NOT NULL,
  `tarikh_penerbangan_pergi_pasangan` date DEFAULT NULL,
  `tarikh_penerbangan_balik_pasangan` date DEFAULT NULL,
  `start_point` varchar(100) NOT NULL,
  `end_point` varchar(100) NOT NULL,
  `pengesahan_user` tinyint(1) DEFAULT 0,
  `tarikh_pengesahan_user` date DEFAULT NULL,
  `markah_prestasi_user` varchar(10000) DEFAULT NULL,
  `hukuman_tatatertib_user` enum('Ada','Tiada','Belum Pasti') DEFAULT 'Belum Pasti',
  `tarikh_csm_permohonan` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `keputusan_permohonan_ketua_jabatan` enum('Diterima','Ditolak','Belum Pasti') DEFAULT 'Belum Pasti',
  `kp_ketua_jabatan` varchar(50) DEFAULT NULL,
  `tarikh_keputusan_ketua_jabatan` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status_permohonan` enum('Belum Disemak','Selesai','Dikuiri','Tolak','Lulus','Batal') DEFAULT 'Belum Disemak',
  `kedudukan_permohonan` enum('Pemohon','CSM','HQ','CSM2','Kewangan') DEFAULT 'Pemohon',
  `status` varchar(255) DEFAULT NULL,
  `tarikh_keputusan_csm1` date DEFAULT NULL,
  `ulasan_pbr_csm1` text DEFAULT NULL,
  `pbr_csm1_id` int(11) DEFAULT NULL,
  `pengesah_csm1_id` int(11) DEFAULT NULL,
  `pegSulit_csm_id` int(11) DEFAULT NULL,
  `tarikh_keputusan_pengesah_csm1` date DEFAULT NULL,
  `ulasan_pengesah_csm1` text DEFAULT NULL,
  `tarikh_keputusan_csm2` date DEFAULT NULL,
  `pbr_csm2_id` int(11) DEFAULT NULL,
  `tarikh_keputusan_pegsulit_csm` date DEFAULT NULL,
  `pengesah_csm2_id` int(11) DEFAULT NULL,
  `tarikh_keputusan_pengesah_csm2` date DEFAULT NULL,
  `ulasan_pengesah_csm2` text DEFAULT NULL,
  `penyemak_HQ1_id` int(11) DEFAULT NULL,
  `ulasan_penyemak_HQ` text DEFAULT NULL,
  `tarikh_keputusan_penyemak_HQ1` date DEFAULT NULL,
  `pengesah_HQ_id` int(11) DEFAULT NULL,
  `ulasan_pengesah_HQ` text DEFAULT NULL,
  `tarikh_keputusan_pengesah_HQ` date DEFAULT NULL,
  `pelulus_HQ_id` int(11) DEFAULT NULL,
  `ulasan_pelulus_HQ` text DEFAULT NULL,
  `tarikh_keputusan_pelulus_HQ` date DEFAULT NULL,
  `penyemak_HQ2_id` int(11) DEFAULT NULL,
  `tarikh_keputusan_penyemak_HQ2` date DEFAULT NULL,
  `penyemakBaki_kewangan_id` int(11) DEFAULT NULL,
  `tarikh_keputusan_penyemakBaki_kewangan` date DEFAULT NULL,
  `ulasan_penyemakBaki_kewangan` text DEFAULT NULL,
  `pengesah_kewangan_id` int(11) DEFAULT NULL,
  `tarikh_keputusan_pengesah_kewangan` date DEFAULT NULL,
  `ulasan_pengesah_kewangan` text DEFAULT NULL,
  `penyediaKemudahan_kewangan_id` int(11) DEFAULT NULL,
  `tarikh_keputusan_penyediaKemudahan_kewangan` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `wilayah_asal_form_fill` tinyint(1) DEFAULT 0,
  `wilayah_asal_from_stage` enum('Empty','BorangWA','BorangWA2','BorangWA3','BorangWA4','BorangWA5','Hantar') DEFAULT NULL,
  `wilayah_asal_matang` tinyint(1) DEFAULT 0,
  `ulasan_superadmin` varchar(255) DEFAULT NULL,
  `tarikh_keputusan_superadmin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wilayah_asal`
--

INSERT INTO `wilayah_asal` (`id`, `user_kp`, `jawatan_gred`, `email_penyelia`, `alamat_menetap_1`, `alamat_menetap_2`, `poskod_menetap`, `bandar_menetap`, `negeri_menetap`, `alamat_berkhidmat_1`, `alamat_berkhidmat_2`, `poskod_berkhidmat`, `bandar_berkhidmat`, `negeri_berkhidmat`, `tarikh_lapor_diri`, `tarikh_terakhir_kemudahan`, `nama_first_pasangan`, `nama_last_pasangan`, `no_kp_pasangan`, `alamat_berkhidmat_1_pasangan`, `alamat_berkhidmat_2_pasangan`, `poskod_berkhidmat_pasangan`, `bandar_berkhidmat_pasangan`, `negeri_berkhidmat_pasangan`, `wilayah_menetap_pasangan`, `nama_bapa`, `no_kp_bapa`, `wilayah_menetap_bapa`, `alamat_menetap_1_bapa`, `alamat_menetap_2_bapa`, `poskod_menetap_bapa`, `bandar_menetap_bapa`, `negeri_menetap_bapa`, `ibu_negeri_bandar_dituju_bapa`, `nama_ibu`, `no_kp_ibu`, `wilayah_menetap_ibu`, `alamat_menetap_1_ibu`, `alamat_menetap_2_ibu`, `poskod_menetap_ibu`, `bandar_menetap_ibu`, `negeri_menetap_ibu`, `ibu_negeri_bandar_dituju_ibu`, `jenis_permohonan`, `tarikh_penerbangan_pergi`, `tarikh_penerbangan_balik`, `tarikh_penerbangan_pergi_pasangan`, `tarikh_penerbangan_balik_pasangan`, `start_point`, `end_point`, `pengesahan_user`, `tarikh_pengesahan_user`, `markah_prestasi_user`, `hukuman_tatatertib_user`, `tarikh_csm_permohonan`, `keputusan_permohonan_ketua_jabatan`, `kp_ketua_jabatan`, `tarikh_keputusan_ketua_jabatan`, `status_permohonan`, `kedudukan_permohonan`, `status`, `tarikh_keputusan_csm1`, `ulasan_pbr_csm1`, `pbr_csm1_id`, `pengesah_csm1_id`, `pegSulit_csm_id`, `tarikh_keputusan_pengesah_csm1`, `ulasan_pengesah_csm1`, `tarikh_keputusan_csm2`, `pbr_csm2_id`, `tarikh_keputusan_pegsulit_csm`, `pengesah_csm2_id`, `tarikh_keputusan_pengesah_csm2`, `ulasan_pengesah_csm2`, `penyemak_HQ1_id`, `ulasan_penyemak_HQ`, `tarikh_keputusan_penyemak_HQ1`, `pengesah_HQ_id`, `ulasan_pengesah_HQ`, `tarikh_keputusan_pengesah_HQ`, `pelulus_HQ_id`, `ulasan_pelulus_HQ`, `tarikh_keputusan_pelulus_HQ`, `penyemak_HQ2_id`, `tarikh_keputusan_penyemak_HQ2`, `penyemakBaki_kewangan_id`, `tarikh_keputusan_penyemakBaki_kewangan`, `ulasan_penyemakBaki_kewangan`, `pengesah_kewangan_id`, `tarikh_keputusan_pengesah_kewangan`, `ulasan_pengesah_kewangan`, `penyediaKemudahan_kewangan_id`, `tarikh_keputusan_penyediaKemudahan_kewangan`, `created_at`, `updated_at`, `wilayah_asal_form_fill`, `wilayah_asal_from_stage`, `wilayah_asal_matang`, `ulasan_superadmin`, `tarikh_keputusan_superadmin`) VALUES
(10, '010201100753', 'WK01', 'yunonajmi@gmail.com', 'NO 891 JLN SUBANG INDAH U69/420', 'NO 891 JLN SUBANG INDAH U69/420', '40170', 'SHAH ALEY', 'SELANGOR', 'NO 891 JLN SUBANG INDAH U69/420', 'NO 891 JLN SUBANG INDAH U69/420', '47301', 'PETALING JAYA', 'SELANGOR', '2024-01-01', '2024-02-01', 'X', 'X', '111111111111', 'ALAMAT 1', 'ALAMAT 2', '12345', 'BESUT', 'Land Of Fire', 'TERENGGANU', 'WAN KHAIRUZZAMAN BIN WAN HARUN', '010101100103', 'TERENGGANO', '891 KAMPUNG BAROI', 'JALAN REYZEK', '23001', 'DUNPISTOL', 'GANU', 'DUNGUN', 'ANISAH BINTI ABDULLAH', '020202020202', 'TERENGGANU', 'Pantai nEW', 'Chendering', '23000', 'Kuala Terengganu', 'Terengganu', 'Kuala Terengganu', 'diri_sendiri', '2025-06-27', '2025-06-28', '2025-06-27', '2025-06-28', 'KLIA', 'SARAWAK', 1, '2025-06-25', NULL, 'Belum Pasti', '2025-06-25 07:29:42', 'Belum Pasti', NULL, '2025-06-25 07:29:42', 'Belum Disemak', 'Pemohon', 'Menunggu pengesahan PBR CSM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-25 07:28:33', '2025-06-25 07:29:42', 1, 'Hantar', 0, NULL, NULL),
(11, '111111111111', 'WK01', 'yunonajmi@gmail.com', 'NO 891 JLN SUBANG INDAH U69/420', 'NO 891 JLN SUBANG INDAH U69/420', '40170', 'SHAH ALEY', 'SELANGOR', 'NO 891 JLN SUBANG INDAH U69/420', 'NO 891 JLN SUBANG INDAH U69/420', '47301', 'PETALING JAYA', 'SELANGOR', '2024-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'WAN KHAIRUZZAMAN BIN WAN HARUN', '010101100103', 'TERENGGANO', '891 KAMPUNG BAROI', 'JALAN REYZEK', '23001', 'DUNPISTOL', 'GANU', 'DUNGUN', 'ANISAH BINTI ABDULLAH', '020202020202', 'TERENGGANU', 'Pantai nEW', 'Chendering', '23000', 'Kuala Terengganu', 'Terengganu', 'Kuala Terengganu', 'keluarga', '2025-06-27', '2025-06-28', '2025-06-27', '2025-06-28', 'KLIA', 'SARAWAK', 1, '2025-06-25', NULL, 'Belum Pasti', '2025-06-25 07:33:28', 'Belum Pasti', NULL, '2025-06-25 07:33:28', 'Belum Disemak', 'Pemohon', 'Menunggu pengesahan PBR CSM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-25 07:32:18', '2025-06-25 07:33:28', 1, 'Hantar', 0, NULL, NULL),
(12, '222222222222', 'F09', 'yunonajmi@gmail.com', 'NO 891 JLN SUBANG INDAH U69/420', 'NO 891 JLN SUBANG INDAH U69/420', '40170', 'SHAH ALEY', 'SELANGOR', 'NO 891 JLN SUBANG INDAH U69/420', 'NO 891 JLN SUBANG INDAH U69/420', '47301', 'PETALING JAYA', 'SELANGOR', '2024-01-01', NULL, 'X', 'X', '111111111111', 'ALAMAT 1', 'ALAMAT 2', '12345', 'BESUT', 'Land Of Fire', 'TERENGGANU', 'WAN KHAIRUZZAMAN BIN WAN HARUN', '010101100103', 'TERENGGANO', '891 KAMPUNG BAROI', 'JALAN REYZEK', '23001', 'DUNPISTOL', 'GANU', 'DUNGUN', 'ANISAH BINTI ABDULLAH', '020202020202', 'TERENGGANU', 'Pantai nEW', 'Chendering', '23000', 'Kuala Terengganu', 'Terengganu', 'Kuala Terengganu', 'diri_sendiri', '2025-06-27', '2025-06-28', '2025-06-26', '2025-06-28', 'KLIA', 'SARAWAK', 1, '2025-06-25', NULL, 'Belum Pasti', '2025-06-25 07:37:01', 'Belum Pasti', NULL, '2025-06-25 07:37:01', 'Belum Disemak', 'Pemohon', 'Menunggu pengesahan PBR CSM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-25 07:35:31', '2025-06-25 07:37:01', 1, 'Hantar', 0, NULL, NULL),
(13, '333333333333', 'K09', 'yunonajmi@gmail.com', 'NO 891 JLN SUBANG INDAH U69/420', 'NO 891 JLN SUBANG INDAH U69/420', '40170', 'SHAH ALEY', 'SELANGOR', 'NO 891 JLN SUBANG INDAH U69/420', 'NO 891 JLN SUBANG INDAH U69/420', '47301', 'PETALING JAYA', 'SELANGOR', '2024-01-01', NULL, 'X', 'X', '111111111111', 'ALAMAT 1', 'ALAMAT 2', '12345', 'BESUT', 'Land Of Fire', 'TERENGGANU', 'WAN KHAIRUZZAMAN BIN WAN HARUN', '010101100103', 'TERENGGANO', '891 KAMPUNG BAROI', 'JALAN REYZEK', '23001', 'DUNPISTOL', 'GANU', 'DUNGUN', 'ANISAH BINTI ABDULLAH', '020202020202', 'TERENGGANU', 'Pantai nEW', 'Chendering', '23000', 'Kuala Terengganu', 'Terengganu', 'Kuala Terengganu', 'keluarga', '2025-06-27', '2025-06-30', '2025-06-27', '2025-07-01', 'KLIA', 'SARAWAK', 1, '2025-06-25', NULL, 'Belum Pasti', '2025-06-25 08:50:03', 'Belum Pasti', NULL, '2025-06-25 08:50:03', 'Batal', 'Pemohon', 'Permohonan dibatalkan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-25 07:39:11', '2025-06-25 08:50:03', 1, 'Hantar', 0, 'haha takleh balik', '2025-06-25'),
(14, '920307085064', 'PENGUASA KASTAM WK9', 'yunonajmi@gmail.com', 'NO 7 XXXX', 'KELANA JAYA', '47301', 'PETALING JAYA', 'SELANGOR DARUL EHSAN', 'JALAN SS6/3', 'KELANA JAYA', '47301', 'PETALING JAYA', 'SELANGOR DARUL EHSAN', '2018-02-07', NULL, 'SYAH', 'SAMAT', '123456789111', 'JALAN SS6/3', 'KELANA JAYA', '47301', 'PETALING JAYA', 'SELANGOR DARUL EHSAN', 'SARAWAK', 'HANIFAH', '123456797899', 'SARAWAK', 'XXX', 'XXX', '47301', 'XX', 'XX', 'XX', 'XX', '123456798888', 'XX', 'XX', 'XX', '47301', 'XX', 'XX', 'XX', 'diri_sendiri', '2025-06-26', '2025-07-12', '2025-06-26', '2025-07-12', 'KLIA', 'KUCHING', 1, '2025-06-25', '100', 'Tiada', '2025-06-25 08:56:05', 'Belum Pasti', NULL, '2025-06-25 08:56:05', 'Selesai', 'Kewangan', 'Permohonan diluluskan', '2025-06-25', NULL, 0, 3, 0, '2025-06-25', NULL, '2025-06-25', 1, '2025-06-25', 3, '2025-06-25', NULL, 4, NULL, '2025-06-25', 5, NULL, '2025-06-25', 6, NULL, '2025-06-25', 4, '2025-06-25', 7, '2025-06-25', '10', 8, '2025-06-25', NULL, 9, '2025-06-25', '2025-06-25 08:01:11', '2025-06-25 08:56:05', 1, 'Hantar', 1, NULL, NULL),
(15, '920307085064', 'PENGUASA KASTAM WK9', 'yunonajmi@gmail.com', 'NO 7 XXXX', 'KELANA JAYA', '47301', 'PETALING JAYA', 'SELANGOR DARUL EHSAN', 'JALAN SS6/3', 'KELANA JAYA', '47301', 'PETALING JAYA', 'SELANGOR DARUL EHSAN', '2023-11-02', '2024-01-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'HANIFAH', '123456797899', 'SARAWAK', 'XXX', 'XXX', '47301', 'XX', 'XX', 'XX', 'XX', '123456798888', 'XX', 'XX', 'XX', '47301', 'XX', 'XX', 'XX', 'diri_sendiri', '2025-06-28', '2025-07-05', '2025-06-28', '2025-07-05', 'klia', 'KUCHING', 1, '2025-06-25', NULL, 'Belum Pasti', '2025-06-25 09:06:26', 'Belum Pasti', NULL, '2025-06-25 09:06:26', 'Belum Disemak', 'Pemohon', 'Menunggu pengesahan PBR CSM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-25 08:59:06', '2025-06-25 09:06:26', 1, 'Hantar', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wilayah_asal_pengikut`
--

CREATE TABLE `wilayah_asal_pengikut` (
  `id` int(11) NOT NULL,
  `wilayah_asal_id` int(11) NOT NULL,
  `nama_first_pengikut` varchar(50) NOT NULL,
  `nama_last_pengikut` varchar(50) NOT NULL,
  `tarikh_lahir_pengikut` date NOT NULL,
  `kp_pengikut` varchar(20) NOT NULL,
  `tarikh_penerbangan_pergi_pengikut` date DEFAULT NULL,
  `tarikh_penerbangan_balik_pengikut` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wilayah_asal_pengikut`
--

INSERT INTO `wilayah_asal_pengikut` (`id`, `wilayah_asal_id`, `nama_first_pengikut`, `nama_last_pengikut`, `tarikh_lahir_pengikut`, `kp_pengikut`, `tarikh_penerbangan_pergi_pengikut`, `tarikh_penerbangan_balik_pengikut`, `created_at`, `updated_at`) VALUES
(1, 10, 'NAIRAH', 'BYE', '2025-06-20', '300201100752', '2025-06-27', '2025-06-28', '2025-06-25 07:28:56', '2025-06-25 07:28:56'),
(2, 10, 'UMAMI', 'NAJMI', '2025-06-11', '4566888', '2025-06-27', '2025-06-29', '2025-06-25 07:28:56', '2025-06-25 07:28:56'),
(3, 12, 'NAIRAH', 'BYE', '2025-06-20', '300201100752', '2025-06-27', '2025-06-28', '2025-06-25 07:36:21', '2025-06-25 07:36:21'),
(5, 14, 'ANAK 1', 'XX', '2024-03-25', '123456789788', '2025-06-26', '2025-07-12', '2025-06-25 08:17:21', '2025-06-25 08:17:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wilayah_asal_id` (`wilayah_asal_id`);

--
-- Indexes for table `document_logs`
--
ALTER TABLE `document_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wilayah_asal_id` (`wilayah_asal_id`);

--
-- Indexes for table `organisasi`
--
ALTER TABLE `organisasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `superadmin`
--
ALTER TABLE `superadmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_lookup` (`user_type`,`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `kp` (`kp`);

--
-- Indexes for table `wilayah_asal`
--
ALTER TABLE `wilayah_asal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wilayah_asal_ibfk_1` (`user_kp`);

--
-- Indexes for table `wilayah_asal_pengikut`
--
ALTER TABLE `wilayah_asal_pengikut`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wilayah_asal_id` (`wilayah_asal_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `document_logs`
--
ALTER TABLE `document_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `organisasi`
--
ALTER TABLE `organisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `wilayah_asal`
--
ALTER TABLE `wilayah_asal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `wilayah_asal_pengikut`
--
ALTER TABLE `wilayah_asal_pengikut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`wilayah_asal_id`) REFERENCES `wilayah_asal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wilayah_asal`
--
ALTER TABLE `wilayah_asal`
  ADD CONSTRAINT `wilayah_asal_ibfk_1` FOREIGN KEY (`user_kp`) REFERENCES `user` (`kp`);

--
-- Constraints for table `wilayah_asal_pengikut`
--
ALTER TABLE `wilayah_asal_pengikut`
  ADD CONSTRAINT `wilayah_asal_pengikut_ibfk_1` FOREIGN KEY (`wilayah_asal_id`) REFERENCES `wilayah_asal` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
