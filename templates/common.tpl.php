<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>

<?php function drawHeader(Session $session) { ?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
    <title>Freelancerz </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="../javascript/script.js" defer></script>
  </head>
  <body>

    <header>
      <h1><a href="/">Freelancerz</a></h1>
      <?php 
         $page = basename($_SERVER['PHP_SELF']);
         if ($session->isLoggedIn()) {
           drawUserMenu($session);
         } elseif ($page !== 'register.php') {
           drawLoginForm($session);
         }
      ?>
    </header>
  
    <section id="messages">
  <?php foreach ($session->getMessages() as $message) { ?>
    <article class="<?=htmlspecialchars($message['type'])?>">
      <?=htmlspecialchars($message['text'])?>
    </article>
  <?php } ?>
</section>

    <main>
<?php } ?>

<?php function drawFooter() { ?>
    </main>

    <footer>
      Frelancerz &copy; 2025
    </footer>
  </body>
</html>
<?php } ?>

<?php function drawLoginForm() { ?>
  <form action="../actions/action_login.php" method="post" class="auth-form">
    <input type="email" name="email" placeholder="email">
    <input type="password" name="password" placeholder="password">
    <a href="../pages/register.php">Register</a>
    <button type="submit">Login</button>
  </form>
<?php } ?>

<?php function drawLogoutForm(Session $session) { ?>
  <form action="../actions/action_logout.php" method="post" class="auth-form">
  <a href="../pages/profile.php" class="username"><?=htmlspecialchars($session->getName())?></a>
    <button type="submit">Logout</button>
  </form>
<?php } ?>

<?php
function getInitial($name) {
    return strtoupper($name[0]);
}

function getRandomColorClass() {
    $colors = ['avatar-blue', 'avatar-red', 'avatar-yellow', 'avatar-purple', 'avatar-orange'];
    return $colors[array_rand($colors)];
}
?>

<?php function drawUserMenu(Session $session) { 
  $initial = htmlspecialchars(getInitial($session->getName()));
  $colorClass = getRandomColorClass();
?>

<div class="profile-menu-container">
  <div class="profile-avatar <?= $colorClass ?>" id="profile-avatar">
    <?= $initial ?>
  </div>
  <div id="dropdown-menu" class="dropdown-menu hidden">
    <a href="../pages/profile.php"><i class="fa fa-cog"></i> Settings</a>
    <form action="../actions/action_logout.php" method="post">
      <button type="submit"><i class="fa fa-door-open"></i> Logout</button>
    </form>
  </div>
</div>

<?php } ?>
