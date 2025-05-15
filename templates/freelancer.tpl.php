<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../database/Freelancer.class.php');
?>

<?php function drawFreelancers(array $artists, array $categories = [], $selectedCategory = '', $selectedSort = '', $selectedRating = '', $search = '') { ?>
  <header>
    <h2>Freelancers</h2>
    <form method="get">
      <input id="searchartist" type="text" name="search" placeholder="search" value="<?=htmlspecialchars($search)?>">
      <select name="category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?=htmlspecialchars($category)?>" <?= $selectedCategory === $category ? 'selected' : '' ?>><?=htmlspecialchars($category)?></option>
        <?php endforeach; ?>
      </select>
      <select name="sort">
        <option value="">Sort By</option>
        <option value="price_asc" <?= $selectedSort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="price_desc" <?= $selectedSort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
      </select>
      <select name="rating">
        <option value="">Any Rating</option>
        <option value="5" <?= $selectedRating === '5' ? 'selected' : '' ?>>5 Stars</option>
        <option value="4" <?= $selectedRating === '4' ? 'selected' : '' ?>>4 Stars & Up</option>
        <option value="3" <?= $selectedRating === '3' ? 'selected' : '' ?>>3 Stars & Up</option>
      </select>
      <button type="submit">Search</button>
    </form>
  </header>
  <section id="freelancers">
    <?php foreach($artists as $artist) { 
      // Usa o rating real do artista, se existir. Ajuste para o nome correto da propriedade/método!
      $rating = isset($artist->rating) ? (int)$artist->rating : 0;
      if ($selectedRating !== '' && $rating != (int)$selectedRating) continue;
    ?> 
      <article>
        <img src="https://picsum.photos/200?<?=$artist->id?>">
        <a href="../pages/artist.php?id=<?=$artist->id?>"><?=$artist->name?></a>
        <p>
          <?= str_repeat('★', $rating) . str_repeat('☆', 5 - $rating) ?>
        </p>
      </article>
    <?php } ?>
  </section>
<?php } ?>

<?php function drawArtist(Artist $artist, array $albums) { ?>
  <h2><?=$artist->name?></h2>
  <section id="albums">
    <?php foreach ($albums as $album) { ?>
    <article>
      <img src="https://picsum.photos/200?<?=$album->id?>">
      <a href="../pages/album.php?id=<?=$album->id?>"><?=$album->title?></a>
      <p class="info"><?=$album->tracks?> tracks / <?=$album->length?> min</p>
    </article>
    <?php } ?>
  </section>
<?php } ?>