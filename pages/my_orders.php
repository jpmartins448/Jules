<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/Services.class.php');

$session = new Session();
$db = getDatabaseConnection();
$userId = $session->getId();

$stmt = $db->prepare('
    SELECT orders.*, services.title, services.price
    FROM orders
    JOIN services ON orders.service_id = services.id
    WHERE orders.client_id = ?
    ORDER BY orders.created_at DESC
');
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();

$total = 0;
foreach ($orders as $order) {
    $total += $order['price'];
}

drawHeader($session);
?>

<div class="homepage-container" style="max-width:700px; margin:40px auto;">
  <h1>My Orders</h1>
  <div style="text-align:right; font-weight:bold; margin-bottom:15px;">
    Total Spent: $<?= number_format($total, 2) ?>
  </div>
  <?php if (empty($orders)): ?>
    <p>You have no orders yet.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($orders as $order): ?>
        <li>
          <?= htmlspecialchars($order['title']) ?> - <?= htmlspecialchars($order['created_at']) ?>
          <span style="float:right;">$<?= number_format($order['price'], 2) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php
drawFooter();
?>