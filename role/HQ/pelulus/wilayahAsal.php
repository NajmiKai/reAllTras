<?php
session_start();
include '../../../connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];
$admin_role = $_SESSION['admin_role'];
$admin_icNo = $_SESSION['admin_icNo'];
$admin_email = $_SESSION['admin_email'];
$admin_phoneNo = $_SESSION['admin_phoneNo'];

$currentPage = basename($_SERVER['PHP_SELF']);

$users = [
    [
        'id' => 1,
        'nama_first' => 'Ahmad',
        'nama_last' => 'Zulkifli2',
        'email' => 'ahmad.zulkifli@example.com',
        'phone' => '0123456789',
        'kp' => '900101-10-1234',
        'bahagian' => 'Bahagian Teknologi Maklumat',
        'status' => 'Kuiri'
    ],
    [    
        'id' => 2,
        'nama_first' => 'Siti',
        'nama_last' => 'Noraini2',
        'email' => 'siti.noraini@example.com',
        'phone' => '0198765432',
        'kp' => '850505-14-5678',
        'bahagian' => 'Bahagian Pentadbiran',
        'status' => 'Kuiri '
    ],
    // Add more users as needed
];

// Sample data for 'wilayah_asal' table as an array of associative arrays
$wilayah_asal = [
    [
        'user_kp' => '900101-10-1234',
        'nama_first' => 'Ahmad',
        'nama_last' => 'Zulkifli',
        'email' => 'ahmad.zulkifli@example.com',
        'phone' => '0123456789',
        'bahagian' => 'Bahagian Teknologi Maklumat',
        'jawatan' => 'Pegawai Teknologi Maklumat',
        'alamat_menetap_1' => 'No. 12 Jalan Mawar',
        'alamat_menetap_2' => 'Taman Melati',
        'poskod_menetap' => '53000',
        'bandar_menetap' => 'Kuala Lumpur',
        'negeri_menetap' => 'Wilayah Persekutuan',
        'tarikh_lapor_diri' => '2022-01-10',
        // Add other fields as needed for your app...
    ],
    // Add more wilayah_asal data if needed
];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Wilayah Asal </title>
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
        <a href="dashboard.php"> <i class="fas fa-home me-2"></i>Laman Utama</a>
        <h6 class="text mt-4">BORANG PERMOHONAN</h6>

        <a href="wilayahAsal.php" class="active"><i class="fas fa-map-marker-alt me-2"></i>Wilayah Asal</a>
        <a href="tugasRasmi.php"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a>
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Laman Utama</h3>

    <h5 class="mb-3">Senarai Pemohon Wilayah Asal </h5>
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover" id="myTable">
                        <thead class="table-light">
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
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['nama_first'] . ' ' . $user['nama_last']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td><?php echo htmlspecialchars($user['kp']); ?></td>
                                <td><?php echo htmlspecialchars($user['bahagian']); ?></td>                              
                                <td><?php echo htmlspecialchars($user['status']); ?></td>
                                <td>
                                    <a class="button" href="viewdetails.php?id=<?= $user['id'] ?>">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>


<script>
    
    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });

</script>
</body>
</html>
