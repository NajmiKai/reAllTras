<?php
include '../../connection.php';

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    header("Location: ../../login.php");
    exit();
}

$user_icNo = $user_data['kp'];

// Check if user has wilayah_asal record
$check_sql = "SELECT * FROM wilayah_asal WHERE user_kp = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $user_icNo);
$check_stmt->execute();
$wilayah_asal_result = $check_stmt->get_result();
$wilayah_asal_data = $wilayah_asal_result->fetch_assoc();

// If no wilayah_asal record exists, redirect to borangWA
if (!$wilayah_asal_data) {
    header("Location: borangWA.php");
    exit();
}

// Fetch pengikut data if exists
$pengikut_sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
$pengikut_stmt = $conn->prepare($pengikut_sql);
$pengikut_stmt->bind_param("i", $wilayah_asal_data['id']);
$pengikut_stmt->execute();
$pengikut_result = $pengikut_stmt->get_result();
$pengikut_data = [];
while ($row = $pengikut_result->fetch_assoc()) {
    $pengikut_data[] = $row;
}
?>

<div class="main-container">
    <!-- Main Content -->
    <div class="col p-4">
        <form action="includes/process_DikuiriWA3.php" method="POST" class="needs-validation" novalidate>
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Maklumat Penerbangan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Jenis Permohonan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_permohonan" id="diri_sendiri" value="diri_sendiri" <?php echo ($wilayah_asal_data['jenis_permohonan'] == 'diri_sendiri') ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="diri_sendiri">
                                    Diri Sendiri/ Pasangan/ Anak Ke Wilayah Ditetapkan
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_permohonan" id="keluarga" value="keluarga" <?php echo ($wilayah_asal_data['jenis_permohonan'] == 'keluarga') ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="keluarga">
                                    Keluarga Pegawai ke Wilayah Berkhidmat
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Penerbangan Pergi</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_pergi" value="<?php echo $wilayah_asal_data['tarikh_penerbangan_pergi']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Penerbangan Balik</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_balik" value="<?php echo $wilayah_asal_data['tarikh_penerbangan_balik']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lapangan Terbang Berlepas</label>
                            <input type="text" class="form-control" name="start_point" value="<?php echo $wilayah_asal_data['start_point']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lapangan Terbang Tiba</label>
                            <input type="text" class="form-control" name="end_point" value="<?php echo $wilayah_asal_data['end_point']; ?>" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Tarikh Penerbangan Pasangan Lain? <span style="font-size: 0.9em; font-style: italic; color: #666;">(Untuk pegawai yang tidak berkenaan, Tanda Tidak)</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="partner_flight_type" id="partner_same" value="same" <?php echo (!$wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']) ? 'checked' : ''; ?> onchange="togglePartnerDates('same')">
                                <label class="form-check-label" for="partner_same">
                                    Tidak
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="partner_flight_type" id="partner_different" value="different" <?php echo ($wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']) ? 'checked' : ''; ?> onchange="togglePartnerDates('different')">
                                <label class="form-check-label" for="partner_different">
                                    Ya
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 partner-dates" style="display: <?php echo ($wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']) ? 'block' : 'none'; ?>;">
                            <label class="form-label">Tarikh Penerbangan Pergi Pasangan</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_pergi_pasangan" value="<?php echo $wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']; ?>">
                        </div>
                        <div class="col-md-6 partner-dates" style="display: <?php echo ($wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']) ? 'block' : 'none'; ?>;">
                            <label class="form-label">Tarikh Penerbangan Balik Pasangan</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_balik_pasangan" value="<?php echo $wilayah_asal_data['tarikh_penerbangan_balik_pasangan']; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accompanying Persons -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Maklumat Pengikut</h5>
                </div>
                <div class="card-body">
                    <div id="followers-container">
                        <?php foreach ($pengikut_data as $index => $pengikut): ?>
                        <div class="follower-entry mb-3 p-3 border rounded" id="follower-<?php echo $index; ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Pengikut <?php echo $index + 1; ?></h6>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeFollower(<?php echo $index; ?>)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Nama Depan</label>
                                    <input type="text" class="form-control" name="followers[<?php echo $index; ?>][nama_first]" value="<?php echo $pengikut['nama_first_pengikut']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Nama Belakang</label>
                                    <input type="text" class="form-control" name="followers[<?php echo $index; ?>][nama_last]" value="<?php echo $pengikut['nama_last_pengikut']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Tarikh Lahir</label>
                                    <input type="date" class="form-control" name="followers[<?php echo $index; ?>][tarikh_lahir]" value="<?php echo $pengikut['tarikh_lahir_pengikut']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">No. KP</label>
                                    <input type="text" class="form-control" name="followers[<?php echo $index; ?>][kp]" value="<?php echo $pengikut['kp_pengikut']; ?>" required>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label">Tarikh Penerbangan</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="followers[<?php echo $index; ?>][flight_date_type]" 
                                            id="same_flight_<?php echo $index; ?>" value="same" 
                                            <?php echo ($pengikut['tarikh_penerbangan_pergi_pengikut'] == $wilayah_asal_data['tarikh_penerbangan_pergi']) ? 'checked' : ''; ?>
                                            onchange="toggleFlightDates(<?php echo $index; ?>, 'same')">
                                        <label class="form-check-label" for="same_flight_<?php echo $index; ?>">
                                            Tarikh Penerbangan Sama
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="followers[<?php echo $index; ?>][flight_date_type]" 
                                            id="different_flight_<?php echo $index; ?>" value="different" 
                                            <?php echo ($pengikut['tarikh_penerbangan_pergi_pengikut'] != $wilayah_asal_data['tarikh_penerbangan_pergi']) ? 'checked' : ''; ?>
                                            onchange="toggleFlightDates(<?php echo $index; ?>, 'different')">
                                        <label class="form-check-label" for="different_flight_<?php echo $index; ?>">
                                            Tarikh Penerbangan Lain
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2 custom-flight-dates-<?php echo $index; ?>" style="display: <?php echo ($pengikut['tarikh_penerbangan_pergi_pengikut'] != $wilayah_asal_data['tarikh_penerbangan_pergi']) ? 'block' : 'none'; ?>;">
                                    <label class="form-label">Tarikh Penerbangan Pergi</label>
                                    <input type="date" class="form-control" name="followers[<?php echo $index; ?>][tarikh_penerbangan_pergi_pengikut]" value="<?php echo $pengikut['tarikh_penerbangan_pergi_pengikut']; ?>">
                                </div>
                                <div class="col-md-6 mb-2 custom-flight-dates-<?php echo $index; ?>" style="display: <?php echo ($pengikut['tarikh_penerbangan_pergi_pengikut'] != $wilayah_asal_data['tarikh_penerbangan_pergi']) ? 'block' : 'none'; ?>;">
                                    <label class="form-label">Tarikh Penerbangan Balik</label>
                                    <input type="date" class="form-control" name="followers[<?php echo $index; ?>][tarikh_penerbangan_balik_pengikut]" value="<?php echo $pengikut['tarikh_penerbangan_balik_pengikut']; ?>">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-outline-primary mt-3" onclick="addFollower()">
                        <i class="fas fa-plus me-2"></i>Tambah Pengikut
                    </button>
                </div>
            </div>
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
                <a href="wilayahAsal.php" class="btn btn-secondary ms-2">
                            <i class="fas fa-times me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    // Follower management
    let followerCount = <?php echo count($pengikut_data); ?>;

    function addFollower() {
        const container = document.getElementById('followers-container');
        const followerDiv = document.createElement('div');
        followerDiv.className = 'follower-entry mb-3 p-3 border rounded';
        followerDiv.id = `follower-${followerCount}`;

        followerDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Pengikut ${followerCount + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeFollower(${followerCount})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">Nama Depan</label>
                    <input type="text" class="form-control" name="nama_first_pengikut[]" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Nama Belakang</label>
                    <input type="text" class="form-control" name="nama_last_pengikut[]" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Tarikh Lahir</label>
                    <input type="date" class="form-control" name="tarikh_lahir_pengikut[]" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">No. KP</label>
                    <input type="text" class="form-control" name="kp_pengikut[]" required>
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label">Tarikh Penerbangan</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flight_date_type_${followerCount}" 
                            id="same_flight_${followerCount}" value="same" checked 
                            onchange="toggleFlightDates(${followerCount}, 'same')">
                        <label class="form-check-label" for="same_flight_${followerCount}">
                            Tarikh Penerbangan Sama
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flight_date_type_${followerCount}" 
                            id="different_flight_${followerCount}" value="different" 
                            onchange="toggleFlightDates(${followerCount}, 'different')">
                        <label class="form-check-label" for="different_flight_${followerCount}">
                            Tarikh Penerbangan Lain
                        </label>
                    </div>
                </div>
                <div class="col-md-6 mb-2 custom-flight-dates-${followerCount}" style="display: none;">
                    <label class="form-label">Tarikh Penerbangan Pergi</label>
                    <input type="date" class="form-control" name="tarikh_penerbangan_pergi_pengikut[]">
                </div>
                <div class="col-md-6 mb-2 custom-flight-dates-${followerCount}" style="display: none;">
                    <label class="form-label">Tarikh Penerbangan Balik</label>
                    <input type="date" class="form-control" name="tarikh_penerbangan_balik_pengikut[]">
                </div>
            </div>
        `;

        container.appendChild(followerDiv);
        followerCount++;
    }

    function removeFollower(index) {
        const followerDiv = document.getElementById(`follower-${index}`);
        if (followerDiv) {
            followerDiv.remove();
        }
    }

    function toggleFlightDates(followerIndex, type) {
        const customDatesDiv = document.querySelectorAll(`.custom-flight-dates-${followerIndex}`);
        const mainFlightDates = {
            pergi: document.querySelector('input[name="tarikh_penerbangan_pergi"]').value,
            balik: document.querySelector('input[name="tarikh_penerbangan_balik"]').value
        };

        if (type === 'same') {
            customDatesDiv.forEach(div => div.style.display = 'none');
            // Set hidden inputs for same flight dates
            const hiddenPergi = document.createElement('input');
            hiddenPergi.type = 'hidden';
            hiddenPergi.name = 'tarikh_penerbangan_pergi_pengikut[]';
            hiddenPergi.value = mainFlightDates.pergi;
            
            const hiddenBalik = document.createElement('input');
            hiddenBalik.type = 'hidden';
            hiddenBalik.name = 'tarikh_penerbangan_balik_pengikut[]';
            hiddenBalik.value = mainFlightDates.balik;

            const followerDiv = document.getElementById(`follower-${followerIndex}`);
            followerDiv.appendChild(hiddenPergi);
            followerDiv.appendChild(hiddenBalik);
        } else {
            customDatesDiv.forEach(div => div.style.display = 'block');
            // Remove hidden inputs if they exist
            const followerDiv = document.getElementById(`follower-${followerIndex}`);
            const hiddenInputs = followerDiv.querySelectorAll('input[type="hidden"]');
            hiddenInputs.forEach(input => input.remove());
        }
    }

    function togglePartnerDates(type) {
        const partnerDates = document.querySelectorAll('.partner-dates');
        const partnerDateInputs = document.querySelectorAll('.partner-dates input');
        
        if (type === 'different') {
            partnerDates.forEach(element => {
                element.style.display = 'block';
            });
            partnerDateInputs.forEach(input => {
                input.required = true;
            });
        } else {
            partnerDates.forEach(element => {
                element.style.display = 'none';
            });
            partnerDateInputs.forEach(input => {
                input.required = false;
                input.value = '';
            });
        }
    }

    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });
</script>
</body>
</html> 