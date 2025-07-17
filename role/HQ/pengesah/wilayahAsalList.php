<?php
session_start();
include '../../../connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../../login.php");
    exit();
}

 // Set session timeout duration (in seconds)
 $timeout_duration = 900; // 900 seconds = 15 minutes

 // Check if the timeout is set and whether it has expired
 if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
     // Session expired
     session_unset();
     session_destroy();
     header("Location: ../../../login.php?timeout=1");
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
$approved_status = $_GET['approved_status'] ?? 'dalam_proses'; 

// Function to count approved applications
function getApprovedCounts($conn) {
    $counts = array();
    // Count Selesai
    $query_selesai = "SELECT COUNT(*) as count FROM wilayah_asal JOIN user ON user.kp = wilayah_asal.user_kp 
                      WHERE status NOT IN ('Menunggu pengesahan pengesah HQ') 
                      AND status_permohonan = 'Selesai'";
    $result_selesai = $conn->query($query_selesai);
    $counts['selesai'] = $result_selesai->fetch_assoc()['count'];
    

    // Count Dalam Proses (not selesai)
    $query_dalam_proses = "SELECT COUNT(*) as count FROM wilayah_asal JOIN user ON user.kp = wilayah_asal.user_kp 
                          WHERE status NOT IN ('Menunggu pengesahan pengesah HQ') 
                          AND status_permohonan != 'Selesai'";
    $result_dalam_proses = $conn->query($query_dalam_proses);
    $counts['dalam_proses'] = $result_dalam_proses->fetch_assoc()['count'];
    
    return $counts;
}

function getStatusFilter($status, $approved_status = 'dalam_proses') {
    switch ($status) {
        case 'processing':
            return "status = 'Menunggu pengesahan pengesah HQ'";
        case 'approved':
            $selesai = "status NOT IN ('Menunggu pengesahan pengesah HQ') AND status_permohonan = 'Selesai'";
            $not_selesai = "status NOT IN ('Menunggu pengesahan pengesah HQ') AND status_permohonan != 'Selesai'";
                return ($approved_status === 'selesai') ? $selesai : $not_selesai; 
        case 'rejected':
            return "1 = 0"; // This ensures no rows returned
        case 'total':
        default:
            return "1 = 1";
    }
}

if ($status === 'total') {
    $status2 = 'Jumlah';
} elseif ($status === 'processing') {
    $status2 = 'Tindakan Perlu';
} elseif ($status === 'approved') {
    $status2 = 'Status Permohonan';
} elseif ($status === 'rejected') {
    $status2 = 'Dikuiri';
} else {
    $status2 = ucfirst($status); // fallback
}


$filter = getStatusFilter($status, $approved_status);
$query = "SELECT * FROM wilayah_asal JOIN user ON user.kp = wilayah_asal.user_kp WHERE $filter ORDER BY wilayah_asal.id DESC";
$result = $conn->query($query);

$approved_counts = getApprovedCounts($conn);
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
    <link rel="icon" href="../../../assets/ALLTRAS.png" type="image/x-icon">

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
    <?php if ($status === 'approved'): ?>
        <!-- Tab Navigation for Approved Status -->
        <ul class="nav nav-tabs mb-3" id="approvedTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link <?= $approved_status === 'dalam_proses' ? 'active' : '' ?>" href="?status=approved&approved_status=dalam_proses">
                Dalam Proses <span class="badge bg-primary text-white rounded-pill ms-1"><?= $approved_counts['dalam_proses'] ?></span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link <?= $approved_status === 'selesai' ? 'active' : '' ?>" href="?status=approved&approved_status=selesai">
                Selesai <span class="badge bg-success text-white rounded-pill ms-1"><?= $approved_counts['selesai'] ?></span>   
                </a>
            </li>
        </ul>
        
        <div class="tab-content" id="approvedTabContent">
            <!-- Dalam Proses Tab -->
            <div class="tab-pane fade <?= $approved_status === 'dalam_proses' ? 'show active' : '' ?>" 
                 id="dalam-proses" 
                 role="tabpanel" 
                 aria-labelledby="dalam-proses-tab">
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
                            <?php 
                            $result->data_seek(0);
                            if ($result && $result->num_rows > 0): ?>
                                <?php while ($user = $result->fetch_assoc()): ?>
                                    <?php if ($approved_status === 'dalam_proses'): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['nama_first'] . ' ' . $user['nama_last']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($user['kp']); ?></td>
                                        <td><?php echo htmlspecialchars($user['bahagian']); ?></td>                              
                                        <td><?php echo htmlspecialchars($user['status']); ?></td>

                                        <td><a href="viewdetails.php?kp=<?= $user['kp'] ?>" class="btn btn-info btn-sm">Lihat</a></td> 
                                        
                                    </tr>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center">Tiada permohonan dalam proses dijumpai.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Selesai Tab -->
            <div class="tab-pane fade <?= $approved_status === 'selesai' ? 'show active' : '' ?>" 
                 id="selesai" 
                 role="tabpanel" 
                 aria-labelledby="selesai-tab">
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
                            <?php 
                            $result->data_seek(0);
                            if ($result && $result->num_rows > 0): ?>
                                <?php while ($user = $result->fetch_assoc()): ?>
                                    <?php if ($approved_status === 'selesai'): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['nama_first'] . ' ' . $user['nama_last']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($user['kp']); ?></td>
                                        <td><?php echo htmlspecialchars($user['bahagian']); ?></td>                              
                                        <td><?php echo htmlspecialchars($user['status']); ?></td>
                                        <td><a href="viewdetails.php?kp=<?= $user['kp'] ?>" class="btn btn-info btn-sm">Lihat</a></td> 
                                        
                                    </tr>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center">Tiada permohonan selesai dijumpai.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    <?php else: ?>
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

                                <td><a href="viewdetails.php?kp=<?= $user['kp'] ?>" class="btn btn-info btn-sm">Lihat</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">Tiada permohonan dijumpai.</td></tr>
            <?php endif; ?>
        </tbody>
        </table>
    </div>
    </div>
    <?php endif; ?>

</body>
</html>
