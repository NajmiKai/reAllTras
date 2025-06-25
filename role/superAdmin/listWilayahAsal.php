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

// Fetch all wilayah_asal entries with user information
$sql = "SELECT wa.*, u.nama_first, u.nama_last, u.email, u.phone, u.bahagian 
        FROM wilayah_asal wa 
        LEFT JOIN user u ON wa.user_kp = u.kp 
        ORDER BY wa.created_at DESC";
$result = $conn->query($sql);

// Create a new result set for display
$display_result = $conn->query($sql);


?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Senarai Permohonan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
</head>
<body>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>



    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Senarai Permohonan</h3>
            <?php include 'includes/greeting.php'; ?>
        </div>

        <?php if (isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Rekod berjaya dipadam.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Permohonan Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Pemohon</th>
                                <th>No. KP</th>
                                <th>Email</th>
                                <th>Bahagian</th>
                                <th>Jenis Permohonan</th>
                                <th>Status</th>
                                <th>Tarikh Permohonan</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $display_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_first'] . ' ' . $row['nama_last']); ?></td>
                                <td><?php echo htmlspecialchars($row['user_kp']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['bahagian']); ?></td>
                                <td><?php echo htmlspecialchars($row['jenis_permohonan']); ?></td>
                                <td>
                                    <?php
                                        $status_class = 'bg-secondary';
                                        switch ($row['status_permohonan']) {
                                            case 'Belum Disemak':
                                                $status_class = 'bg-secondary';
                                                break;
                                            case 'Selesai':
                                                $status_class = 'bg-success';
                                                break;
                                            case 'Dikuiri':
                                                $status_class = 'bg-warning';
                                                break;
                                            case 'Tolak':
                                                $status_class = 'bg-danger';
                                                break;
                                            case 'Lulus':
                                                $status_class = 'bg-primary';
                                                break;
                                            default:
                                                $status_class = 'bg-secondary';
                                        }
                                    ?>
                                    <span class="badge <?php echo $status_class; ?>">
                                        <?php echo htmlspecialchars($row['status_permohonan']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="viewWilayahAsal.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                        <a href="deleteWilayahAsal.php?id=<?php echo $row['id']; ?>" 
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Anda pasti ingin padam rekod ini?');">
                                            <i class="fas fa-trash"></i> Padam
                                        </a>

                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html> 