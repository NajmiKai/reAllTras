<!-- Personal Information -->
<div class="card shadow-sm mb-4">
    <div class="card-header" style="background-color: #d59e3e; color: white;">
        <h5 class="mb-0">Maklumat Pegawai</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Pegawai</label>
                <input type="text" class="form-control" name="nama_pegawai" value="<?= htmlspecialchars($user_name) ?>" readonly required>
            </div>
            <div class="col-md-6">
                <label class="form-label">No. Kad Pengenalan</label>
                <input type="text" class="form-control" name="user_kp" id="user_kp" value="<?= htmlspecialchars($user_icNo) ?>" readonly required oninput="formatIC(this)">
            </div>
            <div class="col-md-6">
                <label class="form-label">Jawatan & Gred</label>
                <input type="text" class="form-control" name="jawatan_gred" value="<?= htmlspecialchars($wilayah_asal_data['jawatan_gred'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email Ketua Cawangan <span style="font-size: 0.9em; font-style: italic; color: #666;">(Email Ketua Bahagian untuk KC dan Setaraf)</span></label>
                <input type="email" class="form-control" name="email_penyelia" value="<?= htmlspecialchars($wilayah_asal_data['email_penyelia'] ?? '') ?>" required>
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
                <input type="text" class="form-control" name="alamat_menetap_1" value="<?= htmlspecialchars($wilayah_asal_data['alamat_menetap_1'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Alamat 2</label>
                <input type="text" class="form-control" name="alamat_menetap_2" value="<?= htmlspecialchars($wilayah_asal_data['alamat_menetap_2'] ?? '') ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Poskod</label>
                <input type="text" class="form-control" name="poskod_menetap" value="<?= htmlspecialchars($wilayah_asal_data['poskod_menetap'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Bandar</label>
                <input type="text" class="form-control" name="bandar_menetap" value="<?= htmlspecialchars($wilayah_asal_data['bandar_menetap'] ?? '') ?>" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">Negeri</label>
                <input type="text" class="form-control" name="negeri_menetap" value="<?= htmlspecialchars($wilayah_asal_data['negeri_menetap'] ?? '') ?>" required>
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
                <input type="text" class="form-control" name="alamat_berkhidmat_1" value="<?= htmlspecialchars($wilayah_asal_data['alamat_berkhidmat_1'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Alamat 2</label>
                <input type="text" class="form-control" name="alamat_berkhidmat_2" value="<?= htmlspecialchars($wilayah_asal_data['alamat_berkhidmat_2'] ?? '') ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Poskod</label>
                <input type="text" class="form-control" name="poskod_berkhidmat" value="<?= htmlspecialchars($wilayah_asal_data['poskod_berkhidmat'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Bandar</label>
                <input type="text" class="form-control" name="bandar_berkhidmat" value="<?= htmlspecialchars($wilayah_asal_data['bandar_berkhidmat'] ?? '') ?>" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">Negeri</label>
                <input type="text" class="form-control" name="negeri_berkhidmat" value="<?= htmlspecialchars($wilayah_asal_data['negeri_berkhidmat'] ?? '') ?>" required>
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
                <input type="date" class="form-control" name="tarikh_lapor_diri" value="<?= htmlspecialchars($wilayah_asal_data['tarikh_lapor_diri'] ?? '') ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Pernah Menggunakan Kemudahan Ini?</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pernah_guna" value="ya" id="pernah_guna_ya" <?= ($wilayah_asal_data['pernah_guna'] ?? '') === 'ya' ? 'checked' : '' ?> onchange="toggleTarikhTerakhir()">
                    <label class="form-check-label" for="pernah_guna_ya">Ya</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pernah_guna" value="tidak" id="pernah_guna_tidak" <?= ($wilayah_asal_data['pernah_guna'] ?? '') === 'tidak' ? 'checked' : '' ?> onchange="toggleTarikhTerakhir()">
                    <label class="form-check-label" for="pernah_guna_tidak">Tidak</label>
                </div>
            </div>
            <div class="col-md-6" id="tarikh_terakhir_container" style="display: <?= ($wilayah_asal_data['pernah_guna'] ?? '') === 'ya' ? 'block' : 'none' ?>;">
                <label class="form-label">Tarikh Terakhir Menggunakan Kemudahan</label>
                <input type="date" class="form-control" name="tarikh_terakhir_kemudahan" value="<?= htmlspecialchars($wilayah_asal_data['tarikh_terakhir_kemudahan'] ?? '') ?>">
            </div>
        </div>
    </div>
