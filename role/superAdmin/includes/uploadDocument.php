<?php
session_start();
include_once '../../../includes/config.php';

if (!isset($_SESSION['super_admin_id'])) {
    die("Akses tidak sah: super_admin_id tidak ditemui dalam sesi.");
}
$superadminid = $_SESSION['super_admin_id'];

if (!isset($_POST['wilayah_asal_id'], $_POST['document_id'])) {
    die("Ralat: Data wilayah_asal_id atau document_id tidak dihantar.");
}

$wilayah_asal_id = $_POST['wilayah_asal_id'];
$id = (int) $_POST['document_id'];

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document_file'])) {
    $file = $_FILES['document_file'];
    $description = $_POST['description'] ?? '';

    if ($file['error'] === 0) {
        // Upload path
        $upload_dir = "../../../uploads/superadmin/" . $wilayah_asal_id;
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($file['name']);
        $file_type = $file['type'];
        $file_size = $file['size'];

        // Unique filename
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_filename = uniqid() . '_' . $wilayah_asal_id . '_' . str_replace(' ', '_', $description) . '.' . $file_extension;
        $target_path = $upload_dir . '/' . $unique_filename;

        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            // Simpan path berdasarkan tempat sebenar fail diakses web
            $web_path = "../../../uploads/superadmin/" . $wilayah_asal_id . '/' . $unique_filename;

            // Save to database
            $sql = "INSERT INTO documents (wilayah_asal_id, file_name, file_path, file_type, file_size, description, file_origin_id, file_origin) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'super admin')";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("isssiss", $wilayah_asal_id, $file_name, $web_path, $file_type, $file_size, $description, $superadminid);
                $success = $stmt->execute();

                if (!$success) {
                    echo "Gagal simpan ke database: " . $stmt->error;
                    exit;
                } else {
                    echo "<script>
                        alert('Fail berjaya dimuat naik dan disimpan.');
                        window.location.href = '../viewWilayahAsal.php?id=$wilayah_asal_id';
                     </script>";
                exit;
                } 
            } else {
                echo "Ralat prepare SQL: " . $conn->error;
                exit;
            }
        } else {
            echo "Gagal muat naik fail ke direktori.";
            exit;
        }
    } else {
        echo "Fail tidak valid atau kosong.";
        exit;
    }
}

