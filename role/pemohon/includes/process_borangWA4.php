<?php
session_start();
include '../../../connection.php';
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

$wilayah_asal_id = $_POST['wilayah_asal_id'];
$user_id = $_SESSION['user_id'];
$user_kp = $_SESSION['user_kp'];

// Create upload directory if it doesn't exist
$upload_dir = "../../../uploads/permohonan/" . $wilayah_asal_id;
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Function to handle file upload
function handleFileUpload($file, $upload_dir, $wilayah_asal_id, $user_kp, $description = '') {
    global $conn;
    
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
            
            // Insert into database
            $sql = "INSERT INTO documents (wilayah_asal_id, file_name, file_path, file_type, file_size, description, file_origin_id, file_origin) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'pemohon')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssiss", $wilayah_asal_id, $file_name, $web_path, $file_type, $file_size, $description, $user_kp);
            
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

// Handle Dokumen Pegawai (Required)
if (!isset($_FILES['dokumen_pegawai']) || $_FILES['dokumen_pegawai']['error'] === UPLOAD_ERR_NO_FILE) {
    $success = false;
    $error_messages[] = "Dokumen Pegawai diperlukan.";
} else {
    if (!handleFileUpload($_FILES['dokumen_pegawai'], $upload_dir, $wilayah_asal_id, $user_kp, 'Dokumen Pegawai')) {
        $success = false;
        $error_messages[] = "Gagal memuat naik Dokumen Pegawai.";
    }
}

