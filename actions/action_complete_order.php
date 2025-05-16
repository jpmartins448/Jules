<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    header('Location: ../pages/login.php');
    exit();
}

$db = getDatabaseConnection();

$order_id = $_POST['order_id'] ?? null;
if (!$order_id) {
    $_SESSION['error'] = 'Order ID not provided.';
    header('Location: ../pages/manage_orders.php?service_id=' . ($_POST['service_id'] ?? ''));
    exit();
}

// Verify the order belongs to a service owned by the logged-in user
$stmt = $db->prepare('
    SELECT services.user_id 
    FROM orders
    JOIN services ON orders.service_id = services.id
    WHERE orders.id = ?
');
$stmt->execute([$order_id]);
$service_owner = $stmt->fetchColumn();

if ($service_owner !== $session->getId()) {
    $_SESSION['error'] = 'Unauthorized action.';
    header('Location: ../pages/manage_orders.php?service_id=' . ($_POST['service_id'] ?? ''));
    exit();
}

// Mark order as completed
$stmt = $db->prepare('UPDATE orders SET status = "completed" WHERE id = ?');
$stmt->execute([$order_id]);

$_SESSION['success'] = 'Order marked as completed successfully.';
header('Location: ../pages/manage_orders.php?service_id=' . ($_POST['service_id'] ?? ''));
exit();
?>