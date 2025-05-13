<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: /'));

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/customer.class.php');

  $db = getDatabaseConnection();

  $user = User::getUser($db, $session->getId());

  if ($user) {
    $user->name = $_POST['name'];
    $user->save($db);

    $session->setName($user->name);
  }

  header('Location: ../pages/profile.php');
?>