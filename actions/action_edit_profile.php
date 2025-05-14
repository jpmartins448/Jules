<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: /'));

  require_once(__DIR__ . '/../database/db.php');
  require_once(__DIR__ . '/../database/User.class.php');

  $db = getDatabaseConnection();

  $user = User::getUser($db, $session->getId());

  if ($user && isset($_POST['name'])) {
    $user->setName($db, $_POST['name']);
    $session->setName($user->getName());        
  }
  

  header('Location: ../pages/profile.php');
?>