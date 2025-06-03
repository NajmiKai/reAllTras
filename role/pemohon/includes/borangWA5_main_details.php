<?php
// Display all the main details in a structured format
?>
<div class="row">
    <!-- Personal Information -->
    <div class="col-md-6 mb-3">
        <h5>Maklumat Peribadi</h5>
        <div class="mb-2">
            <label class="form-label">Jawatan & Gred</label>
            <input type="text" class="form-control" name="jawatan_gred" value="<?php echo htmlspecialchars($wilayah_asal['jawatan_gred']); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Email Penyelia</label>
            <input type="email" class="form-control" name="email_penyelia" value="<?php echo htmlspecialchars($wilayah_asal['email_penyelia']); ?>">
        </div>
    </div>

    <!-- Address Information -->
    <div class="col-md-6 mb-3">
        <h5>Alamat Menetap</h5>
        <div class="mb-2">
            <label class="form-label">Alamat 1</label>
            <input type="text" class="form-control" name="alamat_menetap_1" value="<?php echo htmlspecialchars($wilayah_asal['alamat_menetap_1']); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Alamat 2</label>
            <input type="text" class="form-control" name="alamat_menetap_2" value="<?php echo htmlspecialchars($wilayah_asal['alamat_menetap_2']); ?>">
        </div>
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Poskod</label>
                <input type="text" class="form-control" name="poskod_menetap" value="<?php echo htmlspecialchars($wilayah_asal['poskod_menetap']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Bandar</label>
                <input type="text" class="form-control" name="bandar_menetap" value="<?php echo htmlspecialchars($wilayah_asal['bandar_menetap']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Negeri</label>
                <input type="text" class="form-control" name="negeri_menetap" value="<?php echo htmlspecialchars($wilayah_asal['negeri_menetap']); ?>">
            </div>
        </div>
    </div>

    <!-- Service Address -->
    <div class="col-md-6 mb-3">
        <h5>Alamat Berkhidmat</h5>
        <div class="mb-2">
            <label class="form-label">Alamat 1</label>
            <input type="text" class="form-control" name="alamat_berkhidmat_1" value="<?php echo htmlspecialchars($wilayah_asal['alamat_berkhidmat_1']); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Alamat 2</label>
            <input type="text" class="form-control" name="alamat_berkhidmat_2" value="<?php echo htmlspecialchars($wilayah_asal['alamat_berkhidmat_2']); ?>">
        </div>
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">Poskod</label>
                <input type="text" class="form-control" name="poskod_berkhidmat" value="<?php echo htmlspecialchars($wilayah_asal['poskod_berkhidmat']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Bandar</label>
                <input type="text" class="form-control" name="bandar_berkhidmat" value="<?php echo htmlspecialchars($wilayah_asal['bandar_berkhidmat']); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Negeri</label>
                <input type="text" class="form-control" name="negeri_berkhidmat" value="<?php echo htmlspecialchars($wilayah_asal['negeri_berkhidmat']); ?>">
            </div>
        </div>
    </div>

    <!-- Dates -->
    <div class="col-md-6 mb-3">
        <h5>Tarikh Penting</h5>
        <div class="mb-2">
            <label class="form-label">Tarikh Lapor Diri</label>
            <input type="date" class="form-control" name="tarikh_lapor_diri" value="<?php echo htmlspecialchars($wilayah_asal['tarikh_lapor_diri']); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Tarikh Terakhir Kemudahan</label>
            <input type="date" class="form-control" name="tarikh_terakhir_kemudahan" value="<?php echo htmlspecialchars($wilayah_asal['tarikh_terakhir_kemudahan']); ?>">
        </div>
    </div>

    <!-- Spouse Information -->
    <div class="col-md-6 mb-3">
        <h5>Maklumat Pasangan</h5>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Nama Pertama</label>
                <input type="text" class="form-control" name="nama_first_pasangan" value="<?php echo htmlspecialchars($wilayah_asal['nama_first_pasangan']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Akhir</label>
                <input type="text" class="form-control" name="nama_last_pasangan" value="<?php echo htmlspecialchars($wilayah_asal['nama_last_pasangan']); ?>">
            </div>
        </div>
        <div class="mb-2">
            <label class="form-label">No. KP</label>
            <input type="text" class="form-control" name="no_kp_pasangan" value="<?php echo htmlspecialchars($wilayah_asal['no_kp_pasangan']); ?>">
        </div>
    </div>

    <!-- Flight Information -->
    <div class="col-md-6 mb-3">
        <h5>Maklumat Penerbangan</h5>
        <div class="mb-2">
            <label class="form-label">Tarikh Penerbangan Pergi</label>
            <input type="date" class="form-control" name="tarikh_penerbangan_pergi" value="<?php echo htmlspecialchars($wilayah_asal['tarikh_penerbangan_pergi']); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Tarikh Penerbangan Balik</label>
            <input type="date" class="form-control" name="tarikh_penerbangan_balik" value="<?php echo htmlspecialchars($wilayah_asal['tarikh_penerbangan_balik']); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Tarikh Penerbangan Pergi (Pasangan)</label>
            <input type="date" class="form-control" name="tarikh_penerbangan_pergi_pasangan" value="<?php echo htmlspecialchars($wilayah_asal['tarikh_penerbangan_pergi_pasangan']); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Tarikh Penerbangan Balik (Pasangan)</label>
            <input type="date" class="form-control" name="tarikh_penerbangan_balik_pasangan" value="<?php echo htmlspecialchars($wilayah_asal['tarikh_penerbangan_balik_pasangan']); ?>">
        </div>
    </div>

    <!-- Journey Points -->
    <div class="col-md-6 mb-3">
        <h5>Maklumat Perjalanan</h5>
        <div class="mb-2">
            <label class="form-label">Titik Permulaan</label>
            <input type="text" class="form-control" name="start_point" value="<?php echo htmlspecialchars($wilayah_asal['start_point']); ?>">
        </div>
        <div class="mb-2">
            <label class="form-label">Titik Destinasi</label>
            <input type="text" class="form-control" name="end_point" value="<?php echo htmlspecialchars($wilayah_asal['end_point']); ?>">
        </div>
    </div>
</div> 