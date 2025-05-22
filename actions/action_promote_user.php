<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/User.class.php');

$session = new Session();
$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    die('Unauthorized: Not logged in.');
}

$admin = User::getUser($db, $session->getId());
if (!$admin->isAdmin()) {
    die('Unauthorized: Only admins can promote users.');
}

$targetUserId = $_POST['user_id'] ?? null;
if (!$targetUserId || !is_numeric($targetUserId)) {
    die('Invalid request.');
}

if ((int)$targetUserId === $admin->getId()) {
    die('You cannot promote yourself.');
}

// Promote user
$stmt = $db->prepare('UPDATE users SET role = "admin" WHERE id = ?');
$stmt->execute([$targetUserId]);

$session->addMessage('success', 'User has been promoted to admin.');
header('Location: ../pages/admin_all_users.php');
exit;
