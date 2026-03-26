<?php
// config.php - Database configuration and common functions

session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Change this to your DB user
define('DB_PASS', ''); // Change this to your DB password
define('DB_NAME', 'filemanager');

// Connect to database
function getDB() {
    static $db = null;
    if ($db === null) {
        try {
            $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $db;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get user info
function getUser() {
    if (!isLoggedIn()) return null;
    static $user = null;
    if ($user === null) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return $user;
}

// Sanitize filename
function sanitizeFilename($filename) {
    return preg_replace('/[^a-zA-Z0-9\._-]/', '_', $filename);
}

// Format file size
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

// Get file icon based on type
function getFileIcon($filename, $isFolder = false) {
    if ($isFolder) return '📁';
    
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $icons = [
        'pdf' => '📄',
        'doc' => '📝', 'docx' => '📝',
        'xls' => '📊', 'xlsx' => '📊',
        'ppt' => '📽️', 'pptx' => '📽️',
        'txt' => '📄',
        'jpg' => '🖼️', 'jpeg' => '🖼️', 'png' => '🖼️', 'gif' => '🖼️',
        'mp4' => '🎥', 'avi' => '🎥', 'mov' => '🎥',
        'mp3' => '🎵', 'wav' => '🎵',
        'zip' => '📦', 'rar' => '📦', '7z' => '📦'
    ];
    return $icons[$ext] ?? '📄';
}
?>