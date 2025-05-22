<?php
session_start();
include '../connection.php';

// Debug logging
error_log("process_borangWA4.php accessed");
error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
error_log("POST data: " . print_r($_POST, true));
error_log("FILES data: " . print_r($_FILES, true));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required session data exists
    if (!isset($_SESSION['borangWA_data']) || !isset($_SESSION['parent_info']) || !isset($_SESSION['flight_info']) || !isset($_SESSION['wilayah_asal_id']) || !isset($_SESSION['user_id'])) {
        error_log("Missing required session data");
        header("Location: ../role/pemohon/borangWA.php");
        exit();
    }

    // Get data from session
    $officer_data = $_SESSION['borangWA_data'];
    $parent_data = $_SESSION['parent_info'];
    $flight_data = $_SESSION['flight_info'];
    $wilayah_asal_id = $_SESSION['wilayah_asal_id'];
    $user_id = $_SESSION['user_id'];

    // Get user data to get KP
    $sql = "SELECT kp FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    if (!$user_data) {
        error_log("User data not found for ID: " . $user_id);
        throw new Exception("User data not found");
    }
    
    $user_kp = $user_data['kp'];
    error_log("User KP: " . $user_kp);

    try {
        // Start transaction
        $conn->begin_transaction();

        // Update existing wilayah_asal record with final status
        $sql = "UPDATE wilayah_asal SET 
            status_permohonan = 'DRAF',
            updated_at = NOW(),
            kedudukan_permohonan = 'Pemohon'
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $wilayah_asal_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error updating application status: " . $stmt->error);
        }

        // Handle document uploads
        $upload_dir = "../uploads/permohonan/";
        error_log("Upload directory: " . $upload_dir);
        
        if (!file_exists($upload_dir)) {
            error_log("Creating directory: " . $upload_dir);
            if (!mkdir($upload_dir, 0777, true)) {
                error_log("Failed to create directory: " . $upload_dir);
                throw new Exception("Failed to create upload directory");
            }
        }

        // Process each document
        $document_types = [
            'ic_pegawai' => 'SALINAN_IC_PEGAWAI',
            'ic_pengikut' => 'SALINAN_IC_PENGIKUT',
            'dokumen_sokongan' => 'DOKUMEN_SOKONGAN'
        ];

        foreach ($document_types as $input_name => $doc_type) {
            $file_input_name = $input_name . '_file';
            error_log("Checking file: " . $file_input_name);
            
            if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES[$file_input_name];
                $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $new_filename = $wilayah_asal_id . '_' . $doc_type . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                error_log("Attempting to upload file to: " . $upload_path);
                
                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    error_log("File uploaded successfully");
                    
                    // Insert document record into database
                    $doc_sql = "INSERT INTO documents (
                        wilayah_asal_id,
                        file_name,
                        file_path,
                        file_type,
                        file_size,
                        file_class_origin,
                        file_uploader_origin,
                        description
                    ) VALUES (?, ?, ?, ?, ?, 'pemohon', ?, ?)";

                    $relative_path = 'uploads/permohonan/' . $new_filename;
                    
                    $doc_stmt = $conn->prepare($doc_sql);
                    $doc_stmt->bind_param("isssiss",
                        $wilayah_asal_id,
                        $file['name'],
                        $relative_path,
                        $file['type'],
                        $file['size'],
                        $user_kp,
                        $doc_type
                    );

                    if (!$doc_stmt->execute()) {
                        throw new Exception("Error inserting document record: " . $doc_stmt->error);
                    }
                    
                    error_log("Document record inserted successfully");
                } else {
                    throw new Exception("Error uploading file: " . $file['name']);
                }
            }
        }

        // Commit transaction
        $conn->commit();
        error_log("Transaction committed successfully");

        // Clear session data
        unset($_SESSION['borangWA_data']);
        unset($_SESSION['parent_info']);
        unset($_SESSION['flight_info']);
        unset($_SESSION['wilayah_asal_id']);

        // Set success message
        $_SESSION['success_message'] = "Permohonan berjaya dihantar!";
        
        // Redirect to dashboard
        header("Location: ../role/pemohon/dashboard.php");
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        error_log("Error in process_borangWA4.php: " . $e->getMessage());
        
        // Set error message
        $_SESSION['error_message'] = "Ralat: " . $e->getMessage();
        
        // Redirect back to form
        header("Location: ../role/pemohon/borangWA4.php");
        exit();
    }
} else {
    // If not POST request, redirect back to form
    header("Location: ../role/pemohon/borangWA4.php");
    exit();
}
?> 