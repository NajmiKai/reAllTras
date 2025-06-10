<?php
session_start();
include '../../connection.php';

$wilayah_asal_id = $_SESSION['wilayah_asal_id'] ?? null;

// Check if user has wilayah_asal record
$check_sql = "SELECT * FROM wilayah_asal WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $wilayah_asal_id);
$check_stmt->execute();
$wilayah_asal_result = $check_stmt->get_result();
$wilayah_asal_data = $wilayah_asal_result->fetch_assoc();

// If no wilayah_asal record exists, redirect to borangWA
if (!$wilayah_asal_data) {
    header("Location: dashboard.php");
    exit();
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

<!-- Top Navbar -->
<nav class="navbar navbar-expand navbar-light bg-light shadow-sm px-3 mb-4 w-100">
    <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="nav-link toggle-sidebar" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <span class="nav-link fw-semibold"><?= htmlspecialchars($user_name) ?> (<?= htmlspecialchars($user_role) ?>)</span>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../logoutUser.php" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt me-1"></i> Log Keluar
            </a>
        </li>
    </ul>
</nav>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Borang Permohonan Wilayah Asal (Bahagian 3)</h3>
        
        <!-- Multi-step Indicator -->
        <div class="multi-step-indicator mb-4">
            <div class="step completed">
                <div class="step-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="step-label">Maklumat Pegawai</div>
            </div>
            <div class="step-line"></div>
            <div class="step completed">
                <div class="step-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="step-label">Maklumat Wilayah Menetap Ibu Bapa</div>
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-icon">
                    <i class="fas fa-plane"></i>
                </div>
                <div class="step-label">Maklumat Penerbangan</div>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-icon">
                    <i class="fas fa-file-upload"></i>
                </div>
                <div class="step-label">Muat Naik Dokumen</div>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="step-label">Pengesahan Maklumat</div>
            </div>
        </div>

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
                                <input class="form-check-input" type="radio" name="jenis_permohonan" id="permohonan_asal" value="Asal" <?= $wilayah_asal_data['jenis_permohonan'] === 'Asal' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="permohonan_asal">Asal</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_permohonan" id="permohonan_balik" value="Balik" <?= $wilayah_asal_data['jenis_permohonan'] === 'Balik' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="permohonan_balik">Balik</label>
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
                            <label class="form-label">Titik Permulaan</label>
                            <input type="text" class="form-control" name="start_point" value="<?= htmlspecialchars($wilayah_asal_data['start_point']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Destinasi</label>
                            <input type="text" class="form-control" name="end_point" value="<?= htmlspecialchars($wilayah_asal_data['end_point']) ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partner Flight Information (if applicable) -->
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

            <div class="d-flex justify-content-between mt-4">
                <a href="wilayahAsal.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    Seterusnya<i class="fas fa-arrow-right ms-2"></i>
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
    let followerCount = 0;

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

    // Update form submission handler
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate followers data
        const followers = [];
        const followerInputs = document.querySelectorAll('.follower-entry');
        
        followerInputs.forEach((follower, index) => {
            const nama_first = follower.querySelector(`input[name="followers[${index}][nama_first]"]`).value;
            const nama_last = follower.querySelector(`input[name="followers[${index}][nama_last]"]`).value;
            const tarikh_lahir = follower.querySelector(`input[name="followers[${index}][tarikh_lahir]"]`).value;
            const kp = follower.querySelector(`input[name="followers[${index}][kp]"]`).value;
            const flight_date_type = follower.querySelector(`input[name="followers[${index}][flight_date_type]"]:checked`).value;

            let tarikh_penerbangan_pergi_pengikut, tarikh_penerbangan_balik_pengikut;

            if (flight_date_type === 'same') {
                tarikh_penerbangan_pergi_pengikut = document.querySelector('input[name="tarikh_penerbangan_pergi"]').value;
                tarikh_penerbangan_balik_pengikut = document.querySelector('input[name="tarikh_penerbangan_balik"]').value;
            } else {
                tarikh_penerbangan_pergi_pengikut = follower.querySelector(`input[name="followers[${index}][tarikh_penerbangan_pergi_pengikut]"]`).value;
                tarikh_penerbangan_balik_pengikut = follower.querySelector(`input[name="followers[${index}][tarikh_penerbangan_balik_pengikut]"]`).value;
            }

            if (nama_first && nama_last && tarikh_lahir && kp) {
                followers.push({
                    nama_first,
                    nama_last,
                    tarikh_lahir,
                    kp,
                    tarikh_penerbangan_pergi_pengikut,
                    tarikh_penerbangan_balik_pengikut
                });
            }
        });

        // Add followers data to form
        const followersInput = document.createElement('input');
        followersInput.type = 'hidden';
        followersInput.name = 'followers_data';
        followersInput.value = JSON.stringify(followers);
        this.appendChild(followersInput);

        // Submit the form
        this.submit();
    });

    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });
</script>
</body>
</html>