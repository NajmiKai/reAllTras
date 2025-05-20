<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];
$admin_role = $_SESSION['admin_role'];
$admin_icNo = $_SESSION['admin_icNo'];
$admin_email = $_SESSION['admin_email'];
$admin_phoneNo = $_SESSION['admin_phoneNo'];

// Get wilayah_asal_id from URL parameter
$wilayah_asal_id = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch application details if ID is provided
$application_details = null;
if ($wilayah_asal_id) {
    $stmt = $conn->prepare("SELECT wa.*, u.nama_first, u.nama_last, u.jawatan_gred 
                           FROM wilayah_asal wa 
                           JOIN user u ON wa.user_kp = u.kp 
                           WHERE wa.id = ?");
    $stmt->bind_param("i", $wilayah_asal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $application_details = $result->fetch_assoc();
    }
    $stmt->close();
}

// Fetch existing documents
$documents = [];
if ($wilayah_asal_id) {
    $stmt = $conn->prepare("SELECT * FROM documents WHERE wilayah_asal_id = ? ORDER BY upload_date DESC");
    $stmt->bind_param("i", $wilayah_asal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $documents[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Muat Naik Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/adminStyle.css">
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand navbar-light bg-light shadow-sm px-3 mb-4 w-100">
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link" href="role/csm/pbr/wilayahAsal.php">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <span class="nav-link fw-semibold"><?= htmlspecialchars($admin_name) ?> (<?= htmlspecialchars($admin_role) ?>)</span>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="logout.php" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt me-1"></i> Log Keluar
                </a>
            </li>
        </ul>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Muat Naik Dokumen</h4>
                        
                        <?php if ($application_details): ?>
                            <div class="alert alert-info">
                                <h5 class="alert-heading">Maklumat Permohonan</h5>
                                <p class="mb-1"><strong>Nama:</strong> <?= htmlspecialchars($application_details['nama_first'] . ' ' . $application_details['nama_last']) ?></p>
                                <p class="mb-1"><strong>Jawatan:</strong> <?= htmlspecialchars($application_details['jawatan_gred']) ?></p>
                                <p class="mb-0"><strong>ID Permohonan:</strong> <?= htmlspecialchars($wilayah_asal_id) ?></p>
                            </div>
                        <?php endif; ?>

                        <!-- Document Upload Section -->
                        <div class="upload-container">
                            <input type="hidden" id="wilayah_asal_id" value="<?= htmlspecialchars($wilayah_asal_id) ?>">
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Penerangan Dokumen</label>
                                <textarea class="form-control" id="description" rows="2" placeholder="Masukkan penerangan dokumen (pilihan)"></textarea>
                            </div>

                            <div id="dropZone" class="drop-zone">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <p class="mb-0">Seret dan lepaskan fail di sini atau klik untuk memilih fail</p>
                                <input type="file" id="fileInput" multiple style="display: none;" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            </div>

                            <div id="fileList" class="mt-3"></div>

                            <button id="uploadButton" class="btn btn-primary mt-3">
                                <i class="fas fa-upload me-2"></i>Muat Naik Fail
                            </button>
                        </div>

                        <!-- Existing Documents Section -->
                        <?php if (!empty($documents)): ?>
                            <div class="mt-4">
                                <h5>Dokumen Sedia Ada</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nama Fail</th>
                                                <th>Saiz</th>
                                                <th>Tarikh Muat Naik</th>
                                                <th>Penerangan</th>
                                                <th>Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($documents as $doc): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($doc['file_name']) ?></td>
                                                    <td><?= number_format($doc['file_size'] / 1024, 2) ?> KB</td>
                                                    <td><?= date('d/m/Y H:i', strtotime($doc['upload_date'])) ?></td>
                                                    <td><?= htmlspecialchars($doc['description']) ?></td>
                                                    <td>
                                                        <a href="<?= htmlspecialchars($doc['file_path']) ?>" class="btn btn-sm btn-info" target="_blank">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-danger" onclick="deleteDocument(<?= $doc['id'] ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/document-upload.js"></script>
    <script>
        function deleteDocument(docId) {
            if (confirm('Adakah anda pasti mahu memadamkan dokumen ini?')) {
                fetch('functions/delete_document.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'doc_id=' + docId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Ralat: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Ralat: ' + error.message);
                });
            }
        }
    </script>
</body>
</html> 