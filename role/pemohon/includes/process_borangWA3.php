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

        // Check current stage
        $check_sql = "SELECT * FROM wilayah_asal WHERE id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $wilayah_asal_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $current_record = $result->fetch_assoc();
        $current_stage = $current_record['wilayah_asal_from_stage'];

        // Only proceed with update if stage is BorangWA4
        if ($current_stage === 'BorangWA4' || $current_stage === 'BorangWA5') {
            // Prepare the SQL statement
            $sql = "UPDATE wilayah_asal SET 
                jenis_permohonan = ?,
                tarikh_penerbangan_pergi = ?,
                tarikh_penerbangan_balik = ?,
                start_point = ?,
                end_point = ?,
                tarikh_penerbangan_pergi_pasangan = ?,
                tarikh_penerbangan_balik_pasangan = ?
                WHERE id = ?";

            $stmt = $conn->prepare($sql);
            
            // Prepare values for binding
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

            // Bind parameters
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

            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Error executing statement: " . $stmt->error);
            }

            // Process followers data if any
            if (isset($_POST['followers']) && is_array($_POST['followers'])) {
                // First delete existing followers
                $delete_sql = "DELETE FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("i", $wilayah_asal_id);
                $delete_stmt->execute();

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
        } else {
            // If not BorangWA4, proceed with normal flow
            // Prepare the SQL statement
            $sql = "UPDATE wilayah_asal SET 
                jenis_permohonan = ?,
                tarikh_penerbangan_pergi = ?,
                tarikh_penerbangan_balik = ?,
                start_point = ?,
                end_point = ?,
                tarikh_penerbangan_pergi_pasangan = ?,
                tarikh_penerbangan_balik_pasangan = ?,
                wilayah_asal_from_stage = 'BorangWA4'
                WHERE id = ?";

            $stmt = $conn->prepare($sql);
            
            // Prepare values for binding
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

            // Bind parameters
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

            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Error executing statement: " . $stmt->error);
            }

            // Store flight information in session
            $_SESSION['flight_info'] = [
                'jenis_permohonan' => $jenis_permohonan,
                'tarikh_penerbangan_pergi' => $tarikh_penerbangan_pergi,
                'tarikh_penerbangan_balik' => $tarikh_penerbangan_balik,
                'start_point' => $start_point,
                'end_point' => $end_point
            ];

            // Process followers data if any
            if (isset($_POST['followers']) && is_array($_POST['followers'])) {
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
        }

        // Keep existing borangWA_data if it exists
        if (!isset($_SESSION['borangWA_data'])) {
            $_SESSION['borangWA_data'] = [];
        }

        // Redirect to the next form based on the stage
        if ($current_record['wilayah_asal_form_fill'] === 1) {

            $update_stage_sql2 = "UPDATE wilayah_asal SET wilayah_asal_from_stage = 'BorangWA5' WHERE id = ?";
            $update_stage_stmt2 = $conn->prepare($update_stage_sql2);
            $update_stage_stmt2->bind_param("i", $wilayah_asal_id);
            $update_stage_stmt2->execute();
            $update_stage_stmt2->close();
            header("Location: ../borangWA5.php?id=" . $wilayah_asal_id);
            
        } else {
            header("Location: ../borangWA4.php");
        }
        exit();
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