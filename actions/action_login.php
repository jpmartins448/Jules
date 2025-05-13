<?php
  declare(strict_types = 1);


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

echo "START"; // test marker

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/db.php');
  require_once(__DIR__ . '/../database/User.class.php');

  $db = getDatabaseConnection();

  $user = User::getUserWithPassword($db, $_POST['email'], $_POST['password']);

  if ($user) {
    $session->setId($user->getId());
    $session->setName($user->getName());
    $session->addMessage('success', 'Login successful!');
    header('Location: ../pages/profile.php');
  } else {
    $session->addMessage('error', 'Wrong password!');
    header('Location: ../pages/index.php');
  }

  ?>