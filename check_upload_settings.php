<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>PHP Upload Settings:</h2>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
echo "file_uploads: " . ini_get('file_uploads') . "<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";

echo "<h2>Upload Directory:</h2>";
$upload_dir = "uploads/permohonan/";
echo "Directory exists: " . (file_exists($upload_dir) ? "Yes" : "No") . "<br>";
echo "Directory writable: " . (is_writable($upload_dir) ? "Yes" : "No") . "<br>";
echo "Full path: " . realpath($upload_dir) . "<br>";

echo "<h2>Session Data:</h2>";
echo "wilayah_asal_id: " . (isset($_SESSION['wilayah_asal_id']) ? $_SESSION['wilayah_asal_id'] : "Not set") . "<br>";
?> 