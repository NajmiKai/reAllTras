<?php
session_start();
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if first form data exists in session
    if (!isset($_SESSION['borangWA_data'])) {
        header("Location: ../role/pemohon/borangWA.php");
        exit();
    }

    // Combine data from both forms
    $formData = array_merge($_SESSION['borangWA_data'], [
        'tarikh_penerbangan_pergi' => $_POST['tarikh_penerbangan_pergi'],
        'tarikh_penerbangan_balik' => $_POST['tarikh_penerbangan_balik'],
        'start_point' => $_POST['start_point'],
        'end_point' => $_POST['end_point'],
        'status_permohonan' => 'Belum Disemak',
        'kedudukan_permohonan' => 'Pemohon'
    ]);

    try {
        // Prepare SQL statement
        $sql = "INSERT INTO wilayah_asal (
            user_kp, jawatan_gred,
            alamat_menetap_1, alamat_menetap_2, poskod_menetap, bandar_menetap, negeri_menetap,
            alamat_berkhidmat_1, alamat_berkhidmat_2, poskod_berkhidmat, bandar_berkhidmat, negeri_berkhidmat,
            tarikh_lapor_diri, tarikh_terakhir_kemudahan,
            tarikh_penerbangan_pergi, tarikh_penerbangan_balik,
            start_point, end_point,
            status_permohonan, kedudukan_permohonan
        ) VALUES (
            :user_kp, :jawatan_gred,
            :alamat_menetap_1, :alamat_menetap_2, :poskod_menetap, :bandar_menetap, :negeri_menetap,
            :alamat_berkhidmat_1, :alamat_berkhidmat_2, :poskod_berkhidmat, :bandar_berkhidmat, :negeri_berkhidmat,
            :tarikh_lapor_diri, :tarikh_terakhir_kemudahan,
            :tarikh_penerbangan_pergi, :tarikh_penerbangan_balik,
            :start_point, :end_point,
            :status_permohonan, :kedudukan_permohonan
        )";

        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':user_kp', $formData['user_kp']);
        $stmt->bindParam(':jawatan_gred', $formData['jawatan_gred']);
        $stmt->bindParam(':alamat_menetap_1', $formData['alamat_menetap_1']);
        $stmt->bindParam(':alamat_menetap_2', $formData['alamat_menetap_2']);
        $stmt->bindParam(':poskod_menetap', $formData['poskod_menetap']);
        $stmt->bindParam(':bandar_menetap', $formData['bandar_menetap']);
        $stmt->bindParam(':negeri_menetap', $formData['negeri_menetap']);
        $stmt->bindParam(':alamat_berkhidmat_1', $formData['alamat_berkhidmat_1']);
        $stmt->bindParam(':alamat_berkhidmat_2', $formData['alamat_berkhidmat_2']);
        $stmt->bindParam(':poskod_berkhidmat', $formData['poskod_berkhidmat']);
        $stmt->bindParam(':bandar_berkhidmat', $formData['bandar_berkhidmat']);
        $stmt->bindParam(':negeri_berkhidmat', $formData['negeri_berkhidmat']);
        $stmt->bindParam(':tarikh_lapor_diri', $formData['tarikh_lapor_diri']);
        $stmt->bindParam(':tarikh_terakhir_kemudahan', $formData['tarikh_terakhir_kemudahan']);
        $stmt->bindParam(':tarikh_penerbangan_pergi', $formData['tarikh_penerbangan_pergi']);
        $stmt->bindParam(':tarikh_penerbangan_balik', $formData['tarikh_penerbangan_balik']);
        $stmt->bindParam(':start_point', $formData['start_point']);
        $stmt->bindParam(':end_point', $formData['end_point']);
        $stmt->bindParam(':status_permohonan', $formData['status_permohonan']);
        $stmt->bindParam(':kedudukan_permohonan', $formData['kedudukan_permohonan']);

        // Execute the statement
        $stmt->execute();

        // Clear the session data
        unset($_SESSION['borangWA_data']);

        // Redirect to success page or dashboard
        header("Location: ../role/pemohon/dashboard.php?status=success");
        exit();

    } catch(PDOException $e) {
        // Handle error
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: ../role/pemohon/borangWA2.php");
        exit();
    }
} else {
    // If not POST request, redirect back to first form
    header("Location: ../role/pemohon/borangWA.php");
    exit();
}
?> 