<?php
// Get user data from session
include '../../connection.php';

// Fetch user data from database
$user_id = $_SESSION['super_admin_id'];
$sql = "SELECT * FROM superAdmin WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    header("Location: ../../loginSuperAdmin.php");
    exit();
}

$user_name = $user_data['Name'];

// Set timezone and get greeting
date_default_timezone_set('Asia/Kuala_Lumpur');
$time = date('H');
if ($time >= 5 && $time < 12) {
    $greeting = 'Selamat Pagi';
} elseif ($time >= 12 && $time < 15) {
    $greeting = 'Selamat Tengah Hari';
} elseif ($time >= 15 && $time < 19) {
    $greeting = 'Selamat Petang';    
} else {
    $greeting = 'Selamat Malam';
}
?>

<div class="greeting-box">
    <strong>Hi, <?= $greeting ?>!</strong> <?= $user_name ?>
    <a href="../../logoutSuperAdmin.php" class="btn btn-danger ms-3">
        <i class="fas fa-sign-out-alt me-1"></i> Log Keluar
    </a>
</div> 