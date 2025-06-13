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
    <style>
        .document-section {
            margin-bottom: 2rem;
        }
        .document-item {
            margin-bottom: 1rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        .document-title {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .document-title i {
            margin-left: 0.5rem;
            color: #28a745;
            display: none;
        }
        .document-title i.uploaded {
            display: inline-block;
        }
        .upload-list {
            margin-top: 0.5rem;
        }
        .upload-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.25rem;
        }
        .upload-item .remove-upload {
            margin-left: auto;
            color: #dc3545;
            cursor: pointer;
        }
        .add-more-btn {
            margin-top: 0.5rem;
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
            <input type="hidden" name="wilayah_asal_id" value="<?php echo htmlspecialchars($_SESSION['wilayah_asal_id']); ?>">
            
            <!-- Dokumen Pegawai -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Dokumen Pegawai <span class="text-danger">*</span></h5>
                </div>
                <div class="card-body">
                    <div class="document-item">
                        <div class="document-title">
                            <h6 class="mb-0">Salinan IC Pegawai / Sijil Kelahiran Pegawai</h6>
                            <i class="fas fa-check-circle uploaded"></i>
                        </div>
                        <input type="file" class="form-control" name="dokumen_pegawai" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <div class="document-item mt-3">
                        <div class="document-title">
                            <h6 class="mb-0">Lampiran II</h6>
                            <i class="fas fa-check-circle uploaded"></i>
                        </div>
                        <input type="file" class="form-control" name="lampiran_ii" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                </div>
            </div>

            <!-- Dokumen Pasangan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Dokumen Pasangan <span style="font-size: 0.9em; font-style: italic; color: #666;">(Perlu dimuat naik yang berkenaan sahaja.)</span></h5>
                </div>
                <div class="card-body">
                    <div class="document-item">
                        <div class="document-title">
                            <h6 class="mb-0">Salinan IC Pasangan / Sijil Kelahiran Pasangan</h6>
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <input type="file" class="form-control" name="dokumen_pasangan" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    <div class="document-item mt-3">
                        <div class="document-title">
                            <h6 class="mb-0">Sijil Perkahwinan / Kad Kahwin</h6>
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <input type="file" class="form-control" name="sijil_perkahwinan" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
            </div>

            <!-- Dokumen Pengikut -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Dokumen Pengikut</h5>
                </div>
                <div class="card-body">
                    <div id="pengikut-container">
                        <div class="document-item">
                            <div class="document-title">
                                <h6 class="mb-0">Salinan IC Pengikut / Sijil Kelahiran Pengikut</h6>
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <input type="file" class="form-control" name="dokumen_pengikut[]" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm add-more-btn" onclick="addPengikut()">
                        <i class="fas fa-plus me-2"></i>Tambah Pengikut
                    </button>
                </div>
            </div>

            <!-- Dokumen Sokongan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Dokumen Sokongan</h5>
                </div>
                <div class="card-body">
                    <div id="sokongan-container">
                        <div class="document-item">
                            <div class="document-title">
                                <h6 class="mb-0">Dokumen Pengesahan Ayah</h6>
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <input type="file" class="form-control" name="dokumen_sokongan[]" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="document-item mt-3">
                            <div class="document-title">
                                <h6 class="mb-0">Dokumen Pengesahan Ibu</h6>
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <input type="file" class="form-control" name="dokumen_sokongan[]" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm add-more-btn" onclick="addSokongan()">
                        <i class="fas fa-plus me-2"></i>Tambah Dokumen Sokongan
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="javascript:void(0)" onclick="goBack()" class="btn btn-secondary">
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

    // File upload handling
    document.querySelectorAll('input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function() {
            const checkIcon = this.parentElement.querySelector('.fa-check-circle');
            if (this.files.length > 0) {
                checkIcon.classList.add('uploaded');
            } else {
                checkIcon.classList.remove('uploaded');
            }
        });
    });

    // Add more pengikut
    function addPengikut() {
        const container = document.getElementById('pengikut-container');
        const newItem = document.createElement('div');
        newItem.className = 'document-item';
        newItem.innerHTML = `
            <div class="document-title">
                <h6 class="mb-0">Salinan IC Pengikut / Sijil Kelahiran Pengikut</h6>
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="d-flex">
                <input type="file" class="form-control" name="dokumen_pengikut[]" accept=".pdf,.jpg,.jpeg,.png">
                <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(newItem);
    }

    // Add more sokongan
    function addSokongan() {
        const container = document.getElementById('sokongan-container');
        const newItem = document.createElement('div');
        newItem.className = 'document-item';
        newItem.innerHTML = `
            <div class="document-title">
                <h6 class="mb-0">Dokumen Sokongan</h6>
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="d-flex">
                <input type="file" class="form-control" name="dokumen_sokongan[]" accept=".pdf,.jpg,.jpeg,.png">
                <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(newItem);
    }

    function goBack() {
        // Get the wilayah_asal_id from the form
        const wilayahAsalId = document.querySelector('input[name="wilayah_asal_id"]').value;
        
        // Create a form to submit the data
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'borangWA3.php';
        
        // Add wilayah_asal_id
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'wilayah_asal_id';
        idInput.value = wilayahAsalId;
        form.appendChild(idInput);
        
        // Add all form data
        const formData = new FormData(document.querySelector('form'));
        for (let [key, value] of formData.entries()) {
            if (key !== 'wilayah_asal_id') {  // Skip wilayah_asal_id as we already added it
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
        }
        
        document.body.appendChild(form);
        form.submit();
    }
</script>
</body>
</html> 