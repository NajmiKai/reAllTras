<?php
session_start();
include 'connection.php';
include 'includes/system_logger.php';

// Log the logout event if user was logged in
if (isset($_SESSION['user_kp'])) {
    logAuthEvent($conn, 'logout', 'user', $_SESSION['user_kp'], true);
}

// Clear all session variables
$_SESSION = [];

// Destroy the session cookie (optional but recommended)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: loginUser.php");
exit();
