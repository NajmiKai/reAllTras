<?php
include '../../connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get wilayah_asal_id from session
    $wilayah_asal_id = $_SESSION['wilayah_asal_id'] ?? null;

    if (!$wilayah_asal_id) {
        $_SESSION['error'] = "Sesi tidak sah. Sila cuba semula.";
        header("Location: ../dikuiriWA2.php");
        exit();
    }

    // Prepare the SQL statement for updating wilayah_asal table
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
            updated_at = CURRENT_TIMESTAMP
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bind_param("ssssssssssssssssssi",
        $_POST['nama_bapa'],
        $_POST['no_kp_bapa'],
        $_POST['wilayah_menetap_bapa'],
        $_POST['alamat_menetap_1_bapa'],
        $_POST['alamat_menetap_2_bapa'],
        $_POST['poskod_menetap_bapa'],
        $_POST['bandar_menetap_bapa'],
        $_POST['negeri_menetap_bapa'],
        $_POST['ibu_negeri_bandar_dituju_bapa'],
        $_POST['nama_ibu'],
        $_POST['no_kp_ibu'],
        $_POST['wilayah_menetap_ibu'],
        $_POST['alamat_menetap_1_ibu'],
        $_POST['alamat_menetap_2_ibu'],
        $_POST['poskod_menetap_ibu'],
        $_POST['bandar_menetap_ibu'],
        $_POST['negeri_menetap_ibu'],
        $_POST['ibu_negeri_bandar_dituju_ibu'],
        $wilayah_asal_id
    );

    // Execute the statement
    if ($stmt->execute()) {
        // Success - redirect to next page
        include 'process_Dikuiri_Update.php';
        header("Location: dashboard.php");
        exit();
    } else {
        // Error occurred
        $_SESSION['error'] = "Ralat: " . $stmt->error;
        header("Location: dikuiriWA2.php");
        exit();
    }

    $stmt->close();
} else {
    // If not POST request, redirect back to form
    header("Location: dikuiriWA2.php");
    exit();
}

$conn->close();
?> 