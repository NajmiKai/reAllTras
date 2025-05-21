<?php
session_start();
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required session data exists
    if (!isset($_SESSION['borangWA_data']) || !isset($_SESSION['parent_info']) || !isset($_SESSION['flight_info'])) {
        header("Location: ../role/pemohon/borangWA.php");
        exit();
    }

    // Get data from session
    $officer_data = $_SESSION['borangWA_data'];
    $parent_data = $_SESSION['parent_info'];
    $flight_data = $_SESSION['flight_info'];

    try {
        // Start transaction
        $conn->begin_transaction();

        // Update existing wilayah_asal record with final status
        $sql = "UPDATE wilayah_asal SET 
            status_permohonan = 'DRAF',
            tarikh_permohonan = NOW()
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $wilayah_asal_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error updating application status: " . $stmt->error);
        }

        // Handle document uploads
        $upload_dir = "../uploads/permohonan/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Process each document
        $document_types = [
            'ic_pegawai' => 'SALINAN_IC_PEGAWAI',
            'ic_pengikut' => 'SALINAN_IC_PENGIKUT',
            'dokumen_sokongan' => 'DOKUMEN_SOKONGAN'
        ];

        foreach ($document_types as $input_name => $doc_type) {
            if (isset($_FILES[$input_name . '_file']) && $_FILES[$input_name . '_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES[$input_name . '_file'];
                $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $new_filename = $wilayah_asal_id . '_' . $doc_type . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    // Insert document record into database using existing documents table
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

                    $doc_stmt = $conn->prepare($doc_sql);
                    $doc_stmt->bind_param("isssiss",
                        $wilayah_asal_id,
                        $file['name'],
                        $upload_path,
                        $file['type'],
                        $file['size'],
                        $officer_data['user_kp'],
                        $doc_type
                    );
                    $doc_stmt->execute();
                } else {
                    throw new Exception("Error uploading file: " . $file['name']);
                }
            }
        }

        // Commit transaction
        $conn->commit();

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