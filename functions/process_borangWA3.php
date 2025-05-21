<?php
session_start();
include '../connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Debug: Log POST data
        error_log("POST data received: " . print_r($_POST, true));
        
        // Check if wilayah_asal_id exists in session
        if (!isset($_SESSION['wilayah_asal_id'])) {
            throw new Exception("Session data not found. Please start from the beginning.");
        }

        $wilayah_asal_id = $_SESSION['wilayah_asal_id'];
        error_log("Wilayah Asal ID: " . $wilayah_asal_id);

        // Get form data
        $jenis_permohonan = $_POST['jenis_permohonan'];
        $tarikh_penerbangan_pergi = $_POST['tarikh_penerbangan_pergi'];
        $tarikh_penerbangan_balik = $_POST['tarikh_penerbangan_balik'];
        $start_point = $_POST['start_point'];
        $end_point = $_POST['end_point'];

        // Debug: Log form data
        error_log("Form data: " . print_r([
            'jenis_permohonan' => $jenis_permohonan,
            'tarikh_penerbangan_pergi' => $tarikh_penerbangan_pergi,
            'tarikh_penerbangan_balik' => $tarikh_penerbangan_balik,
            'start_point' => $start_point,
            'end_point' => $end_point
        ], true));

        // Validate dates
        if (strtotime($tarikh_penerbangan_pergi) > strtotime($tarikh_penerbangan_balik)) {
            throw new Exception("Tarikh penerbangan pergi tidak boleh lebih lewat daripada tarikh penerbangan balik");
        }

        // Validate airports
        if ($start_point === $end_point) {
            throw new Exception("Lapangan terbang berlepas dan tiba tidak boleh sama");
        }

        // Start transaction
        $conn->begin_transaction();

        try {
            // Update main wilayah_asal table
            $sql = "UPDATE wilayah_asal SET 
                jenis_permohonan = ?,
                tarikh_penerbangan_pergi = ?,
                tarikh_penerbangan_balik = ?,
                start_point = ?,
                end_point = ?
                WHERE id = ?";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("sssssi", 
                $jenis_permohonan,
                $tarikh_penerbangan_pergi,
                $tarikh_penerbangan_balik,
                $start_point,
                $end_point,
                $wilayah_asal_id
            );

            if (!$stmt->execute()) {
                throw new Exception("Error updating wilayah_asal: " . $stmt->error);
            }

            error_log("Main update successful. Affected rows: " . $stmt->affected_rows);

            // Process accompanying persons if any
            if (isset($_POST['pengikut']) && is_array($_POST['pengikut'])) {
                error_log("Processing pengikut data: " . print_r($_POST['pengikut'], true));

                // Prepare statement for inserting pengikut
                $pengikut_sql = "INSERT INTO wilayah_asal_pengikut (
                    wilayah_asal_id,
                    nama_first_pengikut,
                    nama_last_pengikut,
                    tarikh_lahir_pengikut,
                    kp_pengikut,
                    tarikh_penerbangan_pergi_pengikut,
                    tarikh_penerbangan_balik_pengikut
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";

                $pengikut_stmt = $conn->prepare($pengikut_sql);
                if (!$pengikut_stmt) {
                    throw new Exception("Error preparing pengikut statement: " . $conn->error);
                }

                foreach ($_POST['pengikut'] as $pengikut) {
                    // Use same flight dates if not specified
                    $pengikut_tarikh_pergi = $pengikut['same_flight'] === 'yes' ? 
                        $tarikh_penerbangan_pergi : $pengikut['tarikh_penerbangan_pergi'];
                    $pengikut_tarikh_balik = $pengikut['same_flight'] === 'yes' ? 
                        $tarikh_penerbangan_balik : $pengikut['tarikh_penerbangan_balik'];

                    error_log("Inserting pengikut: " . print_r([
                        'wilayah_asal_id' => $wilayah_asal_id,
                        'nama_first' => $pengikut['nama_depan'],
                        'nama_last' => $pengikut['nama_belakang'],
                        'tarikh_lahir' => $pengikut['tarikh_lahir'],
                        'kp' => $pengikut['no_kp'],
                        'tarikh_pergi' => $pengikut_tarikh_pergi,
                        'tarikh_balik' => $pengikut_tarikh_balik
                    ], true));

                    $pengikut_stmt->bind_param("issssss",
                        $wilayah_asal_id,
                        $pengikut['nama_depan'],
                        $pengikut['nama_belakang'],
                        $pengikut['tarikh_lahir'],
                        $pengikut['no_kp'],
                        $pengikut_tarikh_pergi,
                        $pengikut_tarikh_balik
                    );

                    if (!$pengikut_stmt->execute()) {
                        throw new Exception("Error inserting pengikut: " . $pengikut_stmt->error);
                    }
                    error_log("Pengikut inserted successfully. ID: " . $conn->insert_id);
                }
            }

            // Commit transaction
            $conn->commit();
            error_log("Transaction committed successfully");

            // Store in session for next step
            $_SESSION['flight_info'] = [
                'jenis_permohonan' => $jenis_permohonan,
                'tarikh_penerbangan_pergi' => $tarikh_penerbangan_pergi,
                'tarikh_penerbangan_balik' => $tarikh_penerbangan_balik,
                'start_point' => $start_point,
                'end_point' => $end_point
            ];

            // Redirect to confirmation page
            header("Location: ../role/pemohon/borangWA4.php");
            exit();

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            error_log("Transaction rolled back due to error: " . $e->getMessage());
            throw $e;
        }

    } catch (Exception $e) {
        // Log the error
        error_log("Error in process_borangWA3.php: " . $e->getMessage());
        
        // Set error message in session
        $_SESSION['error'] = $e->getMessage();
        
        // Redirect back to form with error
        header("Location: ../role/pemohon/borangWA3.php");
        exit();
    }
} else {
    // If not POST request, redirect back to form
    header("Location: ../role/pemohon/borangWA3.php");
    exit();
}
?> 