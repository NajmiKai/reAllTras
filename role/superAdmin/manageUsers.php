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
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Log the deletion
        $super_admin_id = $_SESSION['super_admin_id'];
        $log_message = "Super Admin deleted user ID: $id";
        $log_stmt = $conn->prepare("INSERT INTO system_logs (admin_id, action, details) VALUES (?, 'Delete User', ?)");
        $log_stmt->bind_param("is", $super_admin_id, $log_message);
        $log_stmt->execute();
        
        echo "<script>alert('Pengguna berjaya dipadam!'); window.location.href='manageUsers.php';</script>";
    } else {
        echo "<script>alert('Ralat: " . $stmt->error . "');</script>";
    }
}

// Fetch all users
$query = "SELECT * FROM user ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Pengurusan Pengguna - ALLTRAS</title>
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
        <a href="manageUsers.php" class="active"><i class="fas fa-users me-2"></i>Pengurusan Pengguna</a>
        <a href="manageApplications.php"><i class="fas fa-file-alt me-2"></i>Pengurusan Permohonan</a>
        <a href="manageDocuments.php"><i class="fas fa-file me-2"></i>Pengurusan Dokumen</a>
        <a href="systemLogs.php"><i class="fas fa-history me-2"></i>Log Sistem</a>
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Pengurusan Pengguna</h3>
            <a href="registerUser.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Pengguna
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>No. KP</th>
                                <th>Emel</th>
                                <th>No. Telefon</th>
                                <th>Bahagian</th>
                                <th>Tarikh Daftar</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['nama_first'] . ' ' . $row['nama_last']) ?></td>
                                <td><?= htmlspecialchars($row['kp']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                <td><?= htmlspecialchars($row['bahagian']) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <a href="editUser.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="viewUser.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
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
        $('#userTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/ms.json'
            }
        });
    });

    // Delete confirmation
    function confirmDelete(id) {
        if (confirm('Adakah anda pasti mahu memadam pengguna ini?')) {
            window.location.href = 'manageUsers.php?delete=' + id;
        }
    }
</script>
</body>
</html> 