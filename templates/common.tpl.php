<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../database/User.class.php');
?>

<?php function drawHeader(Session $session, string $bodyClass = '', string $extraCss = '') { ?>
<!DOCTYPE html>
<html lang="en-US">
  <head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="/javascript/script.js" defer></script>
    <title>Freelancerz</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Global styles -->
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/dropdown.css">
    <link rel="stylesheet" href="../css/forms.css">
    <link rel="stylesheet" href="../css/homepage.css">
    
    <!-- Optional extra styles -->
    <?php if (!empty($extraCss)): ?>
      <link rel="stylesheet" href="<?= htmlspecialchars($extraCss) ?>">
    <?php endif; ?>

    <!-- Icons and scripts -->
    <link rel="stylesheet" href="../css/style.css">
        <script src="../javascript/script.js" defer></script>
  </head>
  <body<?= $bodyClass ? ' class="' . htmlspecialchars($bodyClass) . '"' : '' ?>>
    <div class="page-container">
      <header>
        <h1><a href="/pages/loged_in.php">Freelancerz</a></h1>
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
          <article class="<?= htmlspecialchars($message['type']) ?>">
            <?= htmlspecialchars($message['text']) ?>
          </article>
        <?php } ?>
      </section>

      <main>
<?php } ?>


<?php function drawFooter() { ?>
    </main>
    <footer>
      Freelancerz &copy; 2025
    </footer>
    </div> <!-- closes .page-container -->
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
    $user = User::getUser(getDatabaseConnection(), $session->getId());
?>

<div class="profile-menu-container">
  <?php if ($user->hasProfilePicture()): ?>
    <img src="/uploads/profile/<?= htmlspecialchars($user->getProfilePicture()) ?>" 
         alt="Profile Picture" 
         class="profile-avatar"
         id="profile-avatar">
  <?php else: ?>
    <div class="profile-avatar <?= getRandomColorClass() ?>" id="profile-avatar">
        <?= htmlspecialchars(strtoupper(substr($user->getName() ?? 'U', 0, 1))) ?>
    </div>
  <?php endif; ?>

  <div id="dropdown-menu" class="dropdown-menu hidden">
  <form action="../pages/profile.php" method="get">
    <button type="submit"><i class="fa fa-user"></i> Profile</button>
  </form>
  <form action="../pages/my_orders.php" method="get">
    <button type="submit"><i class="fa fa-list"></i> My Orders</button>
  </form>
  <form action="../pages/my_services.php" method="get">
    <button type="submit"><i class="fas fa-briefcase"></i> My Services</button>
  </form>
  <form action="../pages/messages.php" method="get">
    <button type="submit"><i class="fa fa-envelope"></i> Messages</button>
  </form>

  <?php if ($user->isAdmin()): ?>
    <hr>
    <form action="../pages/admin_all_services.php" method="get">
      <button type="submit"><i class="fas fa-tools"></i> All Services</button>
    </form>
    <form action="../pages/admin_all_users.php" method="get">
      <button type="submit"><i class="fas fa-users"></i> All Users</button>
    </form>
    <form action="../pages/admin_all_categories.php" method="get">
      <button type="submit"><i class="fas fa-folder-open"></i> All Categories</button>
    </form>
  <?php endif; ?>

  <form action="../actions/action_logout.php" method="post" style="margin:0;">
    <button type="submit" style="width:100%;text-align:left;"><i class="fa fa-door-open"></i> Logout</button>
  </form>
</div>


<?php } ?>