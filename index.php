<?php
// index.php - Main entry point
require_once 'config.php';

try {
    $db = getDB();
    // If database works, check for login
    if (isLoggedIn()) {
        header('Location: dashboard.php');
        exit;
    } else {
        header('Location: login.php');
        exit;
    }
} catch (Exception $e) {
    // If database fails, go directly to dashboard (no authentication)
    header('Location: dashboard.php');
    exit;
}
?>