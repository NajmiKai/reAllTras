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

$wilayah_asal = [
    [
        'id' => 1,
        'nama_first' => 'Ahmad',
        'nama_last' => 'Zulkifli',
        'email' => 'ahmad.zulkifli@example.com',
        'phone' => '0123456789',
        'kp' => '900101-10-1234',
        'bahagian' => 'Bahagian Teknologi Maklumat',
        'jawatan' => 'Pegawai Teknologi Maklumat',
        'alamat_menetap_1' => 'No. 12 Jalan Mawar',
        'alamat_menetap_2' => 'Taman Melati',
        'poskod_menetap' => '53000',
        'bandar_menetap' => 'Kuala Lumpur',
        'negeri_menetap' => 'Wilayah Persekutuan',
        'tarikh_lapor_diri' => '2022-01-10',
    ],
    [
        'id' => 2,
        'nama_first' => 'Siti',
        'nama_last' => 'Noraini',
        'email' => 'siti.noraini@example.com',
        'phone' => '0198765432',
        'kp' => '850505-14-5678',
        'bahagian' => 'Bahagian Pentadbiran',
        'jawatan' => 'Penolong Pegawai Tadbir',
        'alamat_menetap_1' => 'No. 34 Jalan Melur',
        'alamat_menetap_2' => 'Taman Bunga Raya',
        'poskod_menetap' => '68000',
        'bandar_menetap' => 'Petaling Jaya',
        'negeri_menetap' => 'Selangor',
        'tarikh_lapor_diri' => '2021-11-05',
        'status' => 'Sedang Diproses'
    ],
];

$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$record = null;

if ($id) {
    foreach ($wilayah_asal as $wa) {
        if ($wa['id'] === $id) {
            $record = $wa;
            break;
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['dokumen']) && $_FILES['dokumen']['error'] === UPLOAD_ERR_OK) {
        // Save or process uploaded file here if needed
        // $uploadedPath = 'uploads/' . basename($_FILES['dokumen']['name']);
        // move_uploaded_file($_FILES['dokumen']['tmp_name'], $uploadedPath);

        // Change status
        $record['status'] = 'lulusPBRCSM1';
    } else {
        $record['status'] = 'Gagal muat naik fail.';
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/adminStyle.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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
        </div>

        <a href="tugasRasmi.php"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a>
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Laman Utama</h3>


<h2>Butiran Lengkap Pengguna</h2>

<?php if ($record): ?>
<div class="container">
    <form method="POST" enctype="multipart/form-data">
        <!-- Maklumat Diri -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Maklumat Diri</div>
            <div class="card-body row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($record['nama_first'] . ' ' . $record['nama_last']) ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. KP</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($record['kp']) ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telefon</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($record['phone']) ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($record['email']) ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Bahagian</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($record['bahagian']) ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jawatan</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($record['jawatan']) ?>" readonly>
                </div>
            </div>
        </div>

        <!-- Maklumat Pasangan -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">Maklumat Pasangan</div>
            <div class="card-body">
                <p class="text-muted">Maklumat pasangan belum disediakan.</p>
            </div>
        </div>

        <!-- Maklumat Alamat -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">Maklumat Alamat</div>
            <div class="card-body row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Alamat Baris 1</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($record['alamat_menetap_1']) ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Alamat Baris 2</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($record['alamat_menetap_2']) ?>" readonly>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Poskod</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($record['poskod_menetap']) ?>" readonly>
                </div>
                <div class="col-md-5 mb-3">
                    <label class="form-label">Bandar</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($record['bandar_menetap']) ?>" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Negeri</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($record['negeri_menetap']) ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tarikh Lapor Diri</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($record['tarikh_lapor_diri']) ?>" readonly>
                </div>
            </div>
        </div>

        <!-- Bahagian PBR Sumber Manusia dari HQ -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">BAHAGIAN CAWANGAN SUMBER MANUSIA (Pengesah)</div>
            <div class="card-body row">
            
            <div class="col-md-6 mb-3">
                    <label for="status_select" class="form-label">Status Permohonan</label>
                    <select class="form-select" name="status_permohonan" id="status_select" required onchange="toggleUlasan()">
                        <option value="">-- Sila Pilih --</option>
                        <option value="sokong">Disokong</option>
                        <option value="tidakDisokong">Tidak disokong</option>
                    </select>
                </div>
                <div class="col-md-12 mb-3" id="ulasan_section" style="display: none;">
                    <label for="ulasan" class="form-label">Ulasan (jika dikuiri)</label>
                    <textarea class="form-control" name="ulasan" id="ulasan" rows="4" placeholder="Nyatakan sebab dikuiri..."></textarea>
                </div>
            </div>
        </div>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-success">Hantar</button>
            <a href="permohonanIbuPejabat.php" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
<?php else: ?>
    <p style="text-align:center;">Tiada maklumat dijumpai untuk ID ini.</p>
<?php endif; ?>


<script>
    function toggleUlasan() {
        const select = document.getElementById('status_select');
        const ulasanDiv = document.getElementById('ulasan_section');
        if (select.value === 'tidakDisokong') {
            ulasanDiv.style.display = 'block';
            document.getElementById('ulasan').setAttribute('required', 'required');
        } else {
            ulasanDiv.style.display = 'none';
            document.getElementById('ulasan').removeAttribute('required');
        }
    }

</script>
</body>
</html>
