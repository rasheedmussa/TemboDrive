// main.js — TemboDrive (Full Featured)

// ── TRANSLATIONS ─────────────────────────────────────────────────
const i18n = {
  en: {
    appTitle: 'TemboDrive',
    home: 'Home',
    uploadTitle: 'Upload Files',
    uploadMain: 'Drop files here',
    uploadSub: 'or click to browse',
    uploadFolder: 'Upload Folder',
    actionsTitle: 'Actions',
    newFolder: 'New Folder',
    refresh: 'Refresh',
    storageTitle: 'Storage Info',
    totalFiles: 'Total Files',
    totalSize: 'Total Size',
    currentDir: 'Directory',
    searchPlaceholder: 'Search files...',
    sortName: 'Sort by Name',
    sortDate: 'Sort by Date',
    sortSize: 'Sort by Size',
    colName: 'Name',
    colType: 'Type',
    colSize: 'Size',
    colDate: 'Modified',
    colActions: 'Actions',
    noFiles: 'No files in this directory',
    typeFolder: 'Folder',
    renameTitle: 'Rename',
    renamePlaceholder: 'New name...',
    renameBtn: 'Rename',
    folderTitle: 'New Folder',
    folderPlaceholder: 'Folder name...',
    folderBtn: 'Create',
    previewTitle: 'Preview',
    editorTitle: 'Edit File',
    editorSave: 'Save',
    moveTitle: 'Move To',
    copyTitle: 'Copy To',
    moveBtn: 'Move Here',
    copyBtn: 'Copy Here',
    shareTitle: 'Share File',
    shareLinkLabel: 'Share Link',
    shareCopy: 'Copy',
    shareGenerate: 'Generate Link',
    bulkMove: '↗️ Move',
    bulkCopy: '📋 Copy',
    bulkZip: '🗜️ Download ZIP',
    bulkDelete: '🗑️ Delete',
    deselectAll: '✕ Deselect',
    uploadBtn: 'Upload',
    selectAllBtn: 'Select All',
    deselectBtn: 'Deselect',
    goUp: 'Go Up',
    themeLight: 'Light',
    themeDark: 'Dark',
    deleteConfirm: 'Are you sure you want to delete this?',
    bulkDeleteConfirm: 'Delete {n} selected item(s)?',
    uploadSuccess: 'Upload successful!',
    uploadFail: 'Upload failed',
    renameSuccess: 'Renamed successfully',
    renameFail: 'Rename failed',
    deleteSuccess: 'Deleted successfully',
    deleteFail: 'Delete failed',
    folderSuccess: 'Folder created',
    folderFail: 'Failed to create folder',
    moveSuccess: 'Moved successfully',
    moveFail: 'Move failed',
    copySuccess: 'Copied successfully',
    copyFail: 'Copy failed',
    saveSuccess: 'File saved',
    saveFail: 'Save failed',
    zipSuccess: 'ZIP created',
    zipFail: 'ZIP creation failed',
    extractSuccess: 'Extracted successfully',
    extractFail: 'Extraction failed',
    shareSuccess: 'Link copied!',
    shareFail: 'Share failed',
    loading: 'Loading preview...',
    previewError: 'Error loading preview',
    enterName: 'Please enter a name',
    noSelection: 'No items selected',
    loadingFolders: 'Loading folders...',
    linkCopied: 'Link copied to clipboard!',
  },
  sw: {
    appTitle: 'TemboDrive',
    home: 'Nyumbani',
    uploadTitle: 'Pakia Faili',
    uploadMain: 'Buruta faili hapa',
    uploadSub: 'au bonyeza kuchagua',
    uploadFolder: 'Pakia Folda',
    actionsTitle: 'Vitendo',
    newFolder: 'Folda Mpya',
    refresh: 'Onyesha Upya',
    storageTitle: 'Taarifa ya Hifadhi',
    totalFiles: 'Jumla ya Faili',
    totalSize: 'Ukubwa Wote',
    currentDir: 'Saraka',
    searchPlaceholder: 'Tafuta faili...',
    sortName: 'Panga kwa Jina',
    sortDate: 'Panga kwa Tarehe',
    sortSize: 'Panga kwa Ukubwa',
    colName: 'Jina',
    colType: 'Aina',
    colSize: 'Ukubwa',
    colDate: 'Ilirekebishwa',
    colActions: 'Vitendo',
    noFiles: 'Hakuna faili katika saraka hii',
    typeFolder: 'Folda',
    renameTitle: 'Badilisha Jina',
    renamePlaceholder: 'Jina jipya...',
    renameBtn: 'Badilisha',
    folderTitle: 'Folda Mpya',
    folderPlaceholder: 'Jina la folda...',
    folderBtn: 'Tengeneza',
    previewTitle: 'Onyesho',
    editorTitle: 'Hariri Faili',
    editorSave: 'Hifadhi',
    moveTitle: 'Hamisha Kwenda',
    copyTitle: 'Nakili Kwenda',
    moveBtn: 'Hamisha Hapa',
    copyBtn: 'Nakili Hapa',
    shareTitle: 'Shiriki Faili',
    shareLinkLabel: 'Kiungo cha Kushiriki',
    shareCopy: 'Nakili',
    shareGenerate: 'Tengeneza Kiungo',
    bulkMove: '↗️ Hamisha',
    bulkCopy: '📋 Nakili',
    bulkZip: '🗜️ Pakua ZIP',
    bulkDelete: '🗑️ Futa',
    deselectAll: '✕ Ondoa Uchaguzi',
    uploadBtn: 'Pakia',
    selectAllBtn: 'Chagua Yote',
    deselectBtn: 'Ondoa',
    goUp: 'Rudi Juu',
    themeLight: 'Mwanga',
    themeDark: 'Giza',
    deleteConfirm: 'Una uhakika unataka kufuta hii?',
    bulkDeleteConfirm: 'Futa vipande {n} vilivyochaguliwa?',
    uploadSuccess: 'Imepakiwa!',
    uploadFail: 'Upakiaji umeshindwa',
    renameSuccess: 'Jina limebadilishwa',
    renameFail: 'Kubadilisha jina kumeshindwa',
    deleteSuccess: 'Imefutwa',
    deleteFail: 'Kufuta kumeshindwa',
    folderSuccess: 'Folda imetengenezwa',
    folderFail: 'Kutengeneza folda kumeshindwa',
    moveSuccess: 'Imehamishwa',
    moveFail: 'Kuhamisha kumeshindwa',
    copySuccess: 'Imenakiliwa',
    copyFail: 'Kunakili kumeshindwa',
    saveSuccess: 'Faili imehifadhiwa',
    saveFail: 'Kuhifadhi kumeshindwa',
    zipSuccess: 'ZIP imetengenezwa',
    zipFail: 'Kutengeneza ZIP kumeshindwa',
    extractSuccess: 'Imefunguliwa',
    extractFail: 'Kufungua kumeshindwa',
    shareSuccess: 'Kiungo kimenakiliwa!',
    shareFail: 'Kushiriki kumeshindwa',
    loading: 'Inapakia onyesho...',
    previewError: 'Hitilafu ya kupakia onyesho',
    enterName: 'Tafadhali ingiza jina',
    noSelection: 'Hakuna vipande vilivyochaguliwa',
    loadingFolders: 'Inapakia folda...',
    linkCopied: 'Kiungo kimenakiliwa!',
  }
};

