<!-- Document Upload Section -->
<div class="card shadow-sm mb-4">
    <div class="card-header" style="background-color: #d59e3e; color: white;">
        <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i>Muat Naik Dokumen</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <!-- Surat Tawaran -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Surat Tawaran</label>
                    <?php if (!empty($wilayah_asal_data['surat_tawaran'])): ?>
                        <div class="mb-2">
                            <a href="../../uploads/<?= htmlspecialchars($wilayah_asal_data['surat_tawaran']) ?>" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-eye me-1"></i>Lihat Dokumen
                            </a>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="surat_tawaran" accept=".pdf,.doc,.docx">
                    <small class="text-muted">Format yang diterima: PDF, DOC, DOCX</small>
                </div>
            </div>

            <!-- Surat Perakuan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Surat Perakuan</label>
                    <?php if (!empty($wilayah_asal_data['surat_perakuan'])): ?>
                        <div class="mb-2">
                            <a href="../../uploads/<?= htmlspecialchars($wilayah_asal_data['surat_perakuan']) ?>" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-eye me-1"></i>Lihat Dokumen
                            </a>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="surat_perakuan" accept=".pdf,.doc,.docx">
                    <small class="text-muted">Format yang diterima: PDF, DOC, DOCX</small>
                </div>
            </div>

            <!-- Surat Pengesahan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Surat Pengesahan</label>
                    <?php if (!empty($wilayah_asal_data['surat_pengesahan'])): ?>
                        <div class="mb-2">
                            <a href="../../uploads/<?= htmlspecialchars($wilayah_asal_data['surat_pengesahan']) ?>" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-eye me-1"></i>Lihat Dokumen
                            </a>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="surat_pengesahan" accept=".pdf,.doc,.docx">
                    <small class="text-muted">Format yang diterima: PDF, DOC, DOCX</small>
                </div>
            </div>

            <!-- Surat Kebenaran -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Surat Kebenaran</label>
                    <?php if (!empty($wilayah_asal_data['surat_kebenaran'])): ?>
                        <div class="mb-2">
                            <a href="../../uploads/<?= htmlspecialchars($wilayah_asal_data['surat_kebenaran']) ?>" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-eye me-1"></i>Lihat Dokumen
                            </a>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="surat_kebenaran" accept=".pdf,.doc,.docx">
                    <small class="text-muted">Format yang diterima: PDF, DOC, DOCX</small>
                </div>
            </div>

            <!-- Dokumen Tambahan -->
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label">Dokumen Tambahan (Jika Ada)</label>
                    <?php if (!empty($wilayah_asal_data['dokumen_tambahan'])): ?>
                        <div class="mb-2">
                            <a href="../../uploads/<?= htmlspecialchars($wilayah_asal_data['dokumen_tambahan']) ?>" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-eye me-1"></i>Lihat Dokumen
                            </a>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" name="dokumen_tambahan" accept=".pdf,.doc,.docx">
                    <small class="text-muted">Format yang diterima: PDF, DOC, DOCX</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// File size validation
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Check file size (5MB limit)
            if (file.size > 5 * 1024 * 1024) {
                alert('Saiz fail tidak boleh melebihi 5MB');
                this.value = '';
                return;
            }

            // Check file type
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format fail tidak diterima. Sila muat naik fail dalam format PDF, DOC, atau DOCX');
                this.value = '';
                return;
            }
        }
    });
});
</script> 