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

        // Prepare the SQL statement
        $sql = "UPDATE wilayah_asal SET 
            pengesahan = 1,
            tarikh_hantar = NOW()
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bind_param("i", $wilayah_asal_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Clear session data
            unset($_SESSION['wilayah_asal_id']);
            unset($_SESSION['borangWA_data']);
            unset($_SESSION['officer_info']);
            unset($_SESSION['parent_info']);
            unset($_SESSION['flight_info']);
            unset($_SESSION['document_info']);

            // Set success message
            $_SESSION['success'] = "Permohonan berjaya dihantar.";
            
            // Redirect to dashboard
            header("Location: ../dashboard.php");
            exit();
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Log the error
        error_log("Error in process_borangWA5.php: " . $e->getMessage());
        
        // Set error message in session
        $_SESSION['error'] = "Ralat semasa menghantar permohonan. Sila cuba lagi.";
        
        // Redirect back to form with error
        header("Location: ../borangWA5.php");
        exit();
    }
} else {
    // If not POST request, redirect back to form
    header("Location: ../borangWA5.php");
    exit();
}
?> 