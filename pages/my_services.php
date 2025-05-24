<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/Services.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

$session = new Session();
if (!$session->isLoggedIn()) {
  header('Location: index.php');
  exit();
}

$db = getDatabaseConnection();
$userId = $session->getId();
$services = Service::getByUser($db, $userId);

drawHeader($session, 'my-services-page', '../css/services.css');
?>

<h2 style="text-align:center;">My Services</h2>
<script src="/javascript/script.js" defer></script>
<div style="display:flex; flex-wrap:wrap; justify-content:center; gap:20px;">
  <?php foreach ($services as $service): ?>
    <div class="service-card">
      <h3><?= htmlspecialchars($service->getTitle()) ?></h3>

      <p><?= htmlspecialchars($service->getDescription()) ?></p>
      <p><strong>Price:</strong> $<?= $service->getPrice() ?></p>
      <form action="manage_orders.php" method="get">
        <input type="hidden" name="service_id" value="<?= $service->getId() ?>">
        <button type="submit" class="order-btn">Manage</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>
<a href="../pages/addservice.php" class="floating-add-button">
  <i class="fa fa-plus"></i> Add a Service
</a>

<?php drawFooter(); ?>
