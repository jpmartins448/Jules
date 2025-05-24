<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/Services.class.php');

function getOrCreateChatId(PDO $db, int $clientId, int $freelancerId): int {
    $stmt = $db->prepare("SELECT id FROM chats WHERE client_id = ? AND freelancer_id = ?");
    $stmt->execute([$clientId, $freelancerId]);
    $chatId = $stmt->fetchColumn();
    
    if (!$chatId) {
        $stmt = $db->prepare("INSERT INTO chats (client_id, freelancer_id) VALUES (?, ?)");
        $stmt->execute([$clientId, $freelancerId]);
        $chatId = $db->lastInsertId();
    }
    return $chatId;
}

$session = new Session();
$db = getDatabaseConnection();
$userId = $session->getId();

$stmt = $db->prepare('
    SELECT orders.*, 
           services.title, 
           services.price,
           services.user_id as freelancer_id,
           freelancer.username as freelancer_username
    FROM orders
    JOIN services ON orders.service_id = services.id
    JOIN users AS freelancer ON services.user_id = freelancer.id
    WHERE orders.client_id = ?
    ORDER BY orders.created_at DESC
');
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();

$total = 0;
foreach ($orders as $order) {
    $total += $order['price'];
}

drawHeader($session, '', '../css/orders.css');
?>

<div class="orders-list">
  <h1>My Orders</h1>
  <div class="order-summary">
    <h3>Order Summary</h3>
    <script src="/javascript/script.js" defer></script>

    <div>Total Spent: <span>$<?= number_format($total, 2) ?></span></div>
  </div>
  <?php if (empty($orders)): ?>
    <div class="no-orders">
      <p>You have no orders yet.</p>
      <a href="../pages/services.php" class="browse-services-btn">Browse Services</a>
    </div>
  <?php else: ?>
    <?php foreach ($orders as $order): 
    $alreadyReviewedStmt = $db->prepare('SELECT COUNT(*) FROM ratings WHERE service_id = ? AND client_id = ?');
    $alreadyReviewedStmt->execute([$order['service_id'], $userId]);
    $alreadyReviewed = $alreadyReviewedStmt->fetchColumn() > 0;
    ?>
      <div class="order-card">
        <div class="order-info">
          <h3><?= htmlspecialchars($order['title']) ?></h3>
          <span class="order-price">$<?= number_format($order['price'], 2) ?></span>
          <div>
            <span>Ordered on <?= date('M j, Y', strtotime($order['created_at'])) ?></span>
            <span class="order-status <?= $order['status'] === 'completed' ? 'completed' : 'pending' ?>">
              <?= ucfirst($order['status']) ?>
            </span>
          </div>
          <div>
            <img src="https://picsum.photos/40?user=<?= $order['freelancer_id'] ?>" class="order-freelancer-pic">
            <span>Freelancer: <?= htmlspecialchars($order['freelancer_username']) ?></span>
          </div>
        </div>
        <div class="order-actions">
          <a href="../pages/chat_window.php?chat_id=<?= getOrCreateChatId($db, $userId, $order['freelancer_id']) ?>" class="contact-btn">
            <i class="fas fa-envelope"></i> Contact
          </a>
        <?php if ($order['status'] === 'completed' && !$alreadyReviewed): ?>
        <div class="add-review" id="add-review">
          <a href="../pages/service.php?id=<?= $order['service_id'] ?>#add-review" class="review-btn">
            <i class="fas fa-star"></i> Leave Review
          </a>
        </div>
  <?php endif; ?>
</div>

      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php drawFooter(); ?>