<?php
session_start();
include_once '../../includes/config.php';

// Accept wilayah_asal_id via POST and set in session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wilayah_asal_id'])) {
    $wilayah_asal_id = $_POST['wilayah_asal_id'];
    header('Location: wilayahAsalView.php');
    exit();
}

// Get wilayah_asal_id from session
if (!$wilayah_asal_id) {
    header('Location: wilayahAsalList.php');
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
    header("Location: ../../loginUser.php");
    exit();
}

$user_name = $user_data['nama_first'] . ' ' . $user_data['nama_last'];
$user_role = $user_data['bahagian'];
$user_icNo = $user_data['kp'];
$user_email = $user_data['email'];
$user_phoneNo = $user_data['phone'];

// Fetch wilayah_asal record
$check_sql = "SELECT * FROM wilayah_asal WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $wilayah_asal_id);
$check_stmt->execute();
$wilayah_asal_result = $check_stmt->get_result();
$wilayah_asal_data = $wilayah_asal_result->fetch_assoc();

if (!$wilayah_asal_data) {
    header("Location: wilayahAsalList.php");
    exit();
}

// Fetch pengikut data if exists
$pengikut_sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
$pengikut_stmt = $conn->prepare($pengikut_sql);
$pengikut_stmt->bind_param("i", $wilayah_asal_data['id']);
$pengikut_stmt->execute();
$pengikut_result = $pengikut_stmt->get_result();
$pengikut_data = [];
while ($row = $pengikut_result->fetch_assoc()) {
    $pengikut_data[] = $row;
}

// Fetch documents if exists
$doc_sql = "SELECT * FROM documents WHERE wilayah_asal_id = ?";
$doc_stmt = $conn->prepare($doc_sql);
$doc_stmt->bind_param("i", $wilayah_asal_data['id']);
$doc_stmt->execute();
$doc_result = $doc_stmt->get_result();
$documents = [];
while ($row = $doc_result->fetch_assoc()) {
    $documents[] = $row;
}

// Get the current status and position
$status_permohonan = $wilayah_asal_data['status_permohonan'];
$kedudukan_permohonan = $wilayah_asal_data['kedudukan_permohonan'];

