<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/db.php');

$db = getDatabaseConnection();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $name = trim($_POST['name'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm_password'] ?? '';

 
  if (empty($email) || empty($username) || empty($password) || empty($confirm) || empty($name)) {
    $errors[] = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
  } elseif ($password !== $confirm) {
    $errors[] = "Passwords do not match.";
  }

  
  $stmt = $db->prepare('SELECT id FROM users WHERE username = ?');
  $stmt->execute([$username]);
  if ($stmt->fetch()) {
    $errors[] = "Username already taken.";
  }

  if (empty($errors)) {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare('
      INSERT INTO users (username, password, email, name, role)
      VALUES (?, ?, ?, ?, ?)
    ');

    try {
      $stmt->execute([$username, $hash, $email, $name, 'user']);
      $session->addMessage('success', 'Registration successful! You can now login.');
      header('Location: index.php');
      exit();
    } catch (PDOException $e) {
      $errors[] = "Database error: " . $e->getMessage();
    }
  }
}

drawHeader($session);
?>

<div class="register-heading">
  <h2>Create your Freelancerz account</h2>
  <p>Already have an account? <a href="index.php" class="login-link">Login</a></p>
</div>
<?php if (!empty($errors)): ?>
  <ul class="error-messages">
    <?php foreach ($errors as $error): ?>
      <li><?=htmlspecialchars($error)?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="post" action="register.php" class="register-form">
  <label>
    Full Name:
    <input type="text" name="name" required>
  </label>

  <label>
    Username:
    <input type="text" name="username" required>
  </label>

  <label>
    Email:
    <input type="email" name="email" required>
  </label>

  <label>
    Password:
    <input type="password" name="password" required>
  </label>

  <label>
    Confirm Password:
    <input type="password" name="confirm_password" required>
  </label>

  <button type="submit">Register</button>
</form>

