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

drawHeader($session);
?>

<div class="homepage-container" style="max-width: 800px; margin: 40px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
  <h1 style="margin-bottom: 30px; color: #333;">My Orders</h1>
  
  <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
    <h3 style="margin: 0; color: #555;">Order Summary</h3>
    <div style="font-size: 1.2em; font-weight: bold;">
      Total Spent: <span style="color: #0e7a57;">$<?= number_format($total, 2) ?></span>
    </div>
  </div>

  <?php if (empty($orders)): ?>
    <div style="text-align: center; padding: 40px 20px; background: #f8f9fa; border-radius: 8px;">
      <p style="font-size: 1.1em; color: #666;">You have no orders yet.</p>
      <a href="../pages/services.php" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background: #0e7a57; color: white; text-decoration: none; border-radius: 6px;">Browse Services</a>
    </div>
  <?php else: ?>
    <div style="display: grid; gap: 15px;">
      <?php foreach ($orders as $order): ?>
        <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 8px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='none'">
          <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <h3 style="margin: 0; color: #333;"><?= htmlspecialchars($order['title']) ?></h3>
            <span style="font-weight: bold; color: #0e7a57;">$<?= number_format($order['price'], 2) ?></span>
          </div>
          
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <div>
              <span style="color: #666;">Ordered on <?= date('M j, Y', strtotime($order['created_at'])) ?></span>
              <span style="margin: 0 10px; color: #ddd;">|</span>
              <span style="color: <?= $order['status'] === 'completed' ? '#28a745' : '#ffc107' ?>;">
                <?= ucfirst($order['status']) ?>
              </span>
            </div>
            <div style="display: flex; gap: 10px;">
              <a href="../pages/chat_window.php?chat_id=<?= getOrCreateChatId($db, $userId, $order['freelancer_id']) ?>" 
                 style="padding: 8px 15px; background: #17a2b8; color: white; border-radius: 6px; text-decoration: none; display: flex; align-items: center;">
                <i class="fas fa-envelope" style="margin-right: 8px;"></i> Contact
              </a>
            </div>
          </div>
          
          <div style="border-top: 1px dashed #eee; padding-top: 15px; color: #666;">
            <div style="display: flex; align-items: center;">
              <img src="https://picsum.photos/40?user=<?= $order['freelancer_id'] ?>" 
                   style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
              <span>Freelancer: <?= htmlspecialchars($order['freelancer_username']) ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php drawFooter(); ?>