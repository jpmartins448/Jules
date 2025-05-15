<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');

$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: ../pages/index.php'));

$db = getDatabaseConnection();
$userId = $session->getId();
$serviceId = (int) ($_POST['service_id'] ?? 0);

// Ensure the service belongs to this user
$stmt = $db->prepare('SELECT id FROM services WHERE id = ? AND user_id = ?');
$stmt->execute([$serviceId, $userId]);
if ($stmt->fetch()) {
  $stmt = $db->prepare('DELETE FROM services WHERE id = ?');
  $stmt->execute([$serviceId]);
  $session->addMessage('success', 'Service deleted successfully.');
} else {
  $session->addMessage('error', 'Unauthorized or invalid request.');
}

header('Location: ../pages/my_services.php');
exit();
