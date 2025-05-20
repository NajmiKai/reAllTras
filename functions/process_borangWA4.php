<?php
session_start();
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required session data exists
    if (!isset($_SESSION['borangWA_data']) || !isset($_SESSION['parent_info']) || !isset($_SESSION['flight_info'])) {
        header("Location: ../role/pemohon/borangWA.php");
        exit();
    }

    // Get data from session
    $officer_data = $_SESSION['borangWA_data'];
    $parent_data = $_SESSION['parent_info'];
    $flight_data = $_SESSION['flight_info'];

    try {
        // Start transaction
        $conn->begin_transaction();

        // Insert into main application table
        $sql = "INSERT INTO permohonan_wilayah_asal (
            user_kp, nama_pegawai, jawatan_gred,
            alamat_menetap_1, alamat_menetap_2, poskod_menetap, bandar_menetap, negeri_menetap,
            alamat_berkhidmat_1, alamat_berkhidmat_2, poskod_berkhidmat, bandar_berkhidmat, negeri_berkhidmat,
            tarikh_lapor_diri, pernah_guna, tarikh_terakhir_kemudahan,
            nama_bapa, no_kp_bapa, wilayah_menetap_bapa,
            alamat_menetap_1_bapa, alamat_menetap_2_bapa, poskod_menetap_bapa,
            bandar_menetap_bapa, negeri_menetap_bapa, ibu_negeri_bandar_dituju_bapa,
            nama_ibu, no_kp_ibu, wilayah_menetap_ibu,
            alamat_menetap_1_ibu, alamat_menetap_2_ibu, poskod_menetap_ibu,
            bandar_menetap_ibu, negeri_menetap_ibu, ibu_negeri_bandar_dituju_ibu,
            tarikh_penerbangan_pergi, tarikh_penerbangan_balik,
            start_point, end_point,
            status_permohonan, tarikh_permohonan
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'DRAF', NOW())";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssssssssssssssssssssssss",
            $officer_data['user_kp'],
            $officer_data['nama_pegawai'],
            $officer_data['jawatan_gred'],
            $officer_data['alamat_menetap_1'],
            $officer_data['alamat_menetap_2'],
            $officer_data['poskod_menetap'],
            $officer_data['bandar_menetap'],
            $officer_data['negeri_menetap'],
            $officer_data['alamat_berkhidmat_1'],
            $officer_data['alamat_berkhidmat_2'],
            $officer_data['poskod_berkhidmat'],
            $officer_data['bandar_berkhidmat'],
            $officer_data['negeri_berkhidmat'],
            $officer_data['tarikh_lapor_diri'],
            $officer_data['pernah_guna'],
            $officer_data['tarikh_terakhir_kemudahan'],
            $parent_data['nama_bapa'],
            $parent_data['no_kp_bapa'],
            $parent_data['wilayah_menetap_bapa'],
            $parent_data['alamat_menetap_1_bapa'],
            $parent_data['alamat_menetap_2_bapa'],
            $parent_data['poskod_menetap_bapa'],
            $parent_data['bandar_menetap_bapa'],
            $parent_data['negeri_menetap_bapa'],
            $parent_data['ibu_negeri_bandar_dituju_bapa'],
            $parent_data['nama_ibu'],
            $parent_data['no_kp_ibu'],
            $parent_data['wilayah_menetap_ibu'],
            $parent_data['alamat_menetap_1_ibu'],
            $parent_data['alamat_menetap_2_ibu'],
            $parent_data['poskod_menetap_ibu'],
            $parent_data['bandar_menetap_ibu'],
            $parent_data['negeri_menetap_ibu'],
            $parent_data['ibu_negeri_bandar_dituju_ibu'],
            $flight_data['tarikh_penerbangan_pergi'],
            $flight_data['tarikh_penerbangan_balik'],
            $flight_data['start_point'],
            $flight_data['end_point']
        );

        $stmt->execute();
        $application_id = $conn->insert_id;

        // If there are followers, insert them
        if (isset($flight_data['pengikut']) && !empty($flight_data['pengikut'])) {
            $sql_followers = "INSERT INTO pengikut_permohonan (
                permohonan_id, nama_depan, nama_belakang, no_kp, tarikh_lahir,
                tarikh_penerbangan_pergi, tarikh_penerbangan_balik
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt_followers = $conn->prepare($sql_followers);

            foreach ($flight_data['pengikut'] as $pengikut) {
                $stmt_followers->bind_param("issssss",
                    $application_id,
                    $pengikut['nama_depan'],
                    $pengikut['nama_belakang'],
                    $pengikut['no_kp'],
                    $pengikut['tarikh_lahir'],
                    $pengikut['tarikh_penerbangan_pergi'],
                    $pengikut['tarikh_penerbangan_balik']
                );
                $stmt_followers->execute();
            }
        }

        // Commit transaction
        $conn->commit();

        // Clear session data
        unset($_SESSION['borangWA_data']);
        unset($_SESSION['parent_info']);
        unset($_SESSION['flight_info']);

        // Set success message
        $_SESSION['success_message'] = "Permohonan berjaya dihantar!";
        
        // Redirect to dashboard
        header("Location: ../role/pemohon/dashboard.php");
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        // Set error message
        $_SESSION['error_message'] = "Ralat: " . $e->getMessage();
        
        // Redirect back to form
        header("Location: ../role/pemohon/borangWA4.php");
        exit();
    }
} else {
    // If not POST request, redirect back to form
    header("Location: ../role/pemohon/borangWA4.php");
    exit();
}
?> 