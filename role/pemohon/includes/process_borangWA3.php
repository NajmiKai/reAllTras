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
            jenis_permohonan = ?,
            tarikh_penerbangan_pergi = ?,
            tarikh_penerbangan_balik = ?,
            lapangan_terbang_berlepas = ?,
            lapangan_terbang_tiba = ?
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        
        // Prepare values for binding
        $jenis_permohonan = $_POST['jenis_permohonan'];
        $tarikh_penerbangan_pergi = $_POST['tarikh_penerbangan_pergi'];
        $tarikh_penerbangan_balik = $_POST['tarikh_penerbangan_balik'];
        $lapangan_terbang_berlepas = $_POST['lapangan_terbang_berlepas'];
        $lapangan_terbang_tiba = $_POST['lapangan_terbang_tiba'];

        // Bind parameters
        $stmt->bind_param("sssssi",
            $jenis_permohonan,
            $tarikh_penerbangan_pergi,
            $tarikh_penerbangan_balik,
            $lapangan_terbang_berlepas,
            $lapangan_terbang_tiba,
            $wilayah_asal_id
        );

        // Execute the statement
        if ($stmt->execute()) {
            // Store flight information in session
            $_SESSION['flight_info'] = [
                'jenis_permohonan' => $jenis_permohonan,
                'tarikh_penerbangan_pergi' => $tarikh_penerbangan_pergi,
                'tarikh_penerbangan_balik' => $tarikh_penerbangan_balik,
                'lapangan_terbang_berlepas' => $lapangan_terbang_berlepas,
                'lapangan_terbang_tiba' => $lapangan_terbang_tiba
            ];

            // Keep existing borangWA_data if it exists
            if (!isset($_SESSION['borangWA_data'])) {
                $_SESSION['borangWA_data'] = [];
            }

            // Redirect to the next form
            header("Location: ../borangWA4.php");
            exit();
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Log the error
        error_log("Error in process_borangWA3.php: " . $e->getMessage());
        
        // Set error message in session
        $_SESSION['error'] = "Ralat semasa menyimpan data. Sila cuba lagi.";
        
        // Redirect back to form with error
        header("Location: ../borangWA3.php");
        exit();
    }
} else {
    // If not POST request, redirect back to form
    header("Location: ../borangWA3.php");
    exit();
}
?> 