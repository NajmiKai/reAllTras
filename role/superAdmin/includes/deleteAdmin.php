<?php
session_start();
include_once '../../../includes/config.php';
include '../../../includes/system_logger.php';


// Check if user is logged in as super admin
if (!isset($_SESSION['super_admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Admin ID is required']);
    exit();
}

$admin_id = $_GET['id'];
$super_admin_id = (string) $_SESSION['super_admin_id'];
$icNo =   (string) $_SESSION['super_admin_icNo'];



// Delete admin
$sql = "DELETE FROM admin WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);




if ($stmt->execute()) {
    logDataDelete($conn,'data_delete', 'superAdmin', $icNo, "admin", $admin_id, "Delete admin");
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to delete admin']);
} 