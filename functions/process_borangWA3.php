<?php
session_start();
include '../connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $tarikh_penerbangan_pergi = $_POST['tarikh_penerbangan_pergi'];
    $tarikh_penerbangan_balik = $_POST['tarikh_penerbangan_balik'];
    $start_point = $_POST['start_point'];
    $end_point = $_POST['end_point'];

    // Validate dates
    if (strtotime($tarikh_penerbangan_pergi) > strtotime($tarikh_penerbangan_balik)) {
        $_SESSION['error'] = "Tarikh penerbangan pergi tidak boleh lebih lewat daripada tarikh penerbangan balik";
        header("Location: ../role/pemohon/borangWA3.php");
        exit();
    }

    // Validate airports
    if ($start_point === $end_point) {
        $_SESSION['error'] = "Lapangan terbang berlepas dan tiba tidak boleh sama";
        header("Location: ../role/pemohon/borangWA3.php");
        exit();
    }

    // Store in session for next step
    $_SESSION['flight_details'] = [
        'tarikh_penerbangan_pergi' => $tarikh_penerbangan_pergi,
        'tarikh_penerbangan_balik' => $tarikh_penerbangan_balik,
        'start_point' => $start_point,
        'end_point' => $end_point
    ];

    // Redirect to confirmation page
    header("Location: ../role/pemohon/borangWA4.php");
    exit();
} else {
    // If not POST request, redirect back to form
    header("Location: ../role/pemohon/borangWA3.php");
    exit();
}
?> 