<?php
session_start();
include '../../../connection.php';

if (isset($_SESSION['status'])): ?>
    <script>
        <?php if ($_SESSION['status'] === 'success'): ?>
            alert("✅ Permohonan berjaya dihantar.");
        <?php elseif ($_SESSION['status'] === 'fail'): ?>
            alert("❌ Permohonan gagal dihantar. Ralat: <?= addslashes($_SESSION['error']) ?>");
        <?php endif; ?>
    </script>
    <?php unset($_SESSION['status'], $_SESSION['error']); 
endif;



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
$submenuOpen = in_array($currentPage, ['permohonanPengguna.php', 'permohonanIbuPejabat.php', 'permohonanDikuiri.php']);

// Query user table
$sql = "SELECT * FROM user JOIN wilayah_asal ON user.kp = wilayah_asal.user_kp WHERE status = 'Menunggu pengesahan PBR CSM'";
$result = $conn->query($sql);

$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['status'] = 'Sedang diproses'; // Optional: add custom status manually
        $users[] = $row;
    }
} else {
    echo "No users found.";
}

?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Wilayah Asal (Permohonan Pengguna)</title>
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

        <a href="javascript:void(0);" onclick="toggleSubMenu()" class="<?= $submenuOpen ? 'active' : '' ?>">
            <i class="fas fa-map-marker-alt me-2"></i>Wilayah Asal
            <i class="fas fa-chevron-down" style="float: right; margin-right: 10px;"></i>
        </a>
        
        <!-- Submenu -->
        <div id="wilayahSubmenu" class="submenu" style="display: <?= $submenuOpen ? 'block' : 'none' ?>;">
            <a href="permohonanPengguna.php">Permohonan Pengguna</a>
            <a href="permohonanIbuPejabat.php">Permohonan Ibu Pejabat</a>
            <a href="permohonanDikuiri.php">Permohonan Dikuiri</a>
        </div>

        <!-- <a href="tugasRasmi.php"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a> -->
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="col p-4">
    <br><br>

    <h5 class="mb-3">Senarai Pemohon Wilayah Asal (Pengguna)</h5>
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
                        <?php if (count($users) > 0): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['nama_first'] . ' ' . $user['nama_last']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td><?php echo htmlspecialchars($user['kp']); ?></td>
                                <td><?php echo htmlspecialchars($user['bahagian']); ?></td>                              
                                <td><?php echo htmlspecialchars($user['status']); ?></td>
                                <td>
                                    <a class="button" href="viewdetails.php?kp=<?= $user['kp'] ?>">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tiada data pemohon dijumpai.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>


<script>
    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });

    function toggleSubMenu() {
        const submenu = document.getElementById("wilayahSubmenu");
        submenu.style.display = submenu.style.display === "block" ? "none" : "block";
    }

    // $(document).ready(function () {
    //   $('#myTable').DataTable(); // Enables sorting, searching, and pagination
    // });


</script>
</body>
</html>