// ── STATE ────────────────────────────────────────────────────────
let currentLang       = localStorage.getItem('fm_lang')  || 'en';
let currentTheme      = localStorage.getItem('fm_theme') || 'dark';
let currentView       = localStorage.getItem('fm_view')  || 'list';
let currentDir        = '';
let currentRenamePath = '';
let currentRenameName = '';
let currentEditPath   = '';
let currentSharePath  = '';
let moveCopyAction    = 'move'; // 'move' or 'copy'
let moveCopyPaths     = [];     // paths to move/copy
let selectedDestFolder = '';
let selectedFiles     = new Set();

// ── INIT ─────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  currentDir = params.get('dir') || '';

  applyTheme(currentTheme);
  applyLang(currentLang);
  setView(currentView, false);
  initUpload();
  initSearchSort();
  initModalClose();
  initKeyboard();
});

// ── THEME ─────────────────────────────────────────────────────────
function applyTheme(theme) {
  currentTheme = theme;
  document.documentElement.setAttribute('data-theme', theme);
  localStorage.setItem('fm_theme', theme);
  const icon = document.getElementById('themeIcon');
  if (icon) icon.textContent = theme === 'dark' ? '☀️' : '🌙';
}
function toggleTheme() { applyTheme(currentTheme === 'dark' ? 'light' : 'dark'); }

