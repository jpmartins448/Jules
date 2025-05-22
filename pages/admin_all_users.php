<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/User.class.php');

$session = new Session();
$db = getDatabaseConnection();
$user = User::getUser($db, $session->getId());

if (!$user->isAdmin()) {
    die('Unauthorized access');
}

// Fetch all users
$stmt = $db->prepare('SELECT id, username, email, name, role FROM users ORDER BY id ASC');
$stmt->execute();
$users = $stmt->fetchAll();

drawHeader($session, '', '../css/admin_all_users.css');
?>

<div class="admin-panel">
  <h2>All Users</h2>
  <script src="/javascript/script.js" defer></script>

  <table class="admin-table">
    <thead>
      <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Name</th>
        <th>Role</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['name'] ?? '-') ?></td>
          <td><?= htmlspecialchars($u['role']) ?></td>
          <td>
            <?php if ($u['role'] !== 'admin'): ?>
              <form method="post" action="../actions/action_promote_user.php" style="margin:0;">
                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                <button type="submit" class="btn btn-small btn-success">Promote to Admin</button>
              </form>
            <?php else: ?>
              <span class="badge badge-admin">Admin</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php drawFooter(); ?>
