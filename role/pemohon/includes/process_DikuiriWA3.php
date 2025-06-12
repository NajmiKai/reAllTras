<?php
include '../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Check if wilayah_asal_id exists in session
        if (!isset($_SESSION['wilayah_asal_id'])) {
            throw new Exception("Session data not found. Please start from the beginning.");
        }

        $wilayah_asal_id = $_SESSION['wilayah_asal_id'];

        // Update wilayah_asal table
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
            throw new Exception("Error updating wilayah_asal: " . $stmt->error);
        }

        // Handle followers data
        if (isset($_POST['followers']) && is_array($_POST['followers'])) {
            // Get existing follower IDs
            $existing_followers_sql = "SELECT id FROM wilayah_asal_pengikut WHERE wilayah_asal_id = ?";
            $existing_stmt = $conn->prepare($existing_followers_sql);
            $existing_stmt->bind_param("i", $wilayah_asal_id);
            $existing_stmt->execute();
            $existing_result = $existing_stmt->get_result();
            $existing_ids = [];
            while ($row = $existing_result->fetch_assoc()) {
                $existing_ids[] = $row['id'];
            }

            // Get submitted follower IDs
            $submitted_ids = [];
            foreach ($_POST['followers'] as $follower) {
                if (isset($follower['id'])) {
                    $submitted_ids[] = $follower['id'];
                }
            }

            // Delete followers that were removed
            $deleted_ids = array_diff($existing_ids, $submitted_ids);
            if (!empty($deleted_ids)) {
                $delete_sql = "DELETE FROM wilayah_asal_pengikut WHERE id IN (" . implode(',', $deleted_ids) . ")";
                if (!$conn->query($delete_sql)) {
                    throw new Exception("Error deleting followers: " . $conn->error);
                }
            }

            // Also check for explicitly marked deleted followers
            if (isset($_POST['deleted_followers']) && is_array($_POST['deleted_followers'])) {
                $explicit_deleted_ids = array_map('intval', $_POST['deleted_followers']);
                if (!empty($explicit_deleted_ids)) {
                    $delete_sql = "DELETE FROM wilayah_asal_pengikut WHERE id IN (" . implode(',', $explicit_deleted_ids) . ")";
                    if (!$conn->query($delete_sql)) {
                        throw new Exception("Error deleting explicitly marked followers: " . $conn->error);
                    }
                }
            }

            // Update or insert followers
            foreach ($_POST['followers'] as $follower) {
                // Skip if this follower was marked for deletion
                if (isset($_POST['deleted_followers']) && 
                    is_array($_POST['deleted_followers']) && 
                    in_array($follower['id'], $_POST['deleted_followers'])) {
                    continue;
                }

                // Determine which flight dates to use
                $flight_date_type = $follower['flight_date_type'] ?? 'same';
                
                if ($flight_date_type === 'same') {
                    $follower_pergi = $tarikh_penerbangan_pergi;
                    $follower_balik = $tarikh_penerbangan_balik;
                } else {
                    $follower_pergi = $follower['tarikh_penerbangan_pergi_pengikut'];
                    $follower_balik = $follower['tarikh_penerbangan_balik_pengikut'];
                }

                if (isset($follower['id'])) {
                    // Update existing follower
                    $update_sql = "UPDATE wilayah_asal_pengikut SET 
                        nama_first_pengikut = ?,
                        nama_last_pengikut = ?,
                        tarikh_lahir_pengikut = ?,
                        kp_pengikut = ?,
                        tarikh_penerbangan_pergi_pengikut = ?,
                        tarikh_penerbangan_balik_pengikut = ?
                        WHERE id = ?";
                    
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ssssssi",
                        $follower['nama_first'],
                        $follower['nama_last'],
                        $follower['tarikh_lahir'],
                        $follower['kp'],
                        $follower_pergi,
                        $follower_balik,
                        $follower['id']
                    );
                    
                    if (!$update_stmt->execute()) {
                        throw new Exception("Error updating follower: " . $update_stmt->error);
                    }
                } else {
                    // Insert new follower
                    $insert_sql = "INSERT INTO wilayah_asal_pengikut 
                        (wilayah_asal_id, nama_first_pengikut, nama_last_pengikut, 
                        tarikh_lahir_pengikut, kp_pengikut, 
                        tarikh_penerbangan_pergi_pengikut, tarikh_penerbangan_balik_pengikut) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                    
                    $insert_stmt = $conn->prepare($insert_sql);
                    $insert_stmt->bind_param("issssss",
                        $wilayah_asal_id,
                        $follower['nama_first'],
                        $follower['nama_last'],
                        $follower['tarikh_lahir'],
                        $follower['kp'],
                        $follower_pergi,
                        $follower_balik
                    );
                    
                    if (!$insert_stmt->execute()) {
                        throw new Exception("Error inserting follower: " . $insert_stmt->error);
                    }
                }
            }
        }

        // Redirect back to the form with success message
        $_SESSION['success'] = "Data berjaya dikemaskini.";
        include 'process_Dikuiri_Update.php';
        header("Location: dashboard.php");
        exit();

    } catch (Exception $e) {
        // Log the error
        error_log("Error in process_DikuiriWA3.php: " . $e->getMessage());
        
        // Set error message in session
        $_SESSION['error'] = "Ralat semasa menyimpan data: " . $e->getMessage();
        
        // Redirect back to form with error
        header("Location: dikuiriWA3.php");
        exit();
    }
} else {
    // If not POST request, redirect back to form
    header("Location: dikuiriWA3.php");
    exit();
}
?> 