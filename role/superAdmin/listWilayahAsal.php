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
    <link rel="icon" href="../../assets/ALLTRAS.png" type="image/x-icon">
    
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
                Rekod berjaya dibatalkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs mb-3" id="permohonanTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="jumlah-tab" data-bs-toggle="tab" data-bs-target="#jumlah" type="button" role="tab" aria-controls="jumlah" aria-selected="true">Jumlah Keseluruhan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="proses-tab" data-bs-toggle="tab" data-bs-target="#proses" type="button" role="tab" aria-controls="proses" aria-selected="false">Sedang Diproses</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="selesai-tab" data-bs-toggle="tab" data-bs-target="#selesai" type="button" role="tab" aria-controls="selesai" aria-selected="false">Selesai</button>
            </li>
        </ul>
        <div class="tab-content" id="permohonanTabContent">
            <!-- Jumlah Keseluruhan Tab -->
            <div class="tab-pane fade show active" id="jumlah" role="tabpanel" aria-labelledby="jumlah-tab">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
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
                                    <?php 
                                    $display_result->data_seek(0); // Reset pointer
                                    while($row = $display_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nama_first'] . ' ' . $row['nama_last']); ?></td>
                                        <td><?php echo htmlspecialchars($row['user_kp']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['bahagian']); ?></td>
                                        <td><?php echo htmlspecialchars($row['jenis_permohonan']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <!-- <td>
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
                                                    case 'Batal':
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
                                        </td> -->
                                        <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="viewWilayahAsal.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#batalModal" 
                                                        data-id="<?php echo $row['id']; ?>">
                                                    <i class="fas fa-trash"></i> Batal
                                                </button>
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
            <!-- Sedang Diproses Tab -->
            <div class="tab-pane fade" id="proses" role="tabpanel" aria-labelledby="proses-tab">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
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
                                    <?php 
                                    $display_result->data_seek(0); // Reset pointer
                                    while($row = $display_result->fetch_assoc()): 
                                        if (in_array($row['status_permohonan'], ['Belum Disemak', 'Dikuiri', 'Lulus'])): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nama_first'] . ' ' . $row['nama_last']); ?></td>
                                        <td><?php echo htmlspecialchars($row['user_kp']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['bahagian']); ?></td>
                                        <td><?php echo htmlspecialchars($row['jenis_permohonan']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <!-- <td>
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
                                                    case 'Batal':
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
                                        </td> -->
                                        <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="viewWilayahAsal.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#batalModal" 
                                                        data-id="<?php echo $row['id']; ?>">
                                                    <i class="fas fa-trash"></i> Batal
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Selesai Tab -->
            <div class="tab-pane fade" id="selesai" role="tabpanel" aria-labelledby="selesai-tab">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
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
                                    <?php 
                                    $display_result->data_seek(0); // Reset pointer
                                    while($row = $display_result->fetch_assoc()): 
                                        if ($row['status_permohonan'] === 'Selesai'): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nama_first'] . ' ' . $row['nama_last']); ?></td>
                                        <td><?php echo htmlspecialchars($row['user_kp']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['bahagian']); ?></td>
                                        <td><?php echo htmlspecialchars($row['jenis_permohonan']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <!-- <td>
                                            <?php
                                                $status_class = 'bg-success';
                                            ?>
                                            <span class="badge <?php echo $status_class; ?>">
                                                <?php echo htmlspecialchars($row['status_permohonan']); ?>
                                            </span>
                                        </td> -->
                                        <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="viewWilayahAsal.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#batalModal" 
                                                        data-id="<?php echo $row['id']; ?>">
                                                    <i class="fas fa-trash"></i> Batal
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Batal Modal -->
<div class="modal fade" id="batalModal" tabindex="-1" aria-labelledby="batalModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="deleteWilayahAsal.php">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="batalModalLabel">Batal Permohonan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="ulasan" class="form-label">Ulasan</label>
                    <textarea class="form-control" name="ulasan" id="ulasan" rows="3" required></textarea>
                </div>
                <p class="text-danger small">Tindakan ini akan membatalkan permohonan secara kekal.</p>
                <input type="hidden" name="wilayah_asal_id" id="batal-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-danger">Sahkan Batal</button>
            </div>
        </div>
    </form>
  </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var batalModal = document.getElementById('batalModal');

  batalModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');

    var hiddenInput = document.getElementById('batal-id');
    hiddenInput.value = id;
  });
});

</script>
</body>
</html> 