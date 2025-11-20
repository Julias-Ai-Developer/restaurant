<?php
// Start session and set error reporting
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database configuration
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: 'root';
$DB_NAME = getenv('DB_NAME') ?: 'restaurant_db';

// Create connection (procedural mysqli)
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    die('Database connection failed');
}

// Helper: sanitize text output
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Helper: validate uploaded image
function validate_image_upload($file, $maxSize = 2097152) { // 2MB
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return 'No image uploaded or upload error.';
    }
    if ($file['size'] > $maxSize) {
        return 'Image too large. Max 2MB.';
    }
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!isset($allowed[$mime])) {
        return 'Invalid image type. Use JPG, PNG, or WEBP.';
    }
    return true;
}

// CSRF token helpers
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
function csrf_input() {
    echo '<input type="hidden" name="csrf_token" value="' . e($_SESSION['csrf_token']) . '">';
}
function csrf_validate() {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }
}
?>