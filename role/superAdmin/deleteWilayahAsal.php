<?php
// deleteWilayahAsal.php
session_start();
include '../../connection.php';

// Validate and sanitize the ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Execute delete query
    $stmt = $conn->prepare("DELETE FROM wilayah_asal WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back with success message
        header("Location: listWilayahAsal.php?delete=success");
        exit();
    } else {
        echo "Gagal padam rekod.";
    }
} else {
    echo "ID tidak sah.";
}
?>
