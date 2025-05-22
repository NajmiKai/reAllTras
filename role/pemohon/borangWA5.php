<?php
session_start();
include '../../connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if wilayah_asal_id exists in session
if (!isset($_SESSION['wilayah_asal_id'])) {
    error_log("wilayah_asal_id not set in session");
    header("Location: borangWA.php");
    exit();
}

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    error_log("User data not found for ID: " . $user_id);
    header("Location: ../../login.php");
    exit();
}

// Fetch all application data
$wilayah_asal_id = $_SESSION['wilayah_asal_id'];
$sql = "SELECT wa.*, 
        GROUP_CONCAT(DISTINCT wp.nama_first_pengikut, ' ', wp.nama_last_pengikut) as pengikut_names,
        GROUP_CONCAT(DISTINCT d.file_name) as document_names
        FROM wilayah_asal wa 
        LEFT JOIN wilayah_asal_pengikut wp ON wa.id = wp.wilayah_asal_id
        LEFT JOIN documents d ON wa.id = d.wilayah_asal_id
        WHERE wa.id = ?
        GROUP BY wa.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $wilayah_asal_id);
$stmt->execute();
$result = $stmt->get_result();
$application_data = $result->fetch_assoc();

if (!$application_data) {
    header("Location: borangWA.php");
    exit();
}

$user_name = $user_data['nama_first'] . ' ' . $user_data['nama_last'];
$user_role = $user_data['bahagian'];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Borang Wilayah Asal (Semakan)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/adminStyle.css">
    <link rel="stylesheet" href="../../assets/css/multi-step.css">
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand navbar-light bg-light shadow-sm px-3 mb-4 w-100">
    <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="nav-link toggle-sidebar" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <span class="nav-link fw-semibold"><?= htmlspecialchars($user_name) ?> (<?= htmlspecialchars($user_role) ?>)</span>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../../logout.php" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt me-1"></i> Log Keluar
            </a>
        </li>
    </ul>
</nav>

