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

        // Check if record exists and its stage
        $check_sql = "SELECT * FROM wilayah_asal WHERE id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $wilayah_asal_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $existing_record = $result->fetch_assoc();

        // Prepare values for binding
        $nama_bapa = $_POST['nama_bapa'];
        $no_kp_bapa = $_POST['no_kp_bapa_raw'] ?? $_POST['no_kp_bapa'];
        $wilayah_menetap_bapa = $_POST['wilayah_menetap_bapa'];
        $alamat_menetap_1_bapa = $_POST['alamat_menetap_1_bapa'];
        $alamat_menetap_2_bapa = $_POST['alamat_menetap_2_bapa'];
        $poskod_menetap_bapa = $_POST['poskod_menetap_bapa'];
        $bandar_menetap_bapa = $_POST['bandar_menetap_bapa'];
        $negeri_menetap_bapa = $_POST['negeri_menetap_bapa'];
        $ibu_negeri_bandar_dituju_bapa = $_POST['ibu_negeri_bandar_dituju_bapa'];
        
        $nama_ibu = $_POST['nama_ibu'];
        $no_kp_ibu = $_POST['no_kp_ibu_raw'] ?? $_POST['no_kp_ibu'];
        $wilayah_menetap_ibu = $_POST['wilayah_menetap_ibu'];
        $alamat_menetap_1_ibu = $_POST['alamat_menetap_1_ibu'];
        $alamat_menetap_2_ibu = $_POST['alamat_menetap_2_ibu'];
        $poskod_menetap_ibu = $_POST['poskod_menetap_ibu'];
        $bandar_menetap_ibu = $_POST['bandar_menetap_ibu'];
        $negeri_menetap_ibu = $_POST['negeri_menetap_ibu'];
        $ibu_negeri_bandar_dituju_ibu = $_POST['ibu_negeri_bandar_dituju_ibu'];

        if ($existing_record && ($existing_record['wilayah_asal_from_stage'] === 'BorangWA3' || $existing_record['wilayah_asal_from_stage'] === 'BorangWA5')) {
            // Update existing record
            $update_sql = "UPDATE wilayah_asal SET 
                nama_bapa = ?,
                no_kp_bapa = ?,
                wilayah_menetap_bapa = ?,
                alamat_menetap_1_bapa = ?,
                alamat_menetap_2_bapa = ?,
                poskod_menetap_bapa = ?,
                bandar_menetap_bapa = ?,
                negeri_menetap_bapa = ?,
                ibu_negeri_bandar_dituju_bapa = ?,
                nama_ibu = ?,
                no_kp_ibu = ?,
                wilayah_menetap_ibu = ?,
                alamat_menetap_1_ibu = ?,
                alamat_menetap_2_ibu = ?,
                poskod_menetap_ibu = ?,
                bandar_menetap_ibu = ?,
                negeri_menetap_ibu = ?,
                ibu_negeri_bandar_dituju_ibu = ?
                WHERE id = ?";

            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssssssssssssssssi",
                $nama_bapa,
                $no_kp_bapa,
                $wilayah_menetap_bapa,
                $alamat_menetap_1_bapa,
                $alamat_menetap_2_bapa,
                $poskod_menetap_bapa,
                $bandar_menetap_bapa,
                $negeri_menetap_bapa,
                $ibu_negeri_bandar_dituju_bapa,
                $nama_ibu,
                $no_kp_ibu,
                $wilayah_menetap_ibu,
                $alamat_menetap_1_ibu,
                $alamat_menetap_2_ibu,
                $poskod_menetap_ibu,
                $bandar_menetap_ibu,
                $negeri_menetap_ibu,
                $ibu_negeri_bandar_dituju_ibu,
                $wilayah_asal_id
            );

            if (!$update_stmt->execute()) {
                throw new Exception("Error updating record: " . $update_stmt->error);
            }
        } else {
            // Insert new record
            $insert_sql = "UPDATE wilayah_asal SET 
                nama_bapa = ?,
                no_kp_bapa = ?,
                wilayah_menetap_bapa = ?,
                alamat_menetap_1_bapa = ?,
                alamat_menetap_2_bapa = ?,
                poskod_menetap_bapa = ?,
                bandar_menetap_bapa = ?,
                negeri_menetap_bapa = ?,
                ibu_negeri_bandar_dituju_bapa = ?,
                nama_ibu = ?,
                no_kp_ibu = ?,
                wilayah_menetap_ibu = ?,
                alamat_menetap_1_ibu = ?,
                alamat_menetap_2_ibu = ?,
                poskod_menetap_ibu = ?,
                bandar_menetap_ibu = ?,
                negeri_menetap_ibu = ?,
                ibu_negeri_bandar_dituju_ibu = ?
                WHERE id = ?";

            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ssssssssssssssssssi",
                $nama_bapa,
                $no_kp_bapa,
                $wilayah_menetap_bapa,
                $alamat_menetap_1_bapa,
                $alamat_menetap_2_bapa,
                $poskod_menetap_bapa,
                $bandar_menetap_bapa,
                $negeri_menetap_bapa,
                $ibu_negeri_bandar_dituju_bapa,
                $nama_ibu,
                $no_kp_ibu,
                $wilayah_menetap_ibu,
                $alamat_menetap_1_ibu,
                $alamat_menetap_2_ibu,
                $poskod_menetap_ibu,
                $bandar_menetap_ibu,
                $negeri_menetap_ibu,
                $ibu_negeri_bandar_dituju_ibu,
                $wilayah_asal_id
            );

            if (!$insert_stmt->execute()) {
                throw new Exception("Error inserting record: " . $insert_stmt->error);
            }
        }

        // Update wilayah_asal_from_stage
        $update_stage_sql = "UPDATE wilayah_asal SET wilayah_asal_from_stage = 'BorangWA3' WHERE id = ?";
        $update_stage_stmt = $conn->prepare($update_stage_sql);
        $update_stage_stmt->bind_param("i", $wilayah_asal_id);
        $update_stage_stmt->execute();
        $update_stage_stmt->close();

        // Store parent information in session
        $_SESSION['parent_info'] = [
            'nama_bapa' => $nama_bapa,
            'no_kp_bapa' => $no_kp_bapa,
            'wilayah_menetap_bapa' => $wilayah_menetap_bapa,
            'alamat_menetap_1_bapa' => $alamat_menetap_1_bapa,
            'alamat_menetap_2_bapa' => $alamat_menetap_2_bapa,
            'poskod_menetap_bapa' => $poskod_menetap_bapa,
            'bandar_menetap_bapa' => $bandar_menetap_bapa,
            'negeri_menetap_bapa' => $negeri_menetap_bapa,
            'ibu_negeri_bandar_dituju_bapa' => $ibu_negeri_bandar_dituju_bapa,
            'nama_ibu' => $nama_ibu,
            'no_kp_ibu' => $no_kp_ibu,
            'wilayah_menetap_ibu' => $wilayah_menetap_ibu,
            'alamat_menetap_1_ibu' => $alamat_menetap_1_ibu,
            'alamat_menetap_2_ibu' => $alamat_menetap_2_ibu,
            'poskod_menetap_ibu' => $poskod_menetap_ibu,
            'bandar_menetap_ibu' => $bandar_menetap_ibu,
            'negeri_menetap_ibu' => $negeri_menetap_ibu,
            'ibu_negeri_bandar_dituju_ibu' => $ibu_negeri_bandar_dituju_ibu
        ];

        // Keep existing borangWA_data if it exists
        if (!isset($_SESSION['borangWA_data'])) {
            $_SESSION['borangWA_data'] = [];
        }

        // Redirect to the next form based on the stage
        if ($existing_record['wilayah_asal_form_fill'] === 1) {

            $update_stage_sql2 = "UPDATE wilayah_asal SET wilayah_asal_from_stage = 'BorangWA5' WHERE id = ?";
            $update_stage_stmt2 = $conn->prepare($update_stage_sql2);
            $update_stage_stmt2->bind_param("i", $wilayah_asal_id);
            $update_stage_stmt2->execute();
            $update_stage_stmt2->close();
            header("Location: ../borangWA5.php?id=" . $wilayah_asal_id);

        } else {
            header("Location: ../borangWA3.php");
        }
        exit();
    } catch (Exception $e) {
        // Log the error
        error_log("Error in process_borangWA2.php: " . $e->getMessage());
        
        // Set error message in session
        $_SESSION['error'] = "Ralat semasa menyimpan data. Sila cuba lagi.";
        
        // Redirect back to form with error
        header("Location: ../borangWA2.php");
        exit();
    }
} else {
    // If not POST request, redirect back to form
    header("Location: ../borangWA2.php");
    exit();
}
?> 