<!-- Father's Information -->
<div class="card shadow-sm mb-4">
    <div class="card-header" style="background-color: #d59e3e; color: white;">
        <h5 class="mb-0"><i class="fas fa-male me-2"></i>Maklumat Bapa</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Bapa</label>
                <input type="text" class="form-control" name="nama_bapa" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['nama_bapa'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">No. KP Bapa</label>
                <input type="text" class="form-control" name="no_kp_bapa" id="no_kp_bapa" maxlength="14" value="<?= htmlspecialchars($wilayah_asal_data['no_kp_bapa'] ?? '') ?>" oninput="formatIC(this)" title="Format: XXXXXX-XX-XXXX">
            </div>
            <div class="col-md-6">
                <label class="form-label">Wilayah Menetap Bapa</label>
                <input type="text" class="form-control" name="wilayah_menetap_bapa" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['wilayah_menetap_bapa'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat Menetap Bapa</label>
                <input type="text" class="form-control mb-2" name="alamat_menetap_1_bapa" placeholder="Alamat 1" maxlength="100" value="<?= htmlspecialchars($wilayah_asal_data['alamat_menetap_1_bapa'] ?? '') ?>">
                <input type="text" class="form-control" name="alamat_menetap_2_bapa" placeholder="Alamat 2" maxlength="100" value="<?= htmlspecialchars($wilayah_asal_data['alamat_menetap_2_bapa'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Poskod</label>
                <input type="text" class="form-control" name="poskod_menetap_bapa" maxlength="10" pattern="[0-9]{5}" title="5 digit poskod" value="<?= htmlspecialchars($wilayah_asal_data['poskod_menetap_bapa'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Bandar</label>
                <input type="text" class="form-control" name="bandar_menetap_bapa" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['bandar_menetap_bapa'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Negeri</label>
                <input type="text" class="form-control" name="negeri_menetap_bapa" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['negeri_menetap_bapa'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Ibu Negeri/Bandar Dituju</label>
                <input type="text" class="form-control" name="ibu_negeri_bandar_dituju_bapa" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['ibu_negeri_bandar_dituju_bapa'] ?? '') ?>">
            </div>
        </div>
    </div>
</div>

<!-- Mother's Information -->
<div class="card shadow-sm mb-4">
    <div class="card-header" style="background-color: #d59e3e; color: white;">
        <h5 class="mb-0"><i class="fas fa-female me-2"></i>Maklumat Ibu</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Ibu</label>
                <input type="text" class="form-control" name="nama_ibu" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['nama_ibu'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">No. KP Ibu</label>
                <input type="text" class="form-control" name="no_kp_ibu" id="no_kp_ibu" maxlength="14" value="<?= htmlspecialchars($wilayah_asal_data['no_kp_ibu'] ?? '') ?>" oninput="formatIC(this)" title="Format: XXXXXX-XX-XXXX">
            </div>
            <div class="col-md-6">
                <label class="form-label">Wilayah Menetap Ibu</label>
                <input type="text" class="form-control" name="wilayah_menetap_ibu" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['wilayah_menetap_ibu'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat Menetap Ibu</label>
                <input type="text" class="form-control mb-2" name="alamat_menetap_1_ibu" placeholder="Alamat 1" maxlength="100" value="<?= htmlspecialchars($wilayah_asal_data['alamat_menetap_1_ibu'] ?? '') ?>">
                <input type="text" class="form-control" name="alamat_menetap_2_ibu" placeholder="Alamat 2" maxlength="100" value="<?= htmlspecialchars($wilayah_asal_data['alamat_menetap_2_ibu'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Poskod</label>
                <input type="text" class="form-control" name="poskod_menetap_ibu" maxlength="10" pattern="[0-9]{5}" title="5 digit poskod" value="<?= htmlspecialchars($wilayah_asal_data['poskod_menetap_ibu'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Bandar</label>
                <input type="text" class="form-control" name="bandar_menetap_ibu" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['bandar_menetap_ibu'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Negeri</label>
                <input type="text" class="form-control" name="negeri_menetap_ibu" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['negeri_menetap_ibu'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Ibu Negeri/Bandar Dituju</label>
                <input type="text" class="form-control" name="ibu_negeri_bandar_dituju_ibu" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['ibu_negeri_bandar_dituju_ibu'] ?? '') ?>">
            </div>
        </div>
    </div>
</div> 