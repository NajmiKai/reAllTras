<?php
session_start();
include '../../../connection.php';

    if (!isset($_SESSION['admin_id'])) {
        header("Location: /reAllTras/login.php");
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

    $admin_name = $_SESSION['admin_name'];
    $admin_id = $_SESSION['admin_id'];
    $admin_role = $_SESSION['admin_role'];
    $admin_icNo = $_SESSION['admin_icNo'];
    $admin_email = $_SESSION['admin_email'];
    $admin_phoneNo = $_SESSION['admin_phoneNo'];

    if (isset($_GET['kp'])) {
        $kp = $_GET['kp'];

    // Fetch user data from database
    $sql = "SELECT *, wilayah_asal.id AS wilayah_asal_id
    FROM user 
    JOIN wilayah_asal ON user.kp = wilayah_asal.user_kp
    WHERE user.kp = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $kp);
    $stmt->execute();
    $result = $stmt->get_result();
    $application_data = $result->fetch_assoc();

    if ($application_data) {
        $wilayah_asal_id = $application_data['wilayah_asal_id'];

        $query = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $wilayah_asal_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $pengikutData = [];
        while ($row = $result->fetch_assoc()) {
            $pengikutData[] = $row;
        }

        $isApproved = false; // Assume false initially

        $sql = "SELECT penyemakBaki_kewangan_id FROM wilayah_asal WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $wilayah_asal_id);
        $stmt->execute();
        $stmt->bind_result($penyemakBaki_kewangan_id);
        if ($stmt->fetch()) {
            if ($penyemakBaki_kewangan_id === $admin_id || $penyemakBaki_kewangan_id !== null) { 
                $isApproved = true;
            }
        }
        $stmt->close();
        } 
    }


