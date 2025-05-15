<?php
require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/db.php');

$serviceId = $_POST['service_id'];
$clientId = $session->getId();

$db = getDatabaseConnection();
$stmt = $db->prepare('
  INSERT INTO orders (service_id, client_id)
  VALUES (?, ?)
');
$stmt->execute([$serviceId, $clientId]);

$session->addMessage('success', 'Service ordered successfully!');
header('Location: ../pages/loged_in.php');
