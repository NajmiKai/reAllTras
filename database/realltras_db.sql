-- Create user table
CREATE TABLE IF NOT EXISTS user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_first VARCHAR(50) NOT NULL,
    nama_last VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    kp VARCHAR(20) NOT NULL UNIQUE,
    bahagian VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create application table example
CREATE TABLE IF NOT EXISTS wilayah_asal (

    id INT PRIMARY KEY AUTO_INCREMENT,
    user_kp VARCHAR(20) NOT NULL UNIQUE,
    jawatan_gred VARCHAR(100) NOT NULL,

    alamat_menetap_1 VARCHAR(100) NOT NULL,
    alamat_menetap_2 VARCHAR(100),
    poskod_menetap VARCHAR(10) NOT NULL,
    bandar_menetap VARCHAR(50) NOT NULL,
    negeri_menetap VARCHAR(50) NOT NULL,

    alamat_berkhidmat_1 VARCHAR(100) NOT NULL,
    alamat_berkhidmat_2 VARCHAR(100),
    poskod_berkhidmat VARCHAR(10) NOT NULL,
    bandar_berkhidmat VARCHAR(50) NOT NULL,
    negeri_berkhidmat VARCHAR(50) NOT NULL,

    tarikh_lapor_diri DATE NOT NULL,
    tarikh_terakhir_kemudahan DATE,
    
    nama_first_pasangan VARCHAR(50),
    nama_last_pasangan VARCHAR(50),
    no_kp_pasangan VARCHAR(20),
    alamat_berkhidmat_1_pasangan VARCHAR(100),
    alamat_berkhidmat_2_pasangan VARCHAR(100),
    poskod_berkhidmat_pasangan VARCHAR(10),
    bandar_berkhidmat_pasangan VARCHAR(50),
    negeri_berkhidmat_pasangan VARCHAR(50),
    wilayah_menetap_pasangan VARCHAR(50),

    nama_bapa VARCHAR(50),
    no_kp_bapa VARCHAR(20),
    wilayah_menetap_bapa VARCHAR(50),
    alamat_menetap_1_bapa VARCHAR(100),
    alamat_menetap_2_bapa VARCHAR(100),
    poskod_menetap_bapa VARCHAR(10),
    bandar_menetap_bapa VARCHAR(50),
    negeri_menetap_bapa VARCHAR(50),
    ibu_negeri_bandar_dituju_bapa VARCHAR(50),

    nama_ibu VARCHAR(50),
    no_kp_ibu VARCHAR(20),
    wilayah_menetap_ibu VARCHAR(50),
    alamat_menetap_1_ibu VARCHAR(100),
    alamat_menetap_2_ibu VARCHAR(100),
    poskod_menetap_ibu VARCHAR(10),
    bandar_menetap_ibu VARCHAR(50),
    negeri_menetap_ibu VARCHAR(50),
    ibu_negeri_bandar_dituju_ibu VARCHAR(50),

    jenis_permohonan ENUM('diri_sendiri', 'keluarga') NOT NULL,

    tarikh_penerbangan_pergi DATE NOT NULL,
    tarikh_penerbangan_balik DATE NOT NULL,


    start_point VARCHAR(100) NOT NULL,
    end_point VARCHAR(100) NOT NULL,

    pengesahan_user boolean DEFAULT false,
    tarikh_pengesahan_user DATE,

    markah_prestasi_user VARCHAR(10000),
    hukuman_tatatertib_user ENUM('Ada', 'Tiada', 'Belum Pasti') DEFAULT 'Belum Pasti',
    tarikh_csm_permohonan TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,


    keputusan_permohonan_ketua_jabatan ENUM('Diterima', 'Ditolak', 'Belum Pasti') DEFAULT 'Belum Pasti',
    kp_ketua_jabatan VARCHAR(50),
    tarikh_keputusan_ketua_jabatan TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    ulasan_csm1 TEXT,
    ulasan_csm2 TEXT,
    ulasan_hq TEXT,
    ulasan_kewangan TEXT,

    status_permohonan ENUM('Belum Disemak','Selesai','Dikuiri', 'Tolak', 'Lulus') DEFAULT 'Belum Disemak',
    kedudukan_permohonan ENUM('Pemohon','CSM', 'HQ', 'CSM2', 'Kewangan') DEFAULT 'Pemohon',


    FOREIGN KEY (user_kp) REFERENCES user(kp),
    FOREIGN KEY (kp_ketua_jabatan) REFERENCES user(kp),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create table for children's information
CREATE TABLE IF NOT EXISTS wilayah_asal_pengikut (
    id INT PRIMARY KEY AUTO_INCREMENT,

    wilayah_asal_id INT NOT NULL,
    nama_first_pengikut VARCHAR(50) NOT NULL,
    nama_last_pengikut VARCHAR(50) NOT NULL,
    tarikh_lahir_pengikut DATE NOT NULL,
    kp_pengikut VARCHAR(20) NOT NULL UNIQUE,

    tarikh_penerbangan_pergi_pengikut DATE,
    tarikh_penerbangan_balik_pengikut DATE,

    FOREIGN KEY (wilayah_asal_id) REFERENCES wilayah_asal(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create table for document uploads
CREATE TABLE IF NOT EXISTS documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    wilayah_asal_id INT,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_size INT NOT NULL,
    file_class_origin ENUM('pemohon', 'csm1', 'csm2', 'hq', 'kewangan') DEFAULT 'pemohon',
    file_uploader_origin VARCHAR(20) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description TEXT,
    FOREIGN KEY (wilayah_asal_id) REFERENCES wilayah_asal(id) ON DELETE CASCADE,
    FOREIGN KEY (file_uploader_origin) REFERENCES user(kp) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS wa_csm1 (
    id INT PRIMARY KEY AUTO_INCREMENT,
    wilayah_asal_id INT NOT NULL UNIQUE,
    documents_id INT NOT NULL UNIQUE,
    id_csm_pengawai_sulit INT UNIQUE,
    id_csm_pbr INT UNIQUE,
    id_csm_pengesah INT UNIQUE,

    FOREIGN KEY (id_csm_pengawai_sulit) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (id_csm_pbr) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (id_csm_pengesah) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (wilayah_asal_id) REFERENCES wilayah_asal(id) ON DELETE CASCADE,
    FOREIGN KEY (documents_id) REFERENCES documents(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS wa_csm2 (
    id INT PRIMARY KEY AUTO_INCREMENT,
    wilayah_asal_id INT NOT NULL UNIQUE,
    documents_id INT NOT NULL UNIQUE,
    id_csm_pengawai_sulit INT UNIQUE,
    id_csm_pbr INT UNIQUE,
    id_csm_pengesah INT UNIQUE,

    FOREIGN KEY (id_csm_pengawai_sulit) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (id_csm_pbr) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (id_csm_pengesah) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (wilayah_asal_id) REFERENCES wilayah_asal(id) ON DELETE CASCADE,
    FOREIGN KEY (documents_id) REFERENCES documents(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS wa_csm2 (
    id INT PRIMARY KEY AUTO_INCREMENT,
    wilayah_asal_id INT NOT NULL UNIQUE,
    documents_id INT NOT NULL UNIQUE,
    id_csm_pengawai_sulit INT UNIQUE,
    id_csm_pbr INT UNIQUE,
    id_csm_pengesah INT UNIQUE,

    FOREIGN KEY (id_csm_pengawai_sulit) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (id_csm_pbr) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (id_csm_pengesah) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (wilayah_asal_id) REFERENCES wilayah_asal(id) ON DELETE CASCADE,
    FOREIGN KEY (documents_id) REFERENCES documents(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


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


CREATE TABLE `wilayah_asal` (
  `id` int(11) NOT NULL,
  `user_kp` varchar(20) NOT NULL,
  `jawatan_gred` varchar(100) NOT NULL,
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
  `tarikh_csm_permohonan` date DEFAULT NULL,
  `keputusan_permohonan_ketua_jabatan` enum('Diterima','Ditolak','Belum Pasti') DEFAULT 'Belum Pasti',
  `kp_ketua_jabatan` varchar(50) DEFAULT NULL,
  `tarikh_keputusan_ketua_jabatan` date DEFAULT NULL,
  `ulasan_csm1` text DEFAULT NULL,
  `ulasan_csm2` text DEFAULT NULL,
  `ulasan_hq` text DEFAULT NULL,
  `ulasan_kewangan` text DEFAULT NULL,
  `status_permohonan` enum('Belum Disemak','Selesai','Dikuiri','Tolak','Lulus') DEFAULT 'Belum Disemak',
  `kedudukan_permohonan` enum('Pemohon','CSM','HQ','CSM2','Kewangan') DEFAULT 'Pemohon',
  `status` varchar(255) DEFAULT NULL,
  `tarikh_keputusan_csm1` date DEFAULT NULL,
  `tarikh_keputusan_csm2` date DEFAULT NULL,
  `ulasan_pbr_csm1` text DEFAULT NULL,
  `pbr_csm1_id` int(11) DEFAULT NULL,
  `pbr_csm2_id` int(11) DEFAULT NULL,
  `tarikh_keputusan_pegsulit_csm` date DEFAULT NULL,
  `pegSulit_csm_id` int(11) DEFAULT NULL,
  `pengesah_csm1_id` int(11) DEFAULT NULL,
  `pengesah_csm2_id` int(11) DEFAULT NULL,
  `tarikh_keputusan_pengesah_csm1` date DEFAULT NULL,
  `tarikh_keputusan_pengesah_csm2` date DEFAULT NULL,
  `ulasan_pengesah_csm1` text DEFAULT NULL,
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
  `tarikh_keputusan_penyemak_HQ2` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--