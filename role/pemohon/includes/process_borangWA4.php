<?php
session_start();
include '../../../includes/config.php';
include '../../../includes/system_logger.php';

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
    header("Location: ../borangWA4.php");
    exit();
}

// Check if form was submitted
if (!isset($_POST['submit'])) {
    error_log("Form not submitted properly");
    header("Location: ../borangWA4.php");
    exit();
}

$wilayah_asal_id = $_POST['wilayah_asal_id'];
$user_id = $_SESSION['user_id'];
$user_kp = $_SESSION['user_kp'];

// Enable error logging
error_log("Processing borangWA4.php for wilayah_asal_id: " . $wilayah_asal_id);

// Check if we're in edit mode
$is_edit_mode = false;
$sql = "SELECT wilayah_asal_from_stage, wilayah_asal_matang FROM wilayah_asal WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $wilayah_asal_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    // Check if we're in edit mode (either BorangWA5 or if the form is not mature)
    $is_edit_mode = ($row['wilayah_asal_from_stage'] === 'BorangWA5' || !$row['wilayah_asal_matang']);
    error_log("Edit mode check - Stage: " . $row['wilayah_asal_from_stage'] . ", Mature: " . ($row['wilayah_asal_matang'] ? 'true' : 'false'));
    error_log("Edit mode: " . ($is_edit_mode ? "true" : "false"));
}

// If in edit mode, check if any files were uploaded
if ($is_edit_mode) {
    $files_uploaded = false;
    $file_fields = ['dokumen_pegawai', 'lampiran_ii', 'dokumen_pasangan', 'sijil_perkahwinan', 'dokumen_pengikut', 'dokumen_sokongan'];
    
    error_log("Checking for file uploads in edit mode");
    foreach ($file_fields as $field) {
        if (isset($_FILES[$field])) {
            error_log("Checking field: " . $field);
            if (is_array($_FILES[$field]['name'])) {
                // Handle array of files (for dokumen_pengikut and dokumen_sokongan)
                foreach ($_FILES[$field]['name'] as $index => $name) {
                    error_log("Array file check - Field: " . $field . ", Index: " . $index . ", Error: " . $_FILES[$field]['error'][$index]);
                    if ($_FILES[$field]['error'][$index] !== UPLOAD_ERR_NO_FILE) {
                        $files_uploaded = true;
                        error_log("File uploaded found in array field: " . $field);
                        break 2;
                    }
                }
            } else {
                // Handle single file
                error_log("Single file check - Field: " . $field . ", Error: " . $_FILES[$field]['error']);
                if ($_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
                    $files_uploaded = true;
                    error_log("File uploaded found in single field: " . $field);
                    break;
                }
            }
        }
    }
    
    error_log("Files uploaded: " . ($files_uploaded ? "true" : "false"));
    
    // If no files were uploaded in edit mode, redirect to borangWA5
    if (!$files_uploaded) {
        error_log("No files uploaded, updating stage and redirecting to borangWA5");
        // Update wilayah_asal_from_stage to BorangWA5
        $update_stage_sql = "UPDATE wilayah_asal SET wilayah_asal_from_stage = 'BorangWA5' WHERE id = ?";
        $update_stage_stmt = $conn->prepare($update_stage_sql);
        $update_stage_stmt->bind_param("i", $wilayah_asal_id);
        $update_stage_stmt->execute();
        $update_stage_stmt->close();
        
        // Update wilayah_asal_form_fill to true
        $update_form_fill_sql = "UPDATE wilayah_asal SET wilayah_asal_form_fill = true WHERE id = ?";
        $update_form_fill_stmt = $conn->prepare($update_form_fill_sql);
        $update_form_fill_stmt->bind_param("i", $wilayah_asal_id);
        $update_form_fill_stmt->execute();
        $update_form_fill_stmt->close();

        $_SESSION['success'] = "No changes made. Proceeding to next step.";
        header("Location: ../borangWA5.php?id=" . $wilayah_asal_id);
        exit();
    }
}

