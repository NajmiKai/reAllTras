<?php
// Base path configuration
define('BASE_PATH', 'http://localhost/reAllTras');

// Function to get the full URL for a given path
function getFullUrl($path = '') {
    return rtrim(BASE_PATH, '/') . '/' . ltrim($path, '/');
}

// Function to get the base path
function getBasePath() {
    return BASE_PATH;
}
?> 