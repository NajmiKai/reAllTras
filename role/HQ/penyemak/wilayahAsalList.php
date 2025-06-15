<?php
session_start();
include '../../../connection.php';

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
$status = $_GET['status'] ?? 'total';

function getStatusFilter($status, $admin_id) {
    switch ($status) {
        case 'processing':
            return "status IN ('Menunggu pengesahan penyemak1 HQ', 'Menunggu pengesahan penyemak2 HQ')";
        case 'approved':
            return "(penyemak_HQ1_id = $admin_id OR penyemak_HQ2_id = $admin_id) AND status != 'Kembali ke penyemak HQ'";
        case 'rejected':
            return "status = 'Kembali ke penyemak HQ'";
        case 'total':
        default:
            return "(penyemak_HQ1_id = $admin_id OR penyemak_HQ2_id = $admin_id OR status IN ('Menunggu pengesahan penyemak1 HQ', 'Menunggu pengesahan penyemak2 HQ'))";
    }
}

if ($status === 'total') {
    $status2 = 'Jumlah';
} elseif ($status === 'processing') {
    $status2 = 'Sedang diproses';
} elseif ($status === 'approved') {
    $status2 = 'Berjaya diproses';
} elseif ($status === 'rejected') {
    $status2 = 'Dikuiri';
} else {
    $status2 = ucfirst($status); // fallback
}


$filter = getStatusFilter($status, $admin_id);
$query = "SELECT * FROM wilayah_asal JOIN user ON user.kp = wilayah_asal.user_kp WHERE $filter ORDER BY wilayah_asal.id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Senarai Permohonan </title>
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
        <a href="dashboard.php"> <i class="fas fa-home me-2" class="active"></i>Laman Utama</a>
        <h6 class="text mt-4">BORANG PERMOHONAN</h6>
        <a href="wilayahAsal.php"><i class="fas fa-tasks me-2"></i>Wilayah Asal</a>
        <!-- <a href="tugasRasmi.php"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a> -->
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="col p-4">
    <br><br>
    <h3>Senarai Permohonan - <?= ucfirst($status2) ?></h3>
    <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">← Kembali ke Dashboard</a>
    <div class="card shadow-sm">
        <div class="card-body">
        <table class="table table-hover" id="myTable">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>K/P</th>
                    <th>Bahagian</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                                <td><?php echo htmlspecialchars($user['nama_first'] . ' ' . $user['nama_last']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td><?php echo htmlspecialchars($user['kp']); ?></td>
                                <td><?php echo htmlspecialchars($user['bahagian']); ?></td>                              
                                <td><?php echo htmlspecialchars($user['status']); ?></td>

                                <?php if($user['status'] == 'Menunggu pengesahan penyemak2 HQ'){ ?>
                                    <td><a href="viewdetails2.php?kp=<?= $user['kp'] ?>" class="btn btn-info btn-sm">Lihat</a></td>
                                <?php } elseif ($user['status'] == 'Permohonan dikuiri'){ ?>
                                    <td><a href="viewdetailsdikuiri.php?kp=<?= $user['kp'] ?>" class="btn btn-info btn-sm">Lihat</a></td>
                                <?php }else{ ?>
                                    <td><a href="viewdetails.php?kp=<?= $user['kp'] ?>" class="btn btn-info btn-sm">Lihat</a></td>
                                <?php } ?>
                            </tr>



                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">Tiada permohonan dijumpai.</td></tr>
            <?php endif; ?>
        </tbody>
        </table>
    </div>
    </div>
</body>
</html>
