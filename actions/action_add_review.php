<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');

$session = new Session();
if (!$session->isLoggedIn()) die('Not logged in.');

$db = getDatabaseConnection();

$service_id = $_POST['service_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$comment = $_POST['comment'] ?? '';

if (!$service_id || !$rating || $comment === '') die('Missing data.');

// Verifica se o user pode comentar (tem encomenda concluída)
$stmt = $db->prepare('SELECT COUNT(*) FROM orders WHERE service_id = ? AND client_id = ? AND status = "completed"');
$stmt->execute([$service_id, $session->getId()]);
if ($stmt->fetchColumn() == 0) die('Not allowed.');

// Verifica se já comentou
$stmt = $db->prepare('SELECT COUNT(*) FROM ratings WHERE service_id = ? AND client_id = ?');
$stmt->execute([$service_id, $session->getId()]);
if ($stmt->fetchColumn() > 0) die('Already reviewed.');

// Inserir review
$stmt = $db->prepare('INSERT INTO ratings (service_id, client_id, rating, comment) VALUES (?, ?, ?, ?)');
$stmt->execute([$service_id, $session->getId(), $rating, $comment]);

header('Location: ../pages/service.php?id=' . $service_id);
exit;