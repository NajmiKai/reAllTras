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
            ibu_negeri_bandar_dituju_ibu = ?,
            wilayah_asal_form_fill = true,
            wilayah_asal_from_stage = 'Hantar',
            status_permohonan = 'Belum Disemak',
            kedudukan_permohonan = 'Pemohon'
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        
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

        // Bind parameters - 19 parameters total (18 strings + 1 integer)
        $stmt->bind_param("ssssssssssssssssssi",
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

        // Execute the statement
        if ($stmt->execute()) {
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

            // Redirect to the next form
            header("Location: ../wilayahAsal.php");
            exit();
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Log the error
        error_log("Error in process_borangWA2.php: " . $e->getMessage());
        
        // Set error message in session
        $_SESSION['error'] = "Ralat semasa menyimpan data. Sila cuba lagi.";
        
        // Redirect back to form with error
        header("Location: ../dikuiriWA2.php");
        exit();
    }
} else {
    // If not POST request, redirect back to form
    header("Location: ../dikuiriWA2.php");
    exit();
}
?> 