// Create upload directory if it doesn't exist
$upload_dir = "../../../uploads/permohonan/" . $wilayah_asal_id;
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Function to handle file upload
function handleFileUpload($file, $upload_dir, $wilayah_asal_id, $user_kp, $description = '') {
    global $conn, $is_edit_mode;
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($file['name']);
        $file_type = $file['type'];
        $file_size = $file['size'];
        
        // Generate unique filename with new format
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_filename = uniqid() . '_' . $wilayah_asal_id . '_' . str_replace(' ', '_', $description) . '.' . $file_extension;
        $target_path = $upload_dir . '/' . $unique_filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            // Create the web-accessible path for database storage
            $web_path = '../../../uploads/permohonan/' . $wilayah_asal_id . '/' . $unique_filename;
            
            if ($is_edit_mode) {
                // Update existing document if it exists
                $sql = "UPDATE documents SET 
                        file_name = ?, 
                        file_path = ?, 
                        file_type = ?, 
                        file_size = ? 
                        WHERE wilayah_asal_id = ? AND description = ? AND file_origin = 'pemohon'";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssiss", $file_name, $web_path, $file_type, $file_size, $wilayah_asal_id, $description);
                
                if ($stmt->execute() && $stmt->affected_rows === 0) {
                    // If no update occurred, insert new record
                    $sql = "INSERT INTO documents (wilayah_asal_id, file_name, file_path, file_type, file_size, description, file_origin_id, file_origin) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 'pemohon')";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isssiss", $wilayah_asal_id, $file_name, $web_path, $file_type, $file_size, $description, $user_kp);
                }
            } else {
                // Insert new document
                $sql = "INSERT INTO documents (wilayah_asal_id, file_name, file_path, file_type, file_size, description, file_origin_id, file_origin) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 'pemohon')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isssiss", $wilayah_asal_id, $file_name, $web_path, $file_type, $file_size, $description, $user_kp);
            }
            
            if ($stmt->execute()) {
                // Log document upload
                logDocumentEvent($conn, 'document_upload', 'user', $user_kp, $file_name, $stmt->insert_id);
                return true;
            }
        }
    }
    return false;
}

$success = true;
$error_messages = [];

// Handle Dokumen Pegawai (Required if not in edit mode)
if (!$is_edit_mode && (!isset($_FILES['dokumen_pegawai']) || $_FILES['dokumen_pegawai']['error'] === UPLOAD_ERR_NO_FILE)) {
    $success = false;
    $error_messages[] = "Dokumen Pegawai diperlukan.";
} else if (isset($_FILES['dokumen_pegawai']) && $_FILES['dokumen_pegawai']['error'] !== UPLOAD_ERR_NO_FILE) {
    if (!handleFileUpload($_FILES['dokumen_pegawai'], $upload_dir, $wilayah_asal_id, $user_kp, 'Dokumen Pegawai')) {
        $success = false;
        $error_messages[] = "Gagal memuat naik Dokumen Pegawai.";
    }
}

// Handle Lampiran II (Required if not in edit mode)
if (!$is_edit_mode && (!isset($_FILES['lampiran_ii']) || $_FILES['lampiran_ii']['error'] === UPLOAD_ERR_NO_FILE)) {
    $success = false;
    $error_messages[] = "Lampiran II diperlukan.";
} else if (isset($_FILES['lampiran_ii']) && $_FILES['lampiran_ii']['error'] !== UPLOAD_ERR_NO_FILE) {
    if (!handleFileUpload($_FILES['lampiran_ii'], $upload_dir, $wilayah_asal_id, $user_kp, 'Lampiran II')) {
        $success = false;
        $error_messages[] = "Gagal memuat naik Lampiran II.";
    }
}

