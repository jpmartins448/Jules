<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');

$session = new Session();
if (!$session->isLoggedIn()) {
  header('Location: ../pages/index.php');
  exit();
}

$db = getDatabaseConnection();
$userId = $session->getId();
$serviceId = (int) ($_POST['service_id'] ?? 0);

// Fetch the service owner and role
$stmt = $db->prepare('SELECT user_id FROM services WHERE id = ?');
$stmt->execute([$serviceId]);
$ownerId = $stmt->fetchColumn();

if (!$ownerId) {
  $session->addMessage('error', 'Service not found.');
  header('Location: ../pages/loged_in.php');
  exit();
}

// Check if user is owner or admin
$stmt = $db->prepare('SELECT role FROM users WHERE id = ?');
$stmt->execute([$userId]);
$role = $stmt->fetchColumn();

$isAdmin = ($role === 'admin');

if ($ownerId === $userId || $isAdmin) {
  $stmt = $db->prepare('DELETE FROM services WHERE id = ?');
  $stmt->execute([$serviceId]);
  $session->addMessage('success', 'Service deleted successfully.');

  if ($isAdmin) {
    header('Location: ../pages/admin_all_services.php');
  } else {
    header('Location: ../pages/my_services.php');
  }
} else {
  $session->addMessage('error', 'Unauthorized request.');
  header('Location: ../pages/loged_in.php');
}

exit();
