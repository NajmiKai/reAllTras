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

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit();
}

$user_id = $data['id'];

// Start building the SQL query
$sql = "UPDATE user SET ";
$params = [];
$types = "";

// Check each field and add to update if present
if (isset($data['nama_first'])) {
    $sql .= "nama_first = ?, ";
    $params[] = $data['nama_first'];
    $types .= "s";
}

if (isset($data['nama_last'])) {
    $sql .= "nama_last = ?, ";
    $params[] = $data['nama_last'];
    $types .= "s";
}

if (isset($data['email'])) {
    // Check if email is unique (excluding current user)
    $check_sql = "SELECT id FROM user WHERE email = ? AND id != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $data['email'], $user_id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email sudah digunakan']);
        exit();
    }
    $sql .= "email = ?, ";
    $params[] = $data['email'];
    $types .= "s";
}

if (isset($data['phone'])) {
    $sql .= "phone = ?, ";
    $params[] = $data['phone'];
    $types .= "s";
}

if (isset($data['kp'])) {
    // Check if KP is unique (excluding current user)
    $check_sql = "SELECT id FROM user WHERE kp = ? AND id != ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $data['kp'], $user_id);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No. KP sudah digunakan']);
        exit();
    }
    $sql .= "kp = ?, ";
    $params[] = $data['kp'];
    $types .= "s";
}

if (isset($data['bahagian'])) {
    // Validate bahagian exists in organisasi table
    $check_bahagian = $conn->prepare("SELECT id FROM organisasi WHERE nama_cawangan = ?");
    $check_bahagian->bind_param("s", $data['bahagian']);
    $check_bahagian->execute();
    if ($check_bahagian->get_result()->num_rows === 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Bahagian tidak sah']);
        exit();
    }
    $sql .= "bahagian = ?, ";
    $params[] = $data['bahagian'];
    $types .= "s";
}

if (isset($data['password']) && !empty($data['password'])) {
    $sql .= "password = ?, ";
    $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
    $types .= "s";
}

// Remove trailing comma and space
$sql = rtrim($sql, ", ");

// Add WHERE clause
$sql .= " WHERE id = ?";
$params[] = $user_id;
$types .= "i";

// Prepare and execute the update
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update user']);
} 