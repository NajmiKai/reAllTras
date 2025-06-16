<?php
session_start();
include '../../connection.php';
include '../../includes/system_logger.php';

// Check if user is logged in and is super admin
if (!isset($_SESSION['super_admin_id'])) {
    header("Location: ../../loginSuperAdmin.php");
    exit();
}

// Get filter parameters
$event_type = isset($_GET['event_type']) ? $_GET['event_type'] : '';
$user_type = isset($_GET['user_type']) ? $_GET['user_type'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Build query with filters
$query = "SELECT sl.*, 
          CASE 
              WHEN sl.user_type = 'admin' THEN a.Name
              WHEN sl.user_type = 'superAdmin' THEN sa.Name
              WHEN sl.user_type = 'user' THEN CONCAT(u.nama_first, ' ', u.nama_last)
          END as user_name,
          CASE 
              WHEN sl.user_type = 'admin' THEN a.ICNo
              WHEN sl.user_type = 'superAdmin' THEN sa.ICNo
              WHEN sl.user_type = 'user' THEN u.kp
          END as user_identifier
          FROM system_logs sl
          LEFT JOIN admin a ON sl.user_id = a.ICNo AND sl.user_type = 'admin'
          LEFT JOIN superAdmin sa ON sl.user_id = sa.ICNo AND sl.user_type = 'superAdmin'
          LEFT JOIN user u ON sl.user_id = u.kp AND sl.user_type = 'user'
          WHERE 1=1";

$params = [];
$types = "";

if (!empty($event_type)) {
    switch ($event_type) {
        case 'create':
            $query .= " AND sl.event_type IN ('data_create', 'document_upload')";
            break;
        case 'update':
            $query .= " AND sl.event_type IN ('data_update', 'document_download', 'status_change')";
            break;
        case 'delete':
            $query .= " AND sl.event_type IN ('data_delete', 'document_delete')";
            break;
        default:
            $query .= " AND sl.event_type = ?";
            $params[] = $event_type;
            $types .= "s";
    }
}


if (!empty($user_type)) {
    $query .= " AND sl.user_type = ?";
    $params[] = $user_type;
    $types .= "s";
}

if (!empty($date_from)) {
    $query .= " AND DATE(sl.created_at) >= ?";
    $params[] = $date_from;
    $types .= "s";
}

if (!empty($date_to)) {
    $query .= " AND DATE(sl.created_at) <= ?";
    $params[] = $date_to;
    $types .= "s";
}

$query .= " ORDER BY sl.created_at DESC";
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Log Sistem</title>
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
            <h3 class="mb-0">Log Sistem</h3>
            <?php include 'includes/greeting.php'; ?>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Jenis Aktiviti</label>
                        <select name="event_type" class="form-select">
                            <option value="">Semua</option>
                            <option value="login" <?= $event_type === 'login' ? 'selected' : '' ?>>Log Masuk</option>
                            <option value="logout" <?= $event_type === 'logout' ? 'selected' : '' ?>>Log Keluar</option>
                            <option value="create" <?= ($event_type === 'data_create' || $event_type === 'document_upload') ? 'selected' : '' ?>>Cipta</option>
                            <option value="update" <?= ($event_type === 'data_update' || $event_type === 'document_download' || $event_type === 'status_change' ) ? 'selected' : '' ?>>Kemaskini</option>
                            <option value="delete" <?= ($event_type === 'data_delete' || $event_type === 'document_delete') ? 'selected' : '' ?>>Padam</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jenis Pengguna</label>
                        <select name="user_type" class="form-select">
                            <option value="">Semua</option>
                            <option value="admin" <?= $user_type === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="superAdmin" <?= $user_type === 'superAdmin' ? 'selected' : '' ?>>Super Admin</option>
                            <option value="user" <?= $user_type === 'user' ? 'selected' : '' ?>>Pengguna</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tarikh Dari</label>
                        <input type="date" name="date_from" class="form-control" value="<?= $date_from ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tarikh Hingga</label>
                        <input type="date" name="date_to" class="form-control" value="<?= $date_to ?>">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Tapis</button>
                        <a href="viewSystemLog.php" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tarikh & Masa</th>
                                <th>Pengguna</th>
                                <th>No. KP</th>
                                <th>Jenis Pengguna</th>
                                <th>Jenis Aktiviti</th>
                                <th>Butiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare($query);
                            if (!empty($params)) {
                                $stmt->bind_param($types, ...$params);
                            }
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // Add debugging
                            if ($stmt->error) {
                                echo "Query Error: " . $stmt->error;
                            }

                            if ($result->num_rows === 0) {
                                echo "<tr><td colspan='6' class='text-center'>Tiada rekod dijumpai</td></tr>";
                            }

                            while ($row = $result->fetch_assoc()) {
                                // Debug output
                                error_log("Row data: " . print_r($row, true));
                                
                                echo "<tr>";
                                echo "<td>" . date('d/m/Y H:i:s', strtotime($row['created_at'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['user_name'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($row['user_identifier'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($row['user_type'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($row['event_type'] ?? 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars(($row['action'] ?? '') . ($row['description'] ? ': ' . $row['description'] : '')) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 