function getUploadPath($file) {
    return '../../../uploads/' . $file;
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Lihat Wilayah Asal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
    <link rel="icon" href="../../../assets/ALLTRAS.png" type="image/x-icon">
    <style>
        .section-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
        }
        .section-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
            border-radius: 10px 10px 0 0;
        }
        .section-body {
            padding: 1.5rem;
        }
        .info-row {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .info-label {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        .info-value {
            color: #212529;
            font-size: 1rem;
            line-height: 1.5;
        }
        .document-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 0.75rem;
        }
        .document-item:last-child {
            margin-bottom: 0;
        }
        .document-info {
            display: flex;
            align-items: center;
        }
        .document-icon {
            color: #6c757d;
            margin-right: 0.75rem;
        }
        .document-description {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 50rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-badge.belum-disemak {
            background-color: #e9ecef;
            color: #495057;
        }
        .status-badge.dikuiri {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-badge.lulus {
            background-color: #d4edda;
            color: #155724;
        }
        .status-badge.tolak {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-badge.selesai {
            background-color: #cce5ff;
            color: #004085;
        }
    </style>
</head>
<body>
<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Lihat Wilayah Asal</h3>
            <?php include 'includes/greeting.php'; ?>
        </div>

        <!-- Status Permohonan -->
        <div class="mb-4">
            <span class="status-badge <?php
                $badge = 'belum-disemak';
                if ($status_permohonan === 'Dikuiri') $badge = 'dikuiri';
                else if ($status_permohonan === 'Lulus') $badge = 'lulus';
                else if ($status_permohonan === 'Tolak') $badge = 'tolak';
                else if ($status_permohonan === 'Selesai') $badge = 'selesai';
                echo $badge;
            ?>">
                <?= htmlspecialchars($status_permohonan) ?>
            </span>
        </div>

        <!-- Maklumat Pegawai -->
        <div class="section-card">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Maklumat Pegawai
                </h5>
            </div>
            <div class="section-body">
                <?php if ($wilayah_asal_data): ?>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="info-label">Nama</p>
                                <p class="info-value">
                                    <i class="fas fa-user me-2 text-primary"></i><?= htmlspecialchars($user_name) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="info-label">No. KP</p>
                                <p class="info-value">
                                    <i class="fas fa-id-card me-2 text-secondary"></i><?= htmlspecialchars($user_icNo) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="info-label">Jawatan & Gred</p>
                                <p class="info-value"><?= htmlspecialchars($wilayah_asal_data['jawatan_gred']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="info-label">Email Penyelia</p>
                                <p class="info-value"><?= htmlspecialchars($wilayah_asal_data['email_penyelia']) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="info-label">Alamat Menetap</p>
                                <p class="info-value">
                                    <?= htmlspecialchars($wilayah_asal_data['alamat_menetap_1']) ?><br>
                                    <?= $wilayah_asal_data['alamat_menetap_2'] ? htmlspecialchars($wilayah_asal_data['alamat_menetap_2']) . '<br>' : '' ?>
                                    <?= htmlspecialchars($wilayah_asal_data['poskod_menetap']) ?> <?= htmlspecialchars($wilayah_asal_data['bandar_menetap']) ?><br>
                                    <?= htmlspecialchars($wilayah_asal_data['negeri_menetap']) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="info-label">Alamat Berkhidmat</p>
                                <p class="info-value">
                                    <?= htmlspecialchars($wilayah_asal_data['alamat_berkhidmat_1']) ?><br>
                                    <?= $wilayah_asal_data['alamat_berkhidmat_2'] ? htmlspecialchars($wilayah_asal_data['alamat_berkhidmat_2']) . '<br>' : '' ?>
                                    <?= htmlspecialchars($wilayah_asal_data['poskod_berkhidmat']) ?> <?= htmlspecialchars($wilayah_asal_data['bandar_berkhidmat']) ?><br>
                                    <?= htmlspecialchars($wilayah_asal_data['negeri_berkhidmat']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php if ($wilayah_asal_data['nama_first_pasangan'] || $wilayah_asal_data['nama_last_pasangan']): ?>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-12">
                                <p class="info-label">Maklumat Pasangan</p>
                                <p class="info-value">
                                    <?= htmlspecialchars($wilayah_asal_data['nama_first_pasangan'] . ' ' . $wilayah_asal_data['nama_last_pasangan']) ?><br>
                                    No. KP: <?= htmlspecialchars($wilayah_asal_data['no_kp_pasangan']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Maklumat Ibu Bapa -->
        <div class="section-card">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Maklumat Ibu Bapa
                </h5>
            </div>
            <div class="section-body">
                <?php if ($wilayah_asal_data): ?>
                    <?php if ($wilayah_asal_data['nama_bapa']): ?>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="info-label">Maklumat Bapa</p>
                                <p class="info-value">
                                    <?= htmlspecialchars($wilayah_asal_data['nama_bapa']) ?><br>
                                    No. KP: <?= htmlspecialchars($wilayah_asal_data['no_kp_bapa']) ?><br>
                                    Wilayah Menetap: <?= htmlspecialchars($wilayah_asal_data['wilayah_menetap_bapa']) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="info-label">Alamat Bapa</p>
                                <p class="info-value">
                                    <?= htmlspecialchars($wilayah_asal_data['alamat_menetap_1_bapa']) ?><br>
                                    <?= $wilayah_asal_data['alamat_menetap_2_bapa'] ? htmlspecialchars($wilayah_asal_data['alamat_menetap_2_bapa']) . '<br>' : '' ?>
                                    <?= htmlspecialchars($wilayah_asal_data['poskod_menetap_bapa']) ?> <?= htmlspecialchars($wilayah_asal_data['bandar_menetap_bapa']) ?><br>
                                    <?= htmlspecialchars($wilayah_asal_data['negeri_menetap_bapa']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($wilayah_asal_data['nama_ibu']): ?>
                    <div class="info-row">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="info-label">Maklumat Ibu</p>
                                <p class="info-value">
                                    <?= htmlspecialchars($wilayah_asal_data['nama_ibu']) ?><br>
                                    No. KP: <?= htmlspecialchars($wilayah_asal_data['no_kp_ibu']) ?><br>
                                    Wilayah Menetap: <?= htmlspecialchars($wilayah_asal_data['wilayah_menetap_ibu']) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="info-label">Alamat Ibu</p>
                                <p class="info-value">
                                    <?= htmlspecialchars($wilayah_asal_data['alamat_menetap_1_ibu']) ?><br>
                                    <?= $wilayah_asal_data['alamat_menetap_2_ibu'] ? htmlspecialchars($wilayah_asal_data['alamat_menetap_2_ibu']) . '<br>' : '' ?>
                                    <?= htmlspecialchars($wilayah_asal_data['poskod_menetap_ibu']) ?> <?= htmlspecialchars($wilayah_asal_data['bandar_menetap_ibu']) ?><br>
                                    <?= htmlspecialchars($wilayah_asal_data['negeri_menetap_ibu']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Maklumat Penerbangan -->
        <div class="section-card">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-plane me-2"></i>Maklumat Penerbangan
                </h5>
            </div>
            <div class="section-body">
                <?php if ($wilayah_asal_data): ?>
                    <div class="info-row mb-3">
                        <p class="info-label">Jenis Permohonan</p>
                        <p class="info-value">
                            <?php if ($wilayah_asal_data['jenis_permohonan'] === 'diri_sendiri'): ?>
                                Permohonan Diri Sendiri/Pengikut ke Wilayah Menetap
                            <?php elseif ($wilayah_asal_data['jenis_permohonan'] === 'keluarga'): ?>
                                Permohonan Keluarga Pegawai ke Wilayah Berkhidmat
                            <?php endif; ?>
                        </p>
                    </div>
                    <!-- Pemohon's Flight Information -->
                    <div class="info-row mb-4">
                        <h6 class="text-primary mb-3">Pemohon</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="info-label">Tarikh Penerbangan</p>
                                <p class="info-value">
                                    <i class="fas fa-plane-departure me-2 text-primary"></i>Pergi: <?= date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_pergi'])) ?><br>
                                    <i class="fas fa-plane-arrival me-2 text-success"></i>Balik: <?= date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_balik'])) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="info-label">Lokasi</p>
                                <p class="info-value">
                                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>Berlepas: <?= htmlspecialchars($wilayah_asal_data['start_point']) ?><br>
                                    <i class="fas fa-map-marker-alt me-2 text-success"></i>Tiba: <?= htmlspecialchars($wilayah_asal_data['end_point']) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Spouse's Flight Information -->
                    <?php if ($wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']): ?>
                    <div class="info-row mb-4">
                        <h6 class="text-primary mb-3">Pasangan</h6>
                        <div class="row">
                            <div class="col-12">
                                <p class="info-label">Tarikh Penerbangan</p>
                                <p class="info-value">
                                    <i class="fas fa-plane-departure me-2 text-primary"></i>Pergi: <?= date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_pergi_pasangan'])) ?><br>
                                    <i class="fas fa-plane-arrival me-2 text-success"></i>Balik: <?= date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_balik_pasangan'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Followers' Flight Information -->
                    <?php if ($pengikut_data): ?>
                        <?php foreach ($pengikut_data as $index => $pengikut): ?>
                        <div class="info-row <?= $index < count($pengikut_data) - 1 ? 'mb-4' : '' ?>">
                            <h6 class="text-primary mb-3">Pengikut <?= $index + 1 ?></h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="info-label">Maklumat Pengikut</p>
                                    <p class="info-value">
                                        <i class="fas fa-user me-2 text-primary"></i><?= htmlspecialchars($pengikut['nama_first_pengikut'] . ' ' . $pengikut['nama_last_pengikut']) ?><br>
                                        <i class="fas fa-id-card me-2 text-secondary"></i>No. KP: <?= htmlspecialchars($pengikut['kp_pengikut']) ?><br>
                                        <i class="fas fa-birthday-cake me-2 text-info"></i>Tarikh Lahir: <?= date('d/m/Y', strtotime($pengikut['tarikh_lahir_pengikut'])) ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="info-label">Tarikh Penerbangan</p>
                                    <p class="info-value">
                                        <i class="fas fa-plane-departure me-2 text-primary"></i>Pergi: <?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_pergi_pengikut'])) ?><br>
                                        <i class="fas fa-plane-arrival me-2 text-success"></i>Balik: <?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_balik_pengikut'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Dokumen -->
        <div class="section-card">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>Dokumen
                </h5>
            </div>
            <div class="section-body">
                <?php if ($documents): ?>
                    <?php foreach ($documents as $doc): ?>
                    <div class="document-item">
                        <div class="document-info">
                            <i class="fas fa-file document-icon"></i>
                            <div>
                                <div><?= htmlspecialchars($doc['file_name']) ?></div>
                                <small class="document-description"><?= htmlspecialchars($doc['description']) ?></small>
                            </div>
                        </div>
                        <div class="actions">
                            <a href="<?= getUploadPath(str_replace('../../../uploads/', '', htmlspecialchars($doc['file_path']))) ?>" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Tiada dokumen dimuat naik.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-4">
            <a href="wilayahAsalList.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Senarai
            </a>
        </div>
    </div>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });
</script>
</body>
</html> 