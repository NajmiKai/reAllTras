<?php
session_start();
include '../../connection.php';

// Set user ID in session if not set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Set default user ID to 1
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
    <title>ALLTRAS - Borang Wilayah Asal (Bahagian 1)</title>
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
    <div class="sidebar" id="sidebar">
        <h6><img src="../../assets/ALLTRAS.png" alt="ALLTRAS" width="140" style="margin-left: 20px;"><br>ALL REGION TRAVELLING SYSTEM</h6><br>
        <a href="dashboard.php"><i class="fas fa-home me-2"></i>Laman Utama</a>
        <h6 class="text mt-4"></h6>
        <a href="wilayahAsal.php"><i class="fas fa-map-marker-alt me-2"></i>Wilayah Asal</a>
        <a href="tugasRasmi.php"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a>
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../logoutUser.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Borang Permohonan Wilayah Asal (Bahagian 1)</h3>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Multi-step Indicator -->
        <div class="multi-step-indicator mb-4">
            <div class="step active">
                <div class="step-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="step-label">Maklumat Pegawai</div>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="step-label">Maklumat Wilayah Menetap Ibu Bapa</div>
            </div>
            <div class="step-line"></div>
            <div class="step">
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

        <form action="includes/process_borangWA.php" method="POST" class="needs-validation" novalidate>
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
                            <input type="text" class="form-control" name="jawatan_gred" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Ketua Cawangan <span style="font-size: 0.9em; font-style: italic; color: #666;">(Email Ketua Bahagian untuk KC dan Setaraf)</span></label>
                            <input type="email" class="form-control" name="email_penyelia" required>
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
                            <input type="text" class="form-control" name="alamat_menetap_1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat 2</label>
                            <input type="text" class="form-control" name="alamat_menetap_2" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Poskod</label>
                            <input type="text" class="form-control" name="poskod_menetap" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bandar</label>
                            <input type="text" class="form-control" name="bandar_menetap" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Negeri</label>
                            <input type="text" class="form-control" name="negeri_menetap" required>
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
                            <input type="text" class="form-control" name="alamat_berkhidmat_1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat 2</label>
                            <input type="text" class="form-control" name="alamat_berkhidmat_2" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Poskod</label>
                            <input type="text" class="form-control" name="poskod_berkhidmat" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bandar</label>
                            <input type="text" class="form-control" name="bandar_berkhidmat" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Negeri</label>
                            <input type="text" class="form-control" name="negeri_berkhidmat" required>
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
                            <input type="date" class="form-control" name="tarikh_lapor_diri" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pernah Menggunakan Kemudahan Ini?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pernah_guna" value="ya" id="pernah_guna_ya" onchange="toggleTarikhTerakhir()">
                                <label class="form-check-label" for="pernah_guna_ya">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pernah_guna" value="tidak" id="pernah_guna_tidak" onchange="toggleTarikhTerakhir()" checked>
                                <label class="form-check-label" for="pernah_guna_tidak">Tidak</label>
                            </div>
                        </div>
                        <div class="col-md-6" id="tarikh_terakhir_container" style="display: none;">
                            <label class="form-label">Tarikh Terakhir Menggunakan Kemudahan</label>
                            <input type="date" class="form-control" name="tarikh_terakhir_kemudahan">
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
                                    <input class="form-check-input" type="radio" name="ada_pasangan" value="ya" id="ada_pasangan_ya" onchange="togglePartnerDetails()">
                                    <label class="form-check-label" for="ada_pasangan_ya">Ya</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ada_pasangan" value="tidak" id="ada_pasangan_tidak" onchange="togglePartnerDetails()" checked>
                                    <label class="form-check-label" for="ada_pasangan_tidak">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <div id="partner_details_container" style="display: none;" class="mt-3">
                            <div class="border rounded p-4 bg-light">
                                <h6 class="mb-4 text-muted border-bottom pb-2">Maklumat Peribadi Pasangan</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Depan Pasangan</label>
                                        <input type="text" class="form-control" name="nama_first_pasangan" maxlength="50">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Belakang Pasangan</label>
                                        <input type="text" class="form-control" name="nama_last_pasangan" maxlength="50">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No. KP Pasangan</label>
                                        <input type="text" class="form-control" name="no_kp_pasangan" maxlength="14" id="no_kp_pasangan" oninput="formatIC(this)" title="Format: XXXXXX-XX-XXXX">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Wilayah Menetap Pasangan</label>
                                        <input type="text" class="form-control" name="wilayah_menetap_pasangan" maxlength="50">
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded p-4 bg-light mt-4">
                                <h6 class="mb-4 text-muted border-bottom pb-2">Alamat Berkhidmat Pasangan</h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Alamat</label>
                                        <input type="text" class="form-control mb-2" name="alamat_berkhidmat_1_pasangan" placeholder="Alamat 1" maxlength="100">
                                        <input type="text" class="form-control" name="alamat_berkhidmat_2_pasangan" placeholder="Alamat 2" maxlength="100">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Poskod</label>
                                        <input type="text" class="form-control" name="poskod_berkhidmat_pasangan" maxlength="10" pattern="[0-9]{5}" title="5 digit poskod">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Bandar</label>
                                        <input type="text" class="form-control" name="bandar_berkhidmat_pasangan" maxlength="50">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Negeri</label>
                                        <input type="text" class="form-control" name="negeri_berkhidmat_pasangan" maxlength="50">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
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

    // Toggle tarikh terakhir field
    function toggleTarikhTerakhir() {
        const pernahGunaYa = document.getElementById('pernah_guna_ya').checked;
        const tarikhTerakhirContainer = document.getElementById('tarikh_terakhir_container');
        const tarikhTerakhirInput = document.querySelector('input[name="tarikh_terakhir_kemudahan"]');
        
        if (pernahGunaYa) {
            tarikhTerakhirContainer.style.display = 'block';
            tarikhTerakhirInput.required = true;
        } else {
            tarikhTerakhirContainer.style.display = 'none';
            tarikhTerakhirInput.required = false;
            tarikhTerakhirInput.value = '';
        }
    }

    function togglePartnerDetails() {
        const partnerDetails = document.getElementById('partner_details_container');
        const hasPartner = document.getElementById('ada_pasangan_ya').checked;
        partnerDetails.style.display = hasPartner ? 'block' : 'none';
        
        // Toggle required attribute for partner fields
        const partnerFields = partnerDetails.querySelectorAll('input');
        partnerFields.forEach(field => {
            field.required = hasPartner;
        });
    }

    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });

    function formatIC(input) {
        // Remove all non-digit characters
        let value = input.value.replace(/\D/g, '');
        
        // Format the value with hyphens
        if (value.length > 0) {
            if (value.length <= 6) {
                value = value;
            } else if (value.length <= 8) {
                value = value.slice(0, 6) + '-' + value.slice(6);
            } else {
                value = value.slice(0, 6) + '-' + value.slice(6, 8) + '-' + value.slice(8, 12);
            }
        }
        
        // Update the display value
        input.value = value;
        
        // Create a hidden input to store the raw value
        let hiddenInput = document.getElementById('no_kp_pasangan_raw');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'no_kp_pasangan_raw';
            hiddenInput.name = 'no_kp_pasangan_raw';
            input.parentNode.appendChild(hiddenInput);
        }
        hiddenInput.value = input.value.replace(/\D/g, '');
    }
</script>
</body>
</html>
