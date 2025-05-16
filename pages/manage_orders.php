<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/Services.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

function getOrCreateChatId(PDO $db, int $clientId, int $freelancerId): int {
    // Check if chat already exists
    $stmt = $db->prepare("SELECT id FROM chats WHERE client_id = ? AND freelancer_id = ?");
    $stmt->execute([$clientId, $freelancerId]);
    $chatId = $stmt->fetchColumn();
    
    if (!$chatId) {
        // Create new chat if it doesn't exist
        $stmt = $db->prepare("INSERT INTO chats (client_id, freelancer_id) VALUES (?, ?)");
        $stmt->execute([$clientId, $freelancerId]);
        $chatId = $db->lastInsertId();
    }
    
    return $chatId;
}

$session = new Session();
if (!$session->isLoggedIn()) {
    header('Location: ../pages/login.php');
    exit();
}

$db = getDatabaseConnection();
$service_id = $_GET['service_id'] ?? null;

if (!$service_id) {
    die('Service ID not provided.');
}

// Verify the service belongs to the logged-in user
$service = Service::getById($db, (int)$service_id);
if (!$service || $service->getUserId() !== $session->getId()) {
    die('Invalid service or unauthorized access.');
}

// Get all orders for this service
$orders = Order::getOrdersByService($db, (int)$service_id);

drawHeader($session);
?>

<div class="homepage-container" style="max-width: 800px; margin: 40px auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Manage Orders for: <?= htmlspecialchars($service->getTitle()) ?></h1>
        <form action="../actions/action_delete_service.php" method="post" onsubmit="return confirm('Are you sure you want to delete this service? This will also delete all its orders.');">
            <input type="hidden" name="service_id" value="<?= $service->getId() ?>">
            <button type="submit" style="padding: 8px 16px; background: #d33; color: white; border: none; border-radius: 4px;">
                Delete Service
            </button>
        </form>
    </div>
    
    <?php if (empty($orders)): ?>
        <p>No orders for this service yet.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Client</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Order Date</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Status</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #ddd;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 12px;"><?= htmlspecialchars($order['client_username']) ?></td>
                        <td style="padding: 12px;"><?= htmlspecialchars($order['created_at']) ?></td>
                        <td style="padding: 12px;">
                            <?php if ($order['status'] === 'completed'): ?>
                                <span style="color: green;">Completed</span>
                            <?php else: ?>
                                <span style="color: orange;">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px; display: flex; gap: 8px;">
                            <a href="../pages/chat_window.php?chat_id=<?= getOrCreateChatId($db, $order['client_id'], $session->getId()) ?>" 
                               style="padding: 6px 12px; background: #17a2b8; color: white; border: none; border-radius: 4px; text-decoration: none;">
                                Contact Client
                            </a>
                            <?php if ($order['status'] !== 'completed'): ?>
                                <form action="../actions/action_complete_order.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <input type="hidden" name="service_id" value="<?= $service_id ?>">
                                    <button type="submit" style="padding: 6px 12px; background: #28a745; color: white; border: none; border-radius: 4px;">
                                        Complete
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <div style="margin-top: 20px;">
        <a href="../pages/my_services.php" style="padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">
            Back to My Services
        </a>
    </div>
</div>

<?php drawFooter(); ?>