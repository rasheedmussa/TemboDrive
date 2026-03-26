<?php
// dashboard.php - Main file manager dashboard
require_once 'config.php';

// Check if authentication is enabled (database available)
$authEnabled = false;
try {
    $db = getDB();
    $authEnabled = true;
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
    $user = getUser();
} catch (Exception $e) {
    // No authentication - proceed without login
    $authEnabled = false;
}

// Get current directory from URL parameter
$currentDir = isset($_GET['dir']) ? trim($_GET['dir'], '/') : '';
$currentPath = $currentDir ? "/$currentDir" : '';

// Get files and folders from filesystem
$files = getFilesFromDirectory($currentDir);

// Sort files: folders first, then by name
usort($files, function($a, $b) {
    if ($a['is_folder'] != $b['is_folder']) {
        return $b['is_folder'] - $a['is_folder'];
    }
    return strcmp($a['name'], $b['name']);
});

// Calculate total size
$totalSize = 0;
foreach ($files as $file) {
    $totalSize += $file['size'];
}

// Breadcrumb navigation
$breadcrumbs = [];
if ($currentDir) {
    $parts = explode('/', $currentDir);
    $path = '';
    foreach ($parts as $part) {
        $path .= ($path ? '/' : '') . $part;
        $breadcrumbs[] = ['name' => $part, 'path' => $path];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <header class="dashboard-header">
            <div class="header-left">
                <h1>File Manager</h1>
                <div class="breadcrumb">
                    <a href="dashboard.php">Home</a>
                    <?php foreach ($breadcrumbs as $crumb): ?>
                        <span>/</span>
                        <a href="dashboard.php?dir=<?php echo urlencode($crumb['path']); ?>"><?php echo htmlspecialchars($crumb['name']); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="header-right">
                <?php if ($authEnabled): ?>
                    <span>Welcome, <?php echo htmlspecialchars($user['username']); ?></span>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                <?php else: ?>
                    <span>File Manager (No Authentication)</span>
                <?php endif; ?>
            </div>
        </header>

        <div class="dashboard-content">
            <div class="sidebar">
                <div class="upload-section">
                    <h3>Upload Files</h3>
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-placeholder">
                            <span>📁</span>
                            <p>Drag & drop files here or click to browse</p>
                            <input type="file" id="fileInput" multiple style="display: none;">
                            <button class="btn btn-primary" onclick="document.getElementById('fileInput').click()">Choose Files</button>
                        </div>
                    </div>
                    
                    <div class="upload-options">
                        <label>
                            <input type="checkbox" id="folderUpload"> Upload Folder
                        </label>
                    </div>
                    
                    <div class="progress-container" id="progressContainer" style="display: none;">
                        <div class="progress-bar" id="progressBar"></div>
                        <div class="progress-text" id="progressText">Uploading...</div>
                    </div>
                </div>

                <div class="actions-section">
                    <h3>Actions</h3>
                    <button class="btn btn-secondary" onclick="createNewFolder()">Create Folder</button>
                    <button class="btn btn-secondary" onclick="refreshFiles()">Refresh</button>
                </div>

                <div class="stats-section">
                    <h3>Storage Info</h3>
                    <p>Total Files: <?php echo count($files); ?></p>
                    <p>Total Size: <?php echo formatFileSize($totalSize); ?></p>
                </div>
            </div>

            <div class="main-content">
                <div class="toolbar">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search files...">
                    </div>
                    <div class="sort-options">
                        <select id="sortSelect">
                            <option value="name">Sort by Name</option>
                            <option value="date">Sort by Date</option>
                            <option value="size">Sort by Size</option>
                        </select>
                    </div>
                </div>

                <div class="file-list" id="fileList">
                    <?php if (empty($files)): ?>
                        <div class="empty-state">
                            <span>📂</span>
                            <p>No files in this directory</p>
                        </div>
                    <?php else: ?>
                        <div class="file-header">
                            <div class="file-name">Name</div>
                            <div class="file-type">Type</div>
                            <div class="file-size">Size</div>
                            <div class="file-date">Modified</div>
                            <div class="file-actions">Actions</div>
                        </div>
                        <?php foreach ($files as $index => $file): ?>
                            <div class="file-item" data-index="<?php echo $index; ?>" data-name="<?php echo htmlspecialchars($file['name']); ?>" data-folder="<?php echo $file['is_folder']; ?>" data-path="<?php echo htmlspecialchars($file['path']); ?>">
                                <div class="file-name">
                                    <span class="file-icon"><?php echo getFileIcon($file['name'], $file['is_folder']); ?></span>
                                    <?php if ($file['is_folder']): ?>
                                        <a href="dashboard.php?dir=<?php echo urlencode($currentDir . ($currentDir ? '/' : '') . $file['name']); ?>" class="folder-link">
                                            <?php echo htmlspecialchars($file['name']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span><?php echo htmlspecialchars($file['name']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="file-type"><?php echo $file['is_folder'] ? 'Folder' : htmlspecialchars($file['type']); ?></div>
                                <div class="file-size"><?php echo $file['is_folder'] ? '-' : formatFileSize($file['size']); ?></div>
                                <div class="file-date"><?php echo date('M d, Y H:i', $file['modified']); ?></div>
                                <div class="file-actions">
                                    <?php if (!$file['is_folder']): ?>
                                        <button onclick="previewFile('<?php echo htmlspecialchars($file['path']); ?>')" title="Preview">👁️</button>
                                        <button onclick="downloadFile('<?php echo htmlspecialchars($file['path']); ?>')" title="Download">⬇️</button>
                                    <?php endif; ?>
                                    <button onclick="renameFile(<?php echo $index; ?>, '<?php echo htmlspecialchars($file['name']); ?>', '<?php echo htmlspecialchars($file['path']); ?>')" title="Rename">✏️</button>
                                    <button onclick="deleteFile('<?php echo htmlspecialchars($file['path']); ?>')" title="Delete">🗑️</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div id="previewModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('previewModal')">&times;</span>
            <div id="previewContent"></div>
        </div>
    </div>

    <div id="renameModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('renameModal')">&times;</span>
            <h3>Rename File/Folder</h3>
            <input type="text" id="renameInput">
            <button onclick="confirmRename()">Rename</button>
        </div>
    </div>

    <div id="createFolderModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('createFolderModal')">&times;</span>
            <h3>Create New Folder</h3>
            <input type="text" id="folderNameInput" placeholder="Folder name">
            <button onclick="confirmCreateFolder()">Create</button>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>