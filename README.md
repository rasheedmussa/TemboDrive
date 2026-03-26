# File Manager Installation Guide

## Overview
This is a complete PHP file and folder upload system with a modern, responsive interface similar to Google Drive or cPanel File Manager. **No database required for file operations** - uses filesystem only.

## Features
- Admin login system (database required only for authentication)
- Single and multiple file uploads
- Folder upload with structure preservation
- Drag & drop upload interface
- File/folder management (create, rename, delete)
- File preview (images, PDFs, text files)
- Download functionality
- Search and sort capabilities
- Responsive design
- Security features

## Requirements
- PHP 7.4 or higher
- Web server (Apache/Nginx)
- cPanel hosting account
- **No MySQL database required for file operations**

## PHP Configuration (Important for Large Uploads)
Before uploading large files, you need to increase PHP limits in your cPanel:

1. Go to **MultiPHP INI Editor** in cPanel
2. Select your domain
3. Update these settings:
   - `upload_max_filesize = 1024M` (1GB)
   - `post_max_size = 1024M` (1GB)
   - `max_execution_time = 300` (5 minutes)
   - `max_input_time = 300` (5 minutes)
   - `memory_limit = 512M`

4. Click **Apply** and wait for changes to take effect

## File Size Limits
- Maximum file size: **1GB per file**
- Maximum total upload size per request: 1GB
- Supported file types: Images, PDFs, Documents, Videos, Audio, Archives

## Installation Steps

### 1. Upload Files to Server
1. Download all the project files
2. Upload them to your cPanel's `public_html` directory or a subdirectory
3. Make sure the `uploads/` directory is writable (chmod 755 or 777)

### 2. Create Database
1. Log into your cPanel
2. Go to "MySQL Databases"
3. Create a new database (e.g., `filemanager`)
4. Create a database user and assign it to the database with full privileges
5. Note down the database name, username, and password

### 3. Import Database Schema
1. Go to "phpMyAdmin" in cPanel
2. Select your database
3. Click "Import" tab
4. Upload the `db_schema.sql` file
5. Click "Go" to import

### 4. Configure Database Connection
1. Open `config.php` in your file manager
2. Update the database constants:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_db_user');
   define('DB_PASS', 'your_db_password');
   define('DB_NAME', 'your_db_name');
   ```

### 2. Optional: Setup Authentication Database
If you want login system:
1. Create MySQL database
2. Import `db_schema.sql`
3. Update `config.php` with DB credentials

**Skip this step if you don't want authentication - the file manager will work without login!**

### 3. Set Permissions
1. In cPanel File Manager, right-click the `uploads/` folder
2. Set permissions to 755 (or 777 if needed)
3. Make sure `.htaccess` files are uploaded and working

### 4. Access the Application
- Visit `https://yourdomain.com/`
- If authentication enabled: login with `admin` / `admin123`
- Start uploading files immediately!

## Security Notes
- The `.htaccess` files prevent direct access to uploaded PHP files
- File names are sanitized to prevent path traversal
## How It Works Without Database
- **File Storage**: Files stored directly in `uploads/` directory
- **File Listing**: Uses PHP `DirectoryIterator` to scan filesystem
- **File Info**: Gets size, type, modified time from filesystem
- **Operations**: Create, rename, delete use PHP filesystem functions
- **Security**: Path traversal protection with `realpath()` checks

## Advantages of No Database Approach
- ✅ **Simpler deployment** - no database setup required
- ✅ **Better performance** - direct filesystem access
- ✅ **No database corruption issues**
- ✅ **Easier backup** - just backup files
- ✅ **Works on shared hosting without MySQL**

## Usage Without Database
1. Upload files - they go directly to `uploads/` folder
2. Create folders - creates subdirectories
3. Navigate - filesystem-based directory listing
4. All operations work without any database queries

## Optional Authentication
If you want to add login later:
1. Setup database as described
2. Uncomment database code in PHP files
3. Files will be user-specific

The system is designed to work perfectly without any database for file operations!
filemanager/
├── index.php              # Entry point
├── login.php              # Login page
├── logout.php             # Logout handler
├── dashboard.php          # Main dashboard
├── upload.php             # Upload handler
├── create_folder.php      # Create folder handler
├── rename.php             # Rename handler
├── delete.php             # Delete handler
├── download.php           # Download handler
├── preview.php            # Preview handler
├── config.php             # Configuration
├── .htaccess              # Security rules
├── db_schema.sql          # Database schema
├── uploads/               # Upload directory
│   └── .htaccess          # Upload security
└── assets/
    ├── css/
    │   └── style.css      # Styles
    └── js/
        └── main.js        # JavaScript
```

## Customization
- Modify `assets/css/style.css` for custom styling
- Update `config.php` for additional settings
- Add more file type validations in `upload.php`

## Support
This is a complete, working system. Test thoroughly before production use.

## How It Works Without Database
- **File Storage**: Files stored directly in `uploads/` directory
- **File Listing**: Uses PHP `DirectoryIterator` to scan filesystem
- **File Info**: Gets size, type, modified time from filesystem
- **Operations**: Create, rename, delete use PHP filesystem functions
- **Security**: Path traversal protection with `realpath()` checks

## Advantages of No Database Approach
- ✅ **Simpler deployment** - no database setup required
- ✅ **Better performance** - direct filesystem access
- ✅ **No database corruption issues**
- ✅ **Easier backup** - just backup files
- ✅ **Works on shared hosting without MySQL**

## Usage Without Database
1. Upload files - they go directly to `uploads/` folder
2. Create folders - creates subdirectories
3. Navigate - filesystem-based directory listing
4. All operations work without any database queries

## Optional Authentication
If you want to add login later:
1. Setup database as described
2. Uncomment database code in PHP files
3. Files will be user-specific

The system is designed to work perfectly without any database for file operations!