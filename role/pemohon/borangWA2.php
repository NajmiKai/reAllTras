<?php
session_start();
include '../../connection.php';

// Check if user has completed the first form
if (!isset($_SESSION['wilayah_asal_id'])) {
    header("Location: borangWA.php");
    exit();
}

// Set user ID in session if not set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Set default user ID to 1
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

// Check if user has existing data in wilayah_asal
$sql = "SELECT * FROM wilayah_asal WHERE user_kp = ? AND wilayah_asal_matang = false";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_icNo);
$stmt->execute();
$result = $stmt->get_result();
$wilayah_asal_data = $result->fetch_assoc();

if ($wilayah_asal_data) {
    $_SESSION['wilayah_asal_id'] = $wilayah_asal_data['id'];
} else {
    // Clear the session variable if no data found
    unset($_SESSION['wilayah_asal_id']);
}

?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Borang Wilayah Asal (Bahagian 2)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
    <link rel="stylesheet" href="../../assets/css/multi-step.css">
    <style>
        .multi-step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 2rem 0;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .step.completed .step-icon {
            background-color: #d59e3e;
            border-color: #d59e3e;
            color: white;
        }

        .step.active .step-icon {
            background-color: #d59e3e;
            border-color: #d59e3e;
            color: white;
        }

        .step-label {
            font-size: 0.875rem;
            color: #6c757d;
            text-align: center;
        }

        .step.active .step-label {
            color: #d59e3e;
            font-weight: 600;
        }

        .step.completed .step-label {
            color: #d59e3e;
            font-weight: 600;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background-color: #dee2e6;
            margin: 0 1rem;
            position: relative;
            top: -25px;
        }
    </style>
</head>
<body>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Borang Permohonan Wilayah Asal (Bahagian 2)</h3>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Multi-step Indicator -->
        <div class="multi-step-indicator mb-4">
            <div class="step completed">
                <div class="step-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="step-label">Maklumat Pegawai</div>
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="step-label">Maklumat Wilayah Menetap Ibu Bapa</div>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-icon">
                    <i class="fas fa-plane"></i>
                </div>
                <div class="step-label">Maklumat Penerbangan</div>
            </div>
            <div class="step-line"></div>
            <div class="step">
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

        <form action="includes/process_borangWA2.php" method="POST" class="needs-validation" novalidate>
            <!-- Father's Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><i class="fas fa-male me-2"></i>Maklumat Bapa</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Bapa</label>
                            <input type="text" class="form-control" name="nama_bapa" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['nama_bapa'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. KP Bapa</label>
                            <input type="text" class="form-control" name="no_kp_bapa" id="no_kp_bapa" maxlength="14" oninput="formatIC(this)" title="Format: XXXXXX-XX-XXXX" value="<?= htmlspecialchars($wilayah_asal_data['no_kp_bapa'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Wilayah Menetap Bapa</label>
                            <input type="text" class="form-control" name="wilayah_menetap_bapa" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['wilayah_menetap_bapa'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Menetap Bapa</label>
                            <input type="text" class="form-control mb-2" name="alamat_menetap_1_bapa" placeholder="Alamat 1" maxlength="100" value="<?= htmlspecialchars($wilayah_asal_data['alamat_menetap_1_bapa'] ?? '') ?>">
                            <input type="text" class="form-control" name="alamat_menetap_2_bapa" placeholder="Alamat 2" maxlength="100" value="<?= htmlspecialchars($wilayah_asal_data['alamat_menetap_2_bapa'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Poskod</label>
                            <input type="text" class="form-control" name="poskod_menetap_bapa" maxlength="10" pattern="[0-9]{5}" title="5 digit poskod" value="<?= htmlspecialchars($wilayah_asal_data['poskod_menetap_bapa'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bandar</label>
                            <input type="text" class="form-control" name="bandar_menetap_bapa" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['bandar_menetap_bapa'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Negeri</label>
                            <input type="text" class="form-control" name="negeri_menetap_bapa" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['negeri_menetap_bapa'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ibu Negeri/Bandar Dituju</label>
                            <input type="text" class="form-control" name="ibu_negeri_bandar_dituju_bapa" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['ibu_negeri_bandar_dituju_bapa'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mother's Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><i class="fas fa-female me-2"></i>Maklumat Ibu</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" class="form-control" name="nama_ibu" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['nama_ibu'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. KP Ibu</label>
                            <input type="text" class="form-control" name="no_kp_ibu" id="no_kp_ibu" maxlength="14" oninput="formatIC(this)" title="Format: XXXXXX-XX-XXXX" value="<?= htmlspecialchars($wilayah_asal_data['no_kp_ibu'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Wilayah Menetap Ibu</label>
                            <input type="text" class="form-control" name="wilayah_menetap_ibu" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['wilayah_menetap_ibu'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Menetap Ibu</label>
                            <input type="text" class="form-control mb-2" name="alamat_menetap_1_ibu" placeholder="Alamat 1" maxlength="100" value="<?= htmlspecialchars($wilayah_asal_data['alamat_menetap_1_ibu'] ?? '') ?>">
                            <input type="text" class="form-control" name="alamat_menetap_2_ibu" placeholder="Alamat 2" maxlength="100" value="<?= htmlspecialchars($wilayah_asal_data['alamat_menetap_2_ibu'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Poskod</label>
                            <input type="text" class="form-control" name="poskod_menetap_ibu" maxlength="10" pattern="[0-9]{5}" title="5 digit poskod" value="<?= htmlspecialchars($wilayah_asal_data['poskod_menetap_ibu'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bandar</label>
                            <input type="text" class="form-control" name="bandar_menetap_ibu" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['bandar_menetap_ibu'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Negeri</label>
                            <input type="text" class="form-control" name="negeri_menetap_ibu" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['negeri_menetap_ibu'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ibu Negeri/Bandar Dituju</label>
                            <input type="text" class="form-control" name="ibu_negeri_bandar_dituju_ibu" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['ibu_negeri_bandar_dituju_ibu'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="borangWA.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    Seterusnya<i class="fas fa-arrow-right ms-2"></i>
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

    function formatIC(input) {
        // Remove all non-digit characters
        let value = input.value.replace(/\D/g, '');
        
        // Format the value with hyphens
        if (value.length > 0) {
            if (value.length <= 6) {
                value = value;
            } else if (value.length <= 8) {
                value = value.slice(0, 6) + '-' + value.slice(6);
            } else {
                value = value.slice(0, 6) + '-' + value.slice(6, 8) + '-' + value.slice(8, 12);
            }
        }
        
        // Update the display value
        input.value = value;
        
        // Create a hidden input to store the raw value
        let hiddenInput = document.getElementById(input.id + '_raw');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = input.id + '_raw';
            hiddenInput.name = input.name + '_raw';
            input.parentNode.appendChild(hiddenInput);
        }
        hiddenInput.value = input.value.replace(/\D/g, '');
    }

    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });
</script>
</body>
</html> 