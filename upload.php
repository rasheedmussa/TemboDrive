<?php
// upload.php - Handle file uploads via AJAX
require_once 'config.php';

if (!isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$user = getUser();
$uploadDir = 'uploads/';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    try {
        // Check if database is available (optional)
        $dbAvailable = false;
        try {
            $db = getDB();
            $dbAvailable = true;
        } catch (Exception $e) {
            $dbAvailable = false;
        }
        
        // Handle folder upload
        if (isset($_POST['folderUpload']) && $_POST['folderUpload'] === 'true') {
            $files = $_FILES['files'] ?? [];
            
            if (empty($files['name'])) {
                throw new Exception('No files uploaded');
            }
            
            $uploadedFiles = [];
            $createdFolders = [];
            
            // Process each file
            for ($i = 0; $i < count($files['name']); $i++) {
                $originalName = $files['name'][$i];
                $tmpName = $files['tmp_name'][$i];
                $webkitRelativePath = $_POST['webkitRelativePath'][$i] ?? '';
                
                if (empty($webkitRelativePath)) continue;
                
                // Create directory structure
                $relativePath = dirname($webkitRelativePath);
                if ($relativePath !== '.') {
                    $fullDirPath = $uploadDir . $relativePath;
                    if (!is_dir($fullDirPath)) {
                        mkdir($fullDirPath, 0755, true);
                        $createdFolders[] = $fullDirPath;
                    }
                }
                
                // Move file
                $filePath = $uploadDir . $webkitRelativePath;
                if (move_uploaded_file($tmpName, $filePath)) {
                    $uploadedFiles[] = $originalName;
                }
            }
            
            $response['success'] = true;
            $response['message'] = 'Folder uploaded successfully. Files: ' . count($uploadedFiles) . ', Folders: ' . count($createdFolders);
            
        } else {
            // Handle single/multiple file upload
            $files = $_FILES['files'] ?? [];
            
            if (empty($files['name'])) {
                throw new Exception('No files uploaded');
            }
            
            $uploadedFiles = [];
            
            // Handle both single file and multiple files
            if (is_array($files['name'])) {
                $fileCount = count($files['name']);
            } else {
                $fileCount = 1;
                $files = [
                    'name' => [$files['name']],
                    'tmp_name' => [$files['tmp_name']],
                    'error' => [$files['error']],
                    'size' => [$files['size']]
                ];
            }
            
            for ($i = 0; $i < $fileCount; $i++) {
                $originalName = $files['name'][$i];
                $tmpName = $files['tmp_name'][$i];
                $error = $files['error'][$i];
                $size = $files['size'][$i];
                
                // Validate file
                if ($error !== UPLOAD_ERR_OK) {
                    throw new Exception('Upload error: ' . $error);
                }
                
                // Check file size (max 1GB per file)
                if ($size > 1024 * 1024 * 1024) {
                    throw new Exception('File too large: ' . $originalName . ' (max 1GB)');
                }
                
                // Check file type (basic validation)
                $allowedTypes = [
                    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                    'application/pdf', 'text/plain', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'video/mp4', 'audio/mpeg', 'application/zip', 'application/x-rar-compressed'
                ];
                
                $fileType = mime_content_type($tmpName);
                if (!in_array($fileType, $allowedTypes)) {
                    // Allow unknown types but log warning
                    error_log("Unknown file type uploaded: $fileType for file $originalName");
                }
                
                // Sanitize filename
                $safeName = sanitizeFilename($originalName);
                
                // Generate unique filename to prevent overwrites
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $baseName = pathinfo($safeName, PATHINFO_FILENAME);
                $counter = 0;
                $finalName = $safeName;
                
                while (file_exists($uploadDir . $finalName)) {
                    $counter++;
                    $finalName = $baseName . '_' . $counter . '.' . $extension;
                }
                
                $filePath = $uploadDir . $finalName;
                
                // Move file
                if (move_uploaded_file($tmpName, $filePath)) {
                    $uploadedFiles[] = $originalName;
                } else {
                    throw new Exception('Failed to move uploaded file: ' . $originalName);
                }
            }
            
            $response['success'] = true;
            $response['message'] = count($uploadedFiles) . ' file(s) uploaded successfully';
        }
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    
    echo json_encode($response);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
?>