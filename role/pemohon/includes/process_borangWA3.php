<?php
session_start();
include '../../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get wilayah_asal_id from POST or session
        $wilayah_asal_id = $_POST['wilayah_asal_id'] ?? $_SESSION['wilayah_asal_id'] ?? null;

        if (!$wilayah_asal_id) {
            throw new Exception("No wilayah_asal_id found");
        }

        // Get form data
        $nama_penjaga = $_POST['nama_penjaga'];
        $no_kp_penjaga = $_POST['no_kp_penjaga'];
        $wilayah_menetap_penjaga = $_POST['wilayah_menetap_penjaga'];
        $alamat_menetap_1_penjaga = $_POST['alamat_menetap_1_penjaga'];
        $alamat_menetap_2_penjaga = $_POST['alamat_menetap_2_penjaga'];
        $poskod_menetap_penjaga = $_POST['poskod_menetap_penjaga'];
        $bandar_menetap_penjaga = $_POST['bandar_menetap_penjaga'];
        $negeri_menetap_penjaga = $_POST['negeri_menetap_penjaga'];
        $ibu_negeri_bandar_dituju_penjaga = $_POST['ibu_negeri_bandar_dituju_penjaga'];

        // Update guardian information in database
        $sql = "UPDATE wilayah_asal SET 
            nama_penjaga = ?,
            no_kp_penjaga = ?,
            wilayah_menetap_penjaga = ?,
            alamat_menetap_1_penjaga = ?,
            alamat_menetap_2_penjaga = ?,
            poskod_menetap_penjaga = ?,
            bandar_menetap_penjaga = ?,
            negeri_menetap_penjaga = ?,
            ibu_negeri_bandar_dituju_penjaga = ?,
            wilayah_asal_from_stage = 'BorangWA4'
            WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssi",
            $nama_penjaga, $no_kp_penjaga, $wilayah_menetap_penjaga,
            $alamat_menetap_1_penjaga, $alamat_menetap_2_penjaga,
            $poskod_menetap_penjaga, $bandar_menetap_penjaga,
            $negeri_menetap_penjaga, $ibu_negeri_bandar_dituju_penjaga,
            $wilayah_asal_id
        );

        if ($stmt->execute()) {
            // Store guardian information in session
            $_SESSION['guardian_info'] = [
                'nama_penjaga' => $nama_penjaga,
                'no_kp_penjaga' => $no_kp_penjaga,
                'wilayah_menetap_penjaga' => $wilayah_menetap_penjaga,
                'alamat_menetap_1_penjaga' => $alamat_menetap_1_penjaga,
                'alamat_menetap_2_penjaga' => $alamat_menetap_2_penjaga,
                'poskod_menetap_penjaga' => $poskod_menetap_penjaga,
                'bandar_menetap_penjaga' => $bandar_menetap_penjaga,
                'negeri_menetap_penjaga' => $negeri_menetap_penjaga,
                'ibu_negeri_bandar_dituju_penjaga' => $ibu_negeri_bandar_dituju_penjaga
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