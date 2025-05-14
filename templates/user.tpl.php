<?php declare(strict_types = 1); ?>

<?php function drawProfileForm(User $user) { ?>
  <h1>Profile</h1>

  <form action="../actions/action_edit_profile.php" method="post" class="profile">
    <label for="name">Name:</label>
    <input id="name" type="text" name="name" value="<?=htmlspecialchars($user->getName())?>">
    <button type="submit" name="submit_name">Save</button>
  </form>

  <form action="../actions/action_edit_profile.php" method="post" class="profile">
    <label for="email">Email:</label>
    <input id="email" type="email" name="email" value="<?=htmlspecialchars($user->getEmail())?>">
    <button type="submit" name="submit_email">Save</button>
  </form>

  <form action="../actions/action_edit_profile.php" method="post" class="profile">
    <label for="username">Username:</label>
    <input id="username" type="username" name="username" value="<?=htmlspecialchars($user->getUsername())?>">
    <button type="submit" name="submit_username">Save</button>
  </form>
<?php } ?>
