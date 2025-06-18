<?php
include '../../connection.php';
include '../../includes/config.php';

$wilayah_asal_id = $_SESSION['wilayah_asal_id'] ?? null;

// Check if user has wilayah_asal record
$check_sql = "SELECT * FROM wilayah_asal WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $wilayah_asal_id);
$check_stmt->execute();
$wilayah_asal_result = $check_stmt->get_result();
$wilayah_asal_data = $wilayah_asal_result->fetch_assoc();

// If no wilayah_asal record exists, redirect to dashboard
if (!$wilayah_asal_data) {
    header("Location: dashboard.php");
    exit();
}

// Fetch followers data
$followers_sql = "SELECT * FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
$followers_stmt = $conn->prepare($followers_sql);
$followers_stmt->bind_param("i", $wilayah_asal_id);
$followers_stmt->execute();
$followers_result = $followers_stmt->get_result();
$followers_data = [];
while ($row = $followers_result->fetch_assoc()) {
    $followers_data[] = $row;
}

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    header("Location: ../../loginUser.php");
    exit();
}

$user_name = $user_data['nama_first'] . ' ' . $user_data['nama_last'];
$user_role = $user_data['bahagian'];
$user_icNo = $user_data['kp'];
$user_email = $user_data['email'];
$user_phoneNo = $user_data['phone'];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Borang Wilayah Asal (Bahagian 3)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
    <link rel="stylesheet" href="../../assets/css/multi-step.css">
    <style>
        .multi-step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 2rem 0;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .step.completed .step-icon {
            background-color: #d59e3e;
            border-color: #d59e3e;
            color: white;
        }

        .step.active .step-icon {
            background-color: #d59e3e;
            border-color: #d59e3e;
            color: white;
        }

        .step-label {
            font-size: 0.875rem;
            color: #6c757d;
            text-align: center;
        }

        .step.active .step-label {
            color: #d59e3e;
            font-weight: 600;
        }

        .step.completed .step-label {
            color: #d59e3e;
            font-weight: 600;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background-color: #dee2e6;
            margin: 0 1rem;
            position: relative;
            top: -25px;
        }

        .follower-card {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f8f9fa;
        }

        .remove-follower {
            color: #dc3545;
            cursor: pointer;
            font-size: 1.25rem;
        }

        .remove-follower:hover {
            color: #bb2d3b;
        }
    </style>
</head>
<body>

