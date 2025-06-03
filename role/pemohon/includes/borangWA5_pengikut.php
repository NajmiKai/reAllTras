<?php
if (empty($pengikut_list)) {
    echo '<div class="alert alert-info">Tiada pengikut didaftarkan.</div>';
} else {
    foreach ($pengikut_list as $pengikut) {
        ?>
        <div class="card mb-3">
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="update_pengikut" value="1">
                    <input type="hidden" name="pengikut_id" value="<?php echo $pengikut['id']; ?>">
                    <input type="hidden" name="wilayah_asal_id" value="<?php echo $wilayah_asal_id; ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Nama Pertama</label>
                                <input type="text" class="form-control" name="nama_first_pengikut" 
                                       value="<?php echo htmlspecialchars($pengikut['nama_first_pengikut']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Nama Akhir</label>
                                <input type="text" class="form-control" name="nama_last_pengikut" 
                                       value="<?php echo htmlspecialchars($pengikut['nama_last_pengikut']); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Tarikh Lahir</label>
                                <input type="date" class="form-control" name="tarikh_lahir_pengikut" 
                                       value="<?php echo htmlspecialchars($pengikut['tarikh_lahir_pengikut']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">No. KP</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($pengikut['kp_pengikut']); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Tarikh Penerbangan Pergi</label>
                                <input type="date" class="form-control" name="tarikh_penerbangan_pergi_pengikut" 
                                       value="<?php echo htmlspecialchars($pengikut['tarikh_penerbangan_pergi_pengikut']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Tarikh Penerbangan Balik</label>
                                <input type="date" class="form-control" name="tarikh_penerbangan_balik_pengikut" 
                                       value="<?php echo htmlspecialchars($pengikut['tarikh_penerbangan_balik_pengikut']); ?>">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Kemaskini Maklumat Pengikut</button>
                </form>
            </div>
        </div>
        <?php
    }
}
?> 