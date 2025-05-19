<?php
session_start();
include '../../connection.php';

$admin_name = 'najmi';
$admin_role = 'pemohon';
$admin_icNo = '1234567890';
$admin_email = 'najmi@gmail.com';
$admin_phoneNo = '0123456789';
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Borang Wilayah Asal (Bahagian 2)</title>
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
        <a href="dashboard.php"><i class="fas fa-home me-2"></i>Laman Utama</a>
        <h6 class="text mt-4"></h6>
        <a href="wilayahAsal.php"><i class="fas fa-map-marker-alt me-2"></i>Wilayah Asal</a>
        <a href="tugasRasmi.php"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a>
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Borang Permohonan Wilayah Asal (Bahagian 2)</h3>
        
        <form action="../../functions/process_borangWA2.php" method="POST" class="needs-validation" novalidate>
            <!-- Maklumat Penerbangan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Maklumat Penerbangan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Penerbangan Pergi</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_pergi" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Penerbangan Balik</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_balik" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lapangan Terbang Berlepas</label>
                            <select class="form-select" name="start_point" required>
                                <option value="">Pilih Lapangan Terbang</option>
                                <option value="LGK">Langkawi International Airport</option>
                                <option value="AOR">Alor Setar Airport / Sultan Abdul Halim Airport</option>
                                <option value="KBR">Sultan Ismail Petra Airport</option>
                                <option value="PEN">Penang International Airport</option>
                                <option value="TGG">Sultan Mahmud Airport</option>
                                <option value="IPH">Ipoh Airport / Sultan Azlan Shah Airport</option>
                                <option value="KUA">Sultan Haji Ahmad Shah Airport</option>
                                <option value="SZB">Sultan Abdul Aziz Shah Airport</option>
                                <option value="KUL">Kuala Lumpur International Airport 1 / 2</option>
                                <option value="MKZ">Melaka Airport</option>
                                <option value="JHB">Senai International Airport</option>
                                <option value="KCH">Kuching International Airport</option>
                                <option value="MYY">Miri Airport</option>
                                <option value="MZV">Mulu Airport</option>
                                <option value="LMN">Limbang Airport</option>
                                <option value="LBU">Labuan Airport</option>
                                <option value="BKI">Kota Kinabalu International Airport</option>
                                <option value="TWU">Tawau Airport</option>
                                <option value="LDU">Lahad Datu Airport</option>
                                <option value="SDK">Sandakan Airport</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lapangan Terbang Tiba</label>
                            <select class="form-select" name="end_point" required>
                                <option value="">Pilih Lapangan Terbang</option>
                                <option value="LGK">Langkawi International Airport</option>
                                <option value="AOR">Alor Setar Airport / Sultan Abdul Halim Airport</option>
                                <option value="KBR">Sultan Ismail Petra Airport</option>
                                <option value="PEN">Penang International Airport</option>
                                <option value="TGG">Sultan Mahmud Airport</option>
                                <option value="IPH">Ipoh Airport / Sultan Azlan Shah Airport</option>
                                <option value="KUA">Sultan Haji Ahmad Shah Airport</option>
                                <option value="SZB">Sultan Abdul Aziz Shah Airport</option>
                                <option value="KUL">Kuala Lumpur International Airport 1 / 2</option>
                                <option value="MKZ">Melaka Airport</option>
                                <option value="JHB">Senai International Airport</option>
                                <option value="KCH">Kuching International Airport</option>
                                <option value="MYY">Miri Airport</option>
                                <option value="MZV">Mulu Airport</option>
                                <option value="LMN">Limbang Airport</option>
                                <option value="LBU">Labuan Airport</option>
                                <option value="BKI">Kota Kinabalu International Airport</option>
                                <option value="TWU">Tawau Airport</option>
                                <option value="LDU">Lahad Datu Airport</option>
                                <option value="SDK">Sandakan Airport</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                <a href="borangWA.php" class="btn btn-secondary">Kembali</a>
                <div>
                    <button type="submit" class="btn btn-primary">Hantar Permohonan</button>
                    <button type="reset" class="btn btn-secondary">Set Semula</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });
</script>
</body>
</html> 