<?php
session_start();
include '../../connection.php';

// Fetch user data from database
$super_admin_id = $_SESSION['super_admin_id'];
$sql = "SELECT * FROM superAdmin WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $super_admin_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    header("Location: ../../loginSuperAdmin.php");
    exit();
}

$user_name = $user_data['Name'];
$user_icNo = $user_data['ICNo'];
$user_email = $user_data['Email'];
$user_phoneNo = $user_data['PhoneNo'];

// Get wilayah_asal ID from URL
$wilayah_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch wilayah_asal details with user information
$sql = "SELECT wa.*, u.nama_first, u.nama_last, u.email, u.phone, u.bahagian 
        FROM wilayah_asal wa 
        LEFT JOIN user u ON wa.user_kp = u.kp 
        WHERE wa.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $wilayah_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    header("Location: listWilayahAsal.php");
    exit();
}

// Fetch pengikut information
$sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $wilayah_id);
$stmt->execute();
$pengikut_result = $stmt->get_result();
$pengikut_data = [];
while ($row = $pengikut_result->fetch_assoc()) {
    $pengikut_data[] = $row;
}

// Fetch documents if exists
$doc_sql = "SELECT * FROM documents WHERE wilayah_asal_id = ?";
$doc_stmt = $conn->prepare($doc_sql);
$doc_stmt->bind_param("i", $wilayah_id);
$doc_stmt->execute();
$doc_result = $doc_stmt->get_result();
$documents = [];
while ($row = $doc_result->fetch_assoc()) {
    $documents[] = $row;
}

