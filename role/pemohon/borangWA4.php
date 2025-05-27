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

$user_name = $user_data['nama_first'] . ' ' . $user_data['nama_last'];
$user_role = $user_data['bahagian'];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Borang Wilayah Asal (Dokumen)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
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
        <h3 class="mb-3">Muat Naik Dokumen</h3>
        
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
            <div class="step active">
                <div class="step-icon">
                    <i class="fas fa-file-upload"></i>
                </div>
                <div class="step-label">Muat Naik Dokumen</div>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="step-label">Pengesahan Maklumat</div>
            </div>
        </div>

        <form action="includes/process_borangWA4.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <?php
            // Debug information
            error_log("Form submission path: " . realpath("../../functions/process_borangWA4.php"));
            error_log("Current script path: " . __FILE__);
            ?>
            <input type="hidden" name="wilayah_asal_id" value="<?php echo htmlspecialchars($_SESSION['wilayah_asal_id']); ?>">
            <!-- Dokumen Sokongan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Dokumen Sokongan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="document-item d-flex align-items-center mb-3">
                                <div class="form-check me-3">
                                    <input class="form-check-input document-check" type="checkbox" id="ic_pegawai" name="documents[]" value="ic_pegawai" required>
                                    <label class="form-check-label" for="ic_pegawai">
                                        Salinan IC Pegawai <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <input type="file" class="form-control document-file" id="ic_pegawai_file" name="ic_pegawai_file" accept=".pdf,.jpg,.jpeg,.png" required>
                                <div class="upload-status ms-3 d-none">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                            </div>

                            <div class="document-item d-flex align-items-center mb-3">
                                <div class="form-check me-3">
                                    <input class="form-check-input document-check" type="checkbox" id="ic_pengikut" name="documents[]" value="ic_pengikut">
                                    <label class="form-check-label" for="ic_pengikut">
                                        Salinan IC Pengikut dan Pasangan (sekiranya berkait)
                                    </label>
                                </div>
                                <input type="file" class="form-control document-file" id="ic_pengikut_file" name="ic_pengikut_file" accept=".pdf,.jpg,.jpeg,.png">
                                <div class="upload-status ms-3 d-none">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                            </div>

                            <div class="document-item d-flex align-items-center mb-3">
                                <div class="form-check me-3">
                                    <input class="form-check-input document-check" type="checkbox" id="dokumen_sokongan" name="documents[]" value="dokumen_sokongan">
                                    <label class="form-check-label" for="dokumen_sokongan">
                                        Dokument Sokongan (Sijil Kelahiran Ibu Bapa Pegawai / Sijil Kematian Ibu Bapa dan Lain-Lain)
                                    </label>
                                </div>
                                <input type="file" class="form-control document-file" id="dokumen_sokongan_file" name="dokumen_sokongan_file" accept=".pdf,.jpg,.jpeg,.png">
                                <div class="upload-status ms-3 d-none">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="borangWA3.php" class="btn btn-secondary">
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
                console.log('Form submitted');
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    // Document upload handling
    document.querySelectorAll('.document-file').forEach(function(input) {
        input.addEventListener('change', function() {
            console.log('File selected:', this.files[0]);
            const checkbox = this.previousElementSibling.querySelector('.document-check');
            const statusIcon = this.nextElementSibling;
            
            if (this.files.length > 0) {
                checkbox.checked = true;
                statusIcon.classList.remove('d-none');
            } else {
                checkbox.checked = false;
                statusIcon.classList.add('d-none');
            }
        });
    });

    // Checkbox handling
    document.querySelectorAll('.document-check').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            console.log('Checkbox changed:', this.checked);
            const fileInput = this.parentElement.nextElementSibling;
            const statusIcon = fileInput.nextElementSibling;
            
            if (!this.checked) {
                fileInput.value = '';
                statusIcon.classList.add('d-none');
            }
        });
    });
</script>
</body>
</html> 