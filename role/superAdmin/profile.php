<?php
session_start();
include_once '../../includes/config.php';

// Fetch user data from database
$super_admin_id = $_SESSION['super_admin_id'];
$sql = "SELECT * FROM superAdmin WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $super_admin_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// if (!$user_data) {
//     header("Location: ../../loginSuperAdmin.php");
//     exit();
// }

$user_name = $user_data['Name'];
$user_icNo = $user_data['ICNo'];
$user_email = $user_data['Email'];
$user_phoneNo = $user_data['PhoneNo'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneNo = $_POST['phoneNo'];
    $icNo = $_POST['icNo'];

    $update_sql = "UPDATE superadmin SET name = ?, email = ?, phoneNo = ?, icNo = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssi", $name, $email, $phoneNo, $icNo, $super_admin_id);

    if ($stmt->execute()) {
        $success = "Profil berjaya dikemaskini.";
        $user['name'] = $name;      
        $user['email'] = $email;
        $user['phoneNo'] = $phoneNo;
        $user['icNo'] = $icNo;

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
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
    <link rel="icon" href="../../assets/ALLTRAS.png" type="image/x-icon">
</head>
<body>

<div class="main-container">
 
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-3">
        <div class="d-flex justify-content-between align-items-center mb-5" style>
            <h4 class="mb-0 ms-3"> Profil Super Admin</h4>
            <?php include 'includes/greeting.php'; ?>
        </div>


<div class="container py-6">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card card-profile shadow-sm p-4">
            <?php if (!empty($success)): ?>
                        <div class="alert alert-success text-center"><?= $success ?></div>
                    <?php endif; ?>
                <!-- <h5 class="text mt-4 font-weight-bold">Profil Akaun</h5> -->
                <!-- <hr> -->

                <form id="profileForm" action="profile.php" method="POST" enctype="multipart/form-data" class="mt-3">

                    <div class="mb-3">
                        <label for="name">Nama</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($user_data['Name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email">Emel</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user_data['Email']) ?>" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="phoneNo">No. Telefon</label>
                        <input type="text" id="phoneNo" name="phoneNo" class="form-control" maxlength="11" value="<?= htmlspecialchars($user_data['PhoneNo']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="icNo">No. IC</label>
                        <input type="text" id="icNo" name="icNo" class="form-control" maxlength="12" value="<?= htmlspecialchars($user_data['ICNo']) ?>" required>
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

    document.getElementById("profileForm").addEventListener("submit", function(event) {
        const confirmSubmit = confirm("Adakah anda pasti mahu mengemaskini profil anda?");
        if (!confirmSubmit) {
            event.preventDefault();
        }
    });
</script>

</body>
</html>
