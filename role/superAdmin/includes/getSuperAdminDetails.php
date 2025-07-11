<?php
session_start();
include_once '../../../includes/config.php';

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

// Fetch super admin details
$sql = "SELECT ID, Name, Email, PhoneNo, ICNo FROM superAdmin WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $super_admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Super Admin not found']);
    exit();
}

$super_admin = $result->fetch_assoc();
echo json_encode($super_admin); 