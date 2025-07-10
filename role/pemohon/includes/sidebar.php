<?php
// Get the current page filename to determine active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    .sidebar {
        width: 260px;
        transition: transform 0.3s;
    }
    .sidebar.hidden {
        transform: translateX(-100%);
    }
    .sidebar-toggle-btn {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #fff;
        border: none;
        border-radius: 4px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        padding: 6px 10px;
        cursor: pointer;
        z-index: 1001;
    }
    .sidebar-toggle-btn:focus {
        outline: none;
    }
    .submenu-container {
        display: none;
        margin-left: 20px;
    }
    .submenu-container.show {
        display: block;
    }
    .dropdown-caret {
        transition: transform 0.2s;
    }
    .dropdown-caret.rotate {
        transform: rotate(180deg);
    }
</style>
<button class="sidebar-toggle-btn" id="sidebarToggleBtn" title="Toggle Sidebar">
    <i class="fas fa-bars"></i>
</button>
<div class="sidebar" id="sidebar">
    <h6><img src="../../assets/ALLTRAS.png" alt="ALLTRAS" width="140" style="margin-left: 20px;"><br>ALL REGION TRAVELLING SYSTEM</h6><br>
    <a href="dashboard.php" <?= $current_page === 'dashboard.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-home me-2"></i>Laman Utama
    </a>
    <a href="#" id="wilayahAsalMenu" <?= $current_page === 'wilayahAsal.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-map-marker-alt me-2"></i>Wilayah Asal
        <i class="fas fa-caret-down dropdown-caret" id="wilayahAsalCaret" style="margin-left: 5px;"></i>
    </a>
    <div class="submenu-container" id="wilayahAsalSubmenu">
        <a href="wilayahAsal.php" class="submenu-link<?= $current_page === 'wilayahAsal.php' ? ' active' : '' ?>">
            <i class="fas fa-map-marker-alt me-2"></i>Wilayah Asal Terkini
        </a>
        <a href="wilayahAsalList.php" class="submenu-link<?= $current_page === 'wilayahAsalList.php' ? ' active' : '' ?>">
            <i class="fas fa-list me-2"></i>Semua Pemohonan
        </a>
    </div>
    <a href="profile.php" <?= $current_page === 'profile.php' ? 'class="active"' : '' ?>>
        <i class="fas fa-user me-2"></i>Paparan Profil
    </a>
    <a href="../../logoutUser.php">
        <i class="fas fa-sign-out-alt me-2"></i>Log Keluar
    </a>
</div>
<script>
    // Show submenu if current page is wilayahAsal.php or wilayahAsalList.php
    var currentPage = "<?= $current_page ?>";
    var submenu = document.getElementById('wilayahAsalSubmenu');
    var caret = document.getElementById('wilayahAsalCaret');
    if (currentPage === 'wilayahAsal.php' || currentPage === 'wilayahAsalList.php') {
        submenu.classList.add('show');
        caret.classList.add('rotate');
    }
    // Toggle submenu on click
    document.getElementById('wilayahAsalMenu').addEventListener('click', function(e) {
        e.preventDefault();
        submenu.classList.toggle('show');
        caret.classList.toggle('rotate');
    });
    // Sidebar toggle
    var sidebar = document.getElementById('sidebar');
    var sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    sidebarToggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('hidden');
    });
</script>