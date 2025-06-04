<?php
session_start();
include '../../connection.php';

// Check if user is logged in
if (!isset($_SESSION['super_admin_id'])) {
    header("Location: ../../loginSuperAdmin.php");
    exit();
}

// Set session timeout duration (in seconds)
$timeout_duration = 900; // 15 minutes

// Check if the timeout is set and whether it has expired
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: /reAllTras/loginSuperAdmin.php?timeout=1");
    exit();
}
// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();

$super_admin_id = $_SESSION['super_admin_id'];
$super_admin_name = $_SESSION['super_admin_name'];

// Fetch system logs with pagination
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Get total records for pagination
$total_records_query = "SELECT COUNT(*) as count FROM system_logs";
$total_records_result = $conn->query($total_records_query);
$total_records = $total_records_result->fetch_assoc()['count'];
$total_pages = ceil($total_records / $records_per_page);

// Fetch logs with pagination
$query = "SELECT * FROM system_logs ORDER BY timestamp DESC LIMIT $offset, $records_per_page";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Log Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/adminStyle.css">
    <link rel="stylesheet" href="../../assets/css/adminLayout.css">
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
            <span class="nav-link fw-semibold"><?= htmlspecialchars($super_admin_name) ?> (Super Admin)</span>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../logout.php" class="nav-link text-danger">
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
        <h6 class="text mt-4">PENGURUSAN SISTEM</h6>
        <a href="manageAdmins.php"><i class="fas fa-users-cog me-2"></i>Pengurusan Admin</a>
        <a href="manageUsers.php"><i class="fas fa-users me-2"></i>Pengurusan Pengguna</a>
        <a href="systemLogs.php" class="active"><i class="fas fa-history me-2"></i>Log Sistem</a>
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Log Sistem</h3>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari log...">
                    <button class="btn btn-primary" onclick="exportLogs()">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tarikh & Masa</th>
                                <th>Pengguna</th>
                                <th>Aktiviti</th>
                                <th>IP Address</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i:s', strtotime($row['timestamp'])) ?></td>
                                <td><?= htmlspecialchars($row['user']) ?></td>
                                <td><?= htmlspecialchars($row['activity']) ?></td>
                                <td><?= htmlspecialchars($row['ip_address']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $row['status'] === 'success' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
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

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });

    // Export functionality
    function exportLogs() {
        // Implement export functionality here
        alert('Export functionality will be implemented here');
    }
</script>
</body>
</html> 