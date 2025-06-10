<?php
include '../../connection.php';

$wilayah_asal_id = $_SESSION['wilayah_asal_id'] ?? null;

// Fetch existing documents for this wilayah_asal_id
$documents_sql = "SELECT * FROM documents WHERE wilayah_asal_id = ? AND file_origin = 'pemohon' ORDER BY upload_date DESC";
$documents_stmt = $conn->prepare($documents_sql);
$documents_stmt->bind_param("i", $wilayah_asal_id);
$documents_stmt->execute();
$documents_result = $documents_stmt->get_result();

// Check if user has wilayah_asal record
$check_sql = "SELECT * FROM wilayah_asal WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $wilayah_asal_id);
$check_stmt->execute();
$wilayah_asal_result = $check_stmt->get_result();
$wilayah_asal_data = $wilayah_asal_result->fetch_assoc();

// If no wilayah_asal record exists, redirect to borangWA
if (!$wilayah_asal_data) {
    header("Location: dashboard.php");
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

    <!-- Main Content -->
    <div class="col p-4">
        <form action="includes/process_DikuiriWA4.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <input type="hidden" name="wilayah_asal_id" value="<?php echo htmlspecialchars($_SESSION['wilayah_asal_id']); ?>">
            
            <!-- Dokumen Dikuiri -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Dokumen Dikuiri</h5>
                </div>
                <div class="card-body">
                    <!-- Display existing documents -->
                    <?php if ($documents_result->num_rows > 0): ?>
                    <div class="mb-4">
                        <h6>Dokumen Sedia Ada:</h6>
                        <div class="list-group">
                            <?php while ($doc = $documents_result->fetch_assoc()): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file me-2"></i>
                                        <?php echo htmlspecialchars($doc['file_name']); ?>
                                        <small class="text-muted ms-2">(<?php echo date('d/m/Y H:i', strtotime($doc['upload_date'])); ?>)</small>
                                    </div>
                                    <a href="../../<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div id="dikuiri-container">
                        <div class="document-item">
                            <div class="document-title">
                                <h6 class="mb-0">Dokumen Dikuiri</h6>
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="d-flex">
                                <input type="file" class="form-control" name="dokumen_dikuiri[]" accept=".pdf,.jpg,.jpeg,.png" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm add-more-btn" onclick="addDikuiri()">
                        <i class="fas fa-plus me-2"></i>Tambah Dokumen
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="wilayahAsal.php" class="btn btn-secondary">
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


    // Add more dikuiri
    function addDikuiri() {
        const container = document.getElementById('dikuiri-container');
        const newItem = document.createElement('div');
        newItem.className = 'document-item';
        newItem.innerHTML = `
            <div class="document-title">
                <h6 class="mb-0">Dokumen Dikuiri</h6>
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="d-flex">
                <input type="file" class="form-control" name="dokumen_dikuiri[]" accept=".pdf,.jpg,.jpeg,.png" required>
                <button type="button" class="btn btn-danger ms-2" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(newItem);
    }
</script>
</body>
</html>