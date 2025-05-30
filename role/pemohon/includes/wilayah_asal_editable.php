<?php
// Fetch all application data with related information
$sql = "SELECT wa.*, 
        GROUP_CONCAT(DISTINCT wp.nama_first_pengikut, ' ', wp.nama_last_pengikut) as pengikut_names,
        GROUP_CONCAT(DISTINCT d.file_name) as document_names
        FROM wilayah_asal wa 
        LEFT JOIN wilayah_asal_pengikut wp ON wa.id = wp.wilayah_asal_id
        LEFT JOIN documents d ON wa.id = d.wilayah_asal_id
        WHERE wa.id = ?
        GROUP BY wa.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $application_data['id']);
$stmt->execute();
$result = $stmt->get_result();
$full_application_data = $result->fetch_assoc();
?>

<form action="update_borangWA5.php" method="POST" class="needs-validation" novalidate>
    <input type="hidden" name="wilayah_asal_id" value="<?php echo htmlspecialchars($application_data['id']); ?>">
    
    <!-- Maklumat Pegawai -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
            <h5 class="mb-0"><strong>Maklumat Pegawai</strong></h5>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editPegawaiModal">
                <i class="fas fa-edit me-1"></i>Edit
            </button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Pegawai</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($user_name) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">No. Kad Pengenalan</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($user_data['kp']) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jawatan/Gred</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['jawatan_gred']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($full_application_data['nama_first_pasangan'] || $full_application_data['nama_last_pasangan']): ?>
    <!-- Maklumat Pasangan -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
            <h5 class="mb-0"><strong>Maklumat Pasangan</strong></h5>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editPasanganModal">
                <i class="fas fa-edit me-1"></i>Edit
            </button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Pasangan</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['nama_first_pasangan'] . ' ' . $full_application_data['nama_last_pasangan']) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">No. Kad Pengenalan</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['no_kp_pasangan']) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Wilayah Menetap</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['wilayah_menetap_pasangan']) ?></p>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Alamat Berkhidmat</label>
                    <p class="form-control-static ps-2">
                        <?= htmlspecialchars($full_application_data['alamat_berkhidmat_1_pasangan']) ?><br>
                        <?= htmlspecialchars($full_application_data['alamat_berkhidmat_2_pasangan']) ?><br>
                        <?= htmlspecialchars($full_application_data['poskod_berkhidmat_pasangan']) ?> 
                        <?= htmlspecialchars($full_application_data['bandar_berkhidmat_pasangan']) ?>, 
                        <?= htmlspecialchars($full_application_data['negeri_berkhidmat_pasangan']) ?>
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
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editIbuBapaModal">
                <i class="fas fa-edit me-1"></i>Edit
            </button>
        </div>
        <div class="card-body">
            <!-- Maklumat Bapa -->
            <h6 class="mb-3"><strong>Maklumat Bapa</strong></h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Bapa</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['nama_bapa']) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">No. Kad Pengenalan</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['no_kp_bapa']) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Wilayah Menetap</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['wilayah_menetap_bapa']) ?></p>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Alamat Menetap</label>
                    <p class="form-control-static ps-2">
                        <?= htmlspecialchars($full_application_data['alamat_menetap_1_bapa']) ?><br>
                        <?= htmlspecialchars($full_application_data['alamat_menetap_2_bapa']) ?><br>
                        <?= htmlspecialchars($full_application_data['poskod_menetap_bapa']) ?> 
                        <?= htmlspecialchars($full_application_data['bandar_menetap_bapa']) ?>, 
                        <?= htmlspecialchars($full_application_data['negeri_menetap_bapa']) ?>
                    </p>
                </div>
            </div>

            <!-- Maklumat Ibu -->
            <h6 class="mb-3"><strong>Maklumat Ibu</strong></h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Ibu</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['nama_ibu']) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">No. Kad Pengenalan</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['no_kp_ibu']) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Wilayah Menetap</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['wilayah_menetap_ibu']) ?></p>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Alamat Menetap</label>
                    <p class="form-control-static ps-2">
                        <?= htmlspecialchars($full_application_data['alamat_menetap_1_ibu']) ?><br>
                        <?= htmlspecialchars($full_application_data['alamat_menetap_2_ibu']) ?><br>
                        <?= htmlspecialchars($full_application_data['poskod_menetap_ibu']) ?> 
                        <?= htmlspecialchars($full_application_data['bandar_menetap_ibu']) ?>, 
                        <?= htmlspecialchars($full_application_data['negeri_menetap_ibu']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Maklumat Penerbangan -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
            <h5 class="mb-0"><strong>Maklumat Penerbangan</strong></h5>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editPenerbanganModal">
                <i class="fas fa-edit me-1"></i>Edit
            </button>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jenis Permohonan</label>
                    <p class="form-control-static ps-2">
                        <?php
                        $jenis_permohonan = $full_application_data['jenis_permohonan'];
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
                    <p class="form-control-static ps-2"><?= date('d/m/Y', strtotime($full_application_data['tarikh_penerbangan_pergi'])) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tarikh Penerbangan Balik</label>
                    <p class="form-control-static ps-2"><?= date('d/m/Y', strtotime($full_application_data['tarikh_penerbangan_balik'])) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Lapangan Terbang Berlepas</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['start_point']) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Lapangan Terbang Tiba</label>
                    <p class="form-control-static ps-2"><?= htmlspecialchars($full_application_data['end_point']) ?></p>
                </div>
            </div>

            <?php if ($full_application_data['pengikut_names']): ?>
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
                        <?php
                        $sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $full_application_data['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($pengikut = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($pengikut['nama_first_pengikut'] . ' ' . $pengikut['nama_last_pengikut']) ?></td>
                            <td><?= htmlspecialchars($pengikut['kp_pengikut']) ?></td>
                            <td><?= date('d/m/Y', strtotime($pengikut['tarikh_lahir_pengikut'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_pergi_pengikut'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($pengikut['tarikh_penerbangan_balik_pengikut'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Dokumen Sokongan -->
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d59e3e; color: white;">
            <h5 class="mb-0"><strong>Dokumen Sokongan</strong></h5>
            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                <i class="fas fa-upload me-1"></i>Tambah Dokumen
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold">Jenis Dokumen</th>
                            <th class="fw-bold">Nama Fail</th>
                            <th class="fw-bold">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM documents WHERE wilayah_asal_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $full_application_data['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($doc = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($doc['description']) ?></td>
                            <td><?= htmlspecialchars($doc['file_name']) ?></td>
                            <td>
                                <a href="../../<?= htmlspecialchars($doc['file_path']) ?>" target="_blank" class="btn btn-primary btn-sm">
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
        <div class="card-header" style="background-color: #d59e3e; color: white;">
            <h5 class="mb-0"><strong>Pengesahan</strong></h5>
        </div>
        <div class="card-body">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pengesahan" name="pengesahan" required>
                <label class="form-check-label" for="pengesahan">
                    Saya mengesahkan bahawa semua maklumat dan kenyataan yang diberikan adalah benar dan sah. 
                    Saya juga memahami bahawa sekiranya terdapat maklumat palsu, tidak benar atau tidak lengkap, 
                    maka saya boleh dikenakan tindakan tatatertib di bawah Peraturan-Peraturan Pegawai Awam 
                    (Kelakuan dan Tatatertib) 1993.
                </label>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-check me-2"></i>Hantar Semula Permohonan
        </button>
    </div>
</form>

<!-- Edit Modals -->
<?php include 'edit_modals.php'; ?> 