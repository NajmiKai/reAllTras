-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 11:49 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `document_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wilayah_asal_id` (`wilayah_asal_id`);


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wilayah_asal`
--


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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
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
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organisasi`
--
ALTER TABLE `organisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wilayah_asal`
--
ALTER TABLE `wilayah_asal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wilayah_asal_pengikut`
--
ALTER TABLE `wilayah_asal_pengikut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
