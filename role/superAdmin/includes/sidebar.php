<?php
// Get the current page filename to determine active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar" id="sidebar">
    <h6><img src="../../assets/ALLTRAS.png" alt="ALLTRAS" width="140" style="margin-left: 20px;"><br>ALL REGION TRAVELLING SYSTEM</h6><br>
    <a href="dashboard.php" <?= $current_page === 'dashboard.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-home me-2"></i>Laman Utama
    </a>
    <a href="adjustSuperAdmin.php" <?= $current_page === 'adjustSuperAdmin.php' ? 'class="active"' : '' ?>>
        <i class="fa fa-lock me-2"></i>Pengendalian Super Admin
    </a>
    <a href="adjustAdmin.php" <?= $current_page === 'adjustAdmin.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-map-marker-alt me-2"></i>Pengendalian Admin
    </a>
    <a href="adjustUsers.php" <?= $current_page === 'adjustUsers.php' ? 'class="active"' : '' ?>>
        <i class="fa fa-users me-2"></i>Pengedalian Pengguna
    </a>
    <a href="x" <?= $current_page === 'profile.php' ? 'class="active"' : '' ?>>
        <i class="fa fa-file-text me-2"></i>Pengendalian Wilayah Asal
    </a>
    <a href="x" <?= $current_page === 'profile.php' ? 'class="active"' : '' ?>>
        <i class="fa fa-archive me-2"></i>Log Sistem
    </a>
    <a href="../../logoutSuperAdmin.php">
        <i class="fas fa-sign-out-alt me-2"></i>Log Keluar
    </a>
</div> 