// ── LANGUAGE ──────────────────────────────────────────────────────
function applyLang(lang) {
  currentLang = lang;
  localStorage.setItem('fm_lang', lang);
  const label = document.getElementById('langLabel');
  if (label) label.textContent = lang === 'en' ? 'SW' : 'EN';
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (i18n[lang][key] !== undefined) el.textContent = i18n[lang][key];
  });
  document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
    const key = el.getAttribute('data-i18n-placeholder');
    if (i18n[lang][key] !== undefined) el.placeholder = i18n[lang][key];
  });
  applyTheme(currentTheme);
}
function toggleLang() { applyLang(currentLang === 'en' ? 'sw' : 'en'); }
function t(key) { return (i18n[currentLang] && i18n[currentLang][key]) || key; }

// ── TOAST ─────────────────────────────────────────────────────────
function showToast(msg, type = 'info') {
  const container = document.getElementById('toastContainer');
  if (!container) return;
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.textContent = msg;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s';
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// ── VIEW TOGGLE (Feature 8) ───────────────────────────────────────
function setView(mode, persist = true) {
  currentView = mode;
  if (persist) localStorage.setItem('fm_view', mode);

  const panel = document.getElementById('filePanel');
  const btnList = document.getElementById('viewList');
  const btnGrid = document.getElementById('viewGrid');

  if (!panel) return;

  if (mode === 'grid') {
    panel.classList.add('grid-view');
    if (btnList) btnList.classList.remove('active');
    if (btnGrid) btnGrid.classList.add('active');
    buildGridCards();
  } else {
    panel.classList.remove('grid-view');
    if (btnList) btnList.classList.add('active');
    if (btnGrid) btnGrid.classList.remove('active');
  }
}

function buildGridCards() {
  const list = document.getElementById('fileList');
  if (!list) return;
  list.querySelectorAll('.file-item').forEach(item => {
    if (item.querySelector('.grid-card-inner')) return; // already built
    const thumb = item.dataset.thumb;
    const name  = item.dataset.name;
    const isFolder = item.dataset.folder === '1';
    const size  = item.querySelector('.file-size-cell')?.textContent || '';
    const date  = item.querySelector('.file-date-cell')?.textContent || '';
    const icon  = item.querySelector('.file-icon')?.textContent || '📄';

    const inner = document.createElement('div');
    inner.className = 'grid-card-inner';

    let thumbHtml = '';
    if (thumb) {
      thumbHtml = `<div class="grid-thumb" style="background-image:url('${thumb}')"></div>`;
    } else {
      thumbHtml = `<div class="grid-thumb grid-thumb-icon">${icon}</div>`;
    }

    inner.innerHTML = thumbHtml +
      `<div class="grid-card-name">${name}</div>` +
      `<div class="grid-card-meta">${isFolder ? '📁' : size}</div>`;
    item.appendChild(inner);
  });
}

// ── UPLOAD ────────────────────────────────────────────────────────
function initUpload() {
  const uploadArea = document.getElementById('uploadArea');
  const fileInput  = document.getElementById('fileInput');
  if (!uploadArea || !fileInput) return;

  ['dragenter','dragover','dragleave','drop'].forEach(ev =>
    uploadArea.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); }));
  ['dragenter','dragover'].forEach(ev =>
    uploadArea.addEventListener(ev, () => uploadArea.classList.add('dragover')));
  ['dragleave','drop'].forEach(ev =>
    uploadArea.addEventListener(ev, () => uploadArea.classList.remove('dragover')));

  uploadArea.addEventListener('drop', e => handleFiles(e.dataTransfer.files));
  uploadArea.addEventListener('click', () => fileInput.click());
  fileInput.addEventListener('change', e => handleFiles(e.target.files));
}

function handleFiles(files) {
  const isFolderMode = document.getElementById('folderUpload')?.checked;
  const formData = new FormData();
  if (isFolderMode) {
    formData.append('folderUpload', 'true');
    for (const f of files) {
      formData.append('files[]', f);
      formData.append('webkitRelativePath[]', f.webkitRelativePath || f.name);
    }
  } else {
    for (const f of files) formData.append('files[]', f);
  }
  formData.append('currentDir', currentDir);
  sendUpload(formData);
}

