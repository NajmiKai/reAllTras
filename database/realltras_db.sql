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
    role ENUM('admin', 'user', 'pengesah', 'pelulus', 'pembantu_tadbir', 'pembantu_tadbir_rahsia', 'penyedia_kemudahan', 'penyemak_baki', 'ketua_jabatan_cawangan') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create application table example
CREATE TABLE IF NOT EXISTS wilayah_asal (

    id INT PRIMARY KEY AUTO_INCREMENT,
    user_kp VARCHAR(20) NOT NULL UNIQUE,
    nama_first VARCHAR(50) NOT NULL,
    nama_last VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    bahagian VARCHAR(50) NOT NULL,
    jawatan VARCHAR(50) NOT NULL,

    alamat_menetap_1 VARCHAR(100) NOT NULL,
    alamat_menetap_2 VARCHAR(100) NOT NULL,
    poskod_menetap VARCHAR(10) NOT NULL,
    bandar_menetap VARCHAR(50) NOT NULL,
    negeri_menetap VARCHAR(50) NOT NULL,

    alamat_berkhidmat_1 VARCHAR(100) NOT NULL,
    alamat_berkhidmat_2 VARCHAR(100) NOT NULL,
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

    tarikh_penerbangan_pergi_pasangan DATE,
    tarikh_penerbangan_balik_pasangan DATE,

    pengesahan_user boolean DEFAULT false,
    tarikh_pengesahan_user DATE,

    markah_prestasi_user VARCHAR(10000),
    hukuman_tatatertib_user ENUM('Ada', 'Tiada', 'Belum Pasti') DEFAULT 'Belum Pasti',

    keputusan_permohonan_ketua_jabatan ENUM('Diterima', 'Ditolak', 'Belum Pasti') DEFAULT 'Belum Pasti',
    kp_ketua_jabatan VARCHAR(50),
    tarikh_keputusan_ketua_jabatan DATE,

    FOREIGN KEY (user_kp) REFERENCES user(kp),
    FOREIGN KEY (kp_ketua_jabatan) REFERENCES user(kp),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create table for children's information
CREATE TABLE IF NOT EXISTS wilayah_asal_pengikut (
    id INT PRIMARY KEY AUTO_INCREMENT,

    wilayah_asal_id INT NOT NULL UNIQUE,
    nama_first_pengikut VARCHAR(50) NOT NULL,
    nama_last_pengikut VARCHAR(50) NOT NULL,
    tarikh_lahir_pengikut DATE NOT NULL,
    kp_pengikut VARCHAR(20) NOT NULL UNIQUE,
    kp_ibuayah_pengikut VARCHAR(20) NOT NULL UNIQUE,

    tarikh_penerbangan_pergi_pengikut DATE,
    tarikh_penerbangan_balik_pengikut DATE,

    FOREIGN KEY (wilayah_asal_id) REFERENCES wilayah_asal(id),
    FOREIGN KEY (kp_ibuayah_pengikut) REFERENCES user(kp),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
); 