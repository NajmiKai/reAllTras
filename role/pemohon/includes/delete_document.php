<?php
session_start();
include_once '../../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doc_id = intval($_POST['doc_id'] ?? 0);

    // Get file path from DB
    $stmt = $conn->prepare("SELECT file_path FROM documents WHERE id = ?");
    $stmt->bind_param("i", $doc_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $doc = $result->fetch_assoc();

    if ($doc) {
        $file_path = $doc['file_path'];
        // Delete file from server
        $full_path = realpath('../../../' . $file_path);
        if ($full_path && file_exists($full_path)) {
            unlink($full_path);
        }

        // Delete from DB
        $del_stmt = $conn->prepare("DELETE FROM documents WHERE id = ?");
        $del_stmt->bind_param("i", $doc_id);
        $del_stmt->execute();

        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Document not found']);
exit; 