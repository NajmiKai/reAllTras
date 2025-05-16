<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doc_id'])) {
    $doc_id = $_POST['doc_id'];
    
    // Get document information
    $stmt = $conn->prepare("SELECT file_path FROM documents WHERE id = ?");
    $stmt->bind_param("i", $doc_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $document = $result->fetch_assoc();
        $file_path = $document['file_path'];
        
        // Delete from database first
        $stmt = $conn->prepare("DELETE FROM documents WHERE id = ?");
        $stmt->bind_param("i", $doc_id);
        
        if ($stmt->execute()) {
            // Delete physical file
            $full_path = __DIR__ . '/' . $file_path;
            if (file_exists($full_path)) {
                unlink($full_path);
            }
            
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete document from database']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Document not found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?> 