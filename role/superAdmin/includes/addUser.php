<?php
session_start();
include_once '../../../includes/config.php';

// Check if user is super admin
if (!isset($_SESSION['super_admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required_fields = ['nama_first', 'nama_last', 'email', 'kp', 'bahagian', 'password'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => 'Semua medan diperlukan']);
        exit();
    }
}

// Validate email format
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Format email tidak sah']);
    exit();
}

// Check if email already exists
$check_email = $conn->prepare("SELECT id FROM user WHERE email = ?");
$check_email->bind_param("s", $data['email']);
$check_email->execute();
if ($check_email->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Emel sudah wujud']);
    exit();
}

// Check if IC number already exists
$check_kp = $conn->prepare("SELECT id FROM user WHERE kp = ?");
$check_kp->bind_param("s", $data['kp']);
$check_kp->execute();
if ($check_kp->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'No. KP sudah wujud']);
    exit();
}

// Validate bahagian exists in organisasi table
$check_bahagian = $conn->prepare("SELECT id FROM organisasi WHERE nama_cawangan = ?");
$check_bahagian->bind_param("s", $data['bahagian']);
$check_bahagian->execute();
if ($check_bahagian->get_result()->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Cawangan tidak sah']);
    exit();
}

// Hash password
$hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

$phone = $data['phone'] ?? null; //phone is optional

// Insert new user
$stmt = $conn->prepare("INSERT INTO user (nama_first, nama_last, email, phone, kp, bahagian, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", 
    $data['nama_first'],
    $data['nama_last'],
    $data['email'],
    $phone,
    $data['kp'],
    $data['bahagian'],
    $hashed_password
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Pengguna berjaya ditambah']);
    logDataCreate($conn, 'superAdmin', $icNo, 'User', $stmt->insert_id, 'Insert new admin');

} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menambah pengguna']);
}

$stmt->close();
$conn->close();
?> 