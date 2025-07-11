<?php
session_start();
include_once '../../../includes/config.php';

if (!isset($_SESSION['super_admin_id'])) {
    die("Akses tidak sah.");
}

$superadminid = $_SESSION['super_admin_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document_id'], $_POST['wilayah_asal_id'])) {
    $id = (int) $_POST['document_id'];
    $wilayah_asal_id = $_POST['wilayah_asal_id'];

    // 1. Get file path from DB
    $stmt = $conn->prepare("SELECT file_path FROM documents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $file = $result->fetch_assoc();

    if ($file && file_exists('../../../' . $file['file_path'])) {
        unlink('../../../' . $file['file_path']); // Delete file from disk
    }

    // 2. Delete from DB
    $stmt = $conn->prepare("DELETE FROM documents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt) {
        echo "<script>
            alert('Dokumen berjaya dipadamkan.');
            window.location.href = '../viewWilayahAsal.php?id=$wilayah_asal_id';
         </script>";
    exit;
    }
}
