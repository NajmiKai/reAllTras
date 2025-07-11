<?php
session_start();
include_once '../../../includes/config.php';
include '../../../includes/system_logger.php';

// Check if user is super admin
if (!isset($_SESSION['super_admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required_fields = ['name', 'icNo', 'email', 'phoneNo', 'password', 'role'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }
}

// Validate role
$valid_roles = ['PBR CSM', 'Pegawai Sulit CSM', 'Pengesah CSM', 'Penyemak HQ', 'Pengesah HQ', 
                'Pelulus HQ', 'Penyemak Baki Kewangan', 'Pengesah Kewangan', 'Penyedia Kemudahan Kewangan'];

if (!in_array($data['role'], $valid_roles)) {
    echo json_encode(['success' => false, 'message' => 'Peranan tidak sah']);
    exit();
}

// Validate email format
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Format email tidak sah']);
    exit();
}

// Check if email already exists
$check_email = $conn->prepare("SELECT ID FROM admin WHERE Email = ?");
$check_email->bind_param("s", $data['email']);
$check_email->execute();
if ($check_email->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Emel sudah wujud']);
    exit();
}

// Check if IC number already exists
$check_ic = $conn->prepare("SELECT ID FROM admin WHERE ICNo = ?");
$check_ic->bind_param("s", $data['icNo']);
$check_ic->execute();
if ($check_ic->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'No. KP sudah wujud']);
    exit();
}

// Hash password
$hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

// Insert new admin
$stmt = $conn->prepare("INSERT INTO admin (Name, ICNo, Email, PhoneNo, Password, Role) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", 
    $data['name'],
    $data['icNo'],
    $data['email'],
    $data['phoneNo'],
    $hashed_password,
    $data['role']
);
$icNo = $_SESSION['super_admin_icNo'];



if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Admin berjaya ditambah']);
    logDataCreate($conn, 'superAdmin', $icNo, 'Admin', $stmt->insert_id, 'Insert new admin');

} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menambah admin']);
}

$stmt->close();
$conn->close();
?> 