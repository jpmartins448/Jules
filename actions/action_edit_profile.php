<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: /'));

  require_once(__DIR__ . '/../database/db.php');
  require_once(__DIR__ . '/../database/User.class.php');

  $db = getDatabaseConnection();

  $user = User::getUser($db, $session->getId());

  if (isset($_POST['submit_name'])) {
    $user->setName($db, $_POST['name']);
    $session->setName($user->getName());
}

  if (isset($_POST['submit_email'])) {
    $user->setEmail($db, $_POST['email']);
}
  if(isset($_POST['submit_username'])){
    $user->setUsername($db, $_POST['username']);
  }

  header('Location: ../pages/profile.php');
?>