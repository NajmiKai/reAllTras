<?php
// Get the current page filename to determine active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar" id="sidebar">
    <h6><img src="../../assets/ALLTRAS.png" alt="ALLTRAS" width="140" style="margin-left: 20px;"><br>ALL REGION TRAVELLING SYSTEM</h6><br>
    <a href="dashboard.php" <?= $current_page === 'dashboard.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-home me-2"></i>Laman Utama
    </a>
    <h6 class="text mt-4"></h6>
    <a href="wilayahAsal.php" <?= $current_page === 'wilayahAsal.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-map-marker-alt me-2"></i>Wilayah Asal
    </a>
    <a href="tugasRasmi.php" <?= $current_page === 'tugasRasmi.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus
    </a>
    <a href="profile.php" <?= $current_page === 'profile.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-user me-2"></i>Paparan Profil
    </a>
    <a href="../../logoutUser.php">
        <i class="fas fa-sign-out-alt me-2"></i>Log Keluar
    </a>
</div> 