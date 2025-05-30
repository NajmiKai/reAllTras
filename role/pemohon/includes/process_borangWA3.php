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
        if ($stmt->execute()) {
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
                    // Determine which flight dates to use
                    $flight_date_type = $follower['flight_date_type'] ?? 'same';
                    
                    if ($flight_date_type === 'same') {
                        $follower_pergi = $tarikh_penerbangan_pergi;
                        $follower_balik = $tarikh_penerbangan_balik;
                    } else {
                        $follower_pergi = $follower['tarikh_penerbangan_pergi_pengikut'];
                        $follower_balik = $follower['tarikh_penerbangan_balik_pengikut'];
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