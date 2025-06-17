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

// Fetch existing documents if wilayah_asal_from_stage is BorangWA5
$existing_documents = [];
if (isset($_SESSION['wilayah_asal_id'])) {
    $sql = "SELECT d.*, w.wilayah_asal_from_stage 
            FROM documents d 
            JOIN wilayah_asal w ON d.wilayah_asal_id = w.id 
            WHERE d.wilayah_asal_id = ? AND d.file_origin = 'pemohon'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['wilayah_asal_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $existing_documents[$row['description']] = $row;
    }
}

// Check if we're in edit mode
$is_edit_mode = false;
if (isset($_SESSION['wilayah_asal_id'])) {
    $sql = "SELECT wilayah_asal_from_stage FROM wilayah_asal WHERE id = ? AND wilayah_asal_matang = false";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['wilayah_asal_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $is_edit_mode = ($row['wilayah_asal_from_stage'] === 'BorangWA5');
    }
}
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
        .file-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .view-file-btn {
            color: #0d6efd;
            text-decoration: none;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .view-file-btn:hover {
            text-decoration: underline;
        }
        .view-file-btn i {
            font-size: 1rem;
        }
    </style>
</head>
<body>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

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
                            <?php if (isset($existing_documents['Dokumen Pegawai'])): ?>
                                <i class="fas fa-check-circle uploaded"></i>
                                <div class="mt-2">
                                    <small class="text-muted">Current file: <?php echo htmlspecialchars($existing_documents['Dokumen Pegawai']['file_name']); ?></small>
                                    <div class="file-actions">
                                        <a href="<?= getUploadPath(str_replace('../../../uploads/', '', htmlspecialchars($existing_documents['Dokumen Pegawai']['file_path']))) ?>" target="_blank" class="view-file-btn">
                                            <i class="fa-solid fa-eye"></i>View File
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center">
                            <input type="file" class="form-control" name="dokumen_pegawai" accept=".pdf,.jpg,.jpeg,.png" <?php echo (!$is_edit_mode && !isset($existing_documents['Dokumen Pegawai'])) ? 'required' : ''; ?>>
                            <?php if (isset($existing_documents['Dokumen Pegawai'])): ?>
                                <span class="ms-2 text-muted"><?php echo htmlspecialchars($existing_documents['Dokumen Pegawai']['file_name']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="document-item mt-3">
                        <div class="document-title">
                            <h6 class="mb-0">Lampiran II</h6>
                            <?php if (isset($existing_documents['Lampiran II'])): ?>
                                <i class="fas fa-check-circle uploaded"></i>
                                <div class="mt-2">
                                    <small class="text-muted">Current file: <?php echo htmlspecialchars($existing_documents['Lampiran II']['file_name']); ?></small>
                                    <div class="file-actions">
                                        <a href="<?= getUploadPath(str_replace('../../../uploads/', '', htmlspecialchars($existing_documents['Lampiran II']['file_path']))) ?>" target="_blank" class="view-file-btn">
                                            <i class="fa-solid fa-eye"></i>View File
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center">
                            <input type="file" class="form-control" name="lampiran_ii" accept=".pdf,.jpg,.jpeg,.png" <?php echo (!$is_edit_mode && !isset($existing_documents['Lampiran II'])) ? 'required' : ''; ?>>
                            <?php if (isset($existing_documents['Lampiran II'])): ?>
                                <span class="ms-2 text-muted"><?php echo htmlspecialchars($existing_documents['Lampiran II']['file_name']); ?></span>
                            <?php endif; ?>
                        </div>
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
                            <?php if (isset($existing_documents['Dokumen Pasangan'])): ?>
                                <i class="fas fa-check-circle uploaded"></i>
                                <div class="mt-2">
                                    <small class="text-muted">Current file: <?php echo htmlspecialchars($existing_documents['Dokumen Pasangan']['file_name']); ?></small>
                                    <div class="file-actions">
                                        <a href="<?= getUploadPath(str_replace('../../../uploads/', '', htmlspecialchars($existing_documents['Dokumen Pasangan']['file_path']))) ?>" target="_blank" class="view-file-btn">
                                            <i class="fa-solid fa-eye"></i>View File
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center">
                            <input type="file" class="form-control" name="dokumen_pasangan" accept=".pdf,.jpg,.jpeg,.png">
                            <?php if (isset($existing_documents['Dokumen Pasangan'])): ?>
                                <span class="ms-2 text-muted"><?php echo htmlspecialchars($existing_documents['Dokumen Pasangan']['file_name']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="document-item mt-3">
                        <div class="document-title">
                            <h6 class="mb-0">Sijil Perkahwinan / Kad Kahwin</h6>
                            <?php if (isset($existing_documents['Sijil Perkahwinan'])): ?>
                                <i class="fas fa-check-circle uploaded"></i>
                                <div class="mt-2">
                                    <small class="text-muted">Current file: <?php echo htmlspecialchars($existing_documents['Sijil Perkahwinan']['file_name']); ?></small>
                                    <div class="file-actions">
                                        <a href="<?= getUploadPath(str_replace('../../../uploads/', '', htmlspecialchars($existing_documents['Sijil Perkahwinan']['file_path']))) ?>" target="_blank" class="view-file-btn">
                                            <i class="fa-solid fa-eye"></i>View File
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex align-items-center">
                            <input type="file" class="form-control" name="sijil_perkahwinan" accept=".pdf,.jpg,.jpeg,.png">
                            <?php if (isset($existing_documents['Sijil Perkahwinan'])): ?>
                                <span class="ms-2 text-muted"><?php echo htmlspecialchars($existing_documents['Sijil Perkahwinan']['file_name']); ?></span>
                            <?php endif; ?>
                        </div>
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
                        <?php
                        // Display existing pengikut documents
                        $pengikut_count = 1;
                        $pengikut_docs = array_filter($existing_documents, function($doc) {
                            return strpos($doc['description'], 'Dokumen Pengikut') !== false;
                        });
                        
                        if (!empty($pengikut_docs)) {
                            foreach ($pengikut_docs as $doc) {
                                $doc_number = preg_replace('/[^0-9]/', '', $doc['description']);
                                echo '<div class="document-item">
                                    <div class="document-title">
                                        <h6 class="mb-0">Salinan IC Pengikut / Sijil Kelahiran Pengikut #' . $doc_number . '</h6>
                                        <i class="fas fa-check-circle uploaded"></i>
                                        <div class="mt-2">
                                            <small class="text-muted">Current file: ' . htmlspecialchars($doc['file_name']) . '</small>
                                            <div class="file-actions">
                                                <a href="' . getUploadPath(str_replace('../../../uploads/', '', htmlspecialchars($doc['file_path']))) . '" target="_blank" class="view-file-btn">
                                                    <i class="fa-solid fa-eye"></i>View File
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <input type="file" class="form-control" name="dokumen_pengikut[]" accept=".pdf,.jpg,.jpeg,.png">
                                        <span class="ms-2 text-muted">' . htmlspecialchars($doc['file_name']) . '</span>
                                        <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.parentElement.remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>';
                            }
                        } else {
                            // If no existing documents, show default input
                            echo '<div class="document-item">
                                <div class="document-title">
                                    <h6 class="mb-0">Salinan IC Pengikut / Sijil Kelahiran Pengikut</h6>
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <input type="file" class="form-control" name="dokumen_pengikut[]" accept=".pdf,.jpg,.jpeg,.png">
                            </div>';
                        }
                        ?>
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
                        <?php
                        // Display existing sokongan documents
                        $sokongan_count = 1;
                        foreach ($existing_documents as $doc) {
                            if (strpos($doc['description'], 'Dokumen Sokongan') !== false) {
                                echo '<div class="document-item">
                                    <div class="document-title">
                                        <h6 class="mb-0">' . htmlspecialchars($doc['description']) . '</h6>
                                        <i class="fas fa-check-circle uploaded"></i>
                                        <div class="mt-2">
                                            <small class="text-muted">Current file: ' . htmlspecialchars($doc['file_name']) . '</small>
                                            <div class="file-actions">
                                                <a href="' . getUploadPath(str_replace('../../../uploads/', '', htmlspecialchars($doc['file_path']))) . '" target="_blank" class="view-file-btn">
                                                    <i class="fa-solid fa-eye"></i>View File
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <input type="file" class="form-control" name="dokumen_sokongan[]" accept=".pdf,.jpg,.jpeg,.png">
                                        <span class="ms-2 text-muted">' . htmlspecialchars($doc['file_name']) . '</span>
                                        <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.parentElement.remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>';
                                $sokongan_count++;
                            }
                        }
                        // If no existing documents, show default inputs
                        if ($sokongan_count === 1) {
                            echo '<div class="document-item">
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
                            </div>';
                        }
                        ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm add-more-btn" onclick="addSokongan()">
                        <i class="fas fa-plus me-2"></i>Tambah Dokumen Sokongan
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="borangWA3.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-success" name="submit">
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

    // Function to view uploaded files
    function viewFile(filePath) {
        const url = '<?= getBasePath() ?>/' + filePath;
        window.open(url, '_blank');
    }
</script>
</body>
</html> 