<?php
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/Services.class.php');

$db = getDatabaseConnection();

$search   = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort     = $_GET['sort'] ?? '';
$rating   = $_GET['rating'] ?? '';

$services = Service::search($db, $search, $category, $sort, $rating);

foreach ($services as $s): ?>
  <a href="../pages/service.php?id=<?= $s->getId() ?>" target="_blank" style="text-decoration:none; color:inherit;">
  <script src="/javascript/script.js" defer></script>
    <div class="service-card">
      <img src="<?= htmlspecialchars($s->getThumbnailPath()) ?>" class="service-thumb">
      <div class="service-meta">
        <p><strong><?= htmlspecialchars($s->getUsername()) ?></strong></p>
        <p><?= htmlspecialchars($s->getTitle()) ?></p>
        <p>
          <?php
            $stars = str_repeat('<span class="star">★</span>', $s->getRating()) .
                     str_repeat('<span class="star-empty">☆</span>', 5 - $s->getRating());
            echo $stars;
          ?>
        </p>
        <p>$<?= number_format($s->getPrice(), 2) ?></p>
      </div>
    </div>
  </a>
<?php endforeach; ?>
