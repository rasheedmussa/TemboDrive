<?php
// preview.php - Preview file
require_once 'config.php';

if (!isLoggedIn()) {
    http_response_code(403);
    exit('Not authenticated');
}

$user = getUser();

$filePath = trim($_GET['path'] ?? '');

if (empty($filePath)) {
    http_response_code(400);
    exit('Invalid file path');
}

// Security check - ensure file is within uploads directory
$realPath = realpath($filePath);
$uploadDir = realpath('uploads/');
if (!$realPath || strpos($realPath, $uploadDir) !== 0) {
    http_response_code(403);
    exit('Access denied');
}

if (!file_exists($filePath)) {
    http_response_code(404);
    exit('File not found');
}

$fileType = mime_content_type($filePath);
$filename = basename($filePath);

$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if (strpos($fileType, 'image/') === 0) {
    // Image preview
    header('Content-Type: ' . $fileType);
    readfile($filePath);
} elseif ($fileType === 'application/pdf') {
    // PDF preview
    header('Content-Type: application/pdf');
    readfile($filePath);
} elseif ($ext === 'txt') {
    // Text preview
    header('Content-Type: text/plain; charset=utf-8');
    readfile($filePath);
} else {
    // Generic preview - show file info
    echo "<div style='padding: 20px; font-family: Arial, sans-serif;'>";
    echo "<h3>File Preview</h3>";
    echo "<p><strong>Name:</strong> " . htmlspecialchars($filename) . "</p>";
    echo "<p><strong>Type:</strong> " . htmlspecialchars($fileType) . "</p>";
    echo "<p><strong>Size:</strong> " . formatFileSize(filesize($filePath)) . "</p>";
    echo "<p><strong>Modified:</strong> " . date('M d, Y H:i', filemtime($filePath)) . "</p>";
    echo "<p>This file type cannot be previewed directly. <a href='download.php?path=" . urlencode($filePath) . "'>Download</a> to view.</p>";
    echo "</div>";
}
?>