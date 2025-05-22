<?php
session_start();
include '../connection.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$wilayah_asal_id = $_POST['wilayah_asal_id'];
$user_id = $_SESSION['user_id'];

try {
    // Start transaction
    $conn->begin_transaction();

    // Handle different update types
    $update_type = $_POST['update_type'] ?? '';

    switch ($update_type) {
        case 'pegawai':
            // Update pegawai information
            $jawatan_gred = $_POST['jawatan_gred'];
            
            $sql = "UPDATE wilayah_asal SET 
                    jawatan_gred = ?,
                    updated_at = NOW()
                    WHERE id = ? AND user_id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $jawatan_gred, $wilayah_asal_id, $user_id);
            break;

        case 'pasangan':
            // Update pasangan information
            $nama_pasangan = explode(' ', $_POST['nama_pasangan'], 2);
            $nama_first_pasangan = $nama_pasangan[0];
            $nama_last_pasangan = $nama_pasangan[1] ?? '';
            $no_kp_pasangan = $_POST['no_kp_pasangan'];
            $wilayah_menetap_pasangan = $_POST['wilayah_menetap_pasangan'];
            $alamat_pasangan = explode("\n", $_POST['alamat_berkhidmat_pasangan'], 2);
            $alamat_berkhidmat_1_pasangan = $alamat_pasangan[0];
            $alamat_berkhidmat_2_pasangan = $alamat_pasangan[1] ?? '';

            $sql = "UPDATE wilayah_asal SET 
                    nama_first_pasangan = ?,
                    nama_last_pasangan = ?,
                    no_kp_pasangan = ?,
                    wilayah_menetap_pasangan = ?,
                    alamat_berkhidmat_1_pasangan = ?,
                    alamat_berkhidmat_2_pasangan = ?,
                    updated_at = NOW()
                    WHERE id = ? AND user_id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssii", 
                $nama_first_pasangan, 
                $nama_last_pasangan, 
                $no_kp_pasangan, 
                $wilayah_menetap_pasangan,
                $alamat_berkhidmat_1_pasangan,
                $alamat_berkhidmat_2_pasangan,
                $wilayah_asal_id, 
                $user_id
            );
            break;

        case 'ibu_bapa':
            // Update ibu bapa information
            $nama_bapa = $_POST['nama_bapa'];
            $no_kp_bapa = $_POST['no_kp_bapa'];
            $wilayah_menetap_bapa = $_POST['wilayah_menetap_bapa'];
            $alamat_bapa = explode("\n", $_POST['alamat_menetap_bapa'], 2);
            $alamat_menetap_1_bapa = $alamat_bapa[0];
            $alamat_menetap_2_bapa = $alamat_bapa[1] ?? '';

            $nama_ibu = $_POST['nama_ibu'];
            $no_kp_ibu = $_POST['no_kp_ibu'];
            $wilayah_menetap_ibu = $_POST['wilayah_menetap_ibu'];
            $alamat_ibu = explode("\n", $_POST['alamat_menetap_ibu'], 2);
            $alamat_menetap_1_ibu = $alamat_ibu[0];
            $alamat_menetap_2_ibu = $alamat_ibu[1] ?? '';

            $sql = "UPDATE wilayah_asal SET 
                    nama_bapa = ?,
                    no_kp_bapa = ?,
                    wilayah_menetap_bapa = ?,
                    alamat_menetap_1_bapa = ?,
                    alamat_menetap_2_bapa = ?,
                    nama_ibu = ?,
                    no_kp_ibu = ?,
                    wilayah_menetap_ibu = ?,
                    alamat_menetap_1_ibu = ?,
                    alamat_menetap_2_ibu = ?,
                    updated_at = NOW()
                    WHERE id = ? AND user_id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssii", 
                $nama_bapa, 
                $no_kp_bapa, 
                $wilayah_menetap_bapa,
                $alamat_menetap_1_bapa,
                $alamat_menetap_2_bapa,
                $nama_ibu, 
                $no_kp_ibu, 
                $wilayah_menetap_ibu,
                $alamat_menetap_1_ibu,
                $alamat_menetap_2_ibu,
                $wilayah_asal_id, 
                $user_id
            );
            break;

        case 'penerbangan':
            // Update penerbangan information
            $jenis_permohonan = $_POST['jenis_permohonan'];
            $tarikh_penerbangan_pergi = $_POST['tarikh_penerbangan_pergi'];
            $tarikh_penerbangan_balik = $_POST['tarikh_penerbangan_balik'];
            $start_point = $_POST['start_point'];
            $end_point = $_POST['end_point'];

            $sql = "UPDATE wilayah_asal SET 
                    jenis_permohonan = ?,
                    tarikh_penerbangan_pergi = ?,
                    tarikh_penerbangan_balik = ?,
                    start_point = ?,
                    end_point = ?,
                    updated_at = NOW()
                    WHERE id = ? AND user_id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssii", 
                $jenis_permohonan, 
                $tarikh_penerbangan_pergi, 
                $tarikh_penerbangan_balik,
                $start_point,
                $end_point,
                $wilayah_asal_id, 
                $user_id
            );
            break;

        case 'document':
            // Handle document upload
            if (!isset($_FILES['document_file']) || $_FILES['document_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Ralat: Fail tidak berjaya dimuat naik.");
            }

            $file = $_FILES['document_file'];
            $document_type = $_POST['document_type'];
            
            // Validate file type
            $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
            if (!in_array($file['type'], $allowed_types)) {
                throw new Exception("Ralat: Jenis fail tidak dibenarkan. Hanya PDF, JPEG, dan PNG dibenarkan.");
            }

            // Create upload directory if it doesn't exist
            $upload_dir = "../uploads/permohonan/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate unique filename
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $new_filename = $wilayah_asal_id . '_' . $document_type . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;

            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
                throw new Exception("Ralat: Gagal menyimpan fail.");
            }

            // Get user KP for file_uploader_origin
            $sql = "SELECT kp FROM user WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();
            $user_kp = $user_data['kp'];

            // Insert document record
            $relative_path = 'uploads/permohonan/' . $new_filename;
            $sql = "INSERT INTO documents (
                wilayah_asal_id,
                file_name,
                file_path,
                file_type,
                file_size,
                file_class_origin,
                file_uploader_origin,
                description
            ) VALUES (?, ?, ?, ?, ?, 'pemohon', ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssiss",
                $wilayah_asal_id,
                $file['name'],
                $relative_path,
                $file['type'],
                $file['size'],
                $user_kp,
                $document_type
            );
            break;

        default:
            throw new Exception("Ralat: Jenis kemaskini tidak sah.");
    }

    // Execute the prepared statement
    if (!$stmt->execute()) {
        throw new Exception("Ralat: " . $stmt->error);
    }

    // Commit transaction
    $conn->commit();
    $_SESSION['success_message'] = "Maklumat berjaya dikemaskini.";

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $_SESSION['error_message'] = $e->getMessage();
}

// Redirect back to borangWA5.php
header("Location: ../role/pemohon/borangWA5.php");
exit();
?> 