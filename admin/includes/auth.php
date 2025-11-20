<?php
// Include database config
require_once __DIR__ . '/../../config.php';

// If admin is already logged in and lands on a login page, send them to the dashboard
$script = basename($_SERVER['PHP_SELF']);
$loginFiles = ['login.php', 'index.php', 'signin.php']; // adjust names to your login filenames
if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true && in_array($script, $loginFiles, true)) {
    header('Location: ../main/dashboard.php');
    exit;
}