// Handle Dokumen Pasangan (Optional)
if (isset($_FILES['dokumen_pasangan']) && $_FILES['dokumen_pasangan']['error'] !== UPLOAD_ERR_NO_FILE) {
    if (!handleFileUpload($_FILES['dokumen_pasangan'], $upload_dir, $wilayah_asal_id, $user_kp, 'Dokumen Pasangan')) {
        $success = false;
        $error_messages[] = "Gagal memuat naik Dokumen Pasangan.";
    }
}

// Handle Sijil Perkahwinan (Optional)
if (isset($_FILES['sijil_perkahwinan']) && $_FILES['sijil_perkahwinan']['error'] !== UPLOAD_ERR_NO_FILE) {
    if (!handleFileUpload($_FILES['sijil_perkahwinan'], $upload_dir, $wilayah_asal_id, $user_kp, 'Sijil Perkahwinan')) {
        $success = false;
        $error_messages[] = "Gagal memuat naik Sijil Perkahwinan.";
    }
}

// Handle Dokumen Pengikut (Multiple)
if (isset($_FILES['dokumen_pengikut'])) {
    $pengikut_count = 1;
    foreach ($_FILES['dokumen_pengikut']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['dokumen_pengikut']['error'][$key] === UPLOAD_ERR_OK) {
            $file = [
                'name' => $_FILES['dokumen_pengikut']['name'][$key],
                'type' => $_FILES['dokumen_pengikut']['type'][$key],
                'tmp_name' => $tmp_name,
                'error' => $_FILES['dokumen_pengikut']['error'][$key],
                'size' => $_FILES['dokumen_pengikut']['size'][$key]
            ];
            
            if (!handleFileUpload($file, $upload_dir, $wilayah_asal_id, $user_kp, 'Dokumen Pengikut ' . $pengikut_count)) {
                $success = false;
                $error_messages[] = "Gagal memuat naik Dokumen Pengikut #" . $pengikut_count;
            }
            $pengikut_count++;
        }
    }
}

// Handle Dokumen Sokongan (Multiple)
if (isset($_FILES['dokumen_sokongan'])) {
    $sokongan_count = 1;
    foreach ($_FILES['dokumen_sokongan']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['dokumen_sokongan']['error'][$key] === UPLOAD_ERR_OK) {
            $file = [
                'name' => $_FILES['dokumen_sokongan']['name'][$key],
                'type' => $_FILES['dokumen_sokongan']['type'][$key],
                'tmp_name' => $tmp_name,
                'error' => $_FILES['dokumen_sokongan']['error'][$key],
                'size' => $_FILES['dokumen_sokongan']['size'][$key]
            ];
            
            $description = "Dokumen Sokongan " . $sokongan_count;
            
            if (!handleFileUpload($file, $upload_dir, $wilayah_asal_id, $user_kp, $description)) {
                $success = false;
                $error_messages[] = "Gagal memuat naik Dokumen Sokongan #" . $sokongan_count;
            }
            $sokongan_count++;
        }
    }
}

if ($success) {
    // Store the ID in a variable
    $current_id = $wilayah_asal_id;
    
    // Update wilayah_asal_from_stage
    $update_stage_sql = "UPDATE wilayah_asal SET wilayah_asal_from_stage = 'BorangWA5' WHERE id = ?";
    $update_stage_stmt = $conn->prepare($update_stage_sql);
    $update_stage_stmt->bind_param("i", $current_id);
    $update_stage_stmt->execute();
    $update_stage_stmt->close();

    // Update wilayah_asal_form_fill to true
    $update_form_fill_sql = "UPDATE wilayah_asal SET wilayah_asal_form_fill = true WHERE id = ?";
    $update_form_fill_stmt = $conn->prepare($update_form_fill_sql);
    $update_form_fill_stmt->bind_param("i", $current_id);
    $update_form_fill_stmt->execute();
    $update_form_fill_stmt->close();

    $_SESSION['success'] = "Semua dokumen berjaya dimuat naik.";
    header("Location: ../borangWA5.php?id=" . $wilayah_asal_id);
} else {
    $_SESSION['error'] = implode("<br>", $error_messages);
    header("Location: ../borangWA4.php");
}
exit();
?> 