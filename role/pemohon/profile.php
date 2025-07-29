<?php
session_start();
include '../../includes/config.php';

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

$user_name = $user_data['nama_first'] . ' ' . $user_data['nama_last'];
$user_role = $user_data['bahagian'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_first = $_POST['nama_first'];
    $nama_last = $_POST['nama_last'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $bahagian = $_POST['bahagian'];
    // $new_kp = $_POST['kp'];
    // $old_kp = $user_data['kp'];
    
    // // Check if KP is being changed
    // if ($new_kp !== $old_kp) {
    //     // Check if the new KP already exists in the database
    //     $check_kp_sql = "SELECT id FROM user WHERE kp = ? AND id != ?";
    //     $check_kp_stmt = $conn->prepare($check_kp_sql);
    //     $check_kp_stmt->bind_param("si", $new_kp, $user_id);
    //     $check_kp_stmt->execute();
    //     $check_kp_result = $check_kp_stmt->get_result();
        
    //     if ($check_kp_result->num_rows > 0) {
    //         $error_message = "Nombor Kad Pengenalan ini sudah digunakan oleh pengguna lain.";
    //     } else {
    //         // Check if there are any wilayah_asal records
    //         $check_wilayah_sql = "SELECT id FROM wilayah_asal WHERE user_kp = ?";
    //         $check_wilayah_stmt = $conn->prepare($check_wilayah_sql);
    //         $check_wilayah_stmt->bind_param("s", $old_kp);
    //         $check_wilayah_stmt->execute();
    //         $check_wilayah_result = $check_wilayah_stmt->get_result();
            
    //         if ($check_wilayah_result->num_rows > 0) {
    //             $error_message = "Tidak boleh menukar nombor Kad Pengenalan kerana terdapat permohonan wilayah asal yang aktif.";
    //         } else {
    //             // Safe to update KP
    //             $update_sql = "UPDATE user SET nama_first = ?, nama_last = ?, email = ?, phone = ?, bahagian = ?, kp = ? WHERE id = ?";
    //             $update_stmt = $conn->prepare($update_sql);
    //             $update_stmt->bind_param("ssssssi", $nama_first, $nama_last, $email, $phone, $bahagian, $new_kp, $user_id);
                
    //             if ($update_stmt->execute()) {
    //                 $success_message = "Profil berjaya dikemaskini!";
    //                 // Refresh user data
    //                 $stmt = $conn->prepare($sql);
    //                 $stmt->bind_param("i", $user_id);
    //                 $stmt->execute();
    //                 $result = $stmt->get_result();
    //                 $user_data = $result->fetch_assoc();
    //             } else {
    //                 $error_message = "Ralat semasa mengemaskini profil. Sila cuba lagi.";
    //             }
    //         }
    //     }
    // } else {
        // KP not changed, update other fields only
        $update_sql = "UPDATE user SET nama_first = ?, nama_last = ?, email = ?, phone = ?, bahagian = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssssi", $nama_first, $nama_last, $email, $phone, $bahagian, $user_id);
        
        if ($update_stmt->execute()) {
            $success_message = "Profil berjaya dikemaskini!";
            // Refresh user data
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();
        } else {
            $error_message = "Ralat semasa mengemaskini profil. Sila cuba lagi.";
        }
    }
// }
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="icon" href="../../../assets/ALLTRAS.png" type="image/x-icon">
</head>
<body>


<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Paparan Profil</h3>
            <?php include 'includes/greeting.php'; ?>
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

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama_first" class="form-label">Nama Pertama</label>
                            <input type="text" class="form-control" id="nama_first" name="nama_first" 
                                   value="<?= htmlspecialchars($user_data['nama_first']) ?>" required  oninput="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-md-6">
                            <label for="nama_last" class="form-label">Nama Akhir</label>
                            <input type="text" class="form-control" id="nama_last" name="nama_last" 
                                   value="<?= htmlspecialchars($user_data['nama_last']) ?>" required  oninput="this.value = this.value.toUpperCase();">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Emel</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($user_data['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Nombor Telefon</label>
                            <input type="tel" class="form-control" id="phone" name="phone" maxlength="11"
                                   value="<?= htmlspecialchars($user_data['phone']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kp" class="form-label">Nombor Kad Pengenalan</label>
                            <input type="text" class="form-control" id="kp" name="kp" maxlength="12"
                                   value="<?= htmlspecialchars($user_data['kp']) ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="bahagian" class="form-label">Cawangan</label>
                            <select class="form-select select2" id="bahagian" name="bahagian" required>
                                <option value="">Pilih Cawangan</option>
                                <?php
                                // Fetch all bahagian from organisasi table
                                $bahagian_query = "SELECT id, nama_cawangan FROM organisasi ORDER BY nama_cawangan ASC";
                                $bahagian_result = $conn->query($bahagian_query);
                                
                                while ($row = $bahagian_result->fetch_assoc()) {
                                    $selected = ($row['nama_cawangan'] == $user_data['bahagian']) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($row['nama_cawangan']) . "' " . $selected . ">" . 
                                         htmlspecialchars($row['nama_cawangan']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });

    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Cari Bahagian...",
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Tiada hasil dijumpai";
                },
                searching: function() {
                    return "Mencari...";
                }
            }
        });
    });
</script>
</body>
</html> 