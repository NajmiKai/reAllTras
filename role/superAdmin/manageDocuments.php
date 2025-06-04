<?php
session_start();
include '../../connection.php';

// Check if user is logged in as Super Admin
if (!isset($_SESSION['super_admin_id'])) {
    header("Location: ../../loginSuperAdmin.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Get file path before deleting
    $stmt = $conn->prepare("SELECT file_path FROM documents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $file = $result->fetch_assoc();
    
    // Delete from database
    $stmt = $conn->prepare("DELETE FROM documents WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Delete physical file
        if (file_exists($file['file_path'])) {
            unlink($file['file_path']);
        }
        
        // Log the deletion
        $super_admin_id = $_SESSION['super_admin_id'];
        $log_message = "Super Admin deleted document ID: $id";
        $log_stmt = $conn->prepare("INSERT INTO system_logs (admin_id, action, details) VALUES (?, 'Delete Document', ?)");
        $log_stmt->bind_param("is", $super_admin_id, $log_message);
        $log_stmt->execute();
        
        echo "<script>alert('Dokumen berjaya dipadam!'); window.location.href='manageDocuments.php';</script>";
    } else {
        echo "<script>alert('Ralat: " . $stmt->error . "');</script>";
    }
}

// Fetch all documents with related information
$query = "SELECT d.*, u.nama_first, u.nama_last, wa.id as application_id 
          FROM documents d 
          LEFT JOIN user u ON d.file_origin_id = u.kp 
          LEFT JOIN wilayah_asal wa ON d.wilayah_asal_id = wa.id 
          ORDER BY d.id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Pengurusan Dokumen - ALLTRAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/adminStyle.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../../assets/css/adminLayout.css">
</head>
<body>

<div class="main-container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h6><img src="../../assets/ALLTRAS.png" alt="ALLTRAS" width="140" style="margin-left: 20px;"><br>ALL REGION TRAVELLING SYSTEM</h6><br>
        <a href="dashboard.php"><i class="fas fa-home me-2"></i>Laman Utama</a>
        <h6 class="text mt-4">PENGURUSAN SISTEM</h6>
        <a href="manageAdmins.php"><i class="fas fa-users-cog me-2"></i>Pengurusan Admin</a>
        <a href="manageUsers.php"><i class="fas fa-users me-2"></i>Pengurusan Pengguna</a>
        <a href="manageApplications.php"><i class="fas fa-file-alt me-2"></i>Pengurusan Permohonan</a>
        <a href="manageDocuments.php" class="active"><i class="fas fa-file me-2"></i>Pengurusan Dokumen</a>
        <a href="systemLogs.php"><i class="fas fa-history me-2"></i>Log Sistem</a>
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Pengurusan Dokumen</h3>
            <div>
                <a href="uploadDocument.php" class="btn btn-primary">
                    <i class="fas fa-upload me-2"></i>Muat Naik Dokumen
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="documentTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Fail</th>
                                <th>Jenis Fail</th>
                                <th>Saiz</th>
                                <th>Pemohon</th>
                                <th>ID Permohonan</th>
                                <th>Asal</th>
                                <th>Tarikh Muat Naik</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['file_name']) ?></td>
                                <td><?= htmlspecialchars($row['file_type']) ?></td>
                                <td><?= formatFileSize($row['file_size']) ?></td>
                                <td><?= htmlspecialchars($row['nama_first'] . ' ' . $row['nama_last']) ?></td>
                                <td>
                                    <?php if ($row['application_id']): ?>
                                        <a href="viewApplication.php?id=<?= $row['application_id'] ?>">
                                            <?= $row['application_id'] ?>
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['file_origin']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($row['upload_date'])) ?></td>
                                <td>
                                    <a href="<?= $row['file_path'] ?>" class="btn btn-sm btn-info" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= $row['file_path'] ?>" class="btn btn-sm btn-success" download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete(<?= $row['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });

    // Initialize DataTable
    $(document).ready(function() {
        $('#documentTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/ms.json'
            }
        });
    });

    // Delete confirmation
    function confirmDelete(id) {
        if (confirm('Adakah anda pasti mahu memadam dokumen ini?')) {
            window.location.href = 'manageDocuments.php?delete=' + id;
        }
    }
</script>

<?php
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}
?>
</body>
</html> 