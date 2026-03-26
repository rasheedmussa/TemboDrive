<?php
// download.php - Download file
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

// Set headers for download
$filename = basename($filePath);
$fileType = mime_content_type($filePath);
header('Content-Type: ' . $fileType);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Output file
readfile($filePath);
exit;
?>