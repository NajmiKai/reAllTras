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
        <h3 class="mb-3">Borang Permohonan Wilayah Asal</h3>
        
        <form action="../../functions/process_borangWA.php" method="POST" class="needs-validation" novalidate>
            <!-- Personal Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Maklumat Pegawai</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Pegawai</label>
                            <input type="text" class="form-control" name="nama_pegawai" value="<?= htmlspecialchars($admin_name) ?>" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Kad Pengenalan</label>
                            <input type="text" class="form-control" name="user_kp" value="<?= htmlspecialchars($admin_icNo) ?>" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jawatan & Gred</label>
                            <input type="text" class="form-control" name="jawatan_gred" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alamat Menetap -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Alamat Menetap</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Alamat 1</label>
                            <input type="text" class="form-control" name="alamat_menetap_1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat 2</label>
                            <input type="text" class="form-control" name="alamat_menetap_2" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Poskod</label>
                            <input type="text" class="form-control" name="poskod_menetap" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bandar</label>
                            <input type="text" class="form-control" name="bandar_menetap" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Negeri</label>
                            <input type="text" class="form-control" name="negeri_menetap" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alamat Berkhidmat -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Alamat Berkhidmat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Alamat 1</label>
                            <input type="text" class="form-control" name="alamat_berkhidmat_1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat 2</label>
                            <input type="text" class="form-control" name="alamat_berkhidmat_2" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Poskod</label>
                            <input type="text" class="form-control" name="poskod_berkhidmat" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bandar</label>
                            <input type="text" class="form-control" name="bandar_berkhidmat" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Negeri</label>
                            <input type="text" class="form-control" name="negeri_berkhidmat" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maklumat Tambahan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Maklumat Tambahan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Lapor Diri</label>
                            <input type="date" class="form-control" name="tarikh_lapor_diri" required>
                        </div>
                        <div></div>
                        <div class="col-md-6">
                            <label class="form-label">Pernah Menggunakan Perkhidmatan Sebelum Ini?</label>
                            <select class="form-select" name="pernah_guna" id="pernah_guna" required onchange="toggleLastServiceDate()">
                                <option value="">Pilih Jawapan</option>
                                <option value="ya">Ya</option>
                                <option value="tidak">Tidak</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Terakhir Menggunakan Perkhidmatan</label>
                            <input type="date" class="form-control" name="tarikh_terakhir_kemudahan" id="tarikh_terakhir" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Seterusnya</button>
                <button type="reset" class="btn btn-secondary">Set Semula</button>
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

    // Function to toggle last service date field
    function toggleLastServiceDate() {
        const pernahGuna = document.getElementById('pernah_guna');
        const tarikhTerakhir = document.getElementById('tarikh_terakhir');
        
        if (pernahGuna.value === 'ya') {
            tarikhTerakhir.disabled = false;
            tarikhTerakhir.required = true;
        } else {
            tarikhTerakhir.disabled = true;
            tarikhTerakhir.required = false;
            tarikhTerakhir.value = '';
        }
    }

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
