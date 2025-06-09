<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data for Bapa
    $nama_bapa = $_POST['nama_bapa'];
    $no_kp_bapa = $_POST['no_kp_bapa'];
    $wilayah_menetap_bapa = $_POST['wilayah_menetap_bapa'];
    $alamat_menetap_1_bapa = $_POST['alamat_menetap_1_bapa'];
    $alamat_menetap_2_bapa = $_POST['alamat_menetap_2_bapa'];
    $poskod_menetap_bapa = $_POST['poskod_menetap_bapa'];
    $bandar_menetap_bapa = $_POST['bandar_menetap_bapa'];
    $negeri_menetap_bapa = $_POST['negeri_menetap_bapa'];

    // Get form data for Ibu
    $nama_ibu = $_POST['nama_ibu'];
    $no_kp_ibu = $_POST['no_kp_ibu'];
    $wilayah_menetap_ibu = $_POST['wilayah_menetap_ibu'];
    $alamat_menetap_1_ibu = $_POST['alamat_menetap_1_ibu'];
    $alamat_menetap_2_ibu = $_POST['alamat_menetap_2_ibu'];
    $poskod_menetap_ibu = $_POST['poskod_menetap_ibu'];
    $bandar_menetap_ibu = $_POST['bandar_menetap_ibu'];
    $negeri_menetap_ibu = $_POST['negeri_menetap_ibu'];

    // Update database
    $update_sql = "UPDATE wilayah_asal SET 
        nama_bapa = ?,
        no_kp_bapa = ?,
        wilayah_menetap_bapa = ?,
        alamat_menetap_1_bapa = ?,
        alamat_menetap_2_bapa = ?,
        poskod_menetap_bapa = ?,
        bandar_menetap_bapa = ?,
        negeri_menetap_bapa = ?,
        nama_ibu = ?,
        no_kp_ibu = ?,
        wilayah_menetap_ibu = ?,
        alamat_menetap_1_ibu = ?,
        alamat_menetap_2_ibu = ?,
        poskod_menetap_ibu = ?,
        bandar_menetap_ibu = ?,
        negeri_menetap_ibu = ?
        WHERE user_kp = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssssssssssssssss", 
        $nama_bapa,
        $no_kp_bapa,
        $wilayah_menetap_bapa,
        $alamat_menetap_1_bapa,
        $alamat_menetap_2_bapa,
        $poskod_menetap_bapa,
        $bandar_menetap_bapa,
        $negeri_menetap_bapa,
        $nama_ibu,
        $no_kp_ibu,
        $wilayah_menetap_ibu,
        $alamat_menetap_1_ibu,
        $alamat_menetap_2_ibu,
        $poskod_menetap_ibu,
        $bandar_menetap_ibu,
        $negeri_menetap_ibu,
        $user_icNo
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Maklumat ibu bapa berjaya dikemaskini.";
        header("Location: wilayahAsal.php");
        exit();
    } else {
        $_SESSION['error'] = "Ralat: " . $stmt->error;
    }
}
?> 