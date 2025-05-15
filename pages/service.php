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

$images = Service::getServiceImages($db, $service->getId());
$videos = Service::getServiceVideos($db, $service->getId());

drawHeader($session);
?>

<!-- Lightbox -->
<div id="lightbox" class="lightbox">
  <span class="close-btn">&times;</span>
  <div class="lightbox-inner">
    <img id="lightbox-img" src="" alt="Full Image">
  </div>
</div>

<div class="service-detail-container">
  <div class="left-column">
    <img src="https://picsum.photos/150?freelancer=<?= $service->getUserId() ?>" class="freelancer-pic">
    <p class="freelancer-name"><?= htmlspecialchars($service->getUsername()) ?></p>
  </div>

  <div class="center-column">
    <h1><?= htmlspecialchars($service->getTitle()) ?></h1>
    <p><?= nl2br(htmlspecialchars($service->getDescription())) ?></p>

    <?php if (!empty($images)): ?>
  <h3>Images</h3>
  <div class="horizontal-scroll image-gallery">
  <?php foreach ($images as $index => $img): ?>
  <img 
    src="/<?= htmlspecialchars($img) ?>" 
    alt="Service image" 
    class="gallery-thumbnail" 
    data-index="<?= $index ?>">
<?php endforeach; ?>  
  </div>
<?php endif; ?>

    <?php if (!empty($videos)): ?>
      <h3>Videos</h3>
      <div class="video-player">
        <video controls>
          <source src="/<?= htmlspecialchars($videos[0]) ?>" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    <?php endif; ?>
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
