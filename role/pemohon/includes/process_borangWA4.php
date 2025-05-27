<?php
session_start();
include '../../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Check if wilayah_asal_id exists in session
        if (!isset($_SESSION['wilayah_asal_id'])) {
            throw new Exception("Session data not found. Please start from the beginning.");
        }

        $wilayah_asal_id = $_SESSION['wilayah_asal_id'];

        // Handle file uploads
        $upload_dir = "../../uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $document_types = ['surat_rasmi', 'surat_tawaran', 'surat_permohonan', 'dokumen_sokongan'];
        $uploaded_files = [];

        foreach ($document_types as $type) {
            if (isset($_FILES[$type]) && $_FILES[$type]['error'] == 0) {
                $file = $_FILES[$type];
                $file_name = time() . '_' . basename($file['name']);
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    $uploaded_files[$type] = $file_name;
                } else {
                    throw new Exception("Error uploading file: " . $file['name']);
                }
            }
        }

        // Prepare the SQL statement
        $sql = "UPDATE wilayah_asal SET 
            surat_rasmi = ?,
            surat_tawaran = ?,
            surat_permohonan = ?,
            dokumen_sokongan = ?
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("ssssi",
            $uploaded_files['surat_rasmi'] ?? null,
            $uploaded_files['surat_tawaran'] ?? null,
            $uploaded_files['surat_permohonan'] ?? null,
            $uploaded_files['dokumen_sokongan'] ?? null,
            $wilayah_asal_id
        );

        // Execute the statement
        if ($stmt->execute()) {
            // Store document information in session
            $_SESSION['document_info'] = $uploaded_files;

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