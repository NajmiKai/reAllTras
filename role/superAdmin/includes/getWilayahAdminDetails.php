<?php
session_start();
include '../../../connection.php';

// Check if user is logged in as super admin
if (!isset($_SESSION['super_admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Admin ID is required']);
    exit();
}

$document_id = $_GET['id'];

// Fetch admin details
$sql = "SELECT * FROM document_logs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $document_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    http_response_code(404);
    echo json_encode(['error' => 'Admin not found']);
    exit();
}

// Return admin details as JSON
header('Content-Type: application/json');
echo json_encode($admin); 