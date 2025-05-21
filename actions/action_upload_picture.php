<?php
declare(strict_types=1);

// Error reporting
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/User.class.php');

$session = new Session();
$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    header('Location: /');
    exit();
}

// Configuration
$maxFileSize = 5 * 1024 * 1024; // 5MB
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$uploadDir = __DIR__ . '/../uploads/profile/';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['profile_pic'])) {
        throw new Exception('Invalid request');
    }

    // Validate file upload
    if ($_FILES['profile_pic']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Upload error: ' . $_FILES['profile_pic']['error']);
    }

    // Check file size
    if ($_FILES['profile_pic']['size'] > $maxFileSize) {
        throw new Exception('File too large (max 5MB allowed)');
    }

    // Verify file type
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($fileInfo, $_FILES['profile_pic']['tmp_name']);
    finfo_close($fileInfo);

    $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp'
    ];

    if (!in_array($mimeType, $allowedMimeTypes)) {
        throw new Exception('Invalid file type. Allowed: JPG, PNG, GIF, WEBP');
    }

    // Get file extension from original name
    $originalName = $_FILES['profile_pic']['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions)) {
        throw new Exception('Invalid file extension');
    }

    // Create upload directory if needed
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)){    
            throw new Exception('Failed to create upload directory');
        }
    }

    // Verify directory is writable
    if (!is_writable($uploadDir)) {
        throw new Exception('Upload directory not writable');
    }

    // Get user and current profile picture
    $user = User::getUser($db, $session->getId());
    $oldPicture = $user->getProfilePicture();

    // Generate unique filename
    $newFilename = 'profile_' . $user->getId() . '_' . uniqid() . '.' . $extension;
    $targetPath = $uploadDir . $newFilename;

    // Move uploaded file
    if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetPath)) {
        throw new Exception('Failed to move uploaded file');
    }

    // Delete old picture if it exists
    if ($oldPicture && file_exists($uploadDir . $oldPicture)) {
        if (!unlink($uploadDir . $oldPicture)) {
            error_log("Warning: Failed to delete old profile picture: " . $oldPicture);
        }
    }

    // Update database
    if (!$user->setProfilePicture($db, $newFilename)) {
        // Clean up the uploaded file if DB update failed
        unlink($targetPath);
        throw new Exception('Failed to update profile in database');
    }

    $session->addMessage('success', 'Profile picture updated successfully!');
    header('Location: /pages/profile.php');
    exit();

} catch (Exception $e) {
    error_log("Profile picture upload error: " . $e->getMessage());
    $session->addMessage('error', $e->getMessage());
    header('Location: /pages/profile.php');
    exit();
}