<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

$session = new Session();
if (!$session->isLoggedIn()) {
  header('Location: ../pages/index.php');
  exit;
}

$db = getDatabaseConnection();
$userId = $session->getId();

$stmt = $db->prepare("
  SELECT id, client_id, freelancer_id FROM chats
  WHERE client_id = ? OR freelancer_id = ?
  ORDER BY created_at DESC
");
$stmt->execute([$userId, $userId]);
$chats = $stmt->fetchAll();

drawHeader($session);
?>

<h1>Your Messages</h1>
<ul>
  <?php foreach ($chats as $chat): 
    $otherUserId = ($chat['client_id'] == $userId) ? $chat['freelancer_id'] : $chat['client_id'];
  ?>
    <li>
      <a href="chat_window.php?chat_id=<?= $chat['id'] ?>">
        Chat with User #<?= $otherUserId ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>

<?php drawFooter(); ?>
