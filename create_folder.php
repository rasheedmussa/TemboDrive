<?php
// create_folder.php - Create new folder
require_once 'config.php';

if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$user = getUser();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $folderName = trim($_POST['folderName'] ?? '');
    $currentDir = trim($_POST['currentDir'] ?? '', '/');
    
    $response = ['success' => false, 'message' => ''];
    
    try {
        if (empty($folderName)) {
            throw new Exception('Folder name is required');
        }
        
        // Validate folder name
        if (!preg_match('/^[a-zA-Z0-9_\-\s]+$/', $folderName)) {
            throw new Exception('Invalid folder name');
        }
        
        // Check if folder already exists
        $folderPath = 'uploads/' . ($currentDir ? $currentDir . '/' : '') . $folderName;
        if (is_dir($folderPath)) {
            throw new Exception('Folder already exists');
        }
        
        // Create physical directory
        if (!mkdir($folderPath, 0755, true)) {
            throw new Exception('Failed to create directory');
        }
        
        $response['success'] = true;
        $response['message'] = 'Folder created successfully';
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    
    echo json_encode($response);
}
?>