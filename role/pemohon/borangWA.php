<?php
session_start();
include '../../connection.php';

//if (!isset($_SESSION['admin_id'])) {
//    header("Location: login.php");
//    exit();
//}

$admin_name = 'najmi';
$admin_role = 'pemohon';
$admin_icNo = '1234567890';
$admin_email = 'najmi@gmail.com';
$admin_phoneNo = '0123456789';

$stats = [
    "total" => ["Wilayah Asal" => 22, "Tugas Rasmi" => 12],
    "processing" => ["Wilayah Asal" => 7, "Tugas Rasmi" => 12],
    "approved" => ["Wilayah Asal" => 14, "Tugas Rasmi" => 0],
    "rejected" => ["Wilayah Asal" => 14, "Tugas Rasmi" => 0]
];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/adminStyle.css">
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
            <h6><img src="../../assets/ALLTRAS.png" alt="ALLTRAS" width="140" style="margin-left: 20px;"><br>ALL REGION TRAVELLING SYSTEM</h6><br>
            <a href="dashboard.php"  class="active"> <i class="fas fa-home me-2"></i>Laman Utama</a>
            <h6 class="text mt-4"></h6>
            <a href="wilayahAsal.php" ><i class="fas fa-map-marker-alt me-2"></i>Wilayah Asal</a>
            <a href="tugasRasmi.php"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a>
            <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
            <a href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
        </div>


    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Laman Utama</h3>

        <div class="greeting-box">
            <?php  
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
                <div class="card-box bg-primary">
                    <i class="fas fa-user-plus"></i>
                    <h6>Jumlah Permohonan</h6>
                    <p>Wilayah Asal: <?= $stats['total']['Wilayah Asal'] ?></p>
                    <p>Tugas Rasmi: <?= $stats['total']['Tugas Rasmi'] ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box bg-success">
                    <i class="fas fa-spinner"></i>
                    <h6>Sedang Diproses</h6>
                    <p>Wilayah Asal: <?= $stats['processing']['Wilayah Asal'] ?></p>
                    <p>Tugas Rasmi: <?= $stats['processing']['Tugas Rasmi'] ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box bg-warning">
                    <i class="fas fa-check-circle"></i>
                    <h6>Berjaya Diproses</h6>
                    <p>Wilayah Asal: <?= $stats['approved']['Wilayah Asal'] ?></p>
                    <p>Tugas Rasmi: <?= $stats['approved']['Tugas Rasmi'] ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box bg-danger">
                    <i class="fas fa-times-circle"></i>
                    <h6>Permohonan Gagal</h6>
                    <p>Wilayah Asal: <?= $stats['rejected']['Wilayah Asal'] ?></p>
                    <p>Tugas Rasmi: <?= $stats['rejected']['Tugas Rasmi'] ?></p>
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
                            <img src="../../assets/flowchart-tugasrasmi.jpg" alt="Carta Aliran Wilayah Asal" class="img-fluid rounded clickable-image" style="cursor:pointer;">
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
