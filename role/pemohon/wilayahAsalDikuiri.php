<?php
session_start();
include '../../connection.php';
include '../../includes/config.php';

// Get wilayah_asal_id from session
$wilayah_asal_id = $_SESSION['wilayah_asal_id'] ?? null;

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

// Check if user has wilayah_asal record
$check_sql = "SELECT * FROM wilayah_asal WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $wilayah_asal_id);
$check_stmt->execute();
$wilayah_asal_result = $check_stmt->get_result();
$wilayah_asal_data = $wilayah_asal_result->fetch_assoc();

// If no wilayah_asal record exists, redirect to borangWA
if (!$wilayah_asal_data) {
    header("Location: borangWA.php");
    exit();
}

// Get the section to edit from URL parameter
$section = isset($_GET['section']) ? $_GET['section'] : '';

// Validate section
$valid_sections = ['maklumat_pegawai', 'maklumat_ibubapa', 'maklumat_pengikut', 'dokumen'];
if (!in_array($section, $valid_sections)) {
    header("Location: wilayahAsal.php");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($section) {
        case 'maklumat_pegawai':
            include 'includes/process_DikuiriWA.php';
            break;
        case 'maklumat_ibubapa':
            include 'includes/process_DikuiriWA2.php';
            break;
        case 'maklumat_pengikut':
            include 'includes/process_DikuiriWA3.php';
            break;
        case 'dokumen':
            include 'includes/process_DikuiriWA4.php';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Edit Wilayah Asal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
    <style>
        .section-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
        }
        .section-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
            border-radius: 10px 10px 0 0;
        }
        .section-body {
            padding: 1.5rem;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Edit Wilayah Asal</h3>
        </div>

        <!-- Ulasan Display -->
        <div class="alert alert-warning mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fa-2x me-3"></i>
                <div>
                    <p class="mb-0"><?php echo $_SESSION['wilayah_asal_ulasan']; ?></p>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h5 class="mb-0">
                    <?php
                    switch ($section) {
                        case 'maklumat_pegawai':
                            echo '<i class="fas fa-user me-2"></i>Edit Maklumat Pegawai';
                            break;
                        case 'maklumat_ibubapa':
                            echo '<i class="fas fa-users me-2"></i>Edit Maklumat Ibu Bapa';
                            break;
                        case 'maklumat_pengikut':
                            echo '<i class="fas fa-user-friends me-2"></i>Edit Maklumat Pengikut';
                            break;
                        case 'dokumen':
                            echo '<i class="fas fa-file-alt me-2"></i>Edit Dokumen';
                            break;
                    }
                    ?>
                </h5>
            </div>
            <div class="section-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    <?php
                    switch ($section) {
                        case 'maklumat_pegawai':
                            include 'dikuiriWA.php';
                            break;
                        case 'maklumat_ibubapa':
                            include 'dikuiriWA2.php';
                            break;
                        case 'maklumat_pengikut':
                            include 'dikuiriWA3.php';
                            break;
                        case 'dokumen':
                            include 'dikuiriWA4.php';
                            break;
                    }
                    ?>
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
</script>
</body>
</html>
