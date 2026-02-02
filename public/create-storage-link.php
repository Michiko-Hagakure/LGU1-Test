<?php
/**
 * Storage Link Creator
 * 
 * This script creates the storage symlink that `php artisan storage:link` would create.
 * Upload this file to your public folder and access it via browser once.
 * DELETE THIS FILE IMMEDIATELY AFTER USE for security.
 * 
 * Usage: https://your-domain.com/create-storage-link.php
 */

// Security: Only allow from specific IP or add a secret key
$secretKey = 'lgu1-storage-link-2026'; // Change this to something unique
$providedKey = $_GET['key'] ?? '';

if ($providedKey !== $secretKey) {
    http_response_code(403);
    die('Access denied. Please provide the correct key: ?key=YOUR_SECRET_KEY');
}

echo "<pre style='font-family: monospace; padding: 20px; background: #1a1a2e; color: #eee; border-radius: 8px;'>";
echo "=== Storage Link Creator ===\n\n";

// Define paths
$publicPath = __DIR__;
$storagePath = dirname(__DIR__) . '/storage/app/public';
$linkPath = $publicPath . '/storage';

echo "Public folder: $publicPath\n";
echo "Storage folder: $storagePath\n";
echo "Link to create: $linkPath\n\n";

// Check if storage/app/public exists
if (!is_dir($storagePath)) {
    echo "<span style='color: #ff6b6b;'>ERROR: Storage folder does not exist at: $storagePath</span>\n";
    echo "Creating storage/app/public directory...\n";
    
    if (mkdir($storagePath, 0755, true)) {
        echo "<span style='color: #4ecdc4;'>SUCCESS: Created storage/app/public directory</span>\n\n";
    } else {
        echo "<span style='color: #ff6b6b;'>FAILED: Could not create directory. Check permissions.</span>\n";
        die("</pre>");
    }
}

// Check if link already exists
if (file_exists($linkPath)) {
    if (is_link($linkPath)) {
        echo "<span style='color: #ffd93d;'>WARNING: Symlink already exists at: $linkPath</span>\n";
        echo "Target: " . readlink($linkPath) . "\n\n";
        
        // Test if it works
        if (is_dir($linkPath)) {
            echo "<span style='color: #4ecdc4;'>STATUS: Symlink is working correctly!</span>\n";
        } else {
            echo "<span style='color: #ff6b6b;'>STATUS: Symlink exists but is broken. Removing and recreating...</span>\n";
            unlink($linkPath);
        }
    } else {
        echo "<span style='color: #ff6b6b;'>ERROR: A file/folder already exists at $linkPath but it's not a symlink.</span>\n";
        echo "Please manually remove it first.\n";
        die("</pre>");
    }
}

// Create the symlink if it doesn't exist
if (!file_exists($linkPath)) {
    echo "Creating symlink...\n";
    
    // Try symlink first (Unix/Linux)
    if (function_exists('symlink')) {
        $result = @symlink($storagePath, $linkPath);
        
        if ($result) {
            echo "<span style='color: #4ecdc4;'>SUCCESS: Symlink created successfully!</span>\n\n";
            echo "The storage link is now active.\n";
            echo "Your uploaded files should now be accessible.\n";
        } else {
            $error = error_get_last();
            echo "<span style='color: #ff6b6b;'>FAILED: Could not create symlink.</span>\n";
            echo "Error: " . ($error['message'] ?? 'Unknown error') . "\n\n";
            
            // Try alternative: Windows junction (if on Windows server)
            echo "Attempting alternative method (junction/copy)...\n";
            
            // For shared hosting without symlink support, we might need to copy
            echo "<span style='color: #ffd93d;'>NOTE: If symlinks are disabled on your server, you may need to:</span>\n";
            echo "1. Contact your hosting provider to enable symlinks, OR\n";
            echo "2. Use the file copy method below\n\n";
            
            // Offer to copy files instead (not ideal but works)
            if (isset($_GET['copy']) && $_GET['copy'] === 'true') {
                echo "Copying files from storage to public/storage...\n";
                mkdir($linkPath, 0755, true);
                copyDirectory($storagePath, $linkPath);
                echo "<span style='color: #4ecdc4;'>Files copied successfully!</span>\n";
                echo "<span style='color: #ffd93d;'>WARNING: This is a one-time copy. New uploads won't appear until you run this again.</span>\n";
            } else {
                echo "To copy files instead (temporary fix): <a href='?key=$secretKey&copy=true' style='color: #4ecdc4;'>Click here</a>\n";
            }
        }
    } else {
        echo "<span style='color: #ff6b6b;'>ERROR: symlink() function is not available on this server.</span>\n";
    }
}

echo "\n<span style='color: #ff6b6b; font-weight: bold;'>⚠️ SECURITY WARNING: Delete this file immediately after use!</span>\n";
echo "File location: " . __FILE__ . "\n";
echo "</pre>";

// Helper function to copy directory recursively
function copyDirectory($source, $dest) {
    if (!is_dir($dest)) {
        mkdir($dest, 0755, true);
    }
    
    $dir = opendir($source);
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {
            $srcFile = $source . '/' . $file;
            $destFile = $dest . '/' . $file;
            
            if (is_dir($srcFile)) {
                copyDirectory($srcFile, $destFile);
            } else {
                copy($srcFile, $destFile);
            }
        }
    }
    closedir($dir);
}
