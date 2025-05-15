<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/Services.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

$session = new Session();
$db = getDatabaseConnection();

$id = $_GET['id'] ?? null;
if (!$id) die('Service not found.');
$service = Service::getById($db, (int)$id);
if (!$service) die('Service not found.');

// This already opens <html>, <head>, <body> and <main>!
drawHeader($session);
?>

<div class="service-detail-container">
  <div class="left-column">
    <img src="https://picsum.photos/150?freelancer=<?= $service->getUserId() ?>" class="freelancer-pic">
    <p class="freelancer-name"><?= htmlspecialchars($service->getUsername()) ?></p>
  </div>

  <div class="center-column">
    <h1><?= htmlspecialchars($service->getTitle()) ?></h1>
    <p><?= nl2br(htmlspecialchars($service->getDescription())) ?></p>
  </div>

  <div class="right-column">
    <p><strong>Delivery Time:</strong> <?= $service->getDeliveryTime() ?> days</p>
    <p><strong>Price:</strong> $<?= number_format($service->getPrice(), 2) ?></p>

    <form action="../actions/action_create_order.php" method="post">
      <input type="hidden" name="service_id" value="<?= $service->getId() ?>">
      <button type="submit" class="order-btn">Order</button>
    </form>

    <form action="../pages/chat.php" method="get">
      <input type="hidden" name="freelancer_id" value="<?= $service->getUserId() ?>">
      <button type="submit" class="message-btn">Send Message</button>
    </form>
  </div>
</div>

<?php drawFooter(); ?>
