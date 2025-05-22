<?php declare(strict_types = 1); ?>

<?php function drawProfileForm(User $user) { ?>
  <div class="profile-container">
    <div class="profile-header">
      <h1>My Profile</h1>
      <script src="/javascript/script.js" defer></script>

    </div>
    
    <div class="profile-content">
      <div class="profile-picture-section">
        <div class="profile-picture-container">
          <?php if ($user->hasProfilePicture()): ?>
            <img src="../uploads/profile/<?=htmlspecialchars($user->getProfilePicture())?>" alt="Profile Picture" class="profile-picture">
          <?php else: ?>
            <div class="profile-picture-initial">
              <?= htmlspecialchars(strtoupper(substr($user->getName() ?? 'U', 0, 1))) ?>
            </div>
          <?php endif; ?>
        </div>
        <form action="../actions/action_upload_picture.php" method="post" enctype="multipart/form-data" class="upload-form">
          <label for="profile-pic-upload" class="upload-label">
            <i class="fas fa-camera"></i> Change Photo
          </label>
          <input type="file" id="profile-pic-upload" name="profile_pic" accept="image/*" style="display: none;">
          <button type="submit" class="upload-button">Upload</button>
        </form>
      </div>

      <div class="profile-details">
        <form action="../actions/action_edit_profile.php" method="post" class="profile-form">
          <div class="form-group">
            <label for="name">Full Name</label>
            <input id="name" type="text" name="name" value="<?=htmlspecialchars($user->getName() ?? '')?>">
             </div>
              <div class="form-group">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="<?=htmlspecialchars($user->getEmail())?>">
          </div>
          
          <div class="form-group">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" value="<?=htmlspecialchars($user->getUsername())?>">
          </div>
          
          <div class="form-actions">
            <button type="submit" name="submit_profile" class="save-button">Save Changes</button>
          </div>
        
          
          <!-- Rest of your form fields -->
        </form>
      </div>
    </div>
  </div>
<?php } ?>