<?php
session_start();
include '../../connection.php';

// Get wilayah_asal_id from session
$wilayah_asal_id = $_SESSION['wilayah_asal_id'] ?? null;

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

// Check if user has wilayah_asal record
$check_sql = "SELECT * FROM wilayah_asal WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $wilayah_asal_id);
$check_stmt->execute();
$wilayah_asal_result = $check_stmt->get_result();
$wilayah_asal_data = $wilayah_asal_result->fetch_assoc();

// If no wilayah_asal record exists, redirect to borangWA
if (!$wilayah_asal_data) {
    header("Location: borangWA.php");
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

// Fetch E-ticket documents
$doc_sql = "SELECT * FROM documents WHERE wilayah_asal_id = ? AND description = 'E-tiket'";
$doc_stmt = $conn->prepare($doc_sql);
$doc_stmt->bind_param("i", $wilayah_asal_data['id']);
$doc_stmt->execute();
$doc_result = $doc_stmt->get_result();
$documents = [];
while ($row = $doc_result->fetch_assoc()) {
    $documents[] = $row;
}

?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Permohonan Wilayah Asal Selesai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
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
    </style>
</head>
<body>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Wilayah Asal Selesai</h3>
            <?php include 'includes/greeting.php'; ?>
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

        <!-- Download E-Ticket -->
        <div class="section-card">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-ticket-alt me-2"></i>Download E-Ticket
                </h5>
            </div>
            <div class="section-body">
                <?php if ($documents): ?>
                    <?php foreach ($documents as $doc): ?>
                    <div class="document-item">
                        <div class="document-info">
                            <i class="fas fa-file-alt document-icon"></i>
                            <div>
                                <div><?= htmlspecialchars($doc['file_name']) ?></div>
                                <small class="document-description"><?= htmlspecialchars($doc['description']) ?></small>
                            </div>
                        </div>
                        <div class="actions">
                            <a href="<?= getUploadPath(str_replace('../../../uploads/', '', htmlspecialchars($doc['file_path']))) ?>" target="_blank" class="btn btn-sm btn-primary me-2">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                            <a href="<?= getUploadPath(str_replace('../../../uploads/', '', htmlspecialchars($doc['file_path']))) ?>" download class="btn btn-sm btn-success">
                                <i class="fas fa-download"></i> Muat Turun
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Tiada E-tiket tersedia.</p>
                <?php endif; ?>
            </div>
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