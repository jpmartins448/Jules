<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: /'));

  require_once(__DIR__ . '/../database/db.php');
  require_once(__DIR__ . '/../database/User.class.php');

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../templates/user.tpl.php');

  $db = getDatabaseConnection();

  $user = User::getUser($db, $session->getId());
  
  drawHeader($session, 'profile-page');
  drawProfileForm($user);
  drawFooter();
?>