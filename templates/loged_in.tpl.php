<?php function drawHomepage(array $services, array $categories) { ?>
<link rel="stylesheet" href="../css/homepage.css">
<script src="../javascript/homepage.js" defer></script>

<div class="homepage-container">
  <div class="search-filter-bar">
    <input type="text" placeholder="What are you looking for today?" class="search-input">
    <button class="search-button"><i class="fa fa-search"></i></button>

    <select class="filter-dropdown" name="category">
      <option value="">Category</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <select class="filter-dropdown" name="price">
      <option value="">Price</option>
      <option value="asc">Low to High</option>
      <option value="desc">High to Low</option>
    </select>

    <select class="filter-dropdown" name="rating">
      <option value="">Rating</option>
      <option value="5">★★★★★</option>
      <option value="4">★★★★☆</option>
      <option value="3">★★★☆☆</option>
    </select>
  </div>

  <div class="service-scroll-wrapper">
    <div class="service-scroll" id="scroll-container">
      <?php foreach ($services as $s): ?>
        <div class="service-card">
        <img src="<?= htmlspecialchars($s->getThumbnailPath()) ?>" class="service-thumb">
        <div class="service-meta">
            <p><strong><?= htmlspecialchars($s->getUsername()) ?></strong></p>
            <p><?= htmlspecialchars($s->getTitle()) ?></p>
            <p>★ <?= $s->getRating() ?>/5</p>
            <p>$<?= number_format($s->getPrice(), 2) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="scroll-arrow" onclick="scrollRight()">→</div>
  </div>
</div>
<?php } ?>
