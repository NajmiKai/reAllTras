<?php
include_once __DIR__ . '/../connection.php';

// Base path configuration
// define('BASE_PATH', 'http://localhost/reAllTras');
// define('BASE_PATH', 'http://10.13.100.141/reAllTras');
define('BASE_PATH', 'http://alltras.customs.gov.my');


// Upload path configuration
define('UPLOAD_PATH', 'uploads');



// Function to get the full URL for a given path
function getFullUrl($path = '') {
    return rtrim(BASE_PATH, '/') . '/' . ltrim($path, '/');
}

// Function to get the base path
function getBasePath() {
    return BASE_PATH;
}

// Function to get the full upload path
function getUploadPath($subPath = '') {
    return rtrim(BASE_PATH, '/') . '/' . UPLOAD_PATH . '/' . ltrim($subPath, '/');
}

?>