<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/db.php');
  require_once(__DIR__ . '/../database/User.class.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  

  $db = getDatabaseConnection();


  drawHeader($session);
  drawFooter();
?>