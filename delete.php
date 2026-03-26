<?php
// delete.php - Delete file or folder
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
    
    $response = ['success' => false, 'message' => ''];
    
    try {
        if (empty($filePath)) {
            throw new Exception('Invalid file path');
        }
        
        // Security check - ensure file is within uploads directory
        $realPath = realpath($filePath);
        $uploadDir = realpath('uploads/');
        if (!$realPath || strpos($realPath, $uploadDir) !== 0) {
            throw new Exception('Access denied');
        }
        
        // Delete from filesystem
        if (is_dir($filePath)) {
            // Delete folder and all contents
            function deleteDirectory($dir) {
                if (!file_exists($dir)) return true;
                if (!is_dir($dir)) return unlink($dir);
                
                foreach (scandir($dir) as $item) {
                    if ($item == '.' || $item == '..') continue;
                    if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
                }
                return rmdir($dir);
            }
            
            if (!deleteDirectory($filePath)) {
                throw new Exception('Failed to delete folder');
            }
        } else {
            if (!unlink($filePath)) {
                throw new Exception('Failed to delete file');
            }
        }
        
        $response['success'] = true;
        $response['message'] = 'Deleted successfully';
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    
    echo json_encode($response);
}
?>