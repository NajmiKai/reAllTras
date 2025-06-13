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
    header("Location: ../../loginUser.php");
    exit();
}

$user_name = $user_data['nama_first'] . ' ' . $user_data['nama_last'];
$user_role = $user_data['bahagian'];

// Fetch wilayah_asal data
$wilayah_asal_id = $_SESSION['wilayah_asal_id'];
$sql = "SELECT * FROM wilayah_asal WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $wilayah_asal_id);
$stmt->execute();
$result = $stmt->get_result();
$wilayah_asal_data = $result->fetch_assoc();

// Fetch pengikut data
$sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $wilayah_asal_id);
$stmt->execute();
$result = $stmt->get_result();
$pengikut_data = $result->fetch_all(MYSQLI_ASSOC);

// Fetch documents
$sql = "SELECT * FROM documents WHERE wilayah_asal_id = ? AND file_origin = 'pemohon'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $wilayah_asal_id);
$stmt->execute();
$result = $stmt->get_result();
$documents = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Borang Wilayah Asal (Pengesahan)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
    <link rel="stylesheet" href="../../assets/css/multi-step.css">
    <style>
        .section-card {
            margin-bottom: 2rem;
        }
        .section-header {
            background-color: #d59e3e;
            color: white;
            padding: 1rem;
            border-radius: 0.25rem 0.25rem 0 0;
        }
        .section-body {
            padding: 1.5rem;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 0.25rem 0.25rem;
        }
        .info-row {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .edit-btn {
            color: #d59e3e;
            cursor: pointer;
        }
        .edit-btn:hover {
            color: #b88a2e;
        }
        .document-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.25rem;
        }
        .document-item .actions {
            margin-left: auto;
        }
        .document-item .actions button {
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <?php include 'includes/greeting.php'; ?>
        <h3 class="mb-3">Pengesahan Maklumat</h3>
        
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

        <form action="includes/process_borangWA5.php" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="wilayah_asal_id" value="<?php echo htmlspecialchars($wilayah_asal_id); ?>">

            <!-- Maklumat Pegawai -->
            <div class="section-card">
                <div class="section-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Maklumat Pegawai</h5>
                        <a href="borangWA.php" class="btn btn-sm btn-light">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                    </div>
                </div>
                <div class="section-body">
                    <?php if ($wilayah_asal_data): ?>
                        <div class="info-row">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Jawatan & Gred:</strong></p>
                                    <p><?= htmlspecialchars($wilayah_asal_data['jawatan_gred']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Email Penyelia:</strong></p>
                                    <p><?= htmlspecialchars($wilayah_asal_data['email_penyelia']) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Alamat Menetap:</strong></p>
                                    <p>
                                        <?= htmlspecialchars($wilayah_asal_data['alamat_menetap_1']) ?><br>
                                        <?= $wilayah_asal_data['alamat_menetap_2'] ? htmlspecialchars($wilayah_asal_data['alamat_menetap_2']) . '<br>' : '' ?>
                                        <?= htmlspecialchars($wilayah_asal_data['poskod_menetap']) ?> <?= htmlspecialchars($wilayah_asal_data['bandar_menetap']) ?><br>
                                        <?= htmlspecialchars($wilayah_asal_data['negeri_menetap']) ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Alamat Berkhidmat:</strong></p>
                                    <p>
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
                                    <p class="mb-1"><strong>Maklumat Pasangan:</strong></p>
                                    <p>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Maklumat Ibu Bapa</h5>
                        <a href="borangWA2.php" class="btn btn-sm btn-light">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                    </div>
                </div>
                <div class="section-body">
                    <?php if ($wilayah_asal_data): ?>
                        <?php if ($wilayah_asal_data['nama_bapa']): ?>
                        <div class="info-row">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Maklumat Bapa:</strong></p>
                                    <p>
                                        <?= htmlspecialchars($wilayah_asal_data['nama_bapa']) ?><br>
                                        No. KP: <?= htmlspecialchars($wilayah_asal_data['no_kp_bapa']) ?><br>
                                        Wilayah Menetap: <?= htmlspecialchars($wilayah_asal_data['wilayah_menetap_bapa']) ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Alamat Bapa:</strong></p>
                                    <p>
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
                                    <p class="mb-1"><strong>Maklumat Ibu:</strong></p>
                                    <p>
                                        <?= htmlspecialchars($wilayah_asal_data['nama_ibu']) ?><br>
                                        No. KP: <?= htmlspecialchars($wilayah_asal_data['no_kp_ibu']) ?><br>
                                        Wilayah Menetap: <?= htmlspecialchars($wilayah_asal_data['wilayah_menetap_ibu']) ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Alamat Ibu:</strong></p>
                                    <p>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Maklumat Penerbangan</h5>
                        <a href="borangWA3.php" class="btn btn-sm btn-light">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                    </div>
                </div>
                <div class="section-body">
                    <?php if ($wilayah_asal_data): ?>
                        <div class="info-row">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Tarikh Penerbangan:</strong></p>
                                    <p>
                                        Pergi: <?= date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_pergi'])) ?><br>
                                        Balik: <?= date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_balik'])) ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Lokasi:</strong></p>
                                    <p>
                                        Berlepas: <?= htmlspecialchars($wilayah_asal_data['start_point']) ?><br>
                                        Tiba: <?= htmlspecialchars($wilayah_asal_data['end_point']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php if ($wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']): ?>
                        <div class="info-row">
                            <div class="row">
                                <div class="col-12">
                                    <p class="mb-1"><strong>Tarikh Penerbangan Pasangan:</strong></p>
                                    <p>
                                        Pergi: <?= date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_pergi_pasangan'])) ?><br>
                                        Balik: <?= date('d/m/Y', strtotime($wilayah_asal_data['tarikh_penerbangan_balik_pasangan'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Maklumat Pengikut -->
            <div class="section-card">
                <div class="section-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Maklumat Pengikut</h5>
                        <a href="borangWA3.php" class="btn btn-sm btn-light">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                    </div>
                </div>
                <div class="section-body">
                    <?php if ($pengikut_data): ?>
                        <?php foreach ($pengikut_data as $index => $pengikut): ?>
                        <div class="info-row">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Pengikut <?= $index + 1 ?>:</strong></p>
                                    <p>
                                        <?= htmlspecialchars($pengikut['nama_first_pengikut'] . ' ' . $pengikut['nama_last_pengikut']) ?><br>
                                        No. KP: <?= htmlspecialchars($pengikut['kp_pengikut']) ?><br>
                                        Tarikh Lahir: <?= date('d/m/Y', strtotime($pengikut['tarikh_lahir_pengikut'])) ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Tarikh Penerbangan:</strong></p>
                                    <p>
                                        Pergi: <?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_pergi_pengikut'])) ?><br>
                                        Balik: <?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_balik_pengikut'])) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Tiada maklumat pengikut.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="section-card">
                <div class="section-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Dokumen</h5>
                        <a href="borangWA4.php" class="btn btn-sm btn-light">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                    </div>
                </div>
                <div class="section-body">
                    <?php if ($documents): ?>
                        <?php foreach ($documents as $doc): ?>
                        <div class="document-item">
                            <div>
                                <i class="fas fa-file me-2"></i>
                                <?= htmlspecialchars($doc['file_name']) ?>
                                <small class="text-muted ms-2">(<?= htmlspecialchars($doc['description']) ?>)</small>
                            </div>
                            <div class="actions">
                                <a href="../../<?= htmlspecialchars($doc['file_path']) ?>" target="_blank" class="btn btn-sm btn-primary">
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

            <div class="text-end">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirmation" required>
                        <label class="form-check-label" for="confirmation">
                            Saya mengesahkan bahawa semua maklumat dan kenyataan yang diberikan adalah benar dan sah. Saya juga memahami bahawa sekiranya terdapat maklumat palsu, tidak benar atau tidak lengkap, maka saya boleh dikenakan tindakan tatatertib di bawah Peraturan-Peraturan Pegawai Awam (Kelakuan dan Tatatertib) 1993
                        </label>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="javascript:void(0)" onclick="goBack()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Hantar Permohonan
                    </button>
                </div>
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

    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });

    function goBack() {
        // Create a form to submit the data
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'borangWA4.php';
        
        // Get all form data
        const currentForm = document.querySelector('form');
        const formData = new FormData(currentForm);
        
        // Add all form data as hidden inputs
        for (let [key, value] of formData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
        
        // Add wilayah_asal_id if it exists in the URL
        const urlParams = new URLSearchParams(window.location.search);
        const wilayahAsalId = urlParams.get('wilayah_asal_id');
        if (wilayahAsalId) {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'wilayah_asal_id';
            idInput.value = wilayahAsalId;
            form.appendChild(idInput);
        }
        
        document.body.appendChild(form);
        form.submit();
    }
</script>
</body>
</html> 