function sendUpload(formData) {
  const container = document.getElementById('progressContainer');
  const fill      = document.getElementById('progressFill');
  const label     = document.getElementById('progressText');
  if (container) container.style.display = 'block';

  const xhr = new XMLHttpRequest();
  xhr.upload.addEventListener('progress', e => {
    if (e.lengthComputable) {
      const pct = Math.round((e.loaded / e.total) * 100);
      if (fill)  fill.style.width  = pct + '%';
      if (label) label.textContent = pct + '%';
    }
  });
  xhr.addEventListener('load', () => {
    if (container) container.style.display = 'none';
    try {
      const res = JSON.parse(xhr.responseText);
      if (res.success) { showToast(t('uploadSuccess'), 'success'); setTimeout(() => location.reload(), 800); }
      else showToast(t('uploadFail') + ': ' + (res.message || ''), 'error');
    } catch {
      if (xhr.status === 200) { showToast(t('uploadSuccess'), 'success'); setTimeout(() => location.reload(), 800); }
      else showToast(t('uploadFail'), 'error');
    }
  });
  xhr.addEventListener('error', () => { if (container) container.style.display = 'none'; showToast(t('uploadFail'), 'error'); });
  xhr.open('POST', 'upload.php');
  xhr.send(formData);
}

// ── SEARCH & SORT ─────────────────────────────────────────────────
function initSearchSort() {
  document.getElementById('searchInput')?.addEventListener('input', filterFiles);
  document.getElementById('sortSelect')?.addEventListener('change', sortFiles);
}
function filterFiles() {
  const term = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('.file-item').forEach(item => {
    item.style.display = (item.dataset.name?.toLowerCase() || '').includes(term) ? '' : 'none';
  });
}
function sortFiles() {
  const by   = document.getElementById('sortSelect').value;
  const list = document.getElementById('fileList');
  const items = Array.from(list?.querySelectorAll('.file-item') || []);
  items.sort((a, b) => {
    if (by === 'name') return (a.dataset.name||'').localeCompare(b.dataset.name||'');
    if (by === 'date') return (b.dataset.date||0) - (a.dataset.date||0);
    if (by === 'size') return (b.dataset.size||0) - (a.dataset.size||0);
    return 0;
  });
  items.forEach(item => list.appendChild(item));
}

// ── MULTI-SELECT (Feature 2) ──────────────────────────────────────
function toggleSelect(checkbox, path) {
  if (checkbox.checked) selectedFiles.add(path);
  else selectedFiles.delete(path);
  updateBulkBar();
}
function toggleSelectAll(master) {
  // When called from sidebar button, master may be null — treat as select-all
  const check = master || { checked: true };
  if (!master) {
    // Sidebar "Select All" button: always select all
    const headerCb = document.getElementById('selectAll');
    if (headerCb) headerCb.checked = true;
  }
  document.querySelectorAll('.file-check').forEach(cb => {
    cb.checked = check.checked;
    const path = cb.closest('.file-item')?.dataset.path;
    if (path) {
      if (check.checked) selectedFiles.add(path);
      else selectedFiles.delete(path);
    }
  });
  updateBulkBar();
}
function deselectAll() {
  selectedFiles.clear();
  document.querySelectorAll('.file-check').forEach(cb => cb.checked = false);
  const master = document.getElementById('selectAll');
  if (master) master.checked = false;
  updateBulkBar();
}
function updateBulkBar() {
  const bar   = document.getElementById('bulkBar');
  const count = document.getElementById('bulkCount');
  if (!bar) return;
  const n = selectedFiles.size;
  bar.style.display = n > 0 ? 'flex' : 'none';
  if (count) count.textContent = n + ' selected';
}

// ── BULK DELETE ───────────────────────────────────────────────────
function bulkDelete() {
  const paths = Array.from(selectedFiles);
  if (!paths.length) { showToast(t('noSelection'), 'error'); return; }
  const msg = t('bulkDeleteConfirm').replace('{n}', paths.length);
  if (!confirm(msg)) return;

  const fd = new FormData();
  fd.append('action', 'delete');
  fd.append('paths', JSON.stringify(paths));

  fetch('bulk_action.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) { showToast(data.message, 'success'); setTimeout(() => location.reload(), 600); }
      else showToast(t('deleteFail') + ': ' + (data.message || ''), 'error');
    })
    .catch(() => showToast(t('deleteFail'), 'error'));
}

