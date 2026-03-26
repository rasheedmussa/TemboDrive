// main.js - JavaScript for File Manager functionality

let currentDir = '';
let currentRenameId = null;
let currentRenameName = '';

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get current directory from URL
    const urlParams = new URLSearchParams(window.location.search);
    currentDir = urlParams.get('dir') || '';
    
    // Initialize upload functionality
    initUpload();
    
    // Initialize search and sort
    initSearchSort();
    
    // Initialize modals
    initModals();
});

// Initialize file upload
function initUpload() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const folderCheckbox = document.getElementById('folderUpload');
    
    // Drag and drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });
    
    uploadArea.addEventListener('drop', handleDrop, false);
    uploadArea.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', handleFileSelect);
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight() {
        uploadArea.classList.add('dragover');
    }
    
    function unhighlight() {
        uploadArea.classList.remove('dragover');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }
    
    function handleFileSelect(e) {
        const files = e.target.files;
        handleFiles(files);
    }
    
    function handleFiles(files) {
        if (folderCheckbox.checked) {
            // Folder upload
            uploadFolder(files);
        } else {
            // File upload
            uploadFiles(files);
        }
    }
}

// Upload multiple files
function uploadFiles(files) {
    const formData = new FormData();
    
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    
    uploadToServer(formData);
}

// Upload folder
function uploadFolder(files) {
    const formData = new FormData();
    formData.append('folderUpload', 'true');
    
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
        formData.append('webkitRelativePath[]', files[i].webkitRelativePath || files[i].name);
    }
    
    uploadToServer(formData);
}

// Upload to server with progress
function uploadToServer(formData) {
    const xhr = new XMLHttpRequest();
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.querySelector('.progress-bar');
    const progressText = document.getElementById('progressText');
    
    progressContainer.style.display = 'block';
    
    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            progressBar.style.setProperty('--progress', percentComplete + '%');
            progressText.textContent = `Uploading... ${Math.round(percentComplete)}%`;
        }
    });
    
    xhr.addEventListener('load', function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert(response.message);
                location.reload();
            } else {
                alert('Upload failed: ' + response.message);
            }
        } else {
            alert('Upload failed');
        }
        progressContainer.style.display = 'none';
    });
    
    xhr.addEventListener('error', function() {
        alert('Upload failed');
        progressContainer.style.display = 'none';
    });
    
    xhr.open('POST', 'upload.php');
    xhr.send(formData);
}

// Initialize search and sort
function initSearchSort() {
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    
    searchInput.addEventListener('input', filterFiles);
    sortSelect.addEventListener('change', sortFiles);
}

function filterFiles() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const fileItems = document.querySelectorAll('.file-item');
    
    fileItems.forEach(item => {
        const fileName = item.querySelector('.file-name').textContent.toLowerCase();
        if (fileName.includes(searchTerm)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

function sortFiles() {
    const sortBy = document.getElementById('sortSelect').value;
    const fileList = document.getElementById('fileList');
    const fileItems = Array.from(document.querySelectorAll('.file-item'));
    
    fileItems.sort((a, b) => {
        let aVal, bVal;
        
        switch (sortBy) {
            case 'name':
                aVal = a.querySelector('.file-name').textContent.toLowerCase();
                bVal = b.querySelector('.file-name').textContent.toLowerCase();
                return aVal.localeCompare(bVal);
            case 'date':
                aVal = new Date(a.dataset.date);
                bVal = new Date(b.dataset.date);
                return bVal - aVal;
            case 'size':
                aVal = parseInt(a.dataset.size) || 0;
                bVal = parseInt(b.dataset.size) || 0;
                return bVal - aVal;
            default:
                return 0;
        }
    });
    
    // Re-append sorted items
    fileItems.forEach(item => fileList.appendChild(item));
}

// Initialize modals
function initModals() {
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            closeModal(e.target.id);
        }
    });
}

// Modal functions
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function createNewFolder() {
    document.getElementById('createFolderModal').style.display = 'block';
    document.getElementById('folderNameInput').focus();
}

function confirmCreateFolder() {
    const folderName = document.getElementById('folderNameInput').value.trim();
    
    if (!folderName) {
        alert('Please enter a folder name');
        return;
    }
    
    const formData = new FormData();
    formData.append('folderName', folderName);
    formData.append('currentDir', currentDir);
    
    fetch('create_folder.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error creating folder');
    });
    
    closeModal('createFolderModal');
}

function renameFile(index, currentName, filePath) {
    currentRenamePath = filePath;
    currentRenameName = currentName;
    document.getElementById('renameInput').value = currentName;
    document.getElementById('renameModal').style.display = 'block';
    document.getElementById('renameInput').focus();
}

function confirmRename() {
    const newName = document.getElementById('renameInput').value.trim();
    
    if (!newName) {
        alert('Please enter a new name');
        return;
    }
    
    if (newName === currentRenameName) {
        closeModal('renameModal');
        return;
    }
    
    const formData = new FormData();
    formData.append('filePath', currentRenamePath);
    formData.append('newName', newName);
    
    fetch('rename.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error renaming file');
    });
    
    closeModal('renameModal');
}

function deleteFile(filePath) {
    if (!confirm('Are you sure you want to delete this file/folder?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('filePath', filePath);
    
    fetch('delete.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error deleting file');
    });
}

function downloadFile(filePath) {
    window.location.href = 'download.php?path=' + encodeURIComponent(filePath);
}

function previewFile(filePath) {
    const modal = document.getElementById('previewModal');
    const content = document.getElementById('previewContent');
    
    // Show loading
    content.innerHTML = '<div style="text-align: center; padding: 2rem;">Loading preview...</div>';
    modal.style.display = 'block';
    
    // Load preview content
    fetch('preview.php?path=' + encodeURIComponent(filePath))
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.startsWith('image/')) {
            return response.blob().then(blob => {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(blob);
                img.style.maxWidth = '100%';
                img.style.maxHeight = '70vh';
                content.innerHTML = '';
                content.appendChild(img);
            });
        } else if (contentType === 'application/pdf') {
            return response.blob().then(blob => {
                const iframe = document.createElement('iframe');
                iframe.src = URL.createObjectURL(blob);
                iframe.style.width = '100%';
                iframe.style.height = '70vh';
                content.innerHTML = '';
                content.appendChild(iframe);
            });
        } else {
            return response.text().then(text => {
                if (contentType && contentType.startsWith('text/')) {
                    const pre = document.createElement('pre');
                    pre.textContent = text;
                    pre.style.maxHeight = '70vh';
                    pre.style.overflow = 'auto';
                    pre.style.whiteSpace = 'pre-wrap';
                    content.innerHTML = '';
                    content.appendChild(pre);
                } else {
                    content.innerHTML = text;
                }
            });
        }
    })
    .catch(error => {
        content.innerHTML = '<div style="text-align: center; padding: 2rem; color: red;">Error loading preview</div>';
    });
}

function refreshFiles() {
    location.reload();
}