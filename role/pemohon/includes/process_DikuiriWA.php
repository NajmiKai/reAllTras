<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wilayah_asal_id = $_POST['wilayah_asal_id'];

    // Get all form data
    $jawatan_gred = $_POST['jawatan_gred'];
    $email_penyelia = $_POST['email_penyelia'];
    
    // Alamat Menetap
    $alamat_menetap_1 = $_POST['alamat_menetap_1'];
    $alamat_menetap_2 = $_POST['alamat_menetap_2'];
    $poskod_menetap = $_POST['poskod_menetap'];
    $bandar_menetap = $_POST['bandar_menetap'];
    $negeri_menetap = $_POST['negeri_menetap'];
    
    // Alamat Berkhidmat
    $alamat_berkhidmat_1 = $_POST['alamat_berkhidmat_1'];
    $alamat_berkhidmat_2 = $_POST['alamat_berkhidmat_2'];
    $poskod_berkhidmat = $_POST['poskod_berkhidmat'];
    $bandar_berkhidmat = $_POST['bandar_berkhidmat'];
    $negeri_berkhidmat = $_POST['negeri_berkhidmat'];
    
    // Maklumat Tambahan
    $tarikh_lapor_diri = $_POST['tarikh_lapor_diri'];
    $pernah_guna = $_POST['pernah_guna'];
    $tarikh_terakhir_kemudahan = ($pernah_guna === 'ya') ? $_POST['tarikh_terakhir_kemudahan'] : null;
    
    // Partner Information
    $ada_pasangan = $_POST['ada_pasangan'];
    
    // Initialize partner fields as null
    $nama_first_pasangan = null;
    $nama_last_pasangan = null;
    $no_kp_pasangan = null;
    $wilayah_menetap_pasangan = null;
    $alamat_berkhidmat_1_pasangan = null;
    $alamat_berkhidmat_2_pasangan = null;
    $poskod_berkhidmat_pasangan = null;
    $bandar_berkhidmat_pasangan = null;
    $negeri_berkhidmat_pasangan = null;
    
    // If partner exists, get partner information
    if ($ada_pasangan === 'ya') {
        $nama_first_pasangan = $_POST['nama_first_pasangan'];
        $nama_last_pasangan = $_POST['nama_last_pasangan'];
        $no_kp_pasangan = $_POST['no_kp_pasangan'];
        $wilayah_menetap_pasangan = $_POST['wilayah_menetap_pasangan'];
        $alamat_berkhidmat_1_pasangan = $_POST['alamat_berkhidmat_1_pasangan'];
        $alamat_berkhidmat_2_pasangan = $_POST['alamat_berkhidmat_2_pasangan'];
        $poskod_berkhidmat_pasangan = $_POST['poskod_berkhidmat_pasangan'];
        $bandar_berkhidmat_pasangan = $_POST['bandar_berkhidmat_pasangan'];
        $negeri_berkhidmat_pasangan = $_POST['negeri_berkhidmat_pasangan'];
    }

    try {
        // Prepare the SQL statement
        $sql = "UPDATE wilayah_asal SET 
                jawatan_gred = ?,
                email_penyelia = ?,
                alamat_menetap_1 = ?,
                alamat_menetap_2 = ?,
                poskod_menetap = ?,
                bandar_menetap = ?,
                negeri_menetap = ?,
                alamat_berkhidmat_1 = ?,
                alamat_berkhidmat_2 = ?,
                poskod_berkhidmat = ?,
                bandar_berkhidmat = ?,
                negeri_berkhidmat = ?,
                tarikh_lapor_diri = ?,
                tarikh_terakhir_kemudahan = ?,
                nama_first_pasangan = ?,
                nama_last_pasangan = ?,
                no_kp_pasangan = ?,
                wilayah_menetap_pasangan = ?,
                alamat_berkhidmat_1_pasangan = ?,
                alamat_berkhidmat_2_pasangan = ?,
                poskod_berkhidmat_pasangan = ?,
                bandar_berkhidmat_pasangan = ?,
                negeri_berkhidmat_pasangan = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssssssssssssssssi",
            $jawatan_gred,
            $email_penyelia,
            $alamat_menetap_1,
            $alamat_menetap_2,
            $poskod_menetap,
            $bandar_menetap,
            $negeri_menetap,
            $alamat_berkhidmat_1,
            $alamat_berkhidmat_2,
            $poskod_berkhidmat,
            $bandar_berkhidmat,
            $negeri_berkhidmat,
            $tarikh_lapor_diri,
            $tarikh_terakhir_kemudahan,
            $nama_first_pasangan,
            $nama_last_pasangan,
            $no_kp_pasangan,
            $wilayah_menetap_pasangan,
            $alamat_berkhidmat_1_pasangan,
            $alamat_berkhidmat_2_pasangan,
            $poskod_berkhidmat_pasangan,
            $bandar_berkhidmat_pasangan,
            $negeri_berkhidmat_pasangan,
            $wilayah_asal_id
        );

        if ($stmt->execute()) {
            $_SESSION['success'] = "Maklumat berjaya dikemaskini.";
            include 'process_Dikuiri_Update.php';
            header("Location: dashboard.php");
            exit();
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "Ralat: " . $e->getMessage();
        header("Location: ../dikuiriWA.php");
        exit();
    }
} else {
    header("Location: ../dikuiriWA.php");
    exit();
}
?> 