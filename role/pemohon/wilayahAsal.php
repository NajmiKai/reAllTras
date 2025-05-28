<?php
session_start();
include '../../connection.php';

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    header("Location: ../../login.php");
    exit();
}

$user_name = $user_data['nama_first'] . ' ' . $user_data['nama_last'];
$user_role = $user_data['bahagian'];
$user_icNo = $user_data['kp'];

// Check if user has any wilayah asal application
$check_sql = "SELECT * FROM wilayah_asal WHERE user_kp = ? ORDER BY id DESC LIMIT 1";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $user_icNo);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$application_data = $check_result->fetch_assoc();

// Determine which view to show
$view_type = 'new'; // Default view
if ($application_data) {
    switch ($application_data['status_permohonan']) {
        case 'Tolak':
        case 'Lulus':
            $view_type = 'final';
            break;
        case 'Belum Disemak':
            $view_type = 'pending';
            break;
        case 'Dikuiri':
            $view_type = 'query';
            break;
    }
}

// If no application exists, redirect to borangWA
if (!$application_data && $view_type === 'new') {
    header("Location: borangWA.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Wilayah Asal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
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
            <span class="nav-link fw-semibold"><?= htmlspecialchars($user_name) ?> (<?= htmlspecialchars($user_role) ?>)</span>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../../logoutUser.php" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt me-1"></i> Log Keluar
            </a>
        </li>
    </ul>
</nav>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Wilayah Asal</h3>

        <?php if ($view_type === 'final'): ?>
        <!-- Final Status View (Tolak/Lulus) -->
        <div class="card shadow-sm mb-4">
            <div class="card-header" style="background-color: #d59e3e; color: white;">
                <h5 class="mb-0"><strong>Status Permohonan</strong></h5>
            </div>
            <div class="card-body">
                <div class="alert <?= $application_data['status_permohonan'] === 'Lulus' ? 'alert-success' : 'alert-danger' ?>">
                    <h4 class="alert-heading"><?= $application_data['status_permohonan'] ?></h4>
                    <p>Permohonan anda telah <?= strtolower($application_data['status_permohonan']) ?>.</p>
                </div>
                <div class="mt-4">
                    <h5>Ulasan:</h5>
                    <p><?= nl2br(htmlspecialchars($application_data['ulasan_kewangan'] ?? 'Tiada ulasan')) ?></p>
                </div>
            </div>
        </div>

        <?php elseif ($view_type === 'pending'): ?>
        <!-- Pending View (Belum Disemak) -->
        <div class="card shadow-sm mb-4">
            <div class="card-header" style="background-color: #d59e3e; color: white;">
                <h5 class="mb-0"><strong>Status Permohonan</strong></h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h4 class="alert-heading">Permohonan Sedang Diproses</h4>
                    <p>Permohonan anda sedang dalam proses semakan. Sila tunggu untuk maklumat lanjut.</p>
                </div>
            </div>
        </div>

        <!-- Summary View (Same as borangWA5 but without editing) -->
        <?php include 'includes/wilayah_asal_summary.php'; ?>

        <?php elseif ($view_type === 'query'): ?>
        <!-- Query View (Dikuiri) -->
        <div class="card shadow-sm mb-4">
            <div class="card-header" style="background-color: #d59e3e; color: white;">
                <h5 class="mb-0"><strong>Status Permohonan</strong></h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h4 class="alert-heading">Permohonan Dikuiri</h4>
                    <p>Sila semak dan kemaskini maklumat berikut:</p>
                </div>
                <?php
                $ulasan = '';
                switch ($application_data['kedudukan_permohonan']) {
                    case 'CSM':
                        $ulasan = $application_data['ulasan_csm1'];
                        break;
                    case 'HQ':
                        $ulasan = $application_data['ulasan_hq'];
                        break;
                    case 'CSM2':
                        $ulasan = $application_data['ulasan_csm2'];
                        break;
                    case 'Kewangan':
                        $ulasan = $application_data['ulasan_kewangan'];
                        break;
                }
                ?>
                <div class="mt-4">
                    <h5>Ulasan:</h5>
                    <p><?= nl2br(htmlspecialchars($ulasan)) ?></p>
                </div>
            </div>
        </div>

        <!-- Editable Form View (Same as borangWA5) -->
        <?php include 'includes/wilayah_asal_editable.php'; ?>

        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });
</script>
</body>
</html> 