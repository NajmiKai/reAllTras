<?php
session_start();
include '../../../includes/config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../loginUser.php");
    exit();
}

// Check if wilayah_asal_id exists
if (!isset($_POST['wilayah_asal_id'])) {
    $_SESSION['error'] = "ID Wilayah Asal tidak dijumpai.";
    header("Location: ../borangWA4Edit.php");
    exit();
}

$wilayah_asal_id = $_POST['wilayah_asal_id'];
$user_id = $_SESSION['user_id'];
$user_kp = $_SESSION['user_kp'];

// Create upload directory if it doesn't exist
$upload_dir = "../../uploads/permohonan/" . $wilayah_asal_id;
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Function to handle file upload
function handleFileUpload($file, $upload_dir, $wilayah_asal_id, $user_kp) {
    global $conn;
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($file['name']);
        $file_type = $file['type'];
        $file_size = $file['size'];
        
        // Generate unique filename
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_filename = uniqid() . '_' . $wilayah_asal_id . '.' . $file_extension;
        $target_path = $upload_dir . '/' . $unique_filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            // Create the web-accessible path for database storage
            $web_path = '../../../uploads/permohonan/' . $wilayah_asal_id . '/' . $unique_filename;
            
            // Insert into database
            $sql = "INSERT INTO documents (wilayah_asal_id, file_name, file_path, file_type, file_size, description, file_origin_id, file_origin) 
                    VALUES (?, ?, ?, ?, ?, 'Dokumen Tambahan', ?, 'pemohon')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssis", $wilayah_asal_id, $file_name, $web_path, $file_type, $file_size, $user_kp);
            
            if ($stmt->execute()) {
                return true;
            }
        }
    }
    return false;
}

$success = true;
$error_messages = [];

// Handle Dokumen Dikuiri (Multiple)
if (isset($_FILES['dokumen_dikuiri'])) {
    $upload_count = 0;
    foreach ($_FILES['dokumen_dikuiri']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['dokumen_dikuiri']['error'][$key] === UPLOAD_ERR_OK) {
            $file = [
                'name' => $_FILES['dokumen_dikuiri']['name'][$key],
                'type' => $_FILES['dokumen_dikuiri']['type'][$key],
                'tmp_name' => $tmp_name,
                'error' => $_FILES['dokumen_dikuiri']['error'][$key],
                'size' => $_FILES['dokumen_dikuiri']['size'][$key]
            ];
            
            if (!handleFileUpload($file, $upload_dir, $wilayah_asal_id, $user_kp)) {
                $success = false;
                $error_messages[] = "Gagal memuat naik Dokumen Dikuiri #" . ($key + 1);
            } else {
                $upload_count++;
            }
        }
    }
    
    if ($upload_count === 0) {
        $success = false;
        $error_messages[] = "Sila pilih sekurang-kurangnya satu dokumen untuk dimuat naik.";
    }
} else {
    $success = false;
    $error_messages[] = "Tiada dokumen dipilih untuk dimuat naik.";
}

if ($success) {
    $_SESSION['success'] = "Dokumen berjaya dimuat naik.";
    header("Location: ../borangWA5.php?id=" . $wilayah_asal_id);
} else {
    $_SESSION['error'] = implode("<br>", $error_messages);
    header("Location: borangWA4Edit.php");
}
exit();
?> 