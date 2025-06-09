<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if file was uploaded
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['document'];
        $description = $_POST['description'];
        
        // Create upload directory if it doesn't exist
        $upload_dir = "../../../uploads/documents/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate unique filename
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
        $file_path = $upload_dir . $new_filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Insert document record into database
            $insert_sql = "INSERT INTO documents (
                wilayah_asal_id,
                file_name,
                file_path,
                description,
                upload_date
            ) VALUES (?, ?, ?, ?, NOW())";

            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("isss", 
                $wilayah_asal_data['id'],
                $file['name'],
                $file_path,
                $description
            );

            if ($stmt->execute()) {
                $_SESSION['success'] = "Dokumen berjaya dimuat naik.";
                header("Location: wilayahAsal.php");
                exit();
            } else {
                $_SESSION['error'] = "Ralat: " . $stmt->error;
                // Delete uploaded file if database insert fails
                unlink($file_path);
            }
        } else {
            $_SESSION['error'] = "Ralat semasa memuat naik fail.";
        }
    } else {
        $_SESSION['error'] = "Sila pilih fail untuk dimuat naik.";
    }
}
?> 