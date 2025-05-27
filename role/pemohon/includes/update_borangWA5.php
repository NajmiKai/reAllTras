<?php
session_start();
include '../../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $wilayah_asal_id = $_POST['wilayah_asal_id'];
        $update_type = $_POST['update_type'];

        switch ($update_type) {
            case 'pegawai':
                $sql = "UPDATE wilayah_asal SET 
                    jawatan_gred = ?
                    WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $_POST['jawatan_gred'], $wilayah_asal_id);
                break;

            case 'pasangan':
                $sql = "UPDATE wilayah_asal SET 
                    nama_pasangan = ?,
                    no_kp_pasangan = ?,
                    wilayah_menetap_pasangan = ?,
                    alamat_menetap_1_pasangan = ?,
                    alamat_menetap_2_pasangan = ?,
                    poskod_menetap_pasangan = ?,
                    bandar_menetap_pasangan = ?,
                    negeri_menetap_pasangan = ?,
                    ibu_negeri_bandar_dituju_pasangan = ?
                    WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssssi",
                    $_POST['nama_pasangan'],
                    $_POST['no_kp_pasangan'],
                    $_POST['wilayah_menetap_pasangan'],
                    $_POST['alamat_menetap_1_pasangan'],
                    $_POST['alamat_menetap_2_pasangan'],
                    $_POST['poskod_menetap_pasangan'],
                    $_POST['bandar_menetap_pasangan'],
                    $_POST['negeri_menetap_pasangan'],
                    $_POST['ibu_negeri_bandar_dituju_pasangan'],
                    $wilayah_asal_id
                );
                break;

            case 'ibu_bapa':
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
                    ibu_negeri_bandar_dituju_ibu = ?
                    WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssssssssssssssi",
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
                break;

            case 'penerbangan':
                $sql = "UPDATE wilayah_asal SET 
                    jenis_permohonan = ?,
                    tarikh_berlepas = ?,
                    tarikh_kembali = ?,
                    lapangan_terbang_berlepas = ?,
                    lapangan_terbang_tiba = ?
                    WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssi",
                    $_POST['jenis_permohonan'],
                    $_POST['tarikh_berlepas'],
                    $_POST['tarikh_kembali'],
                    $_POST['lapangan_terbang_berlepas'],
                    $_POST['lapangan_terbang_tiba'],
                    $wilayah_asal_id
                );
                break;

            case 'dokumen':
                // Handle file upload
                $upload_dir = "../../uploads/";
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                if (isset($_FILES['dokumen']) && $_FILES['dokumen']['error'] == 0) {
                    $file = $_FILES['dokumen'];
                    $file_name = time() . '_' . basename($file['name']);
                    $target_path = $upload_dir . $file_name;

                    if (move_uploaded_file($file['tmp_name'], $target_path)) {
                        $sql = "INSERT INTO dokumen_sokongan (wilayah_asal_id, jenis_dokumen, nama_fail) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("iss", $wilayah_asal_id, $_POST['jenis_dokumen'], $file_name);
                    } else {
                        throw new Exception("Error uploading file");
                    }
                } else {
                    throw new Exception("No file uploaded");
                }
                break;

            default:
                throw new Exception("Invalid update type");
        }

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
    } catch (Exception $e) {
        error_log("Error in update_borangWA5.php: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    header("Location: ../wilayahAsal.php");
    exit();
}
?> 