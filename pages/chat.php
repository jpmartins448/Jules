<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');

$session = new Session();
if (!$session->isLoggedIn()) {
  header('Location: ../pages/index.php');
  exit;
}

$db = getDatabaseConnection();
$clientId = $session->getId();
$freelancerId = (int) $_GET['freelancer_id'];

// Check if chat exists
$stmt = $db->prepare("SELECT id FROM chats WHERE client_id = ? AND freelancer_id = ?");
$stmt->execute([$clientId, $freelancerId]);
$chatId = $stmt->fetchColumn();

if (!$chatId) {
  $stmt = $db->prepare("INSERT INTO chats (client_id, freelancer_id) VALUES (?, ?)");
  $stmt->execute([$clientId, $freelancerId]);
  $chatId = $db->lastInsertId();
}

header("Location: ../pages/chat_window.php?chat_id=$chatId");
exit;
