<?php
// Prevent HTML output
error_reporting(0);
ini_set('display_errors', 0);

// Set JSON header
header('Content-Type: application/json');

session_start();
include '../../../connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in as super admin
if (!isset($_SESSION['super_admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'User ID is required']));
}

$user_id = $_GET['id'];

if ($user_id <= 0) {
    sendJson(false, 'Invalid user ID', 400);
}

// Delete user
$sql = "DELETE FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        sendJson(true, 'Pengguna berjaya dipadamkan');
    } else {
        sendJson(false, 'Pengguna tidak dijumpai', 404);
    }
} else {
    sendJson(false, 'Gagal memadamkan pengguna', 500);
}

$stmt->close();
$conn->close();
?> 