<div class="main-container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h6><img src="../../assets/ALLTRAS.png" alt="ALLTRAS" width="140" style="margin-left: 20px;"><br>ALL REGION TRAVELLING SYSTEM</h6><br>
        <a href="dashboard.php"><i class="fas fa-home me-2"></i>Laman Utama</a>
        <h6 class="text mt-4"></h6>
        <a href="wilayahAsal.php"><i class="fas fa-map-marker-alt me-2"></i>Wilayah Asal</a>
        <a href="tugasRasmi.php"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a>
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Semakan Permohonan</h3>
        
        <!-- Multi-step Indicator -->
        <div class="multi-step-indicator mb-4">
            <div class="step completed">
                <div class="step-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="step-label">Maklumat Pegawai</div>
            </div>
            <div class="step-line"></div>
            <div class="step completed">
                <div class="step-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="step-label">Maklumat Wilayah Menetap Ibu Bapa</div>
            </div>
            <div class="step-line"></div>
            <div class="step completed">
                <div class="step-icon">
                    <i class="fas fa-plane"></i>
                </div>
                <div class="step-label">Maklumat Penerbangan</div>
            </div>
            <div class="step-line"></div>
            <div class="step completed">
                <div class="step-icon">
                    <i class="fas fa-file-upload"></i>
                </div>
                <div class="step-label">Muat Naik Dokumen</div>
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="step-label">Pengesahan Maklumat</div>
            </div>
        </div>

        <form action="../../functions/process_borangWA5.php" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="wilayah_asal_id" value="<?php echo htmlspecialchars($wilayah_asal_id); ?>">
            
            <!-- Maklumat Pegawai -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Maklumat Pegawai</strong></h5>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editPegawaiModal">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Pegawai</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($user_name) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Kad Pengenalan</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($user_data['kp']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jawatan/Gred</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['jawatan_gred']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($application_data['nama_first_pasangan'] || $application_data['nama_last_pasangan']): ?>
            <!-- Maklumat Pasangan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Maklumat Pasangan</strong></h5>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editPasanganModal">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Pasangan</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['nama_first_pasangan'] . ' ' . $application_data['nama_last_pasangan']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Kad Pengenalan</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['no_kp_pasangan']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Wilayah Menetap</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['wilayah_menetap_pasangan']) ?></p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Berkhidmat</label>
                            <p class="form-control-static ps-2">
                                <?= htmlspecialchars($application_data['alamat_berkhidmat_1_pasangan']) ?><br>
                                <?= htmlspecialchars($application_data['alamat_berkhidmat_2_pasangan']) ?><br>
                                <?= htmlspecialchars($application_data['poskod_berkhidmat_pasangan']) ?> 
                                <?= htmlspecialchars($application_data['bandar_berkhidmat_pasangan']) ?>, 
                                <?= htmlspecialchars($application_data['negeri_berkhidmat_pasangan']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Maklumat Ibu Bapa -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Maklumat Ibu Bapa</strong></h5>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editIbuBapaModal">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                </div>
                <div class="card-body">
                    <!-- Maklumat Bapa -->
                    <h6 class="mb-3"><strong>Maklumat Bapa</strong></h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Bapa</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['nama_bapa']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Kad Pengenalan</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['no_kp_bapa']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Wilayah Menetap</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['wilayah_menetap_bapa']) ?></p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Menetap</label>
                            <p class="form-control-static ps-2">
                                <?= htmlspecialchars($application_data['alamat_menetap_1_bapa']) ?><br>
                                <?= htmlspecialchars($application_data['alamat_menetap_2_bapa']) ?><br>
                                <?= htmlspecialchars($application_data['poskod_menetap_bapa']) ?> 
                                <?= htmlspecialchars($application_data['bandar_menetap_bapa']) ?>, 
                                <?= htmlspecialchars($application_data['negeri_menetap_bapa']) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Maklumat Ibu -->
                    <h6 class="mb-3"><strong>Maklumat Ibu</strong></h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Ibu</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['nama_ibu']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Kad Pengenalan</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['no_kp_ibu']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Wilayah Menetap</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['wilayah_menetap_ibu']) ?></p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Menetap</label>
                            <p class="form-control-static ps-2">
                                <?= htmlspecialchars($application_data['alamat_menetap_1_ibu']) ?><br>
                                <?= htmlspecialchars($application_data['alamat_menetap_2_ibu']) ?><br>
                                <?= htmlspecialchars($application_data['poskod_menetap_ibu']) ?> 
                                <?= htmlspecialchars($application_data['bandar_menetap_ibu']) ?>, 
                                <?= htmlspecialchars($application_data['negeri_menetap_ibu']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maklumat Penerbangan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Maklumat Penerbangan</strong></h5>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editPenerbanganModal">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Permohonan</label>
                            <p class="form-control-static ps-2">
                                <?php
                                $jenis_permohonan = $application_data['jenis_permohonan'];
                                if ($jenis_permohonan === 'diri_sendiri') {
                                    echo "Diri Sendiri/ Pasangan/ Anak Ke Wilayah Ditetapkan";
                                } else if ($jenis_permohonan === 'keluarga') {
                                    echo "Keluarga Pegawai ke Wilayah Berkhidmat";
                                } else {
                                    echo htmlspecialchars($jenis_permohonan);
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tarikh Penerbangan Pergi</label>
                            <p class="form-control-static ps-2"><?= date('d/m/Y', strtotime($application_data['tarikh_penerbangan_pergi'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tarikh Penerbangan Balik</label>
                            <p class="form-control-static ps-2"><?= date('d/m/Y', strtotime($application_data['tarikh_penerbangan_balik'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Lapangan Terbang Berlepas</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['start_point']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Lapangan Terbang Tiba</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['end_point']) ?></p>
                        </div>
                    </div>

                    <?php if ($application_data['pengikut_names']): ?>
                    <!-- Maklumat Pengikut -->
                    <h6 class="mt-4 mb-3"><strong>Maklumat Pengikut</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-bold">Nama Pengikut</th>
                                    <th class="fw-bold">No. Kad Pengenalan</th>
                                    <th class="fw-bold">Tarikh Lahir</th>
                                    <th class="fw-bold">Tarikh Penerbangan Pergi</th>
                                    <th class="fw-bold">Tarikh Penerbangan Balik</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $wilayah_asal_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($pengikut = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($pengikut['nama_first_pengikut'] . ' ' . $pengikut['nama_last_pengikut']) ?></td>
                                    <td><?= htmlspecialchars($pengikut['kp_pengikut']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($pengikut['tarikh_lahir_pengikut'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_pergi_pengikut'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_balik_pengikut'])) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Dokumen Sokongan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Dokumen Sokongan</strong></h5>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i class="fas fa-upload me-1"></i>Tambah Dokumen
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-bold">Jenis Dokumen</th>
                                    <th class="fw-bold">Nama Fail</th>
                                    <th class="fw-bold">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM documents WHERE wilayah_asal_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $wilayah_asal_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($doc = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($doc['description']) ?></td>
                                    <td><?= htmlspecialchars($doc['file_name']) ?></td>
                                    <td>
                                        <a href="../../<?= htmlspecialchars($doc['file_path']) ?>" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Lihat Dokumen
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Edit Modals -->
            <!-- Edit Pegawai Modal -->
            <div class="modal fade" id="editPegawaiModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Maklumat Pegawai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="../../functions/update_borangWA5.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="wilayah_asal_id" value="<?= $wilayah_asal_id ?>">
                                <input type="hidden" name="update_type" value="pegawai">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jawatan/Gred</label>
                                    <input type="text" class="form-control" name="jawatan_gred" value="<?= htmlspecialchars($application_data['jawatan_gred']) ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Pasangan Modal -->
            <div class="modal fade" id="editPasanganModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Maklumat Pasangan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="../../functions/update_borangWA5.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="wilayah_asal_id" value="<?= $wilayah_asal_id ?>">
                                <input type="hidden" name="update_type" value="pasangan">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Pasangan</label>
                                    <input type="text" class="form-control" name="nama_pasangan" value="<?= htmlspecialchars($application_data['nama_first_pasangan'] . ' ' . $application_data['nama_last_pasangan']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">No. Kad Pengenalan</label>
                                    <input type="text" class="form-control" name="no_kp_pasangan" value="<?= htmlspecialchars($application_data['no_kp_pasangan']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Wilayah Menetap</label>
                                    <input type="text" class="form-control" name="wilayah_menetap_pasangan" value="<?= htmlspecialchars($application_data['wilayah_menetap_pasangan']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Alamat Berkhidmat</label>
                                    <textarea class="form-control" name="alamat_berkhidmat_pasangan" rows="3" required><?= htmlspecialchars($application_data['alamat_berkhidmat_1_pasangan'] . "\n" . $application_data['alamat_berkhidmat_2_pasangan']) ?></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Ibu Bapa Modal -->
            <div class="modal fade" id="editIbuBapaModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Maklumat Ibu Bapa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="../../functions/update_borangWA5.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="wilayah_asal_id" value="<?= $wilayah_asal_id ?>">
                                <input type="hidden" name="update_type" value="ibu_bapa">
                                
                                <h6 class="mb-3"><strong>Maklumat Bapa</strong></h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Nama Bapa</label>
                                        <input type="text" class="form-control" name="nama_bapa" value="<?= htmlspecialchars($application_data['nama_bapa']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">No. Kad Pengenalan</label>
                                        <input type="text" class="form-control" name="no_kp_bapa" value="<?= htmlspecialchars($application_data['no_kp_bapa']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Wilayah Menetap</label>
                                        <input type="text" class="form-control" name="wilayah_menetap_bapa" value="<?= htmlspecialchars($application_data['wilayah_menetap_bapa']) ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Alamat Menetap</label>
                                        <textarea class="form-control" name="alamat_menetap_bapa" rows="3" required><?= htmlspecialchars($application_data['alamat_menetap_1_bapa'] . "\n" . $application_data['alamat_menetap_2_bapa']) ?></textarea>
                                    </div>
                                </div>

                                <h6 class="mb-3"><strong>Maklumat Ibu</strong></h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Nama Ibu</label>
                                        <input type="text" class="form-control" name="nama_ibu" value="<?= htmlspecialchars($application_data['nama_ibu']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">No. Kad Pengenalan</label>
                                        <input type="text" class="form-control" name="no_kp_ibu" value="<?= htmlspecialchars($application_data['no_kp_ibu']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Wilayah Menetap</label>
                                        <input type="text" class="form-control" name="wilayah_menetap_ibu" value="<?= htmlspecialchars($application_data['wilayah_menetap_ibu']) ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Alamat Menetap</label>
                                        <textarea class="form-control" name="alamat_menetap_ibu" rows="3" required><?= htmlspecialchars($application_data['alamat_menetap_1_ibu'] . "\n" . $application_data['alamat_menetap_2_ibu']) ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Penerbangan Modal -->
            <div class="modal fade" id="editPenerbanganModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Maklumat Penerbangan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="../../functions/update_borangWA5.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="wilayah_asal_id" value="<?= $wilayah_asal_id ?>">
                                <input type="hidden" name="update_type" value="penerbangan">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jenis Permohonan</label>
                                    <select class="form-select" name="jenis_permohonan" required>
                                        <option value="diri_sendiri" <?= $application_data['jenis_permohonan'] === 'diri_sendiri' ? 'selected' : '' ?>>Diri Sendiri/ Pasangan/ Anak Ke Wilayah Ditetapkan</option>
                                        <option value="keluarga" <?= $application_data['jenis_permohonan'] === 'keluarga' ? 'selected' : '' ?>>Keluarga Pegawai ke Wilayah Berkhidmat</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tarikh Penerbangan Pergi</label>
                                    <input type="date" class="form-control" name="tarikh_penerbangan_pergi" value="<?= date('Y-m-d', strtotime($application_data['tarikh_penerbangan_pergi'])) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tarikh Penerbangan Balik</label>
                                    <input type="date" class="form-control" name="tarikh_penerbangan_balik" value="<?= date('Y-m-d', strtotime($application_data['tarikh_penerbangan_balik'])) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Lapangan Terbang Berlepas</label>
                                    <input type="text" class="form-control" name="start_point" value="<?= htmlspecialchars($application_data['start_point']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Lapangan Terbang Tiba</label>
                                    <input type="text" class="form-control" name="end_point" value="<?= htmlspecialchars($application_data['end_point']) ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Upload Document Modal -->
            <div class="modal fade" id="uploadDocumentModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Dokumen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="../../functions/update_borangWA5.php" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="wilayah_asal_id" value="<?= $wilayah_asal_id ?>">
                                <input type="hidden" name="update_type" value="document">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jenis Dokumen</label>
                                    <select class="form-select" name="document_type" required>
                                        <option value="SALINAN_IC_PEGAWAI">Salinan IC Pegawai</option>
                                        <option value="SALINAN_IC_PENGIKUT">Salinan IC Pengikut</option>
                                        <option value="DOKUMEN_SOKONGAN">Dokumen Sokongan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Fail</label>
                                    <input type="file" class="form-control" name="document_file" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Muat Naik</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pengesahan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Pengesahan</strong></h5>
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="pengesahan" name="pengesahan" required>
                        <label class="form-check-label" for="pengesahan">
                            Saya mengesahkan bahawa semua maklumat dan kenyataan yang diberikan adalah benar dan sah. 
                            Saya juga memahami bahawa sekiranya terdapat maklumat palsu, tidak benar atau tidak lengkap, 
                            maka saya boleh dikenakan tindakan tatatertib di bawah Peraturan-Peraturan Pegawai Awam 
                            (Kelakuan dan Tatatertib) 1993.
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="borangWA4.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check me-2"></i>Hantar Permohonan
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
</body>
</html>