?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Butiran Permohonan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/adminStyle.css">
    <link rel="stylesheet" href="../../../assets/css/multi-step.css">
    <!-- Bootstrap 5 JS (includes collapse) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
        <a href="wilayahAsal.php" class="active"><i class="fas fa-tasks me-2"></i>Wilayah Asal</a>
        <!-- <a href="tugasRasmi.php"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a> -->
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

  <!-- Main Content -->
  <div class="col p-4" style="margin-left: 250px;">
        <h3 class="mb-3">Semakan Permohonan</h3>
        <div class="container">
        <!-- Multi-step Indicator -->
        <div class="multi-step-indicator mb-4">
            <div class="step completed">
                <div class="step-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="step-label">Maklumat Pegawai</div>
            </div>
            <div class="step-line"></div>
            <div class="step completed">
                <div class="step-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="step-label">Maklumat Wilayah Menetap Ibu Bapa</div>
            </div>
            <div class="step-line"></div>
            <div class="step completed">
                <div class="step-icon">
                    <i class="fas fa-plane"></i>
                </div>
                <div class="step-label">Maklumat Penerbangan</div>
            </div>
            <div class="step-line"></div>
            <div class="step completed">
                <div class="step-icon">
                    <i class="fas fa-file-upload"></i>
                </div>
                <div class="step-label">Muat Naik Dokumen</div>
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="step-label">Pengesahan Maklumat</div>
            </div>
        </div>

        <form action="send_mail.php" method="POST" enctype="multipart/form-data">            
            <!-- Maklumat Pegawai -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Maklumat Pegawai</strong></h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Pegawai</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['nama_first'] . ' ' . $application_data['nama_last']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Kad Pengenalan</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['kp']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jawatan/Gred</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['jawatan_gred']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($application_data['nama_first_pasangan'] || $application_data['nama_last_pasangan']): ?>
            <!-- Maklumat Pasangan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Maklumat Pasangan</strong></h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Pasangan</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['nama_first_pasangan'] . ' ' . $application_data['nama_last_pasangan']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Kad Pengenalan</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['no_kp_pasangan']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Wilayah Menetap</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['wilayah_menetap_pasangan']) ?></p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Berkhidmat</label>
                            <p class="form-control-static ps-2">
                                <?= htmlspecialchars($application_data['alamat_berkhidmat_1_pasangan']) ?><br>
                                <?= htmlspecialchars($application_data['alamat_berkhidmat_2_pasangan']) ?><br>
                                <?= htmlspecialchars($application_data['poskod_berkhidmat_pasangan']) ?> 
                                <?= htmlspecialchars($application_data['bandar_berkhidmat_pasangan']) ?>, 
                                <?= htmlspecialchars($application_data['negeri_berkhidmat_pasangan']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Maklumat Ibu Bapa -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Maklumat Ibu Bapa</strong></h5>
                </div>
                <div class="card-body">
                    <!-- Maklumat Bapa -->
                    <h6 class="mb-3"><strong>Maklumat Bapa</strong></h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Bapa</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['nama_bapa']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Kad Pengenalan</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['no_kp_bapa']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Wilayah Menetap</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['wilayah_menetap_bapa']) ?></p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Menetap</label>
                            <p class="form-control-static ps-2">
                                <?= htmlspecialchars($application_data['alamat_menetap_1_bapa']) ?><br>
                                <?= htmlspecialchars($application_data['alamat_menetap_2_bapa']) ?><br>
                                <?= htmlspecialchars($application_data['poskod_menetap_bapa']) ?> 
                                <?= htmlspecialchars($application_data['bandar_menetap_bapa']) ?>, 
                                <?= htmlspecialchars($application_data['negeri_menetap_bapa']) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Maklumat Ibu -->
                    <h6 class="mb-3"><strong>Maklumat Ibu</strong></h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Ibu</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['nama_ibu']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Kad Pengenalan</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['no_kp_ibu']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Wilayah Menetap</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['wilayah_menetap_ibu']) ?></p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Menetap</label>
                            <p class="form-control-static ps-2">
                                <?= htmlspecialchars($application_data['alamat_menetap_1_ibu']) ?><br>
                                <?= htmlspecialchars($application_data['alamat_menetap_2_ibu']) ?><br>
                                <?= htmlspecialchars($application_data['poskod_menetap_ibu']) ?> 
                                <?= htmlspecialchars($application_data['bandar_menetap_ibu']) ?>, 
                                <?= htmlspecialchars($application_data['negeri_menetap_ibu']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maklumat Penerbangan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Maklumat Penerbangan</strong></h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Permohonan</label>
                            <p class="form-control-static ps-2">
                                <?php
                                $jenis_permohonan = $application_data['jenis_permohonan'];
                                if ($jenis_permohonan === 'diri_sendiri') {
                                    echo "Diri Sendiri/ Pasangan/ Anak Ke Wilayah Ditetapkan";
                                } else if ($jenis_permohonan === 'keluarga') {
                                    echo "Keluarga Pegawai ke Wilayah Berkhidmat";
                                } else {
                                    echo htmlspecialchars($jenis_permohonan);
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tarikh Penerbangan Pergi</label>
                            <p class="form-control-static ps-2"><?= date('d/m/Y', strtotime($application_data['tarikh_penerbangan_pergi'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tarikh Penerbangan Balik</label>
                            <p class="form-control-static ps-2"><?= date('d/m/Y', strtotime($application_data['tarikh_penerbangan_balik'])) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Lapangan Terbang Berlepas</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['start_point']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Lapangan Terbang Tiba</label>
                            <p class="form-control-static ps-2"><?= htmlspecialchars($application_data['end_point']) ?></p>
                        </div>
                    </div>


                    <!-- Maklumat Pengikut -->
                    <h6 class="mt-4 mb-3"><strong>Maklumat Pengikut</strong></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-bold">Nama Pengikut</th>
                                    <th class="fw-bold">No. Kad Pengenalan</th>
                                    <th class="fw-bold">Tarikh Lahir</th>
                                    <th class="fw-bold">Tarikh Penerbangan Pergi</th>
                                    <th class="fw-bold">Tarikh Penerbangan Balik</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pengikutData as $index => $pengikut): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pengikut['nama_first_pengikut'] . ' ' . $pengikut['nama_last_pengikut']) ?></td>
                                    <td><?= htmlspecialchars($pengikut['kp_pengikut']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($pengikut['tarikh_lahir_pengikut'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_pergi_pengikut'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_balik_pengikut'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <!-- Dokumen Sokongan -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Dokumen Sokongan</strong></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-bold">Nama Fail</th>
                                    <th class="fw-bold">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM documents WHERE wilayah_asal_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $wilayah_asal_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($doc = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($doc['file_name']) ?></td>
                                    <td>
                                        <a href="<?= htmlspecialchars($doc['file_path']) ?>" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Lihat Dokumen
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

           

            <!-- Pengesahan -->
            <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><strong>Pengesahan</strong></h5>
                    <a class="text-black text-decoration-underline" data-bs-toggle="collapse" href="#logDokumenTable" role="button" aria-expanded="false" aria-controls="logDokumenTable" style="font-size: 0.8rem;">
                        Rekod Log Dokumen
                    </a>
                </div>
                
                <div class="card-body">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Baki kewangan (RM) / Ulasan :</label>
                        <input type="text" class="form-control" name="baki_kewangan" placeholder="RM" <?php if ($isApproved) echo 'disabled'; ?>>
                    </div>
                </div>
             </div>

              <!-- Collapsible Log Table -->
              <div class="collapse mt-4" id="logDokumenTable">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="fw-bold">Tarikh</th>
                                            <th class="fw-bold">Nama Admin</th>
                                            <th class="fw-bold">Peranan</th>
                                            <th class="fw-bold">Tindakan</th>
                                            <th class="fw-bold">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $log_sql = "SELECT * FROM document_logs WHERE wilayah_asal_id = ? ORDER BY tarikh DESC";
                                        $log_stmt = $conn->prepare($log_sql);
                                        $log_stmt->bind_param("i", $wilayah_asal_id);
                                        $log_stmt->execute();
                                        $log_result = $log_stmt->get_result();

                                        if ($log_result->num_rows > 0):
                                            while ($log = $log_result->fetch_assoc()):
                                        ?>
                                            <tr>
                                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($log['tarikh']))) ?></td>
                                                <td><?= htmlspecialchars($log['namaAdmin']) ?></td>
                                                <td><?= htmlspecialchars($log['peranan']) ?></td>
                                                <td><?= htmlspecialchars($log['tindakan']) ?></td>
                                                <td><?= nl2br(htmlspecialchars($log['catatan'])) ?></td>
                                            </tr>
                                        <?php
                                            endwhile;
                                        else:
                                        ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Tiada rekod log untuk wilayah ini.</td>
                                            </tr>
                                        <?php endif;
                                        $log_stmt->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>

            </div>
            <input type="hidden" name="wilayah_asal_id" value="<?= $wilayah_asal_id ?>">

            <div class="d-flex justify-content-between mt-4">
                <a href="wilayahAsal.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-success" <?php if ($isApproved) echo 'disabled'; ?>
                    <i class="fas fa-check me-2"></i>Hantar Permohonan
                </button>
            </div>
            <?php if ($isApproved): ?>
                <div class="alert alert-info mt-3">
                    Permohonan telah diluluskan dan tidak boleh dikemaskini lagi.
                </div>
            <?php endif; ?>
        </form>
        
    </div>
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
