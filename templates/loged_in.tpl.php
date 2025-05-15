<?php
function drawHomepage(array $services, array $categories, $category = '', $sort = '', $rating = '', $search = '') { ?>
<link rel="stylesheet" href="../css/homepage.css">
<script src="../javascript/homepage.js" defer></script>

<div class="homepage-container">
  <form method="get" class="search-filter-bar">
    <input type="text" name="search" placeholder="What are you looking for today?" class="search-input" value="<?= htmlspecialchars($search ?? '') ?>">
    <button type="submit" class="search-button"><i class="fa fa-search"></i></button>

    <select class="filter-dropdown" name="category">
      <option value="">Category</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= $cat['id'] ?>" <?= ($category == $cat['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select class="filter-dropdown" name="sort">
      <option value="">Price</option>
      <option value="price_asc" <?= ($sort == 'price_asc') ? 'selected' : '' ?>>Low to High</option>
      <option value="price_desc" <?= ($sort == 'price_desc') ? 'selected' : '' ?>>High to Low</option>
    </select>

    <select class="filter-dropdown" name="rating">
      <option value="">Rating</option>
      <option value="5" <?= ($rating == '5') ? 'selected' : '' ?>>★★★★★</option>
      <option value="4" <?= ($rating == '4') ? 'selected' : '' ?>>★★★★☆</option>
      <option value="3" <?= ($rating == '3') ? 'selected' : '' ?>>★★★☆☆</option>
    </select>
  </form>

  <div class="service-scroll-wrapper">
    <div class="service-scroll" id="scroll-container">
      <?php if (empty($services)): ?>
        <div style="text-align:center; margin: 60px 0; font-size: 1.3em; color: #888;">
          Can't find something along your preferences
        </div>
      <?php else: ?>
        <?php foreach ($services as $s): ?>
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
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="scroll-arrow" onclick="scrollRight()">→</div>
  </div>
</div>
<?php } ?>
