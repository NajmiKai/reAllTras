<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Function to sanitize file names
function sanitizeFileName($fileName) {
    // Remove any directory components
    $fileName = basename($fileName);
    // Replace spaces with underscores
    $fileName = str_replace(' ', '_', $fileName);
    // Remove any non-alphanumeric characters except dots and underscores
    $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '', $fileName);
    return $fileName;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wilayah_asal_id = $_POST['wilayah_asal_id'] ?? null;
    $description = $_POST['description'] ?? '';
    
    if (!$wilayah_asal_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid application ID']);
        exit();
    }

    // Create uploads directory if it doesn't exist
    $uploadDir = '../uploads/' . $wilayah_asal_id . '/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $response = ['success' => false, 'files' => []];

    // Check if files were uploaded
    if (isset($_FILES['documents'])) {
        $files = $_FILES['documents'];
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
        $maxFileSize = 10 * 1024 * 1024; // 10MB

        // Loop through each file
        for ($i = 0; $i < count($files['name']); $i++) {
            $fileName = sanitizeFileName($files['name'][$i]);
            $fileType = $files['type'][$i];
            $fileTmpName = $files['tmp_name'][$i];
            $fileSize = $files['size'][$i];
            $fileError = $files['error'][$i];

            // Validate file
            if ($fileError === 0) {
                if (in_array($fileType, $allowedTypes)) {
                    if ($fileSize <= $maxFileSize) {
                        // Generate unique filename
                        $uniqueFileName = uniqid() . '_' . $fileName;
                        $filePath = $uploadDir . $uniqueFileName;

                        // Move file to upload directory
                        if (move_uploaded_file($fileTmpName, $filePath)) {
                            // Insert file information into database
                            $stmt = $conn->prepare("INSERT INTO documents (wilayah_asal_id, file_name, file_path, file_type, file_size, description, file_uploader_origin) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $relativePath = 'uploads/' . $wilayah_asal_id . '/' . $uniqueFileName;
                            $uploader_kp = $_SESSION['admin_icNo'];
                            $stmt->bind_param("isssiss", $wilayah_asal_id, $fileName, $relativePath, $fileType, $fileSize, $description, $uploader_kp);
                            
                            if ($stmt->execute()) {
                                $response['files'][] = [
                                    'name' => $fileName,
                                    'path' => $relativePath,
                                    'type' => $fileType,
                                    'size' => $fileSize
                                ];
                                $response['success'] = true;
                            } else {
                                $response['files'][] = [
                                    'name' => $fileName,
                                    'error' => 'Database error'
                                ];
                            }
                            $stmt->close();
                        } else {
                            $response['files'][] = [
                                'name' => $fileName,
                                'error' => 'Failed to move uploaded file'
                            ];
                        }
                    } else {
                        $response['files'][] = [
                            'name' => $fileName,
                            'error' => 'File size exceeds limit'
                        ];
                    }
                } else {
                    $response['files'][] = [
                        'name' => $fileName,
                        'error' => 'Invalid file type'
                    ];
                }
            } else {
                $response['files'][] = [
                    'name' => $fileName,
                    'error' => 'Upload error'
                ];
            }
        }
    }

    echo json_encode($response);
    exit();
}
?> 