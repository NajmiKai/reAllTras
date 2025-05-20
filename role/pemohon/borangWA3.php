<?php
session_start();
include '../../connection.php';

// Check if required session data exists
if (!isset($_SESSION['borangWA_data']) || !isset($_SESSION['parent_info'])) {
    header("Location: borangWA.php");
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
    header("Location: ../../login.php");
    exit();
}

$admin_name = $user_data['name'];
$admin_role = $user_data['role'];
$admin_icNo = $user_data['ic_no'];
$admin_email = $user_data['email'];
$admin_phoneNo = $user_data['phone_no'];
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Borang Wilayah Asal (Bahagian 3)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/adminStyle.css">
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
            <span class="nav-link fw-semibold"><?= htmlspecialchars($admin_name) ?> (<?= htmlspecialchars($admin_role) ?>)</span>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../../logout.php" class="nav-link text-danger">
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
        <a href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

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
                <div class="step-label">Maklumat Ibu Bapa</div>
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
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="step-label">Pengesahan</div>
            </div>
        </div>

        <form action="../../functions/process_borangWA3.php" method="POST" class="needs-validation" novalidate>
            <!-- Flight Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #d59e3e; color: white;">
                    <h5 class="mb-0">Maklumat Penerbangan</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Penerbangan Pergi</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_pergi" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tarikh Penerbangan Balik</label>
                            <input type="date" class="form-control" name="tarikh_penerbangan_balik" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lapangan Terbang Berlepas</label>
                            <input type="text" class="form-control" name="start_point" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lapangan Terbang Tiba</label>
                            <input type="text" class="form-control" name="end_point" required>
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
                        <!-- Followers will be added here dynamically -->
                    </div>
                    <button type="button" class="btn btn-outline-primary mt-3" onclick="addFollower()">
                        <i class="fas fa-plus me-2"></i>Tambah Pengikut
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="borangWA2.php" class="btn btn-secondary">
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
        followerDiv.className = 'follower-card';
        followerDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Pengikut ${followerCount + 1}</h6>
                <i class="fas fa-times remove-follower" onclick="removeFollower(this)"></i>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Depan</label>
                    <input type="text" class="form-control" name="pengikut[${followerCount}][nama_depan]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Belakang</label>
                    <input type="text" class="form-control" name="pengikut[${followerCount}][nama_belakang]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. Kad Pengenalan</label>
                    <input type="text" class="form-control" name="pengikut[${followerCount}][no_kp]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tarikh Lahir</label>
                    <input type="date" class="form-control" name="pengikut[${followerCount}][tarikh_lahir]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tarikh Penerbangan Pergi</label>
                    <input type="date" class="form-control" name="pengikut[${followerCount}][tarikh_penerbangan_pergi]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tarikh Penerbangan Balik</label>
                    <input type="date" class="form-control" name="pengikut[${followerCount}][tarikh_penerbangan_balik]" required>
                </div>
            </div>
        `;
        container.appendChild(followerDiv);
        followerCount++;
    }

    function removeFollower(element) {
        element.closest('.follower-card').remove();
    }

    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });
</script>
</body>
</html> 