// ── BULK ZIP DOWNLOAD ─────────────────────────────────────────────
function bulkDownloadZip() {
  const paths = Array.from(selectedFiles);
  if (!paths.length) { showToast(t('noSelection'), 'error'); return; }
  const form  = document.createElement('form');
  form.method = 'POST';
  form.action = 'bulk_zip.php';
  form.style.display = 'none';
  const inp   = document.createElement('input');
  inp.type    = 'hidden';
  inp.name    = 'paths';
  inp.value   = JSON.stringify(paths);
  form.appendChild(inp);
  document.body.appendChild(form);
  form.submit();
  setTimeout(() => document.body.removeChild(form), 2000);
}

// ── MOVE / COPY (Feature 3) ───────────────────────────────────────
function bulkMove() {
  const paths = Array.from(selectedFiles);
  if (!paths.length) { showToast(t('noSelection'), 'error'); return; }
  openMoveCopyModal('move', paths);
}
function bulkCopy() {
  const paths = Array.from(selectedFiles);
  if (!paths.length) { showToast(t('noSelection'), 'error'); return; }
  openMoveCopyModal('copy', paths);
}

function openMoveCopyModal(action, paths) {
  moveCopyAction = action;
  moveCopyPaths  = paths;
  selectedDestFolder = '';

  const title  = document.getElementById('moveCopyTitle');
  const btn    = document.getElementById('moveCopyBtn');
  if (title) title.textContent = action === 'move' ? t('moveTitle') : t('copyTitle');
  if (btn)   btn.textContent   = action === 'move' ? t('moveBtn')   : t('copyBtn');

  // Load folders
  const tree = document.getElementById('folderTree');
  if (tree) {
    tree.innerHTML = `<div style="color:var(--text-400);font-size:.82rem;padding:.5rem">${t('loadingFolders')}</div>`;
  }

  openModal('moveModal');

  fetch('get_folders.php')
    .then(r => r.json())
    .then(data => {
      if (!tree) return;
      let html = `<div class="folder-option selected" data-path="" onclick="selectDestFolder(this)">📁 / (root)</div>`;
      (data.folders || []).forEach(f => {
        const indent = '&nbsp;'.repeat(f.depth * 4);
        html += `<div class="folder-option" data-path="${f.path}" onclick="selectDestFolder(this)">${indent}📁 ${f.name}</div>`;
      });
      tree.innerHTML = html;
    })
    .catch(() => {
      if (tree) tree.innerHTML = `<div style="color:var(--danger);font-size:.82rem;padding:.5rem">Failed to load folders</div>`;
    });
}

function selectDestFolder(el) {
  document.querySelectorAll('.folder-option').forEach(o => o.classList.remove('selected'));
  el.classList.add('selected');
  selectedDestFolder = el.dataset.path || '';
}

function confirmMoveCopy() {
  if (!moveCopyPaths.length) return;
  closeModal('moveModal');

  const requests = moveCopyPaths.map(path => {
    const fd = new FormData();
    fd.append('action', moveCopyAction);
    fd.append('source', path);
    fd.append('dest',   selectedDestFolder);
    return fetch('move.php', { method: 'POST', body: fd }).then(r => r.json());
  });

  Promise.all(requests).then(results => {
    const failed = results.filter(r => !r.success);
    if (failed.length === 0) {
      showToast(moveCopyAction === 'move' ? t('moveSuccess') : t('copySuccess'), 'success');
      setTimeout(() => location.reload(), 700);
    } else {
      showToast((moveCopyAction === 'move' ? t('moveFail') : t('copyFail')) + ': ' + failed[0].message, 'error');
    }
  }).catch(() => showToast(moveCopyAction === 'move' ? t('moveFail') : t('copyFail'), 'error'));
}

