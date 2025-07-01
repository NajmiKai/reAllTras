<?php
session_start();
include '../../../connection.php';


// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Set session timeout duration (in seconds)
$timeout_duration = 900; // 900 seconds = 15 minutes

// Check if the timeout is set and whether it has expired
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Session expired
    session_unset();
    session_destroy();
    header("Location: /reAllTras/login.php?timeout=1");
    exit();
}
// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();

$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'];
$admin_role = $_SESSION['admin_role'];
$admin_icNo = $_SESSION['admin_icNo'];
$admin_email = $_SESSION['admin_email'];
$admin_phoneNo = $_SESSION['admin_phoneNo'];



// Function to count rows by table and status
function countByStatus($conn, $table, $admin_id, $status = 'total') {
    if ($status === 'total') {
        // Count all rows for this admin_id without status filter
        $query = "SELECT COUNT(*) AS jumlah FROM $table WHERE pbr_csm1_id = ? OR pbr_csm2_id = ? OR status = 'Menunggu pengesahan PBR CSM' OR status = 'Menunggu pengesahan PBR2 CSM' OR status = 'Kembali ke PBR CSM'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $admin_id, $admin_id);
    } elseif ($status === 'Sedang diproses') {
        $query = "SELECT COUNT(*) AS jumlah FROM $table WHERE status = 'Menunggu pengesahan PBR CSM' OR status = 'Menunggu pengesahan PBR2 CSM'";
        $stmt = $conn->prepare($query);
    } elseif ($status === 'Berjaya diproses') {
        $query = "SELECT COUNT(*) AS jumlah FROM $table WHERE pbr_csm1_id = ? OR pbr_csm2_id = ? ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $admin_id, $admin_id);
    } elseif ($status === 'Dikuiri') {
        $query = "SELECT COUNT(*) AS jumlah FROM $table WHERE status = 'Kembali ke PBR CSM'";
        $stmt = $conn->prepare($query);
    } else {
        return 0;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return (int)$row['jumlah'];
}


$stats = [
    'total' => [],
    'processing' => [],
    'approved' => [],
    'rejected' => []
];

// Fill the counts for Wilayah Asal
$stats['total']['Wilayah Asal'] = countByStatus($conn, 'wilayah_asal', $admin_id, 'total');
$stats['processing']['Wilayah Asal'] = countByStatus($conn, 'wilayah_asal', $admin_id, 'Sedang diproses');
$stats['approved']['Wilayah Asal'] = countByStatus($conn, 'wilayah_asal', $admin_id, 'Berjaya diproses');
$stats['rejected']['Wilayah Asal'] = countByStatus($conn, 'wilayah_asal', $admin_id, 'Dikuiri');

$currentPage = basename($_SERVER['PHP_SELF']);
$submenuOpen = in_array($currentPage, ['permohonanPengguna.php', 'permohonanIbuPejabat.php', 'permohonanDikuiri.php']);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/adminStyle.css">
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
            <span class="nav-link fw-semibold"><?= htmlspecialchars($admin_name) ?> (<?= htmlspecialchars($admin_role) ?>)</span>
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
        <h6><img src="../../../assets/ALLTRAS.png" alt="ALLTRAS" width="140" style="margin-left: 20px;"><br>ALL REGION TRAVELLING SYSTEM</h6><br>
        <a href="dashboard.php" class="active"> <i class="fas fa-home me-2"></i>Laman Utama</a>
        <h6 class="text mt-4">BORANG PERMOHONAN</h6>

        <a href="javascript:void(0);" onclick="toggleSubMenu1()" class="<?= $submenuOpen ? 'active' : '' ?>">
            <i class="fas fa-map-marker-alt me-2"></i>Wilayah Asal
            <i class="fas fa-chevron-down" style="float: right; margin-right: 10px;"></i>
        </a>
    
        
        <!-- Submenu -->
        <div id="wilayahSubmenu" class="submenu" style="display: <?= $submenuOpen ? 'block' : 'none' ?>;">
            <a href="permohonanPengguna.php">Semakan Permohonan</a>
            <a href="permohonanIbuPejabat.php">Buku Rekod</a>
            <a href="permohonanDikuiri.php">Permohonan Dikuiri</a>
        </div>

        <!-- <a href="tugasRasmi.php"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a> -->
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>


    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Laman Utama</h3>

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
            <strong>Hi, <?= $greeting ?>!</strong> <?= $admin_name ?>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
            <a href="wilayahAsalList.php?status=total" class="text-decoration-none text-white">
                <div class="card-box bg-primary">
                    <i class="fas fa-user-plus"></i>
                    <h6>Jumlah Permohonan</h6>
                    <p>Wilayah Asal: <?= $stats['total']['Wilayah Asal'] ?></p>
                </div></a>
            </div>
            <div class="col-md-3">
            <a href="wilayahAsalList.php?status=processing" class="text-decoration-none text-white">
                <div class="card-box bg-success">
                    <i class="fas fa-spinner"></i>
                    <h6>Sedang Diproses</h6>
                    <p>Wilayah Asal: <?= $stats['processing']['Wilayah Asal'] ?></p>
                </div></a>
            </div>
            <div class="col-md-3">
            <a href="wilayahAsalList.php?status=approved" class="text-decoration-none text-white">
                <div class="card-box bg-warning">
                    <i class="fas fa-check-circle"></i>
                    <h6>Berjaya Diproses</h6>
                    <p>Wilayah Asal: <?= $stats['approved']['Wilayah Asal'] ?></p>
                </div></a>
            </div>
            <div class="col-md-3">
            <a href="wilayahAsalList.php?status=rejected" class="text-decoration-none text-white">
                <div class="card-box bg-danger">
                    <i class="fas fa-times-circle"></i>
                    <h6>Permohonan Dikuiri</h6>
                    <p>Wilayah Asal: <?= $stats['rejected']['Wilayah Asal'] ?></p>
                </div></a>
            </div>
        </div>

        <div class="container my-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Carta Aliran Permohonan</h5>
                            <h6 class="text-primary mb-3">Wilayah Asal</h6>
                            <img src="../../../assets/flowchart-wilayah.jpg" alt="Carta Aliran Wilayah Asal" class="img-fluid rounded clickable-image" style="cursor:pointer;">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Carta Aliran Permohonan</h5>
                            <h6 class="text-danger mb-3">Tugas Rasmi / Kursus</h6>
                            <img src="../../../assets/flowchart-tugasrasmi.jpg" alt="Carta Aliran Wilayah Asal" class="img-fluid rounded clickable-image" style="cursor:pointer;">
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

    function toggleSubMenu1() {
        const submenu = document.getElementById("wilayahSubmenu");
        submenu.style.display = submenu.style.display === "block" ? "none" : "block";
    }

    function toggleSubMenu(event) {
        event.preventDefault();
        const submenu = event.target.closest('.sidebar-link').nextElementSibling;
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    }
</script>
</body>
</html>
