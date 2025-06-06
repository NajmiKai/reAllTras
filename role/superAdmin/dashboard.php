<?php
session_start();
include '../../connection.php';

// Check if user is logged in
if (!isset($_SESSION['super_admin_id'])) {
    header("Location: ../../loginSuperAdmin.php");
    exit();
}

// Set session timeout duration (in seconds)
$timeout_duration = 900; // 900 seconds = 15 minutes

// Check if the timeout is set and whether it has expired
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Session expired
    session_unset();
    session_destroy();
    header("Location: /reAllTras/loginSuperAdmin.php?timeout=1");
    exit();
}
// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();

$super_admin_id = $_SESSION['super_admin_id'];
$super_admin_name = $_SESSION['super_admin_name'];
$super_admin_icNo = $_SESSION['super_admin_icNo'];
$super_admin_email = $_SESSION['super_admin_email'];
$super_admin_phoneNo = $_SESSION['super_admin_phoneNo'];

// Function to count total admins
function countTotalAdmins($conn) {
    $query = "SELECT COUNT(*) AS total FROM admin";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Function to count total users
function countTotalUsers($conn) {
    $query = "SELECT COUNT(*) AS total FROM user";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Function to count total applications
function countTotalApplications($conn) {
    $query = "SELECT COUNT(*) AS total FROM wilayah_asal";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Function to count pending applications
function countPendingApplications($conn) {
    $query = "SELECT COUNT(*) AS total FROM wilayah_asal WHERE status = 'Menunggu pengesahan PBR CSM' OR status = 'Menunggu pengesahan PBR2 CSM' OR status IS NULL";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}

$stats = [
    'total_admins' => countTotalAdmins($conn),
    'total_users' => countTotalUsers($conn),
    'total_applications' => countTotalApplications($conn),
    'pending_applications' => countPendingApplications($conn)
];

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Super Admin Dashboard</title>
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
        <a href="dashboard.php" class="active"><i class="fas fa-home me-2"></i>Laman Utama</a>
        <h6 class="text mt-4">PENGURUSAN SISTEM</h6>
        <a href="manageAdmins.php"><i class="fas fa-users-cog me-2"></i>Pengurusan Admin</a>
        <a href="manageUsers.php"><i class="fas fa-users me-2"></i>Pengurusan Pengguna</a>
        <a href="systemLogs.php"><i class="fas fa-history me-2"></i>Log Sistem</a>
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Laman Utama Super Admin</h3>

        <div class="greeting-box">
            <?php  
                date_default_timezone_set('Asia/Kuala_Lumpur');
                $time = date('H');
                if ($time < 12) {
                    $greeting = 'Selamat Pagi';
                } elseif ($time < 15) {
                    $greeting = 'Selamat Tengah Hari';
                } elseif ($time < 19) {
                    $greeting = 'Selamat Petang';    
                } else {
                    $greeting = 'Selamat Malam';
                }
            ?>
            <strong>Hi, <?= $greeting ?>!</strong> <?= $super_admin_name ?>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card-box bg-primary">
                    <i class="fas fa-users-cog"></i>
                    <h6>Jumlah Admin</h6>
                    <p><?= $stats['total_admins'] ?> Admin</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box bg-success">
                    <i class="fas fa-users"></i>
                    <h6>Jumlah Pengguna</h6>
                    <p><?= $stats['total_users'] ?> Pengguna</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box bg-warning">
                    <i class="fas fa-file-alt"></i>
                    <h6>Jumlah Permohonan</h6>
                    <p><?= $stats['total_applications'] ?> Permohonan</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box bg-danger">
                    <i class="fas fa-clock"></i>
                    <h6>Permohonan Tertunggak</h6>
                    <p><?= $stats['pending_applications'] ?> Permohonan</p>
                </div>
            </div>
        </div>

        <div class="container my-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Carta Aliran Permohonan</h5>
                            <h6 class="text-primary mb-3">Wilayah Asal</h6>
                            <img src="../../assets/flowchart-wilayah.jpg" alt="Carta Aliran Wilayah Asal" class="img-fluid rounded clickable-image" style="cursor:pointer;">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Carta Aliran Permohonan</h5>
                            <h6 class="text-danger mb-3">Tugas Rasmi / Kursus</h6>
                            <img src="../../assets/flowchart-tugasrasmi.jpg" alt="Carta Aliran Tugas Rasmi" class="img-fluid rounded clickable-image" style="cursor:pointer;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <img src="" id="modalImage" class="img-fluid rounded" alt="Expanded Image">
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
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

    // Image Modal
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    const modalImage = document.getElementById('modalImage');

    document.querySelectorAll('.clickable-image').forEach(img => {
        img.addEventListener('click', () => {
            modalImage.src = img.src;
            imageModal.show();
        });
    });
</script>
</body>
</html> 