// ── TEXT EDITOR (Feature 4) ───────────────────────────────────────
function editFile(path) {
  currentEditPath = path;
  const nameEl = document.getElementById('editorFileName');
  if (nameEl) nameEl.textContent = path.split('/').pop();
  const content = document.getElementById('editorContent');
  if (content) content.value = t('loading');
  openModal('editorModal');

  fetch('preview.php?path=' + encodeURIComponent(path))
    .then(r => r.text())
    .then(text => { if (content) content.value = text; })
    .catch(() => { if (content) content.value = ''; showToast(t('previewError'), 'error'); });
}

function saveEdit() {
  const content = document.getElementById('editorContent')?.value ?? '';
  const fd = new FormData();
  fd.append('path',    currentEditPath);
  fd.append('content', content);

  fetch('save_file.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) { showToast(t('saveSuccess'), 'success'); closeModal('editorModal'); }
      else showToast(t('saveFail') + ': ' + (data.message || ''), 'error');
    })
    .catch(() => showToast(t('saveFail'), 'error'));
}

// ── ZIP (Feature 6) ───────────────────────────────────────────────
function createZip(path) {
  const fd = new FormData();
  fd.append('action', 'create');
  fd.append('path',   path);
  fetch('zip_action.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) { showToast(t('zipSuccess') + ': ' + data.message, 'success'); setTimeout(() => location.reload(), 700); }
      else showToast(t('zipFail') + ': ' + (data.message || ''), 'error');
    })
    .catch(() => showToast(t('zipFail'), 'error'));
}

function extractZip(path) {
  const fd = new FormData();
  fd.append('action', 'extract');
  fd.append('path',   path);
  fetch('zip_action.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) { showToast(t('extractSuccess') + ': ' + data.message, 'success'); setTimeout(() => location.reload(), 700); }
      else showToast(t('extractFail') + ': ' + (data.message || ''), 'error');
    })
    .catch(() => showToast(t('extractFail'), 'error'));
}

// ── SHARE (Feature 7) ─────────────────────────────────────────────
function shareFile(path, name) {
  currentSharePath = path;
  const nameEl = document.getElementById('shareFileName');
  if (nameEl) nameEl.textContent = name;
  const wrap = document.getElementById('shareLinkWrap');
  if (wrap) wrap.style.display = 'none';
  const btn = document.getElementById('shareGenerateBtn');
  if (btn) btn.style.display = 'block';
  openModal('shareModal');
}

function generateShareLink() {
  const fd = new FormData();
  fd.append('path', currentSharePath);
  fetch('share.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const input = document.getElementById('shareLinkInput');
        if (input) input.value = data.url;
        const wrap = document.getElementById('shareLinkWrap');
        if (wrap) wrap.style.display = 'block';
        const btn = document.getElementById('shareGenerateBtn');
        if (btn) btn.style.display = 'none';
      } else {
        showToast(t('shareFail') + ': ' + (data.message || ''), 'error');
      }
    })
    .catch(() => showToast(t('shareFail'), 'error'));
}

function copyShareLink() {
  const input = document.getElementById('shareLinkInput');
  if (!input || !input.value) return;
  navigator.clipboard.writeText(input.value)
    .then(() => showToast(t('linkCopied'), 'success'))
    .catch(() => { input.select(); document.execCommand('copy'); showToast(t('linkCopied'), 'success'); });
}

// ── PREVIEW (Feature 5 — video/audio added) ──────────────────────
function previewFile(path) {
  const content = document.getElementById('previewContent');
  if (!content) return;

  const ext = path.split('.').pop().toLowerCase();
  const videoExts = ['mp4','webm','ogg','mov','avi'];
  const audioExts = ['mp3','wav','ogg','aac','flac','m4a'];

  content.innerHTML = `<div style="text-align:center;padding:2rem;color:var(--text-400)">${t('loading')}</div>`;
  openModal('previewModal');

  if (videoExts.includes(ext)) {
    const vid = document.createElement('video');
    vid.src = 'preview.php?path=' + encodeURIComponent(path);
    vid.controls = true;
    vid.style.cssText = 'width:100%;max-height:65vh;border-radius:8px;display:block';
    content.innerHTML = '';
    content.appendChild(vid);
    return;
  }
  if (audioExts.includes(ext)) {
    const aud = document.createElement('audio');
    aud.src = 'preview.php?path=' + encodeURIComponent(path);
    aud.controls = true;
    aud.style.cssText = 'width:100%;margin:1.5rem 0;display:block';
    content.innerHTML = '';
    content.appendChild(aud);
    return;
  }

  fetch('preview.php?path=' + encodeURIComponent(path))
    .then(response => {
      const ct = response.headers.get('content-type') || '';
      if (ct.startsWith('image/')) {
        return response.blob().then(blob => {
          const img = document.createElement('img');
          img.src = URL.createObjectURL(blob);
          img.style.maxWidth = '100%';
          content.innerHTML = '';
          content.appendChild(img);
        });
      } else if (ct === 'application/pdf') {
        return response.blob().then(blob => {
          const iframe = document.createElement('iframe');
          iframe.src = URL.createObjectURL(blob);
          iframe.style.cssText = 'width:100%;height:70vh;border:none';
          content.innerHTML = '';
          content.appendChild(iframe);
        });
      } else {
        return response.text().then(text => {
          const pre = document.createElement('pre');
          pre.textContent = text;
          content.innerHTML = '';
          content.appendChild(pre);
        });
      }
    })
    .catch(() => {
      content.innerHTML = `<div style="text-align:center;padding:2rem;color:var(--danger)">${t('previewError')}</div>`;
    });
}

