<?php
// Enable error logging but disable display
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../../logs/php-error.log');

// Set response type
header('Content-Type: application/json');

// Utility function
function sendJson($success, $message, $code = 200) {
    http_response_code($code);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

// Error handler function
function handleError($errno, $errstr, $errfile, $errline) {
    error_log("Error [$errno] $errstr on line $errline in file $errfile");
    sendJson(false, 'An error occurred while processing your request', 500);
}

// Set custom error handler
set_error_handler('handleError');

session_start();

// Check login
if (!isset($_SESSION['super_admin_id'])) {
    sendJson(false, 'Unauthorized', 401);
}

// Include database connection using absolute path
require_once __DIR__ . '/../../../connection.php';

// Verify database connection
if (!isset($conn) || $conn === null) {
    error_log("Database connection failed in deleteUser.php");
    sendJson(false, 'Database connection failed', 500);
}

// Check for POST data
if (!isset($_POST['id'])) {
    sendJson(false, 'User ID is required', 400);
}

$user_id = intval($_POST['id']);
if ($user_id <= 0) {
    sendJson(false, 'Invalid user ID', 400);
}

try {
    error_log("Starting deletion process for user ID: " . $user_id);
    
    // Start transaction
    if (!$conn->begin_transaction()) {
        throw new Exception("Failed to start transaction: " . $conn->error);
    }
    error_log("Transaction started successfully");

    // Temporarily disable foreign key checks
    if (!$conn->query("SET FOREIGN_KEY_CHECKS = 0")) {
        throw new Exception("Failed to disable foreign key checks: " . $conn->error);
    }
    error_log("Foreign key checks disabled");

    // First, get the user's KP number for logging
    $get_user_sql = "SELECT kp FROM user WHERE id = ?";
    $get_user_stmt = $conn->prepare($get_user_sql);
    if (!$get_user_stmt) {
        throw new Exception("Failed to prepare get user statement: " . $conn->error);
    }
    
    $get_user_stmt->bind_param("i", $user_id);
    if (!$get_user_stmt->execute()) {
        throw new Exception("Failed to execute get user statement: " . $get_user_stmt->error);
    }
    
    $user_result = $get_user_stmt->get_result();
    if ($user_result->num_rows === 0) {
        error_log("User not found with ID: " . $user_id);
        // Re-enable foreign key checks before rolling back
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");
        $conn->rollback();
        sendJson(false, 'Pengguna tidak dijumpai', 404);
    }
    
    $user_data = $user_result->fetch_assoc();
    $user_kp = $user_data['kp'];
    error_log("Found user with KP: " . $user_kp);

    // Delete all related records
    $tables_to_delete = [
        'documents' => [
            "DELETE FROM documents WHERE file_origin_id = ?",
            "s"
        ],
        'wilayah_asal' => [
            "DELETE FROM wilayah_asal WHERE user_kp = ?",
            "s"
        ],
        'user' => [
            "DELETE FROM user WHERE id = ?",
            "i"
        ]
    ];

    foreach ($tables_to_delete as $table => $query_info) {
        $sql = $query_info[0];
        $types = $query_info[1];
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare delete statement for $table: " . $conn->error);
        }

        // Bind parameters based on the query type
         if ($table === 'user') {
            $stmt->bind_param($types, $user_id);
        } else {
            $stmt->bind_param($types, $user_kp);
        }

        if (!$stmt->execute()) {
            throw new Exception("Failed to execute delete statement for $table: " . $stmt->error);
        }
        
        $affected_rows = $stmt->affected_rows;
        error_log("Deleted $affected_rows rows from $table");
        
        $stmt->close();
    }

    // Log the deletion
    if (function_exists('logSystemEvent')) {
        logSystemEvent(
            $conn,
            'data_delete',
            'superAdmin',
            $_SESSION['super_admin_id'],
            'Delete User',
            'User deleted by super admin',
            'user',
            $user_id,
            $user_kp,
            null
        );
        error_log("System event logged for user deletion");
    }

    // Re-enable foreign key checks
    if (!$conn->query("SET FOREIGN_KEY_CHECKS = 1")) {
        throw new Exception("Failed to re-enable foreign key checks: " . $conn->error);
    }
    error_log("Foreign key checks re-enabled");
    
    // Commit the transaction
    if (!$conn->commit()) {
        throw new Exception("Failed to commit transaction: " . $conn->error);
    }
    error_log("Transaction committed successfully");
    
    // Verify the deletion
    $verify_sql = "SELECT COUNT(*) as count FROM user WHERE id = ?";
    $verify_stmt = $conn->prepare($verify_sql);
    $verify_stmt->bind_param("i", $user_id);
    $verify_stmt->execute();
    $verify_result = $verify_stmt->get_result();
    $verify_data = $verify_result->fetch_assoc();
    
    if ($verify_data['count'] > 0) {
        error_log("WARNING: User still exists after deletion! User ID: " . $user_id);
        throw new Exception("User deletion verification failed");
    }
    error_log("User deletion verified successfully");
    
    sendJson(true, 'Pengguna berjaya dipadamkan');

} catch (Exception $e) {
    error_log("Error in deleteUser.php: " . $e->getMessage());
    // Make sure to re-enable foreign key checks even if there's an error
    if (isset($conn)) {
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");
        $conn->rollback();
        error_log("Transaction rolled back due to error");
    }
    sendJson(false, 'Gagal memadamkan pengguna: ' . $e->getMessage(), 500);
} finally {
    // Close all statements
    if (isset($get_user_stmt)) $get_user_stmt->close();
    if (isset($verify_stmt)) $verify_stmt->close();
    
    // Close connection
    if (isset($conn)) {
        $conn->close();
        error_log("Database connection closed");
    }
}
?>