<div class="main-container">

    <!-- Main Content -->
    <div class="col p-4">
        <form action="includes/process_DikuiriWA3.php" method="POST" class="needs-validation" novalidate>
            <!-- Flight Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><i class="fas fa-plane me-2"></i>Maklumat Penerbangan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Jenis Permohonan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_permohonan" id="diri_sendiri" value="diri_sendiri" <?= $wilayah_asal_data['jenis_permohonan'] === 'diri_sendiri' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="diri_sendiri">Diri Sendiri/ Pasangan/ Anak Ke Wilayah Ditetapkan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_permohonan" id="keluarga" value="keluarga" <?= $wilayah_asal_data['jenis_permohonan'] === 'keluarga' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="keluarga">Keluarga Pegawai ke Wilayah Berkhidmat</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Penerbangan Pergi</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_pergi" value="<?= htmlspecialchars($wilayah_asal_data['tarikh_penerbangan_pergi']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Penerbangan Balik</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_balik" value="<?= htmlspecialchars($wilayah_asal_data['tarikh_penerbangan_balik']) ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Lapangan Terbang Berlepas</label>
                            <input type="text" class="form-control" name="start_point" value="<?= htmlspecialchars($wilayah_asal_data['start_point']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lapangan Terbang Tiba</label>
                            <input type="text" class="form-control" name="end_point" value="<?= htmlspecialchars($wilayah_asal_data['end_point']) ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partner Flight Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><i class="fas fa-user-friends me-2"></i>Maklumat Penerbangan Pasangan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tarikh Penerbangan Pasangan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="partner_flight_type" id="same_flight" value="same" 
                                    <?= ($wilayah_asal_data['tarikh_penerbangan_pergi'] == $wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']) ? 'checked' : '' ?> 
                                    onchange="togglePartnerDates('same')">
                                <label class="form-check-label" for="same_flight">Sama dengan tarikh penerbangan pemohon</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="partner_flight_type" id="different_flight" value="different" 
                                    <?= ($wilayah_asal_data['tarikh_penerbangan_pergi'] != $wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']) ? 'checked' : '' ?> 
                                    onchange="togglePartnerDates('different')">
                                <label class="form-check-label" for="different_flight">Berbeza dengan tarikh penerbangan pemohon</label>
                            </div>
                        </div>

                        <div class="col-md-6 partner-dates" style="display: <?= ($wilayah_asal_data['tarikh_penerbangan_pergi'] != $wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']) ? 'block' : 'none' ?>;">
                            <label class="form-label">Tarikh Penerbangan Pergi Pasangan</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_pergi_pasangan" value="<?= htmlspecialchars($wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']) ?>">
                        </div>
                        <div class="col-md-6 partner-dates" style="display: <?= ($wilayah_asal_data['tarikh_penerbangan_pergi'] != $wilayah_asal_data['tarikh_penerbangan_pergi_pasangan']) ? 'block' : 'none' ?>;">
                            <label class="form-label">Tarikh Penerbangan Balik Pasangan</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_balik_pasangan" value="<?= htmlspecialchars($wilayah_asal_data['tarikh_penerbangan_balik_pasangan']) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Followers Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Maklumat Pengikut</h5>
                </div>
                <div class="card-body">
                    <div id="followers-container">
                        <?php foreach ($followers_data as $index => $follower): ?>
                        <div class="follower-entry mb-3 p-3 border rounded" id="follower-<?= $index ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Pengikut <?= $index + 1 ?></h6>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeFollower(<?= $index ?>, <?= $follower['id'] ?>)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <input type="hidden" name="followers[<?= $index ?>][id]" value="<?= $follower['id'] ?>">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Nama Depan</label>
                                    <input type="text" class="form-control" name="followers[<?= $index ?>][nama_first]" value="<?= htmlspecialchars($follower['nama_first_pengikut']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Nama Belakang</label>
                                    <input type="text" class="form-control" name="followers[<?= $index ?>][nama_last]" value="<?= htmlspecialchars($follower['nama_last_pengikut']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Tarikh Lahir</label>
                                    <input type="date" class="form-control" name="followers[<?= $index ?>][tarikh_lahir]" value="<?= htmlspecialchars($follower['tarikh_lahir_pengikut']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">No. KP</label>
                                    <input type="text" class="form-control" name="followers[<?= $index ?>][kp]" value="<?= htmlspecialchars($follower['kp_pengikut']) ?>" required>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label">Tarikh Penerbangan</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="followers[<?= $index ?>][flight_date_type]" 
                                            id="same_flight_<?= $index ?>" value="same" 
                                            <?= ($follower['tarikh_penerbangan_pergi_pengikut'] == $wilayah_asal_data['tarikh_penerbangan_pergi']) ? 'checked' : '' ?>
                                            onchange="toggleFlightDates(<?= $index ?>, 'same')">
                                        <label class="form-check-label" for="same_flight_<?= $index ?>">
                                            Tarikh Penerbangan Sama
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="followers[<?= $index ?>][flight_date_type]" 
                                            id="different_flight_<?= $index ?>" value="different" 
                                            <?= ($follower['tarikh_penerbangan_pergi_pengikut'] != $wilayah_asal_data['tarikh_penerbangan_pergi']) ? 'checked' : '' ?>
                                            onchange="toggleFlightDates(<?= $index ?>, 'different')">
                                        <label class="form-check-label" for="different_flight_<?= $index ?>">
                                            Tarikh Penerbangan Lain
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2 custom-flight-dates-<?= $index ?>" style="display: <?= ($follower['tarikh_penerbangan_pergi_pengikut'] != $wilayah_asal_data['tarikh_penerbangan_pergi']) ? 'block' : 'none' ?>;">
                                    <label class="form-label">Tarikh Penerbangan Pergi</label>
                                    <input type="date" class="form-control" name="followers[<?= $index ?>][tarikh_penerbangan_pergi_pengikut]" value="<?= htmlspecialchars($follower['tarikh_penerbangan_pergi_pengikut']) ?>">
                                </div>
                                <div class="col-md-6 mb-2 custom-flight-dates-<?= $index ?>" style="display: <?= ($follower['tarikh_penerbangan_pergi_pengikut'] != $wilayah_asal_data['tarikh_penerbangan_pergi']) ? 'block' : 'none' ?>;">
                                    <label class="form-label">Tarikh Penerbangan Balik</label>
                                    <input type="date" class="form-control" name="followers[<?= $index ?>][tarikh_penerbangan_balik_pengikut]" value="<?= htmlspecialchars($follower['tarikh_penerbangan_balik_pengikut']) ?>">
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

            <div class="d-flex justify-content-between mt-4">
                <a href="wilayahAsal.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan<i class="fas fa-save ms-2"></i>
                </button>
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
    let followerCount = <?= count($followers_data) ?>;

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
                    <input type="text" class="form-control" name="followers[${followerCount}][nama_first]" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Nama Belakang</label>
                    <input type="text" class="form-control" name="followers[${followerCount}][nama_last]" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Tarikh Lahir</label>
                    <input type="date" class="form-control" name="followers[${followerCount}][tarikh_lahir]" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">No. KP</label>
                    <input type="text" class="form-control" name="followers[${followerCount}][kp]" required>
                </div>
                <div class="col-12 mb-2">
                    <label class="form-label">Tarikh Penerbangan</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="followers[${followerCount}][flight_date_type]" 
                            id="same_flight_${followerCount}" value="same" checked 
                            onchange="toggleFlightDates(${followerCount}, 'same')">
                        <label class="form-check-label" for="same_flight_${followerCount}">
                            Tarikh Penerbangan Sama
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="followers[${followerCount}][flight_date_type]" 
                            id="different_flight_${followerCount}" value="different" 
                            onchange="toggleFlightDates(${followerCount}, 'different')">
                        <label class="form-check-label" for="different_flight_${followerCount}">
                            Tarikh Penerbangan Lain
                        </label>
                    </div>
                </div>
                <div class="col-md-6 mb-2 custom-flight-dates-${followerCount}" style="display: none;">
                    <label class="form-label">Tarikh Penerbangan Pergi</label>
                    <input type="date" class="form-control" name="followers[${followerCount}][tarikh_penerbangan_pergi_pengikut]">
                </div>
                <div class="col-md-6 mb-2 custom-flight-dates-${followerCount}" style="display: none;">
                    <label class="form-label">Tarikh Penerbangan Balik</label>
                    <input type="date" class="form-control" name="followers[${followerCount}][tarikh_penerbangan_balik_pengikut]">
                </div>
            </div>
        `;

        container.appendChild(followerDiv);
        followerCount++;
    }

    function removeFollower(index, id = null) {
        const followerDiv = document.getElementById(`follower-${index}`);
        if (followerDiv) {
            if (id) {
                // If this is an existing follower, add a hidden input to mark it for deletion
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = `deleted_followers[]`;
                deleteInput.value = id;
                followerDiv.appendChild(deleteInput);
                followerDiv.style.display = 'none';
            } else {
                // If this is a new follower, just remove the div
                followerDiv.remove();
            }
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
            hiddenPergi.name = `followers[${followerIndex}][tarikh_penerbangan_pergi_pengikut]`;
            hiddenPergi.value = mainFlightDates.pergi;
            
            const hiddenBalik = document.createElement('input');
            hiddenBalik.type = 'hidden';
            hiddenBalik.name = `followers[${followerIndex}][tarikh_penerbangan_balik_pengikut]`;
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

    // Show success/error messages if they exist
    <?php if (isset($_SESSION['success'])): ?>
        alert('<?= $_SESSION['success'] ?>');
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        alert('<?= $_SESSION['error'] ?>');
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</script>
</body>
</html>