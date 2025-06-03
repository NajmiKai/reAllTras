<?php
session_start();
include '../../connection.php';

// Check if user is logged in as Super Admin
if (!isset($_SESSION['super_admin_id'])) {
    header("Location: ../../loginSuperAdmin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from form
    $nama = $_POST['name'];
    $icNo = $_POST['icNo'];
    $email = $_POST['email'];
    $phoneNo = $_POST['phoneNo'];
    $kataLaluan = $_POST['password'];
    $pengesahan = $_POST['confirmPassword'];
    $role = $_POST['role'];

    // Basic validation
    if ($kataLaluan !== $pengesahan) {
        echo "<script>alert('Kata laluan dan pengesahan tidak sepadan'); window.history.back();</script>";
        exit();
    }

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT Email FROM admin WHERE Email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();
    
    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Emel sudah didaftarkan'); window.history.back();</script>";
        exit();
    }

    // Check if IC already exists
    $checkIC = $conn->prepare("SELECT ICNo FROM admin WHERE ICNo = ?");
    $checkIC->bind_param("s", $icNo);
    $checkIC->execute();
    $checkIC->store_result();
    
    if ($checkIC->num_rows > 0) {
        echo "<script>alert('Nombor KP sudah didaftarkan'); window.history.back();</script>";
        exit();
    }

    // Hash the password
    $kataLaluanHash = password_hash($kataLaluan, PASSWORD_DEFAULT);

    // Insert into admin table
    $stmt = $conn->prepare("INSERT INTO admin (Name, ICNo, Email, PhoneNo, Password, Role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nama, $icNo, $email, $phoneNo, $kataLaluanHash, $role);

    if ($stmt->execute()) {
        // Log the registration
        $super_admin_id = $_SESSION['super_admin_id'];
        $log_message = "Super Admin registered new admin: $nama ($email) with role: $role";
        $log_stmt = $conn->prepare("INSERT INTO system_logs (admin_id, action, details) VALUES (?, 'Register Admin', ?)");
        $log_stmt->bind_param("is", $super_admin_id, $log_message);
        $log_stmt->execute();

        echo "<script>alert('Pendaftaran admin berjaya!'); window.location.href='manageAdmins.php';</script>";
    } else {
        echo "<script>alert('Ralat: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Daftar Admin - ALLTRAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/adminStyle.css">
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
            <span class="nav-link fw-semibold"><?= htmlspecialchars($_SESSION['super_admin_name']) ?> (Super Admin)</span>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../logout.php" class="nav-link text-danger">
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
        <h6 class="text mt-4">PENGURUSAN SISTEM</h6>
        <a href="manageAdmins.php" class="active"><i class="fas fa-users-cog me-2"></i>Pengurusan Admin</a>
        <a href="manageUsers.php"><i class="fas fa-users me-2"></i>Pengurusan Pengguna</a>
        <a href="systemLogs.php"><i class="fas fa-history me-2"></i>Log Sistem</a>
        <a href="profile.php"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Daftar Admin Baru</h3>
            <a href="manageAdmins.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="registerSuperAdmin.php" method="POST" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="name" placeholder="Nama" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kad Pengenalan</label>
                            <input type="text" class="form-control" placeholder="Nombor IC" name="icNo" maxlength="14" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Emel</label>
                            <input type="email" class="form-control" name="email" placeholder="Emel" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No Telefon</label>
                            <input type="text" class="form-control" placeholder="No Telefon" name="phoneNo" maxlength="11" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kata Laluan</label>
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="Kata Laluan" name="password" id="password" required>
                                <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="passwordToggle"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pengesahan Kata Laluan</label>
                            <div class="input-group">
                                <input type="password" class="form-control" placeholder="Pengesahan Kata Laluan" name="confirmPassword" id="confirmPassword" required>
                                <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('confirmPassword')">
                                    <i class="fas fa-eye" id="confirmPasswordToggle"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Peranan Admin</label>
                            <select class="form-select" name="role" required>
                                <option value="" selected disabled>Pilih peranan</option>
                                <?php
                                $sql = "SELECT role FROM adminRole";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['role']) . "'>" . htmlspecialchars($row['role']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary px-4">Daftar Admin</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(inputId + 'Toggle');
        
        if (input.type === 'password') {
            input.type = 'text';
            toggle.classList.remove('fa-eye');
            toggle.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            toggle.classList.remove('fa-eye-slash');
            toggle.classList.add('fa-eye');
        }
    }

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
</script>
</body>
</html> 