</div>

<!-- Partner Information Section -->
<div class="card shadow-sm mb-4">
    <div class="card-header" style="background-color: #d59e3e; color: white;">
        <h5 class="mb-0"><i class="fas fa-user-friends me-2"></i>Maklumat Pasangan</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 mb-4">
                <label class="form-label fw-bold">Adakah Anda Mempunyai Pasangan?</label>
                <div class="d-flex gap-4 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ada_pasangan" value="ya" id="ada_pasangan_ya" <?= ($wilayah_asal_data['ada_pasangan'] ?? '') === 'ya' ? 'checked' : '' ?> onchange="togglePartnerDetails()">
                        <label class="form-check-label" for="ada_pasangan_ya">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ada_pasangan" value="tidak" id="ada_pasangan_tidak" <?= ($wilayah_asal_data['ada_pasangan'] ?? '') === 'tidak' ? 'checked' : '' ?> onchange="togglePartnerDetails()">
                        <label class="form-check-label" for="ada_pasangan_tidak">Tidak</label>
                    </div>
                </div>
            </div>

            <div id="partner_details_container" style="display: <?= ($wilayah_asal_data['ada_pasangan'] ?? '') === 'ya' ? 'block' : 'none' ?>;" class="mt-3">
                <div class="border rounded p-4 bg-light">
                    <h6 class="mb-4 text-muted border-bottom pb-2">Maklumat Peribadi Pasangan</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Depan Pasangan</label>
                            <input type="text" class="form-control" name="nama_first_pasangan" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['nama_first_pasangan'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Belakang Pasangan</label>
                            <input type="text" class="form-control" name="nama_last_pasangan" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['nama_last_pasangan'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. KP Pasangan</label>
                            <input type="text" class="form-control" name="no_kp_pasangan" maxlength="14" id="no_kp_pasangan" value="<?= htmlspecialchars($wilayah_asal_data['no_kp_pasangan'] ?? '') ?>" oninput="formatIC(this)" title="Format: XXXXXX-XX-XXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Wilayah Menetap Pasangan</label>
                            <input type="text" class="form-control" name="wilayah_menetap_pasangan" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['wilayah_menetap_pasangan'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="border rounded p-4 bg-light mt-4">
                    <h6 class="mb-4 text-muted border-bottom pb-2">Alamat Berkhidmat Pasangan</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <input type="text" class="form-control mb-2" name="alamat_berkhidmat_1_pasangan" placeholder="Alamat 1" maxlength="100" value="<?= htmlspecialchars($wilayah_asal_data['alamat_berkhidmat_1_pasangan'] ?? '') ?>">
                            <input type="text" class="form-control" name="alamat_berkhidmat_2_pasangan" placeholder="Alamat 2" maxlength="100" value="<?= htmlspecialchars($wilayah_asal_data['alamat_berkhidmat_2_pasangan'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Poskod</label>
                            <input type="text" class="form-control" name="poskod_berkhidmat_pasangan" maxlength="10" pattern="[0-9]{5}" title="5 digit poskod" value="<?= htmlspecialchars($wilayah_asal_data['poskod_berkhidmat_pasangan'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bandar</label>
                            <input type="text" class="form-control" name="bandar_berkhidmat_pasangan" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['bandar_berkhidmat_pasangan'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Negeri</label>
                            <input type="text" class="form-control" name="negeri_berkhidmat_pasangan" maxlength="50" value="<?= htmlspecialchars($wilayah_asal_data['negeri_berkhidmat_pasangan'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 