<?php
session_start();
include_once 'includes/config.php';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to user dashboard or home page
    header("Location: role/pemohon/dashboard.php");
    exit();
} else {
    // Redirect to login page if not logged in
    header("Location: loginUser.php");
    exit();
}
?> 