<?php
  declare(strict_types = 1);
  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) {
    header('Location: ../pages/index.php');
    exit;
  }

  require_once(__DIR__ . '/../templates/common.tpl.php');
  drawHeader($session);
?>

<div class="welcome-section">
  <h2>Welcome back, <?=htmlspecialchars($session->getName())?>!</h2>
</div>

<?php drawFooter(); ?>
