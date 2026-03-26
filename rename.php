<?php
// rename.php - Rename file or folder
require_once 'config.php';

if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$user = getUser();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filePath = trim($_POST['filePath'] ?? '');
    $newName = trim($_POST['newName'] ?? '');
    
    $response = ['success' => false, 'message' => ''];
    
    try {
        if (empty($filePath) || empty($newName)) {
            throw new Exception('Invalid parameters');
        }
        
        // Validate new name
        if (!preg_match('/^[a-zA-Z0-9_\-\s\.]+$/', $newName)) {
            throw new Exception('Invalid name');
        }
        
        // Security check - ensure file is within uploads directory
        $realPath = realpath($filePath);
        $uploadDir = realpath('uploads/');
        if (!$realPath || strpos($realPath, $uploadDir) !== 0) {
            throw new Exception('Access denied');
        }
        
        // Generate new path
        $pathParts = explode('/', $filePath);
        $pathParts[count($pathParts) - 1] = $newName;
        $newPath = implode('/', $pathParts);
        
        // Check if new name already exists
        if (file_exists($newPath)) {
            throw new Exception('Name already exists');
        }
        
        // Rename file/folder
        if (!rename($filePath, $newPath)) {
            throw new Exception('Failed to rename');
        }
        
        $response['success'] = true;
        $response['message'] = 'Renamed successfully';
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    
    echo json_encode($response);
}
?>