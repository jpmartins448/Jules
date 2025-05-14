<?php declare(strict_types = 1); ?>

<?php function drawProfileForm(User $user) { ?>
<h1>Profile</h1>

<form action="../actions/action_edit_profile.php" method="post" class="profile">

  <label for="name">  Name:</label>
  <input id="name" type="text" name="name" value="<?=$user->name?>">
  
  <button type="submit">Save</button>
</form>
<?php } ?>