<?php
session_start();
include '../../connection.php';
require_once 'includes/borangWA5_functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_kp'])) {
    header("Location: ../../loginUser.php");
    exit();
}

$user_kp = $_SESSION['user_kp'];
$wilayah_asal_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$wilayah_asal_id) {
    header("Location: dashboard.php");
    exit();
}

// Get wilayah asal details
$wilayah_asal = getWilayahAsalDetails($conn, $wilayah_asal_id, $user_kp);

// Debug information
error_log("User KP: " . $user_kp);
error_log("Wilayah Asal ID: " . $wilayah_asal_id);
error_log("Wilayah Asal Data: " . print_r($wilayah_asal, true));

if (!$wilayah_asal) {
    error_log("No wilayah asal found or user mismatch");
    header("Location: dashboard.php");
    exit();
}

// Get pengikut details
$pengikut_list = getPengikutDetails($conn, $wilayah_asal_id);

// Get documents
$documents = getDocuments($conn, $wilayah_asal_id);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_wilayah_asal'])) {
        updateWilayahAsal($conn, $wilayah_asal_id, $_POST);
    } elseif (isset($_POST['update_pengikut'])) {
        updatePengikut($conn, $_POST);
    } elseif (isset($_POST['upload_document'])) {
        uploadDocument($conn, $wilayah_asal_id, $user_kp, $_FILES, $_POST);
    }
    // Refresh the page to show updated data
    header("Location: borangWA5.php?id=" . $wilayah_asal_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Permohonan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Review Permohonan</h2>
        
        <!-- Main Details Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Maklumat Utama</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="update_wilayah_asal" value="1">
                    <?php include 'includes/borangWA5_main_details.php'; ?>
                    <button type="submit" class="btn btn-primary mt-3">Kemaskini Maklumat Utama</button>
                </form>
            </div>
        </div>

        <!-- Pengikut Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Senarai Pengikut</h4>
            </div>
            <div class="card-body">
                <?php include 'includes/borangWA5_pengikut.php'; ?>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Dokumen Sokongan</h4>
            </div>
            <div class="card-body">
                <?php include 'includes/borangWA5_documents.php'; ?>
            </div>
        </div>

        <div class="mb-4">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html> 