<?php
session_start();
include '../../../connection.php';
include '../../../includes/system_logger.php';


// Check if user is logged in as super admin
if (!isset($_SESSION['super_admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get JSON data from request body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit();
}

$admin_id = $data['id'];
$super_admin_id = $_SESSION['super_admin_id'];


// Start building the SQL query
$sql = "UPDATE admin SET ";
$params = [];
$types = "";

// Check each field and add to update if present
if (isset($data['name'])) {
    $sql .= "Name = ?, ";
    $params[] = $data['name'];
    $types .= "s";
}

if (isset($data['email'])) {
    // Check if email is unique (excluding current admin)
    $check_sql = "SELECT ID FROM admin WHERE Email = ? AND ID != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $data['email'], $admin_id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email sudah digunakan']);
        exit();
    }
    $sql .= "Email = ?, ";
    $params[] = $data['email'];
    $types .= "s";
}

if (isset($data['phoneNo'])) {
    $sql .= "PhoneNo = ?, ";
    $params[] = $data['phoneNo'];
    $types .= "s";
}

if (isset($data['icNo'])) {
    // Check if IC number is unique (excluding current admin)
    $check_sql = "SELECT ID FROM admin WHERE ICNo = ? AND ID != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $data['icNo'], $admin_id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No. KP sudah digunakan']);
        exit();
    }
    $sql .= "ICNo = ?, ";
    $params[] = $data['icNo'];
    $types .= "s";
}

if (isset($data['role'])) {
    $sql .= "Role = ?, ";
    $params[] = $data['role'];
    $types .= "s";
}

if (isset($data['password']) && !empty($data['password'])) {
    $sql .= "Password = ?, ";
    $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
    $types .= "s";
}

// Remove trailing comma and space
$sql = rtrim($sql, ", ");

// Add WHERE clause
$sql .= " WHERE ID = ?";
$params[] = $admin_id;
$types .= "i";

// Prepare and execute the update
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update admin']);
} 