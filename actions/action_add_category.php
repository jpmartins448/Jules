<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');

$session = new Session();
$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    $_SESSION['error'] = 'You must be logged in to add a category.';
    header('Location: ../pages/login.php');
    exit();
}

// Check if user is admin
$stmt = $db->prepare('SELECT role FROM users WHERE id = ?');
$stmt->execute([$session->getId()]);
$role = $stmt->fetchColumn();

if ($role !== 'admin') {
    $_SESSION['error'] = 'Only admins can add categories.';
    header('Location: ../pages/loged_in.php');
    exit();
}

// Validate and insert new category
$categoryName = trim($_POST['category_name'] ?? '');

if (empty($categoryName)) {
    $_SESSION['error'] = 'Category name cannot be empty.';
} else {
    $stmt = $db->prepare('INSERT OR IGNORE INTO categories (name) VALUES (?)');
    $stmt->execute([$categoryName]);

    if ($stmt->rowCount() === 0) {
        $_SESSION['error'] = 'Category already exists.';
    } else {
        $_SESSION['success'] = 'Category added successfully.';
    }
}

header('Location: ../pages/admin_all_categories.php');
exit();