// Get the current status and position
$status_permohonan = $data['status_permohonan'];
$kedudukan_permohonan = $data['kedudukan_permohonan'];
$ulasan = $data['ulasan_pbr_csm1'];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Maklumat Permohonan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
    <style>
        .section-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
        }
        .section-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
            border-radius: 10px 10px 0 0;
        }
        .section-body {
            padding: 1.5rem;
        }
        .info-row {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .info-label {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        .info-value {
            color: #212529;
            font-size: 1rem;
            line-height: 1.5;
        }
        .document-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 0.75rem;
        }
        .document-item:last-child {
            margin-bottom: 0;
        }
        .document-info {
            display: flex;
            align-items: center;
        }
        .document-icon {
            color: #6c757d;
            margin-right: 0.75rem;
        }
        .document-description {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 50rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-badge.belum-disemak {
            background-color: #e9ecef;
            color: #495057;
        }
        .status-badge.dikuiri {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-badge.lulus {
            background-color: #d4edda;
            color: #155724;
        }
        .status-badge.tolak {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-badge.selesai {
            background-color: #cce5ff;
            color: #004085;
        }
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .alert-warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .alert-heading {
            color: #856404;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Maklumat Permohonan</h3>
            <a href="listWilayahAsal.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?php if ($status_permohonan === 'Dikuiri'): ?>
            <?php if ($ulasan): ?>
            <div class="alert alert-warning mb-4">
                <h5 class="alert-heading">
                    <i class="fas fa-exclamation-circle me-2"></i>Kuiri / Ulasan
                </h5>
                <p class="mb-0"><?= nl2br(htmlspecialchars($ulasan)) ?></p>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Maklumat Pegawai -->
        <div class="section-card">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Maklumat Pegawai
                </h5>
            </div>
            <div class="section-body">
                <div class="info-row">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="info-label">Nama</p>
                            <p class="info-value">
                                <i class="fas fa-user me-2 text-primary"></i><?= htmlspecialchars($data['nama_first'] . ' ' . $data['nama_last']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="info-label">No. KP</p>
                            <p class="info-value">
                                <i class="fas fa-id-card me-2 text-secondary"></i><?= htmlspecialchars($data['user_kp']) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="info-label">Jawatan & Gred</p>
                            <p class="info-value"><?= htmlspecialchars($data['jawatan_gred']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="info-label">Email Penyelia</p>
                            <p class="info-value"><?= htmlspecialchars($data['email_penyelia']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="info-label">Alamat Menetap</p>
                            <p class="info-value">
                                <?= htmlspecialchars($data['alamat_menetap_1']) ?><br>
                                <?= $data['alamat_menetap_2'] ? htmlspecialchars($data['alamat_menetap_2']) . '<br>' : '' ?>
                                <?= htmlspecialchars($data['poskod_menetap']) ?> <?= htmlspecialchars($data['bandar_menetap']) ?><br>
                                <?= htmlspecialchars($data['negeri_menetap']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="info-label">Alamat Berkhidmat</p>
                            <p class="info-value">
                                <?= htmlspecialchars($data['alamat_berkhidmat_1']) ?><br>
                                <?= $data['alamat_berkhidmat_2'] ? htmlspecialchars($data['alamat_berkhidmat_2']) . '<br>' : '' ?>
                                <?= htmlspecialchars($data['poskod_berkhidmat']) ?> <?= htmlspecialchars($data['bandar_berkhidmat']) ?><br>
                                <?= htmlspecialchars($data['negeri_berkhidmat']) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php if ($data['nama_first_pasangan'] || $data['nama_last_pasangan']): ?>
                <div class="info-row">
                    <div class="row">
                        <div class="col-12">
                            <p class="info-label">Maklumat Pasangan</p>
                            <p class="info-value">
                                <?= htmlspecialchars($data['nama_first_pasangan'] . ' ' . $data['nama_last_pasangan']) ?><br>
                                No. KP: <?= htmlspecialchars($data['no_kp_pasangan']) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Maklumat Ibu Bapa -->
        <div class="section-card">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Maklumat Ibu Bapa
                </h5>
            </div>
            <div class="section-body">
                <?php if ($data['nama_bapa']): ?>
                <div class="info-row">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="info-label">Maklumat Bapa</p>
                            <p class="info-value">
                                <?= htmlspecialchars($data['nama_bapa']) ?><br>
                                No. KP: <?= htmlspecialchars($data['no_kp_bapa']) ?><br>
                                Wilayah Menetap: <?= htmlspecialchars($data['wilayah_menetap_bapa']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="info-label">Alamat Bapa</p>
                            <p class="info-value">
                                <?= htmlspecialchars($data['alamat_menetap_1_bapa']) ?><br>
                                <?= $data['alamat_menetap_2_bapa'] ? htmlspecialchars($data['alamat_menetap_2_bapa']) . '<br>' : '' ?>
                                <?= htmlspecialchars($data['poskod_menetap_bapa']) ?> <?= htmlspecialchars($data['bandar_menetap_bapa']) ?><br>
                                <?= htmlspecialchars($data['negeri_menetap_bapa']) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($data['nama_ibu']): ?>
                <div class="info-row">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="info-label">Maklumat Ibu</p>
                            <p class="info-value">
                                <?= htmlspecialchars($data['nama_ibu']) ?><br>
                                No. KP: <?= htmlspecialchars($data['no_kp_ibu']) ?><br>
                                Wilayah Menetap: <?= htmlspecialchars($data['wilayah_menetap_ibu']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="info-label">Alamat Ibu</p>
                            <p class="info-value">
                                <?= htmlspecialchars($data['alamat_menetap_1_ibu']) ?><br>
                                <?= $data['alamat_menetap_2_ibu'] ? htmlspecialchars($data['alamat_menetap_2_ibu']) . '<br>' : '' ?>
                                <?= htmlspecialchars($data['poskod_menetap_ibu']) ?> <?= htmlspecialchars($data['bandar_menetap_ibu']) ?><br>
                                <?= htmlspecialchars($data['negeri_menetap_ibu']) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Maklumat Penerbangan -->
        <div class="section-card">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-plane me-2"></i>Maklumat Penerbangan
                </h5>
            </div>
            <div class="section-body">
                <!-- Pemohon's Flight Information -->
                <div class="info-row mb-4">
                    <h6 class="text-primary mb-3">Pemohon</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="info-label">Tarikh Penerbangan</p>
                            <p class="info-value">
                                <i class="fas fa-plane-departure me-2 text-primary"></i>Pergi: <?= date('d/m/Y', strtotime($data['tarikh_penerbangan_pergi'])) ?><br>
                                <i class="fas fa-plane-arrival me-2 text-success"></i>Balik: <?= date('d/m/Y', strtotime($data['tarikh_penerbangan_balik'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="info-label">Lokasi</p>
                            <p class="info-value">
                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>Berlepas: <?= htmlspecialchars($data['start_point']) ?><br>
                                <i class="fas fa-map-marker-alt me-2 text-success"></i>Tiba: <?= htmlspecialchars($data['end_point']) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Spouse's Flight Information -->
                <?php if ($data['tarikh_penerbangan_pergi_pasangan']): ?>
                <div class="info-row mb-4">
                    <h6 class="text-primary mb-3">Pasangan</h6>
                    <div class="row">
                        <div class="col-12">
                            <p class="info-label">Tarikh Penerbangan</p>
                            <p class="info-value">
                                <i class="fas fa-plane-departure me-2 text-primary"></i>Pergi: <?= date('d/m/Y', strtotime($data['tarikh_penerbangan_pergi_pasangan'])) ?><br>
                                <i class="fas fa-plane-arrival me-2 text-success"></i>Balik: <?= date('d/m/Y', strtotime($data['tarikh_penerbangan_balik_pasangan'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Followers' Flight Information -->
                <?php if ($pengikut_data): ?>
                    <?php foreach ($pengikut_data as $index => $pengikut): ?>
                    <div class="info-row <?= $index < count($pengikut_data) - 1 ? 'mb-4' : '' ?>">
                        <h6 class="text-primary mb-3">Pengikut <?= $index + 1 ?></h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="info-label">Maklumat Pengikut</p>
                                <p class="info-value">
                                    <i class="fas fa-user me-2 text-primary"></i><?= htmlspecialchars($pengikut['nama_first_pengikut'] . ' ' . $pengikut['nama_last_pengikut']) ?><br>
                                    <i class="fas fa-id-card me-2 text-secondary"></i>No. KP: <?= htmlspecialchars($pengikut['kp_pengikut']) ?><br>
                                    <i class="fas fa-birthday-cake me-2 text-info"></i>Tarikh Lahir: <?= date('d/m/Y', strtotime($pengikut['tarikh_lahir_pengikut'])) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="info-label">Tarikh Penerbangan</p>
                                <p class="info-value">
                                    <i class="fas fa-plane-departure me-2 text-primary"></i>Pergi: <?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_pergi_pengikut'])) ?><br>
                                    <i class="fas fa-plane-arrival me-2 text-success"></i>Balik: <?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_balik_pengikut'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Dokumen -->
        <div class="section-card">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>Dokumen
                </h5>
            </div>
            <div class="section-body">
                <?php if ($documents): ?>
                    <?php foreach ($documents as $doc): ?>
                    <div class="document-item">
                        <div class="document-info">
                            <i class="fas fa-file document-icon"></i>
                            <div>
                                <div><?= htmlspecialchars($doc['file_name']) ?></div>
                                <small class="document-description"><?= htmlspecialchars($doc['description']) ?></small>
                            </div>
                        </div>
                        <div class="actions">
                            <a href="/reAllTras/<?= str_replace('../../../', '', htmlspecialchars($doc['file_path'])) ?>" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Tiada dokumen dimuat naik.</p>
                <?php endif; ?>
            </div>
        </div>


          <!-- Dokumen -->
          <div class="section-card">
            <div class="section-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>Log Rekod
                </h5>
            </div>

            <!-- Collapsible Log Table -->
            <div class="section-body">
          <div id="logDokumenTable">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold">Tarikh</th>
                            <th class="fw-bold">Nama Admin</th>
                            <th class="fw-bold">Peranan</th>
                            <th class="fw-bold">Tindakan</th>
                            <th class="fw-bold">Catatan</th>
                            <th class="fw-bold">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $log_sql = "SELECT * FROM document_logs WHERE wilayah_asal_id = ? ORDER BY tarikh DESC";
                            $log_stmt = $conn->prepare($log_sql);
                            $log_stmt->bind_param("i", $wilayah_id);
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
                            <td>
                            <button type="button" class="btn btn-warning btn-sm"
                                onclick="kembaliTindakan(<?= $log['wilayah_asal_id'] ?>, <?= $log['id'] ?>)">
                                <i class="fas fa-undo"></i> Undur Tindakan
                            </button>
                            </td>
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



<!-- View Admin Modal -->
<!-- <div class="modal fade" id="viewAdminModal" tabindex="-1" aria-labelledby="viewAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAdminModalLabel">Maklumat Log Rekod</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="adminDetails">
                <form id="editAdminForm">
                    <input type="hidden" id="editWilayahAsalId" name="id">
                    <div class="mb-3">
                        <label for="editNamaAdmin" class="form-label">Nama Admin</label>
                        <input type="text" class="form-control" id="editNamaAdmin" name="editNamaAdmin" disabled required>
                    </div>
                    <div class="mb-3">
                        <label for="editPeranan" class="form-label">Peranan</label>
                        <input type="email" class="form-control" id="editPeranan" name="editPeranan" disabled required>
                    </div>
                    <div class="mb-3">
                        <label for="editTindakan" class="form-label">Tindakan</label>
                        <input type="text" class="form-control" id="editTindakan" name="editTindakan" required>
                         <div id="tindakanWrapper"></div> 
                    </div>
                    <div class="mb-3">
                        <label for="editCatatan" class="form-label">Catatan</label>
                        <input type="text" class="form-control" id="editCatatan" name="editCatatan">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="saveAdminChanges()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div> -->


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });

    function kembaliTindakan(wilayah_id, log_id) {
    if (confirm("Adakah anda pasti mahu undur tindakan ini?")) {
        fetch('includes/kembaliTindakan.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ wilayah_id: wilayah_id, log_id: log_id })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Ralat berlaku. Sila cuba lagi.");
        });
    }
}

    // function viewDocumentLog(wilayah_id) {
    // fetch(`includes/getWilayahAdminDetails.php?id=${wilayah_id}`)
    //     .then(response => response.json())
    //     .then(data => {
    //         // Populate form fields
    //         document.getElementById('editWilayahAsalId').value = data.ID;
    //         document.getElementById('editNamaAdmin').value = data.namaAdmin;
    //         document.getElementById('editPeranan').value = data.peranan;
    //         document.getElementById('editCatatan').value = data.catatan;

    //         const tindakanWrapper = document.getElementById('tindakanWrapper');
    //         tindakanWrapper.innerHTML = ''; // clear old content

    //         // Check value of tindakan
    //         if (data.tindakan === 'Disahkan' || data.tindakan === 'Tidak disahkan') {
    //             // Render dropdown
    //             tindakanWrapper.innerHTML = `
    //                 <label for="editTindakanSelect" class="form-label">Tindakan</label>
    //                 <select class="form-select" id="editTindakanSelect" name="editTindakan">
    //                     <option value="">-- Pilih Tindakan --</option>
    //                     <option value="disahkan">Disahkan</option>
    //                     <option value="tidak disahkan">Tidak Disahkan</option>
    //                 </select>
    //             `;

    //             // Set selected value
    //             document.getElementById('editTindakanSelect').value = data.tindakan;

    //         }  // Check value of tindakan
    //         else if (data.tindakan === 'Diterima' || data.tindakan === 'Tidak diterima') {
    //             // Render dropdown
    //             tindakanWrapper.innerHTML = `
    //                 <label for="editTindakanSelect" class="form-label">Tindakan</label>
    //                 <select class="form-select" id="editTindakanSelect" name="editTindakan">
    //                     <option value="">-- Pilih Tindakan --</option>
    //                     <option value="diterima">Diterima</option>
    //                     <option value="tidak diterima">Tidak Diterima</option>
    //                 </select>
    //             `;

    //             // Set selected value
    //             document.getElementById('editTindakanSelect').value = data.tindakan;
            
            
    //         }  else if (data.tindakan === 'Diluluskan' || data.tindakan === 'Tidak diluluskan') {
    //             // Render dropdown
    //             tindakanWrapper.innerHTML = `
    //                 <label for="editTindakanSelect" class="form-label">Tindakan</label>
    //                 <select class="form-select" id="editTindakanSelect" name="editTindakan">
    //                     <option value="">-- Pilih Tindakan --</option>
    //                     <option value="diluluskan">Diluluskan</option>
    //                     <option value="tidak diluluskan">Tidak Diluluskan</option>
    //                 </select>
    //             `;

    //             // Set selected value
    //             document.getElementById('editTindakanSelect').value = data.tindakan;
            
    //         } else {
    //             // Render checkboxes
    //             const options = ['Telah disemak baki'];
    //             let checkboxHTML = `<label class="form-label d-block">Tindakan</label>`;

    //             options.forEach(opt => {
    //                 const isChecked = data.tindakan.includes(opt) ? 'checked' : '';
    //                 checkboxHTML += `
    //                     <div class="form-check">
    //                         <input class="form-check-input" type="checkbox" value="${opt}" name="editTindakan[]" id="tindakan_${opt}" ${isChecked}>
    //                         <label class="form-check-label" for="tindakan_${opt}">${opt.charAt(0).toUpperCase() + opt.slice(1)}</label>
    //                     </div>
    //                 `;
    //             });

    //             tindakanWrapper.innerHTML = checkboxHTML;
    //         }

    //         // Show modal
    //         new bootstrap.Modal(document.getElementById('viewAdminModal')).show();
    //     })
    //     .catch(error => console.error('Error:', error));
    // }

</script>
</body>
</html>
