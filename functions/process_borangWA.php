<?php
session_start();
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store form data in session
    $_SESSION['borangWA_data'] = [
        'user_kp' => $_POST['user_kp'],
        'nama_pegawai' => $_POST['nama_pegawai'],
        'jawatan_gred' => $_POST['jawatan_gred'],
        'alamat_menetap_1' => $_POST['alamat_menetap_1'],
        'alamat_menetap_2' => $_POST['alamat_menetap_2'],
        'poskod_menetap' => $_POST['poskod_menetap'],
        'bandar_menetap' => $_POST['bandar_menetap'],
        'negeri_menetap' => $_POST['negeri_menetap'],
        'alamat_berkhidmat_1' => $_POST['alamat_berkhidmat_1'],
        'alamat_berkhidmat_2' => $_POST['alamat_berkhidmat_2'],
        'poskod_berkhidmat' => $_POST['poskod_berkhidmat'],
        'bandar_berkhidmat' => $_POST['bandar_berkhidmat'],
        'negeri_berkhidmat' => $_POST['negeri_berkhidmat'],
        'tarikh_lapor_diri' => $_POST['tarikh_lapor_diri'],
        'pernah_guna' => $_POST['pernah_guna'],
        'tarikh_terakhir_kemudahan' => $_POST['pernah_guna'] === 'ya' ? $_POST['tarikh_terakhir_kemudahan'] : null
    ];

    // Redirect to second form
    header("Location: ../role/pemohon/borangWA2.php");
    exit();
} else {
    // If not POST request, redirect back to form
    header("Location: ../role/pemohon/borangWA.php");
    exit();
}
?> 