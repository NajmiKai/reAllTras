<?php
session_start();
include_once '../../includes/config.php';

// Fetch user_id from session
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../../loginUser.php');
    exit();
}

// Get user data to retrieve kp
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    header('Location: ../../loginUser.php');
    exit();
}

$user_kp = $user_data['kp'];

// Handle View button POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wilayah_asal_id'])) {
    $_SESSION['wilayah_asal_id'] = $_POST['wilayah_asal_id'];
    header('Location: wilayahAsal.php');
    exit();
}

// Fetch all wilayah_asal records for this user
$list_sql = "SELECT id, tarikh_pengesahan_user, status_permohonan FROM wilayah_asal WHERE user_kp = ? ORDER BY id DESC";
$list_stmt = $conn->prepare($list_sql);
$list_stmt->bind_param("s", $user_kp);
$list_stmt->execute();
$list_result = $list_stmt->get_result();
$wilayah_list = [];
while ($row = $list_result->fetch_assoc()) {
    $wilayah_list[] = $row;
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Senarai Wilayah Asal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
    <link rel="icon" href="../../../assets/ALLTRAS.png" type="image/x-icon">
</head>
<body>
<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Senarai Wilayah Asal</h3>
            <?php include 'includes/greeting.php'; ?>
        </div>
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Wilayah Asal Anda</h5>
                <?php if (empty($wilayah_list)): ?>
                    <div class="alert alert-info">Tiada permohonan Wilayah Asal dijumpai.</div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tarikh Pengesahan Pemohon</th>
                                <th scope="col">Status Permohonan</th>
                                <th scope="col">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($wilayah_list as $i => $row): ?>
                            <tr>
                                <td><?= $i+1 ?></td>
                                <td><?= $row['tarikh_pengesahan_user'] ? htmlspecialchars($row['tarikh_pengesahan_user']) : '-' ?></td>
                                <td>
                                    <?php
                                    $status = $row['status_permohonan'];
                                    $badge = 'belum-disemak';
                                    if ($status === 'Dikuiri') $badge = 'dikuiri';
                                    else if ($status === 'Lulus') $badge = 'lulus';
                                    else if ($status === 'Tolak') $badge = 'tolak';
                                    else if ($status === 'Selesai') $badge = 'selesai';
                                    ?>
                                    <span class="status-badge <?= $badge ?>">
                                        <?= htmlspecialchars($status) ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="wilayah_asal_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Lihat
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<style>
.status-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    border-radius: 50rem;
    font-size: 0.875rem;
    font-weight: 500;
}
.status-badge.belum-disemak {
    background-color: #e9ecef;
    color: #495057;
}
.status-badge.dikuiri {
    background-color: #fff3cd;
    color: #856404;
}
.status-badge.lulus {
    background-color: #d4edda;
    color: #155724;
}
.status-badge.tolak {
    background-color: #f8d7da;
    color: #721c24;
}
.status-badge.selesai {
    background-color: #cce5ff;
    color: #004085;
}
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
