<?php
session_start();
include '../../../connection.php';

// Check if user is logged in as super admin
if (!isset($_SESSION['super_admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get JSON data from request body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit();
}

// Validate required fields
$required_fields = ['name', 'email', 'phoneNo', 'icNo', 'password'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Semua medan diperlukan']);
        exit();
    }
}

// Check if email already exists
$check_email_sql = "SELECT ID FROM superAdmin WHERE Email = ?";
$check_email_stmt = $conn->prepare($check_email_sql);
$check_email_stmt->bind_param("s", $data['email']);
$check_email_stmt->execute();
if ($check_email_stmt->get_result()->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email sudah digunakan']);
    exit();
}

// Check if IC number already exists
$check_ic_sql = "SELECT ID FROM superAdmin WHERE ICNo = ?";
$check_ic_stmt = $conn->prepare($check_ic_sql);
$check_ic_stmt->bind_param("s", $data['icNo']);
$check_ic_stmt->execute();
if ($check_ic_stmt->get_result()->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No. KP sudah digunakan']);
    exit();
}

// Hash password
$hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

// Insert new super admin
$sql = "INSERT INTO superAdmin (Name, Email, PhoneNo, ICNo, Password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", 
    $data['name'],
    $data['email'],
    $data['phoneNo'],
    $data['icNo'],
    $hashed_password
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to add super admin']);
} 