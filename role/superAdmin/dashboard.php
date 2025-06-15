<?php
session_start();
include '../../connection.php';

// Fetch user data from database
$super_admin_id = $_SESSION['super_admin_id'];
$sql = "SELECT * FROM superAdmin WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $super_admin_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    header("Location: ../../loginSuperAdmin.php");
    exit();
}

$user_name = $user_data['Name'];
$user_icNo = $user_data['ICNo'];
$user_email = $user_data['Email'];
$user_phoneNo = $user_data['PhoneNo'];

?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
</head>
<body>


<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Laman Utama</h3>
            <?php include 'includes/greeting.php'; ?>
        </div>
        
        <!-- Info Cards Section -->
        <div class="row">
            <?php
            // Get admin count
            $admin_sql = "SELECT COUNT(*) as admin_count FROM admin";
            $admin_result = $conn->query($admin_sql);
            $admin_count = $admin_result->fetch_assoc()['admin_count'];

            // Get user count
            $user_sql = "SELECT COUNT(*) as user_count FROM user";
            $user_result = $conn->query($user_sql);
            $user_count = $user_result->fetch_assoc()['user_count'];

            // Get wilayah_asal count
            $wilayah_sql = "SELECT COUNT(*) as wilayah_count FROM wilayah_asal";
            $wilayah_result = $conn->query($wilayah_sql);
            $wilayah_count = $wilayah_result->fetch_assoc()['wilayah_count'];
            ?>

            <!-- Admin Card -->
            <div class="col-md-4 mb-4">
            <a href="adjustAdmin.php" class="text-decoration-none">
                <div class="card shadow-sm h-90">
                    <div class="card-body"> 
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users-cog fa-3x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title mb-1">Jumlah Admin</h6>
                                <h2 class="mb-0"><?php echo $admin_count; ?></h2>
                            </div>
                        </div>
                    </div>
                </div></a>
            </div>

            

            <!-- User Card -->
            <div class="col-md-4 mb-4">
            <a href="adjustUsers.php" class="text-decoration-none">
                <div class="card shadow-sm h-90">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user fa-3x text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title mb-1">Jumlah Pengguna</h6>
                                <h2 class="mb-0"><?php echo $user_count; ?></h2>
                            </div>
                        </div>
                    </div>
                </div></a>
            </div>

            <!-- Wilayah Asal Card -->
            <div class="col-md-4 mb-4">
            <a href="listWilayahAsal.php" class="text-decoration-none">

                <div class="card shadow-sm h-90">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-map-marker-alt fa-3x text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title mb-1">Jumlah Wilayah Asal</h6>
                                <h2 class="mb-0"><?php echo $wilayah_count; ?></h2>
                            </div>
                        </div>
                    </div>
                </div></a>
            </div>
        </div>

           <div class="container my-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Carta Aliran Permohonan</h5>
                            <h6 class="text-primary mb-3">Wilayah Asal</h6>
                            <img src="../../assets/flowchart-wilayah.jpg" alt="Carta Aliran Wilayah Asal" class="img-fluid rounded clickable-image" style="cursor:pointer;">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Carta Aliran Permohonan</h5>
                            <h6 class="text-danger mb-3">Tugas Rasmi / Kursus</h6>
                            <img src="../../assets/flowchart-tugasrasmi.jpg" alt="Carta Aliran Wilayah Asal" class="img-fluid rounded clickable-image" style="cursor:pointer;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            <div class="modal-body p-0">
                <img src="" id="modalImage" class="img-fluid rounded" alt="Expanded Image">
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>


    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Image Modal
  const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    const modalImage = document.getElementById('modalImage');

    document.querySelectorAll('.clickable-image').forEach(img => {
        img.addEventListener('click', () => {
        modalImage.src = img.src;
        imageModal.show();
        });
    });
</script>
</body>
</html>
