-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2025 at 08:48 AM
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `Name`, `ICNo`, `Email`, `PhoneNo`, `Password`, `Role`, `reset_token`, `token_expiry`) VALUES
(1, 'Haniszainee1105', '020511140514', 'haniszainee1105@gmail.com', '0126676565', '$2y$10$kofqWLOVJ2cb8SE2E0HPVepjUi5psHsMvrM5W.nV/rmd2ZbUsVrOK', 'PBR CSM', NULL, NULL),
(2, 'test', '02051111111111', 'pbr_csm@gmail.com', '01222222222', '$2y$10$uG7ZFXf0s9SsVKFPDEe/POIHbCtb39y4pq4j6Y/HzIyY7.eiucat6', 'PBR CSM', NULL, NULL),
(3, 'Syafiq', '020300330023', 'syafiq2703@gmail.com', '012333344', '$2y$10$eg5E/Hi3Pyx5Wrq82/Zp6OmSzyzwoWgao3f0lqFe.ikU2MdbBFoBK', 'Pegawai Sulit CSM', NULL, NULL),
(4, 'Adib Hayqal', '00202404002043', 'adibhayqal90@gmail.com', '01133737605', '$2y$10$5nYeMmN2m5CTaG81qNlNyet3QBJACOXnD6oQtHgAwIrEmGB9EfKf6', 'Pengesah CSM', NULL, NULL),
(5, 'PenyemakHQ', '00000000000000', 'penyemakHQ@gmail.com', '00000000000', '$2y$10$HNkT43l13Nll/muqJouFzeT72NZPPq4R.yJhRzHmVT/a5Uwb76ucK', 'Penyemak HQ', NULL, NULL),
(6, 'pengesahHQ', '11111111111111', 'pengesahHQ@gmail.com', '11111111111', '$2y$10$r66KyPvHf8lE/tUIhmexrumIrBEQvdPyptnQHJ9Out3zI5GDjYBWe', 'Pengesah HQ', NULL, NULL),
(7, 'Pelulus HQ', '33333333333333', 'pelulusHQ@gmail.com', '33333333333', '$2y$10$c0MjcTteEq4Wu0t7a2L/NeDLXI73vkuGfIAzpis.3O1LJVSYy5Vgu', 'Pelulus HQ', NULL, NULL),
(8, 'penyemakBakiKewangan', '44444444444444', 'penyemakBakiKewangan@gmail.com', '44444444444', '$2y$10$IKd0dfdDTLey42WC4l6xG.QnxOw0ZoEkoI424k1l41G39.R/xJYja', 'Penyemak Baki Kewangan', NULL, NULL),
(9, 'PengesahKewangan', '55555555555555', 'pengesahKewangan@gmail.com', '55555555555', '$2y$10$Ln.xd7af3i4BFLg2.uXf2./OwF3fj4u0zO/XV5by/lk7GQgjxa0SW', 'Pengesah Kewangan', NULL, NULL),
(10, 'penyediaKemudahanKewangan', '88888888888888', 'penyediaKemudahanKewangan@gmail.com', '88888888888', '$2y$10$TSlYl7QjsWeMGU8OjJNDZu8vST7Z1PjIor8kIvaGWKGrcEkMnSuKi', 'Penyedia Kemudahan Kewangan', NULL, NULL),
(11, 'Amira', '020511140511', 'amira@gmail.com', '0123310666', '$2y$10$362Emyen3lcV9NrOUgt5D.s6CUssiPGVYCAjDbnuvJdJeo2Tq8RZm', 'Pegawai Sulit CSM', NULL, NULL),
(12, 'Alisa', '020511140516', 'alisa@gmail.com', '0123885785', '$2y$10$6CPL/5sEVfIVowfF2YDZyOuXMd6m2r05UdupM1bahP0Dzg28EyI0C', 'Penyemak HQ', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `adminrole`
--

CREATE TABLE `adminrole` (
  `ID` int(11) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `wilayah_asal_id`, `file_name`, `file_path`, `file_type`, `file_size`, `description`, `file_origin`, `file_origin_id`, `upload_date`) VALUES
(7, 2, 'MESYUARAT PENGARAH KASTAM WPKL (2).pdf', 'uploads/permohonan/2/68466594b8884_2_Dokumen_Pegawai.pdf', 'application/pdf', 517376, 'Dokumen Pegawai', 'pemohon', '010201100753', '2025-06-09 04:39:48'),
(8, 2, 'Resume_Najmi_Khairuzzaman.pdf', 'uploads/permohonan/2/68466594b9d59_2_Lampiran_II.pdf', 'application/pdf', 501109, 'Lampiran II', 'pemohon', '010201100753', '2025-06-09 04:39:48'),
(9, 2, '35x50.pdf', 'uploads/permohonan/2/68466594baffe_2_Dokumen_Pasangan.pdf', 'application/pdf', 15242, 'Dokumen Pasangan', 'pemohon', '010201100753', '2025-06-09 04:39:48'),
(10, 2, 'AMIR ZAKARIAH_LAB 5.pdf', 'uploads/permohonan/2/68466594bcfdf_2_Sijil_Perkahwinan.pdf', 'application/pdf', 68477, 'Sijil Perkahwinan', 'pemohon', '010201100753', '2025-06-09 04:39:48'),
(11, 2, 'BMM Dependents Form .pdf', 'uploads/permohonan/2/68466594bda28_2_Dokumen_Pengikut.pdf', 'application/pdf', 117039, 'Dokumen Pengikut', 'pemohon', '010201100753', '2025-06-09 04:39:48'),
(12, 2, 'Cover Letter.pdf', 'uploads/permohonan/2/68466594be54b_2_Dokumen_Pengikut.pdf', 'application/pdf', 77436, 'Dokumen Pengikut', 'pemohon', '010201100753', '2025-06-09 04:39:48'),
(13, 2, 'F6(b) Form-new Jan2025-CSP650.pdf', 'uploads/permohonan/2/68466594bef1c_2_Dokumen_Sokongan_1.pdf', 'application/pdf', 135791, 'Dokumen Sokongan 1', 'pemohon', '010201100753', '2025-06-09 04:39:48'),
(14, 2, 'F10 – FINAL PROJECT PRESENTATION FORM.pdf', 'uploads/permohonan/2/68466594bf8fc_2_Dokumen_Sokongan_2.pdf', 'application/pdf', 381253, 'Dokumen Sokongan 2', 'pemohon', '010201100753', '2025-06-09 04:39:48'),
(15, 2, 'F11 – PROJECT REPORT EVALUATION FORM.pdf', 'uploads/permohonan/2/68466594c031f_2_Dokumen_Sokongan_3.pdf', 'application/pdf', 492100, 'E-tiket', 'kewangan', '010201100753', '2025-06-09 04:39:48'),
(16, 6, 'ilovepdf_merged.pdf', 'uploads/permohonan/6/6848d4eae4a9e_6_Dokumen_Pegawai.pdf', 'application/pdf', 4729832, 'Dokumen Pegawai', 'pemohon', '010201100753', '2025-06-11 00:59:22'),
(17, 6, '202505_KEBENARAN MASUK LEWAT_ASEAN.pdf', 'uploads/permohonan/6/6848d4eae71c1_6_Lampiran_II.pdf', 'application/pdf', 148765, 'Lampiran II', 'pemohon', '010201100753', '2025-06-11 00:59:22'),
(18, 6, 'LAPORAN_KEHADIRAN_KAKITANGAN_INDIVIDU_5.pdf', 'uploads/permohonan/6/6848d4eae8a57_6_Dokumen_Pasangan.pdf', 'application/pdf', 3161164, 'Dokumen Pasangan', 'pemohon', '010201100753', '2025-06-11 00:59:22'),
(19, 6, '162478-386543_20250531.pdf', 'uploads/permohonan/6/6848d4eaea8f2_6_Sijil_Perkahwinan.pdf', 'application/pdf', 165053, 'Sijil Perkahwinan', 'pemohon', '010201100753', '2025-06-11 00:59:22'),
(20, 6, 'Resume_Najmi_Khairuzzaman.pdf', 'uploads/permohonan/6/6848d4eaeb6af_6_Dokumen_Pengikut.pdf', 'application/pdf', 501109, 'Dokumen Pengikut', 'pemohon', '010201100753', '2025-06-11 00:59:22'),
(21, 6, 'Najmi Khairuzzaman Resume Minimal.pdf', 'uploads/permohonan/6/6848d4eaec6f2_6_Dokumen_Sokongan_1.pdf', 'application/pdf', 501098, 'Dokumen Sokongan 1', 'pemohon', '010201100753', '2025-06-11 00:59:22'),
(22, 6, 'Najmi Khairuzzaman Resume Minimal.pdf', 'uploads/permohonan/6/6848d4eaed54f_6_Dokumen_Sokongan_2.pdf', 'application/pdf', 501098, 'Dokumen Sokongan 2', 'pemohon', '010201100753', '2025-06-11 00:59:22');

-- --------------------------------------------------------

--
-- Table structure for table `organisasi`
--

CREATE TABLE `organisasi` (
  `id` int(11) NOT NULL,
  `nama_cawangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `event_type`, `user_type`, `user_id`, `action`, `description`, `affected_table`, `affected_record_id`, `old_value`, `new_value`, `ip_address`, `user_agent`, `created_at`) VALUES
(11, 'login', 'admin', '020511140514', 'User Login', 'Successful login attempt by PBR CSM', NULL, NULL, NULL, NULL, '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-06-10 20:34:08'),
(12, 'document_upload', 'admin', '02051111111111', 'Document Upload', 'Uploaded travel document for review', 'documents', 1, NULL, NULL, '192.168.1.101', 'Chrome/91.0.4472.124', '2025-06-10 20:34:08'),
(13, 'status_change', 'admin', '020300330023', 'Status Update', 'Changed application status to Lulus', 'wilayah_asal', 5, NULL, NULL, '192.168.1.102', 'Firefox/89.0', '2025-06-10 20:34:08'),
(14, 'data_update', 'user', '010201100753', 'Profile Update', 'Updated personal information in KEWANGAN', 'user', 3, NULL, NULL, '192.168.1.103', 'Safari/14.1.1', '2025-06-10 20:34:08'),
(15, 'document_download', 'admin', '020511140516', 'Document Download', 'Downloaded application form for HQ review', 'documents', 2, NULL, NULL, '192.168.1.104', 'Edge/91.0.864.59', '2025-06-10 20:34:08');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama_first`, `nama_last`, `email`, `phone`, `kp`, `bahagian`, `password`, `created_at`, `updated_at`, `reset_token`, `token_expiry`) VALUES
(1, 'NAJMI', 'KHAIRUZZAMAN', 'yunonajmi@gmail.com', '0179813005', '010201100753', 'KEWANGAN', '$2y$10$x9Jea.LwWKbv633LkpA8Kulp3U8ErdPkC1TQjqza5Igc1kbTnMNZm', '2025-06-03 15:30:04', '2025-06-09 03:52:42', NULL, NULL);

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
  `status_permohonan` enum('Belum Disemak','Selesai','Dikuiri','Tolak','Lulus') DEFAULT 'Belum Disemak',
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
  `wilayah_asal_matang` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wilayah_asal`
--

INSERT INTO `wilayah_asal` (`id`, `user_kp`, `jawatan_gred`, `email_penyelia`, `alamat_menetap_1`, `alamat_menetap_2`, `poskod_menetap`, `bandar_menetap`, `negeri_menetap`, `alamat_berkhidmat_1`, `alamat_berkhidmat_2`, `poskod_berkhidmat`, `bandar_berkhidmat`, `negeri_berkhidmat`, `tarikh_lapor_diri`, `tarikh_terakhir_kemudahan`, `nama_first_pasangan`, `nama_last_pasangan`, `no_kp_pasangan`, `alamat_berkhidmat_1_pasangan`, `alamat_berkhidmat_2_pasangan`, `poskod_berkhidmat_pasangan`, `bandar_berkhidmat_pasangan`, `negeri_berkhidmat_pasangan`, `wilayah_menetap_pasangan`, `nama_bapa`, `no_kp_bapa`, `wilayah_menetap_bapa`, `alamat_menetap_1_bapa`, `alamat_menetap_2_bapa`, `poskod_menetap_bapa`, `bandar_menetap_bapa`, `negeri_menetap_bapa`, `ibu_negeri_bandar_dituju_bapa`, `nama_ibu`, `no_kp_ibu`, `wilayah_menetap_ibu`, `alamat_menetap_1_ibu`, `alamat_menetap_2_ibu`, `poskod_menetap_ibu`, `bandar_menetap_ibu`, `negeri_menetap_ibu`, `ibu_negeri_bandar_dituju_ibu`, `jenis_permohonan`, `tarikh_penerbangan_pergi`, `tarikh_penerbangan_balik`, `tarikh_penerbangan_pergi_pasangan`, `tarikh_penerbangan_balik_pasangan`, `start_point`, `end_point`, `pengesahan_user`, `tarikh_pengesahan_user`, `markah_prestasi_user`, `hukuman_tatatertib_user`, `tarikh_csm_permohonan`, `keputusan_permohonan_ketua_jabatan`, `kp_ketua_jabatan`, `tarikh_keputusan_ketua_jabatan`, `status_permohonan`, `kedudukan_permohonan`, `status`, `tarikh_keputusan_csm1`, `ulasan_pbr_csm1`, `pbr_csm1_id`, `pengesah_csm1_id`, `pegSulit_csm_id`, `tarikh_keputusan_pengesah_csm1`, `ulasan_pengesah_csm1`, `tarikh_keputusan_csm2`, `pbr_csm2_id`, `tarikh_keputusan_pegsulit_csm`, `pengesah_csm2_id`, `tarikh_keputusan_pengesah_csm2`, `ulasan_pengesah_csm2`, `penyemak_HQ1_id`, `ulasan_penyemak_HQ`, `tarikh_keputusan_penyemak_HQ1`, `pengesah_HQ_id`, `ulasan_pengesah_HQ`, `tarikh_keputusan_pengesah_HQ`, `pelulus_HQ_id`, `ulasan_pelulus_HQ`, `tarikh_keputusan_pelulus_HQ`, `penyemak_HQ2_id`, `tarikh_keputusan_penyemak_HQ2`, `penyemakBaki_kewangan_id`, `tarikh_keputusan_penyemakBaki_kewangan`, `ulasan_penyemakBaki_kewangan`, `pengesah_kewangan_id`, `tarikh_keputusan_pengesah_kewangan`, `ulasan_pengesah_kewangan`, `penyediaKemudahan_kewangan_id`, `tarikh_keputusan_penyediaKemudahan_kewangan`, `created_at`, `updated_at`, `wilayah_asal_form_fill`, `wilayah_asal_from_stage`, `wilayah_asal_matang`) VALUES
(2, '010201100753', 'WK02', 'boss@customs.gov.my', 'NO 64 JALAN PULAU INDAH U10/59', 'TAMAN SUBANG IMPIAN', '40170', 'SHAH ALAM', 'SELANGOR', 'Kompleks Kastam WPKL, 22', 'Jalan SS 6/3 Kelana Jaya', '47301', 'Petaling Jaya', 'Selangor', '2024-01-01', '2024-06-02', 'Sayyidati', 'Hawa', '111111111111', 'Dunia Tipu Tipu', 'Akhirat yang benar', '99999', 'Konoha', 'Land Of Fire', 'TERENGGANU', 'WAN KHAIRUZZAMAN WAN HARUN', '010101100101', 'TERENGGANU', '891 KAMPUNG BARU', '-', '23000', 'DUNGUN', 'TERENGGANU', 'DUNGUN', 'ANISAH BINTI ABDULLAH', '020202020202', 'TERENGGANU', 'Pantai Baru', 'Chendering', '23000', 'Kuala Terengganu', 'Terengganu', 'Kuala Terengganu', 'diri_sendiri', '2025-06-13', '2025-06-14', '2025-06-13', '2025-06-15', 'SUBANG', 'SARAWAK', 1, '2024-06-10', NULL, 'Belum Pasti', '2025-06-11 00:12:11', 'Belum Pasti', NULL, '2025-06-11 00:12:11', 'Selesai', 'Kewangan', NULL, NULL, 'IC tidak Lengkap', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-09 04:33:51', '2025-06-11 00:12:11', 1, 'Hantar', 1),
(6, '010201100753', 'WK01', 'boss@customs.gov.my', 'NO 64 JALAN PULAU INDAH U10/59', 'TAMAN SUBANG IMPIAN', '40170', 'SHAH ALAM', 'SELANGOR', 'Kompleks Kastam WPKL, 22', 'Jalan SS 6/3 Kelana Jaya', '47301', 'Petaling Jaya', 'Selangor', '2024-01-01', '2025-06-12', 'WAT', 'daheck', '010611110646', 'NO 891 JLN SUBANG INDAH U69/420', 'NO 891 JLN SUBANG INDAH U69/420', '99999', 'Konohey', 'Land Of Fire', 'TERENGGASUI', 'WAN KHAIRUZZAMAN BIN WAN HARUN', '010101100103', 'TERENGGANO', '891 KAMPUNG BAROI', 'JALAN REYZEK', '23001', 'DUNPISTOL', 'GANU', '', 'ANISAH BINTI ABDULLAH', '020202020202', 'TERENGGANU', 'Pantai nEW', 'Chendering', '23000', 'Kuala Terengganu', 'Terengganu', 'Kuala Terengganu', 'diri_sendiri', '2025-06-12', '2025-06-14', '2025-06-17', '2025-06-20', 'KLIA', 'KOTA KINABALU', 1, '2025-06-11', NULL, 'Belum Pasti', '2025-06-11 00:59:36', 'Belum Pasti', NULL, '2025-06-11 00:59:36', 'Belum Disemak', 'Pemohon', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-11 00:41:48', '2025-06-11 00:59:36', 1, 'Hantar', 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wilayah_asal_pengikut`
--

INSERT INTO `wilayah_asal_pengikut` (`id`, `wilayah_asal_id`, `nama_first_pengikut`, `nama_last_pengikut`, `tarikh_lahir_pengikut`, `kp_pengikut`, `tarikh_penerbangan_pergi_pengikut`, `tarikh_penerbangan_balik_pengikut`, `created_at`, `updated_at`) VALUES
(5, 2, 'Test', 'Cubaan', '2025-06-11', '2345', '2025-06-13', '2025-06-14', '2025-06-09 18:58:04', '2025-06-09 19:00:44'),
(6, 2, 'UMAMI', 'NAJMI', '2025-06-08', '310711100890', '0000-00-00', '0000-00-00', '2025-06-09 19:00:44', '2025-06-09 19:00:44'),
(7, 6, 'HELLO', 'BYE', '2025-06-19', '1231', '2025-06-12', '2025-06-14', '2025-06-11 00:49:19', '2025-06-11 00:49:19'),
(8, 6, 'WAIT', 'WAD', '2031-07-11', '4566888', '2025-06-20', '2025-06-21', '2025-06-11 00:49:19', '2025-06-11 00:49:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wilayah_asal_id` (`wilayah_asal_id`),
  ADD KEY `file_origin_id` (`file_origin_id`);

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
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wilayah_asal`
--
ALTER TABLE `wilayah_asal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wilayah_asal_pengikut`
--
ALTER TABLE `wilayah_asal_pengikut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`wilayah_asal_id`) REFERENCES `wilayah_asal` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`file_origin_id`) REFERENCES `user` (`kp`) ON DELETE CASCADE;

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