// ── MODALS ────────────────────────────────────────────────────────
function initModalClose() {
  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(modal.id); });
  });
}
function initKeyboard() {
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal.active').forEach(m => closeModal(m.id));
    if (e.key === 'Enter') {
      if (document.getElementById('renameModal')?.classList.contains('active')) confirmRename();
      if (document.getElementById('createFolderModal')?.classList.contains('active')) confirmCreateFolder();
    }
  });
}
function openModal(id) { document.getElementById(id)?.classList.add('active'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('active'); }

// ── FOLDER ────────────────────────────────────────────────────────
function createNewFolder() {
  const input = document.getElementById('folderNameInput');
  if (input) input.value = '';
  openModal('createFolderModal');
  setTimeout(() => input?.focus(), 100);
}
function confirmCreateFolder() {
  const name = document.getElementById('folderNameInput')?.value.trim();
  if (!name) { showToast(t('enterName'), 'error'); return; }
  const fd = new FormData();
  fd.append('folderName', name);
  fd.append('currentDir', currentDir);
  fetch('create_folder.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      closeModal('createFolderModal');
      if (data.success) { showToast(t('folderSuccess'), 'success'); setTimeout(() => location.reload(), 600); }
      else showToast(t('folderFail') + ': ' + (data.message || ''), 'error');
    })
    .catch(() => { closeModal('createFolderModal'); showToast(t('folderFail'), 'error'); });
}

// ── RENAME ────────────────────────────────────────────────────────
function renameFile(index, name, path) {
  currentRenamePath = path;
  currentRenameName = name;
  const input = document.getElementById('renameInput');
  if (input) input.value = name;
  openModal('renameModal');
  setTimeout(() => { input?.focus(); input?.select(); }, 100);
}
function confirmRename() {
  const newName = document.getElementById('renameInput')?.value.trim();
  if (!newName) { showToast(t('enterName'), 'error'); return; }
  if (newName === currentRenameName) { closeModal('renameModal'); return; }
  const fd = new FormData();
  fd.append('filePath', currentRenamePath);
  fd.append('newName',  newName);
  fetch('rename.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      closeModal('renameModal');
      if (data.success) { showToast(t('renameSuccess'), 'success'); setTimeout(() => location.reload(), 600); }
      else showToast(t('renameFail') + ': ' + (data.message || ''), 'error');
    })
    .catch(() => { closeModal('renameModal'); showToast(t('renameFail'), 'error'); });
}

// ── DELETE ────────────────────────────────────────────────────────
function deleteFile(path) {
  if (!confirm(t('deleteConfirm'))) return;
  const fd = new FormData();
  fd.append('filePath', path);
  fetch('delete.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) { showToast(t('deleteSuccess'), 'success'); setTimeout(() => location.reload(), 600); }
      else showToast(t('deleteFail') + ': ' + (data.message || ''), 'error');
    })
    .catch(() => showToast(t('deleteFail'), 'error'));
}

// ── DOWNLOAD ──────────────────────────────────────────────────────
function downloadFile(path) {
  window.location.href = 'download.php?path=' + encodeURIComponent(path);
}

// ── REFRESH ───────────────────────────────────────────────────────
function refreshFiles() { location.reload(); }
