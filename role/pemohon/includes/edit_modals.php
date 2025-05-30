<!-- Edit Pegawai Modal -->
<div class="modal fade" id="editPegawaiModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Maklumat Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="update_borangWA5.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="wilayah_asal_id" value="<?= $application_data['id'] ?>">
                    <input type="hidden" name="update_type" value="pegawai">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jawatan/Gred</label>
                        <input type="text" class="form-control" name="jawatan_gred" value="<?= htmlspecialchars($full_application_data['jawatan_gred']) ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Pasangan Modal -->
<div class="modal fade" id="editPasanganModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Maklumat Pasangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="update_borangWA5.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="wilayah_asal_id" value="<?= $application_data['id'] ?>">
                    <input type="hidden" name="update_type" value="pasangan">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Pasangan</label>
                        <input type="text" class="form-control" name="nama_pasangan" value="<?= htmlspecialchars($full_application_data['nama_first_pasangan'] . ' ' . $full_application_data['nama_last_pasangan']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">No. Kad Pengenalan</label>
                        <input type="text" class="form-control" name="no_kp_pasangan" value="<?= htmlspecialchars($full_application_data['no_kp_pasangan']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Wilayah Menetap</label>
                        <input type="text" class="form-control" name="wilayah_menetap_pasangan" value="<?= htmlspecialchars($full_application_data['wilayah_menetap_pasangan']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Berkhidmat</label>
                        <textarea class="form-control" name="alamat_berkhidmat_pasangan" rows="3" required><?= htmlspecialchars($full_application_data['alamat_berkhidmat_1_pasangan'] . "\n" . $full_application_data['alamat_berkhidmat_2_pasangan']) ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Ibu Bapa Modal -->
<div class="modal fade" id="editIbuBapaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Maklumat Ibu Bapa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="update_borangWA5.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="wilayah_asal_id" value="<?= $application_data['id'] ?>">
                    <input type="hidden" name="update_type" value="ibu_bapa">
                    
                    <h6 class="mb-3"><strong>Maklumat Bapa</strong></h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Bapa</label>
                            <input type="text" class="form-control" name="nama_bapa" value="<?= htmlspecialchars($full_application_data['nama_bapa']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Kad Pengenalan</label>
                            <input type="text" class="form-control" name="no_kp_bapa" value="<?= htmlspecialchars($full_application_data['no_kp_bapa']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Wilayah Menetap</label>
                            <input type="text" class="form-control" name="wilayah_menetap_bapa" value="<?= htmlspecialchars($full_application_data['wilayah_menetap_bapa']) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Menetap</label>
                            <textarea class="form-control" name="alamat_menetap_bapa" rows="3" required><?= htmlspecialchars($full_application_data['alamat_menetap_1_bapa'] . "\n" . $full_application_data['alamat_menetap_2_bapa']) ?></textarea>
                        </div>
                    </div>

                    <h6 class="mb-3"><strong>Maklumat Ibu</strong></h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Ibu</label>
                            <input type="text" class="form-control" name="nama_ibu" value="<?= htmlspecialchars($full_application_data['nama_ibu']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. Kad Pengenalan</label>
                            <input type="text" class="form-control" name="no_kp_ibu" value="<?= htmlspecialchars($full_application_data['no_kp_ibu']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Wilayah Menetap</label>
                            <input type="text" class="form-control" name="wilayah_menetap_ibu" value="<?= htmlspecialchars($full_application_data['wilayah_menetap_ibu']) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Menetap</label>
                            <textarea class="form-control" name="alamat_menetap_ibu" rows="3" required><?= htmlspecialchars($full_application_data['alamat_menetap_1_ibu'] . "\n" . $full_application_data['alamat_menetap_2_ibu']) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Penerbangan Modal -->
<div class="modal fade" id="editPenerbanganModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Maklumat Penerbangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="update_borangWA5.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="wilayah_asal_id" value="<?= $application_data['id'] ?>">
                    <input type="hidden" name="update_type" value="penerbangan">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Permohonan</label>
                        <select class="form-select" name="jenis_permohonan" required>
                            <option value="diri_sendiri" <?= $full_application_data['jenis_permohonan'] === 'diri_sendiri' ? 'selected' : '' ?>>Diri Sendiri/ Pasangan/ Anak Ke Wilayah Ditetapkan</option>
                            <option value="keluarga" <?= $full_application_data['jenis_permohonan'] === 'keluarga' ? 'selected' : '' ?>>Keluarga Pegawai ke Wilayah Berkhidmat</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tarikh Penerbangan Pergi</label>
                        <input type="date" class="form-control" name="tarikh_penerbangan_pergi" value="<?= date('Y-m-d', strtotime($full_application_data['tarikh_penerbangan_pergi'])) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tarikh Penerbangan Balik</label>
                        <input type="date" class="form-control" name="tarikh_penerbangan_balik" value="<?= date('Y-m-d', strtotime($full_application_data['tarikh_penerbangan_balik'])) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Lapangan Terbang Berlepas</label>
                        <input type="text" class="form-control" name="start_point" value="<?= htmlspecialchars($full_application_data['start_point']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Lapangan Terbang Tiba</label>
                        <input type="text" class="form-control" name="end_point" value="<?= htmlspecialchars($full_application_data['end_point']) ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="update_borangWA5.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="wilayah_asal_id" value="<?= $application_data['id'] ?>">
                    <input type="hidden" name="update_type" value="document">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Dokumen</label>
                        <select class="form-select" name="document_type" required>
                            <option value="SALINAN_IC_PEGAWAI">Salinan IC Pegawai</option>
                            <option value="SALINAN_IC_PENGIKUT">Salinan IC Pengikut</option>
                            <option value="DOKUMEN_SOKONGAN">Dokumen Sokongan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Fail</label>
                        <input type="file" class="form-control" name="document_file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Muat Naik</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Handle edit form submissions with AJAX
document.querySelectorAll('form[action*="update_borangWA5.php"]').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Maklumat berjaya dikemaskini');
                // Reload the page to show updated data
                window.location.reload();
            } else {
                // Show error message
                alert(data.message || 'Ralat: Gagal mengemaskini maklumat');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ralat: Gagal mengemaskini maklumat');
        });
    });
});
</script> 