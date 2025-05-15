<?php
require_once('../database/Services.class.php');
require_once('../database/db.php');
$db = getDatabaseConnection();

$id = $_GET['id'] ?? null;
if (!$id) die('Service not found.');

$service = Service::getById($db, $id);
if (!$service) die('Service not found.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($service->getTitle()) ?> - Service Details</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <div class="homepage-container" style="max-width: 700px; margin: 40px auto;">
    <h1 style="font-size:2.2em; margin-bottom: 10px;"><?= htmlspecialchars($service->getTitle()) ?></h1>
    <div style="margin-bottom: 18px;">
      <strong>Info:</strong> <?= htmlspecialchars($service->getDescription()) ?>
    </div>
    <div style="margin-bottom: 18px;">
      <strong>Anunciante:</strong> <?= htmlspecialchars($service->getUsername()) ?>
    </div>
    <div style="margin-bottom: 18px;">
      <strong>Preço:</strong> $<?= number_format($service->getPrice(), 2) ?>
    </div>
    <!-- Comentários -->
    <div style="margin-top: 40px;">
      <h2 style="font-size:1.3em;">Comentários</h2>
      <form method="post" style="margin-bottom: 20px;">
        <textarea name="comment" rows="3" style="width:100%; border-radius:6px; border:1px solid #ccc; padding:8px;" placeholder="Escreve o teu comentário..."></textarea>
        <button type="submit" class="search-button" style="margin-top:8px;">Enviar</button>
      </form>
      <!-- Aqui podes listar comentários reais se implementares -->
      <div style="color:#888;">(Os comentários aparecem aqui...)</div>
    </div>
  </div>
</body>
</html>