// Handle Lampiran II (Required)
if (!isset($_FILES['lampiran_ii']) || $_FILES['lampiran_ii']['error'] === UPLOAD_ERR_NO_FILE) {
    $success = false;
    $error_messages[] = "Lampiran II diperlukan.";
} else {
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
    foreach ($_FILES['dokumen_pengikut']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['dokumen_pengikut']['error'][$key] === UPLOAD_ERR_OK) {
            $file = [
                'name' => $_FILES['dokumen_pengikut']['name'][$key],
                'type' => $_FILES['dokumen_pengikut']['type'][$key],
                'tmp_name' => $tmp_name,
                'error' => $_FILES['dokumen_pengikut']['error'][$key],
                'size' => $_FILES['dokumen_pengikut']['size'][$key]
            ];
            
            if (!handleFileUpload($file, $upload_dir, $wilayah_asal_id, $user_kp, 'Dokumen Pengikut')) {
                $success = false;
                $error_messages[] = "Gagal memuat naik Dokumen Pengikut #" . ($key + 1);
            }
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

    $_SESSION['success'] = "Semua dokumen berjaya dimuat naik.";
    header("Location: ../borangWA5.php?id=" . $wilayah_asal_id);
} else {
    $_SESSION['error'] = implode("<br>", $error_messages);
    header("Location: ../borangWA4.php");
}
exit();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get wilayah_asal_id from POST or session
        $wilayah_asal_id = $_POST['wilayah_asal_id'] ?? $_SESSION['wilayah_asal_id'] ?? null;

        if (!$wilayah_asal_id) {
            throw new Exception("No wilayah_asal_id found");
        }

        // Get form data
        $jenis_permohonan = $_POST['jenis_permohonan'];
        $tarikh_penerbangan_pergi = $_POST['tarikh_penerbangan_pergi'];
        $tarikh_penerbangan_balik = $_POST['tarikh_penerbangan_balik'];
        $start_point = $_POST['start_point'];
        $end_point = $_POST['end_point'];
        
        // Get partner flight dates
        $partner_flight_type = $_POST['partner_flight_type'] ?? 'same';
        if ($partner_flight_type === 'same') {
            $tarikh_penerbangan_pergi_pasangan = $tarikh_penerbangan_pergi;
            $tarikh_penerbangan_balik_pasangan = $tarikh_penerbangan_balik;
        } else {
            $tarikh_penerbangan_pergi_pasangan = $_POST['tarikh_penerbangan_pergi_pasangan'];
            $tarikh_penerbangan_balik_pasangan = $_POST['tarikh_penerbangan_balik_pasangan'];
        }

        // Update flight information in database
        $sql = "UPDATE wilayah_asal SET 
            jenis_permohonan = ?,
            tarikh_penerbangan_pergi = ?,
            tarikh_penerbangan_balik = ?,
            start_point = ?,
            end_point = ?,
            tarikh_penerbangan_pergi_pasangan = ?,
            tarikh_penerbangan_balik_pasangan = ?,
            wilayah_asal_from_stage = 'BorangWA5'
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi",
            $jenis_permohonan,
            $tarikh_penerbangan_pergi,
            $tarikh_penerbangan_balik,
            $start_point,
            $end_point,
            $tarikh_penerbangan_pergi_pasangan,
            $tarikh_penerbangan_balik_pasangan,
            $wilayah_asal_id
        );

        if ($stmt->execute()) {
            // Store flight information in session
            $_SESSION['flight_info'] = [
                'jenis_permohonan' => $jenis_permohonan,
                'tarikh_penerbangan_pergi' => $tarikh_penerbangan_pergi,
                'tarikh_penerbangan_balik' => $tarikh_penerbangan_balik,
                'start_point' => $start_point,
                'end_point' => $end_point,
                'tarikh_penerbangan_pergi_pasangan' => $tarikh_penerbangan_pergi_pasangan,
                'tarikh_penerbangan_balik_pasangan' => $tarikh_penerbangan_balik_pasangan
            ];

            // Process followers data if any
            if (isset($_POST['followers']) && is_array($_POST['followers'])) {
                // First delete existing followers for this wilayah_asal_id
                $delete_sql = "DELETE FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("i", $wilayah_asal_id);
                $delete_stmt->execute();
                $delete_stmt->close();

                // Insert new followers
                $followers_sql = "INSERT INTO wilayah_asal_pengikut 
                    (wilayah_asal_id, nama_first_pengikut, nama_last_pengikut, 
                    tarikh_lahir_pengikut, kp_pengikut, 
                    tarikh_penerbangan_pergi_pengikut, tarikh_penerbangan_balik_pengikut) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                
                $followers_stmt = $conn->prepare($followers_sql);
                
                foreach ($_POST['followers'] as $follower) {
                    // Determine which flight dates to use based on flight_date_type
                    $flight_date_type = $follower['flight_date_type'] ?? 'same';
                    
                    if ($flight_date_type === 'same') {
                        // Use main applicant's flight dates
                        $follower_pergi = $tarikh_penerbangan_pergi;
                        $follower_balik = $tarikh_penerbangan_balik;
                    } else {
                        // Use follower's specific flight dates
                        $follower_pergi = $follower['tarikh_penerbangan_pergi_pengikut'] ?? null;
                        $follower_balik = $follower['tarikh_penerbangan_balik_pengikut'] ?? null;
                    }

                    $followers_stmt->bind_param("issssss",
                        $wilayah_asal_id,
                        $follower['nama_first'],
                        $follower['nama_last'],
                        $follower['tarikh_lahir'],
                        $follower['kp'],
                        $follower_pergi,
                        $follower_balik
                    );
                    
                    if (!$followers_stmt->execute()) {
                        throw new Exception("Error saving follower data: " . $followers_stmt->error);
                    }
                }
                
                $followers_stmt->close();
            }

            // Keep existing borangWA_data if it exists
            if (!isset($_SESSION['borangWA_data'])) {
                $_SESSION['borangWA_data'] = [];
            }

            // Redirect to the next form
            header("Location: ../borangWA5.php");
            exit();
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Log the error
        error_log("Error in process_borangWA4.php: " . $e->getMessage());
        
        // Set error message in session
        $_SESSION['error'] = "Ralat semasa menyimpan data. Sila cuba lagi.";
        
        // Redirect back to form with error
        header("Location: ../borangWA4.php");
        exit();
    }
} else {
    // If not POST request, redirect back to form
    header("Location: ../borangWA4.php");
    exit();
}
?> 