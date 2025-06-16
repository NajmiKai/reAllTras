<?php
// Set response type
header('Content-Type: application/json');

// Utility function
function sendJson($success, $message, $code = 200) {
    http_response_code($code);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

session_start();
include '../../connection.php';

// Check login
if (!isset($_SESSION['super_admin_id'])) {
    sendJson(false, 'Unauthorized', 401);
}

// Check for POST data
if (!isset($_POST['id'])) {
    sendJson(false, 'User ID is required', 400);
}

$user_id = intval($_POST['id']);
if ($user_id <= 0) {
    sendJson(false, 'Invalid user ID', 400);
}

// Execute deletion
$sql = "DELETE FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    sendJson(false, 'SQL prepare failed: ' . $conn->error, 500);
}

$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        sendJson(true, 'Pengguna berjaya dipadamkan');
    } else {
        sendJson(false, 'Pengguna tidak dijumpai', 404);
    }
} else {
    sendJson(false, 'Gagal memadamkan pengguna: ' . $stmt->error, 500);
}

$stmt->close();
$conn->close();
?>
