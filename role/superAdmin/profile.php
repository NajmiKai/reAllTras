<?php
session_start();
include '../../connection.php';

// Check if user is logged in
if (!isset($_SESSION['super_admin_id'])) {
    header("Location: ../../loginSuperAdmin.php");
    exit();
}

// Set session timeout duration (in seconds)
$timeout_duration = 900; // 15 minutes

// Check if the timeout is set and whether it has expired
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: /reAllTras/loginSuperAdmin.php?timeout=1");
    exit();
}
// Update last activity time
$_SESSION['LAST_ACTIVITY'] = time();

$super_admin_id = $_SESSION['super_admin_id'];
$super_admin_name = $_SESSION['super_admin_name'];
$super_admin_icNo = $_SESSION['super_admin_icNo'];
$super_admin_email = $_SESSION['super_admin_email'];
$super_admin_phoneNo = $_SESSION['super_admin_phoneNo'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['phone'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    $check_password_query = "SELECT password FROM super_admin WHERE super_admin_id = ?";
    $stmt = $conn->prepare($check_password_query);
    $stmt->bind_param("i", $super_admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (password_verify($current_password, $row['password'])) {
        // Update profile information
        $update_query = "UPDATE super_admin SET name = ?, email = ?, phone_no = ? WHERE super_admin_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssi", $new_name, $new_email, $new_phone, $super_admin_id);
        
        if ($stmt->execute()) {
            // Update session variables
            $_SESSION['super_admin_name'] = $new_name;
            $_SESSION['super_admin_email'] = $new_email;
            $_SESSION['super_admin_phoneNo'] = $new_phone;
            
            // Update password if provided
            if (!empty($new_password) && $new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password_query = "UPDATE super_admin SET password = ? WHERE super_admin_id = ?";
                $stmt = $conn->prepare($update_password_query);
                $stmt->bind_param("si", $hashed_password, $super_admin_id);
                $stmt->execute();
            }
            
            $success_message = "Profil berjaya dikemaskini!";
        } else {
            $error_message = "Ralat semasa mengemaskini profil.";
        }
    } else {
        $error_message = "Kata laluan semasa tidak sah.";
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Paparan Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/adminStyle.css">
    <link rel="stylesheet" href="../../assets/css/adminLayout.css">
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
            <span class="nav-link fw-semibold"><?= htmlspecialchars($super_admin_name) ?> (Super Admin)</span>
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
        <a href="manageAdmins.php"><i class="fas fa-users-cog me-2"></i>Pengurusan Admin</a>
        <a href="manageUsers.php"><i class="fas fa-users me-2"></i>Pengurusan Pengguna</a>
        <a href="systemLogs.php"><i class="fas fa-history me-2"></i>Log Sistem</a>
        <a href="profile.php" class="active"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="page-header">
            <h3>Paparan Profil</h3>
        </div>

        <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">No. Kad Pengenalan</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($super_admin_icNo) ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($super_admin_name) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Emel</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($super_admin_email) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No. Telefon</label>
                                <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($super_admin_phoneNo) ?>" required>
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3">Tukar Kata Laluan</h5>

                            <div class="mb-3">
                                <label class="form-label">Kata Laluan Semasa</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kata Laluan Baru</label>
                                <input type="password" name="new_password" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sahkan Kata Laluan Baru</label>
                                <input type="password" name="confirm_password" class="form-control">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-6x text-primary"></i>
                        </div>
                        <h5 class="card-title"><?= htmlspecialchars($super_admin_name) ?></h5>
                        <p class="text-muted">Super Admin</p>
                        <hr>
                        <p class="mb-1"><i class="fas fa-id-card me-2"></i><?= htmlspecialchars($super_admin_icNo) ?></p>
                        <p class="mb-1"><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($super_admin_email) ?></p>
                        <p class="mb-0"><i class="fas fa-phone me-2"></i><?= htmlspecialchars($super_admin_phoneNo) ?></p>
                    </div>
                </div>
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
</script>
</body>
</html> 