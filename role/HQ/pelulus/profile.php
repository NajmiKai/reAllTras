<?php
session_start();
include '../../../connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

 // Set session timeout duration (in seconds)
 $timeout_duration = 900; // 900 seconds = 15 minutes

 // Check if the timeout is set and whether it has expired
 if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
     // Session expired
     session_unset();
     session_destroy();
     header("Location: /reAllTras/login.php?timeout=1");
     exit();
 }
 // Update last activity time
 $_SESSION['LAST_ACTIVITY'] = time();

$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'];
$admin_role = $_SESSION['admin_role'];
$admin_icNo = $_SESSION['admin_icNo'];
$admin_email = $_SESSION['admin_email'];
$admin_phoneNo = $_SESSION['admin_phoneNo'];


$sql = "SELECT name, icNo, email, phoneNo FROM admin WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Fallback in case user not found
    $user = [
        'name' => '',
        'icNo' => '',
        'email' => '',
        'phoneNo' => '',
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneNo = $_POST['phoneNo'];
    $icNo = $_POST['icNo'];

    $update_sql = "UPDATE admin SET name = ?, email = ?, phoneNo = ?, icNo = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssi", $name, $email, $phoneNo, $icNo, $admin_id);

    if ($stmt->execute()) {
        $success = "Profil berjaya dikemaskini.";
        $user['name'] = $name;      
        $user['email'] = $email;
        $user['phoneNo'] = $phoneNo;
        $user['icNo'] = $icNo;

         // Update session data so the top navbar reflects the change
         $_SESSION['admin_name'] = $name;
         $_SESSION['admin_email'] = $email;
         $_SESSION['admin_icNo'] = $icNo;
         $_SESSION['admin_phoneNo'] = $phoneNo;
    } else {
        $success = "Ralat ketika mengemaskini profil.";
    }
}

?>


<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Profil Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/adminStyle.css">
    <link rel="icon" href="../../../assets/ALLTRAS.png" type="image/x-icon">

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
        <h6><img src="../../../assets/ALLTRAS.png" alt="ALLTRAS" width="140" style="margin-left: 20px;"><br>ALL REGION TRAVELLING SYSTEM</h6><br>
        <a href="dashboard.php"> <i class="fas fa-home me-2"></i>Laman Utama</a>
        <h6 class="text mt-4">BORANG PERMOHONAN</h6>
        <a href="wilayahAsal.php"><i class="fas fa-tasks me-2"></i>Wilayah Asal</a>
        <!-- <a href="tugasRasmi.php"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a> -->
        <a href="profile.php" class="active"><i class="fas fa-user me-2"></i>Paparan Profil</a>
        <a href="../../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Log Keluar</a>
    </div>


<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-profile shadow-sm p-4" style="margin-top: 3rem; margin-left:250px;">
            <?php if (!empty($success)): ?>
                        <div class="alert alert-success text-center"><?= $success ?></div>
                    <?php endif; ?>
                <h5 class="text mt-4 font-weight-bold">Profil Akaun</h5>
                <hr>

                <form id="profileForm" action="profile.php" method="POST" enctype="multipart/form-data" class="mt-3">

                    <div class="mb-3">
                        <label for="name">Nama</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email">Emel</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="phoneNo">No. Telefon</label>
                        <input type="text" id="phoneNo" name="phoneNo" class="form-control" maxlength="11"  value="<?= htmlspecialchars($user['phoneNo']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="icNo">No. IC</label>
                        <input type="text" id="icNo" name="icNo" class="form-control" maxlength="12" value="<?= htmlspecialchars($user['icNo']) ?>" required>
                    </div>


                    <div class="text-center mt-4">
                        <a href="dashboard.php" class="btn btn-success px-5 py-2 rounded-pill shadow-sm">
                           Kembali
                        </a>
                        <button type="submit" class="btn btn-dark px-5 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>



                </form>
            </div>

        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>
    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });

    document.getElementById("profileForm").addEventListener("submit", function(event) {
        const confirmSubmit = confirm("Adakah anda pasti mahu mengemaskini profil anda?");
        if (!confirmSubmit) {
            event.preventDefault();
        }
    });
</script>

</body>
</html>
