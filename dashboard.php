<?php
// dashboard.php
require_once 'config.php';

$currentDir = isset($_GET['dir']) ? trim($_GET['dir'], '/') : '';
$files = getFilesFromDirectory($currentDir);

usort($files, function($a, $b) {
    if ($a['is_folder'] != $b['is_folder']) return $b['is_folder'] - $a['is_folder'];
    return strcmp($a['name'], $b['name']);
});

$totalSize = 0;
foreach ($files as $f) $totalSize += $f['size'];

$breadcrumbs = [];
if ($currentDir) {
    $parts = explode('/', $currentDir);
    $path = '';
    foreach ($parts as $part) {
        $path .= ($path ? '/' : '') . $part;
        $breadcrumbs[] = ['name' => $part, 'path' => $path];
    }
}

// Helper: editable extensions
$editableExts = ['txt','php','js','css','html','htm','json','xml','md','py','sql','csv','ini','conf','log','sh','bat'];
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TemboDrive</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="dashboard">

    <!-- ══ NAVBAR ══ -->
    <nav class="navbar">
        <a href="dashboard.php" class="nav-brand">
            <div class="nav-logo">🐘</div>
            <div>
                <div class="nav-name">TemboDrive</div>
            </div>
        </a>

        <div class="nav-center">
            <div class="breadcrumb">
                <a href="dashboard.php">~</a>
                <?php foreach ($breadcrumbs as $i => $crumb): ?>
                    <span class="sep">/</span>
                    <?php if ($i === count($breadcrumbs) - 1): ?>
                        <span class="crumb-current"><?php echo htmlspecialchars($crumb['name']); ?></span>
                    <?php else: ?>
                        <a href="dashboard.php?dir=<?php echo urlencode($crumb['path']); ?>"><?php echo htmlspecialchars($crumb['name']); ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="nav-actions">
            <button class="nav-pill pill-lang" onclick="toggleLang()">
                <span>🌐</span><span id="langLabel">SW</span>
            </button>
            <button class="nav-icon-btn" id="themeToggle" onclick="toggleTheme()" title="Toggle theme">
                <span id="themeIcon">☀️</span>
            </button>
        </div>
    </nav>

    <!-- ══ BODY ══ -->
    <div class="dashboard-body">

        <!-- ══ SIDEBAR ══ -->
        <aside class="sidebar">

            <!-- Upload -->
            <div class="panel">
                <div class="panel-title" data-i18n="uploadTitle">Upload Files</div>
                <div class="upload-zone" id="uploadArea">
                    <input type="file" id="fileInput" multiple>
                    <div class="upload-cloud">☁️</div>
                    <div class="upload-main-text" data-i18n="uploadMain">Drop files here</div>
                    <div class="upload-sub-text" data-i18n="uploadSub">or click to browse</div>
                </div>
                <label class="check-label">
                    <input type="checkbox" id="folderUpload">
                    <span data-i18n="uploadFolder">Upload Folder</span>
                </label>
                <div id="progressContainer" style="display:none" class="progress-wrap">
                    <div class="progress-track">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                    <div class="progress-label" id="progressText">0%</div>
                </div>
            </div>

            <!-- Actions -->
            <div class="panel">
                <div class="panel-title" data-i18n="actionsTitle">Actions</div>
                <div class="action-grid">
                    <button class="action-tile action-tile--primary" onclick="createNewFolder()">
                        <span class="action-tile-icon">📁</span>
                        <span class="action-tile-label" data-i18n="newFolder">New Folder</span>
                    </button>
                    <button class="action-tile" onclick="document.getElementById('fileInput').click()">
                        <span class="action-tile-icon">⬆️</span>
                        <span class="action-tile-label" data-i18n="uploadBtn">Upload</span>
                    </button>
                    <button class="action-tile" onclick="toggleSelectAll(null)">
                        <span class="action-tile-icon">☑️</span>
                        <span class="action-tile-label" data-i18n="selectAllBtn">Select All</span>
                    </button>
                    <button class="action-tile" onclick="deselectAll()">
                        <span class="action-tile-icon">✕</span>
                        <span class="action-tile-label" data-i18n="deselectBtn">Deselect</span>
                    </button>
                    <?php if ($currentDir): ?>
                    <button class="action-tile" onclick="window.location.href='dashboard.php<?php echo count($breadcrumbs) > 1 ? '?dir=' . urlencode(implode('/', array_slice(array_column($breadcrumbs, 'path'), 0, -1))) : ''; ?>'">
                        <span class="action-tile-icon">↩️</span>
                        <span class="action-tile-label" data-i18n="goUp">Go Up</span>
                    </button>
                    <?php endif; ?>
                    <button class="action-tile" onclick="refreshFiles()">
                        <span class="action-tile-icon">🔄</span>
                        <span class="action-tile-label" data-i18n="refresh">Refresh</span>
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="panel">
                <div class="panel-title" data-i18n="storageTitle">Storage Info</div>
                <div class="stat-row">
                    <span class="stat-label" data-i18n="totalFiles">Total Files</span>
                    <span class="stat-val"><?php echo count($files); ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label" data-i18n="totalSize">Total Size</span>
                    <span class="stat-val"><?php echo formatFileSize($totalSize); ?></span>
                </div>
                <div class="stat-row">
                    <span class="stat-label" data-i18n="currentDir">Directory</span>
                    <span class="stat-val">/<?php echo htmlspecialchars($currentDir ?: 'root'); ?></span>
                </div>
            </div>

        </aside>

        <!-- ══ MAIN ══ -->
        <main class="main-content">

            <!-- Toolbar -->
            <div class="toolbar">
                <div class="search-wrap">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput"
                           data-i18n-placeholder="searchPlaceholder"
                           placeholder="Search files...">
                </div>
                <div class="sort-wrap">
                    <select id="sortSelect">
                        <option value="name" data-i18n="sortName">Sort by Name</option>
                        <option value="date" data-i18n="sortDate">Sort by Date</option>
                        <option value="size" data-i18n="sortSize">Sort by Size</option>
                    </select>
                </div>
                <!-- View toggle -->
                <div class="view-toggle">
                    <button class="view-btn active" id="viewList" onclick="setView('list')" title="List view">☰</button>
                    <button class="view-btn" id="viewGrid" onclick="setView('grid')" title="Grid view">⊞</button>
                </div>
            </div>

            <!-- Bulk Action Bar (hidden until selection) -->
            <div class="bulk-bar" id="bulkBar" style="display:none">
                <span class="bulk-count" id="bulkCount">0 selected</span>
                <div class="bulk-actions">
                    <button class="btn btn-outline btn-sm" onclick="bulkMove()" data-i18n="bulkMove">↗️ Move</button>
                    <button class="btn btn-outline btn-sm" onclick="bulkCopy()" data-i18n="bulkCopy">📋 Copy</button>
                    <button class="btn btn-outline btn-sm" onclick="bulkDownloadZip()" data-i18n="bulkZip">🗜️ Download ZIP</button>
                    <button class="btn btn-danger btn-sm" onclick="bulkDelete()" data-i18n="bulkDelete">🗑️ Delete</button>
                    <button class="btn btn-outline btn-sm" onclick="deselectAll()" data-i18n="deselectAll">✕ Deselect</button>
                </div>
            </div>

            <!-- File Panel -->
            <div class="file-panel" id="filePanel">
                <?php if (empty($files)): ?>
                    <div class="empty-state">
                        <div class="empty-icon">📂</div>
                        <p data-i18n="noFiles">No files in this directory</p>
                    </div>
                <?php else: ?>
                    <div class="file-header">
                        <div class="col-check">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)" title="Select all">
                        </div>
                        <div data-i18n="colName">Name</div>
                        <div data-i18n="colType">Type</div>
                        <div data-i18n="colSize">Size</div>
                        <div data-i18n="colDate">Modified</div>
                        <div data-i18n="colActions">Actions</div>
                    </div>
                    <div class="file-list" id="fileList">
                        <?php foreach ($files as $index => $file):
                            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                            $isEditable = in_array($ext, $editableExts);
                            $isZip = ($ext === 'zip');
                            $isVideo = in_array($ext, ['mp4','webm','avi','mov','ogg']);
                            $isAudio = in_array($ext, ['mp3','wav','ogg','aac','flac']);
                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg']);
                            $isPdf   = ($ext === 'pdf');
                            $isPreviewable = $isImage || $isPdf || $isVideo || $isAudio || $isEditable;
                            $thumbUrl = ($isImage) ? 'preview.php?path=' . urlencode($file['path']) : '';
                        ?>
                        <div class="file-item"
                             data-index="<?php echo $index; ?>"
                             data-name="<?php echo htmlspecialchars($file['name']); ?>"
                             data-folder="<?php echo (int)$file['is_folder']; ?>"
                             data-path="<?php echo htmlspecialchars($file['path']); ?>"
                             data-size="<?php echo $file['size']; ?>"
                             data-date="<?php echo $file['modified']; ?>"
                             data-ext="<?php echo htmlspecialchars($ext); ?>"
                             data-thumb="<?php echo htmlspecialchars($thumbUrl); ?>">

                            <!-- Checkbox -->
                            <div class="col-check">
                                <input type="checkbox" class="file-check"
                                       onchange="toggleSelect(this, '<?php echo addslashes($file['path']); ?>')">
                            </div>

                            <!-- Name -->
                            <div class="file-name-cell">
                                <span class="file-icon"><?php echo getFileIcon($file['name'], $file['is_folder']); ?></span>
                                <?php if ($file['is_folder']): ?>
                                    <a href="dashboard.php?dir=<?php echo urlencode($currentDir . ($currentDir ? '/' : '') . $file['name']); ?>" class="folder-link">
                                        <?php echo htmlspecialchars($file['name']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="file-name-text"><?php echo htmlspecialchars($file['name']); ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Type -->
                            <div class="file-type-cell">
                                <?php echo $file['is_folder'] ? '<span data-i18n="typeFolder">Folder</span>' : htmlspecialchars($file['type']); ?>
                            </div>

                            <!-- Size -->
                            <div class="file-size-cell">
                                <?php echo $file['is_folder'] ? '—' : formatFileSize($file['size']); ?>
                            </div>

                            <!-- Date -->
                            <div class="file-date-cell">
                                <?php echo date('d M Y, H:i', $file['modified']); ?>
                            </div>

                            <!-- Actions -->
                            <div class="file-actions-cell">
                                <?php if ($file['is_folder']): ?>
                                    <button class="icon-btn" title="Create ZIP"
                                        onclick="createZip('<?php echo addslashes($file['path']); ?>')">🗜️</button>
                                <?php else: ?>
                                    <?php if ($isEditable): ?>
                                        <button class="icon-btn" title="Edit"
                                            onclick="editFile('<?php echo addslashes($file['path']); ?>')">✍️</button>
                                    <?php elseif ($isPreviewable): ?>
                                        <button class="icon-btn" title="Preview"
                                            onclick="previewFile('<?php echo addslashes($file['path']); ?>')">👁️</button>
                                    <?php endif; ?>
                                    <?php if ($isZip): ?>
                                        <button class="icon-btn" title="Extract ZIP"
                                            onclick="extractZip('<?php echo addslashes($file['path']); ?>')">📦</button>
                                    <?php endif; ?>
                                    <button class="icon-btn" title="Download"
                                        onclick="downloadFile('<?php echo addslashes($file['path']); ?>')">⬇️</button>
                                <?php endif; ?>
                                <button class="icon-btn" title="Share"
                                    onclick="shareFile('<?php echo addslashes($file['path']); ?>', '<?php echo addslashes($file['name']); ?>')">🔗</button>
                                <button class="icon-btn" title="Rename"
                                    onclick="renameFile(<?php echo $index; ?>, '<?php echo addslashes($file['name']); ?>', '<?php echo addslashes($file['path']); ?>')">✏️</button>
                                <button class="icon-btn del" title="Delete"
                                    onclick="deleteFile('<?php echo addslashes($file['path']); ?>')">🗑️</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </main>
    </div>
</div>

<!-- ══ TOAST ══ -->
<div class="toast-container" id="toastContainer"></div>

<!-- ══ RENAME MODAL ══ -->
<div id="renameModal" class="modal">
    <div class="modal-box">
        <div class="modal-head">
            <span class="modal-title" data-i18n="renameTitle">Rename</span>
            <button class="modal-close" onclick="closeModal('renameModal')">✕</button>
        </div>
        <input type="text" id="renameInput" class="modal-input" data-i18n-placeholder="renamePlaceholder" placeholder="New name...">
        <button class="btn btn-primary btn-block" onclick="confirmRename()" data-i18n="renameBtn">Rename</button>
    </div>
</div>

<!-- ══ NEW FOLDER MODAL ══ -->
<div id="createFolderModal" class="modal">
    <div class="modal-box">
        <div class="modal-head">
            <span class="modal-title" data-i18n="folderTitle">New Folder</span>
            <button class="modal-close" onclick="closeModal('createFolderModal')">✕</button>
        </div>
        <input type="text" id="folderNameInput" class="modal-input" data-i18n-placeholder="folderPlaceholder" placeholder="Folder name...">
        <button class="btn btn-primary btn-block" onclick="confirmCreateFolder()" data-i18n="folderBtn">Create</button>
    </div>
</div>

<!-- ══ PREVIEW MODAL ══ -->
<div id="previewModal" class="modal">
    <div class="modal-box modal-wide">
        <div class="modal-head">
            <span class="modal-title" data-i18n="previewTitle">Preview</span>
            <button class="modal-close" onclick="closeModal('previewModal')">✕</button>
        </div>
        <div id="previewContent"></div>
    </div>
</div>

<!-- ══ EDITOR MODAL ══ -->
<div id="editorModal" class="modal">
    <div class="modal-box modal-wide">
        <div class="modal-head">
            <span class="modal-title" id="editorFileName" data-i18n="editorTitle">Edit File</span>
            <div style="display:flex;align-items:center;gap:.5rem">
                <button class="btn btn-primary" onclick="saveEdit()" data-i18n="editorSave">Save</button>
                <button class="modal-close" onclick="closeModal('editorModal')">✕</button>
            </div>
        </div>
        <textarea id="editorContent" class="editor-textarea" spellcheck="false"></textarea>
    </div>
</div>

<!-- ══ MOVE/COPY MODAL ══ -->
<div id="moveModal" class="modal">
    <div class="modal-box">
        <div class="modal-head">
            <span class="modal-title" id="moveCopyTitle">Move To</span>
            <button class="modal-close" onclick="closeModal('moveModal')">✕</button>
        </div>
        <div class="folder-tree" id="folderTree">
            <div class="folder-option selected" data-path="" onclick="selectDestFolder(this)">
                📁 / (root)
            </div>
        </div>
        <button class="btn btn-primary btn-block" id="moveCopyBtn" onclick="confirmMoveCopy()" style="margin-top:1rem" data-i18n="moveBtn">Move Here</button>
    </div>
</div>

<!-- ══ SHARE MODAL ══ -->
<div id="shareModal" class="modal">
    <div class="modal-box">
        <div class="modal-head">
            <span class="modal-title" data-i18n="shareTitle">Share File</span>
            <button class="modal-close" onclick="closeModal('shareModal')">✕</button>
        </div>
        <p id="shareFileName" style="font-size:.85rem;color:var(--text-300);margin-bottom:1rem"></p>
        <div id="shareLinkWrap" style="display:none">
            <label style="font-size:.75rem;color:var(--text-400);margin-bottom:.4rem;display:block" data-i18n="shareLinkLabel">Share Link</label>
            <div style="display:flex;gap:.5rem;margin-bottom:0">
                <input type="text" id="shareLinkInput" class="modal-input" readonly style="margin-bottom:0;flex:1;font-size:.78rem">
                <button class="btn btn-outline" onclick="copyShareLink()" data-i18n="shareCopy">Copy</button>
            </div>
        </div>
        <button class="btn btn-primary btn-block" id="shareGenerateBtn" onclick="generateShareLink()" data-i18n="shareGenerate" style="margin-top:1rem">Generate Link</button>
    </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
