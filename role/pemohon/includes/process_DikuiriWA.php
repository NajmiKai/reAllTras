<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $jawatan_gred = $_POST['jawatan_gred'];
    $email_penyelia = $_POST['email_penyelia'];
    $alamat_menetap_1 = $_POST['alamat_menetap_1'];
    $alamat_menetap_2 = $_POST['alamat_menetap_2'];
    $poskod_menetap = $_POST['poskod_menetap'];
    $bandar_menetap = $_POST['bandar_menetap'];
    $negeri_menetap = $_POST['negeri_menetap'];
    $alamat_berkhidmat_1 = $_POST['alamat_berkhidmat_1'];
    $alamat_berkhidmat_2 = $_POST['alamat_berkhidmat_2'];
    $poskod_berkhidmat = $_POST['poskod_berkhidmat'];
    $bandar_berkhidmat = $_POST['bandar_berkhidmat'];
    $negeri_berkhidmat = $_POST['negeri_berkhidmat'];
    $nama_first_pasangan = $_POST['nama_first_pasangan'];
    $nama_last_pasangan = $_POST['nama_last_pasangan'];
    $no_kp_pasangan = $_POST['no_kp_pasangan'];

    // Update database
    $update_sql = "UPDATE wilayah_asal SET 
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
        nama_first_pasangan = ?,
        nama_last_pasangan = ?,
        no_kp_pasangan = ?
        WHERE user_kp = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssssssssssss", 
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
        $nama_first_pasangan,
        $nama_last_pasangan,
        $no_kp_pasangan,
        $user_icNo
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Maklumat pegawai berjaya dikemaskini.";
        header("Location: wilayahAsal.php");
        exit();
    } else {
        $_SESSION['error'] = "Ralat: " . $stmt->error;
    }
}
?> 