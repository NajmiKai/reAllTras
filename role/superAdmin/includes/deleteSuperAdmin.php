<?php
session_start();
include '../../../connection.php';

// Check if user is logged in as super admin
if (!isset($_SESSION['super_admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get super admin ID from request
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Super Admin ID is required']);
    exit();
}

$super_admin_id = $_GET['id'];

// Prevent deleting self
if ($super_admin_id == $_SESSION['super_admin_id']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tidak boleh memadamkan akaun sendiri']);
    exit();
}

// Delete super admin
$sql = "DELETE FROM superAdmin WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $super_admin_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to delete super admin']);
} 