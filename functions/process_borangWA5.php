<?php
session_start();
include '../connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug logging
error_log("=== Form Submission Debug ===");
error_log("Time: " . date('Y-m-d H:i:s'));
error_log("POST data: " . print_r($_POST, true));
error_log("SESSION data: " . print_r($_SESSION, true));
error_log("FILES data: " . print_r($_FILES, true));

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if wilayah_asal_id exists
if (!isset($_POST['wilayah_asal_id'])) {
    $_SESSION['error_message'] = "Ralat: ID permohonan tidak sah.";
    header("Location: ../role/pemohon/borangWA5.php");
    exit();
}

// Check if pengesahan checkbox is checked
if (!isset($_POST['pengesahan']) || $_POST['pengesahan'] !== 'on') {
    $_SESSION['error_message'] = "Sila tandakan kotak pengesahan untuk meneruskan.";
    header("Location: ../role/pemohon/borangWA5.php");
    exit();
}

$wilayah_asal_id = $_POST['wilayah_asal_id'];
$user_id = $_SESSION['user_id'];

try {
    // Start transaction
    $conn->begin_transaction();

    // Update the application status to 'submitted'
    $sql = "UPDATE wilayah_asal SET 
            status = 'submitted',
            submitted_at = NOW(),
            updated_at = NOW()
            WHERE id = ? AND user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $wilayah_asal_id, $user_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Ralat: " . $stmt->error);
    }

    // Commit transaction
    $conn->commit();
    
    // Clear the wilayah_asal_id from session
    unset($_SESSION['wilayah_asal_id']);
    
    $_SESSION['success_message'] = "Permohonan berjaya dihantar.";
    header("Location: ../role/pemohon/dashboard.php");
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $_SESSION['error_message'] = $e->getMessage();
    header("Location: ../role/pemohon/borangWA5.php");
    exit();
}
?> 