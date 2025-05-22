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

// In the SQL query:
$stmt = $db->prepare("
    SELECT 
        chats.id,
        chats.client_id,
        chats.freelancer_id,
        users.username AS other_username,
        (
            SELECT COUNT(*) 
            FROM messages 
            WHERE messages.chat_id = chats.id 
                AND messages.sender_id != ? 
                AND messages.is_read = 0
        ) AS unread_count
    FROM chats
    JOIN users ON users.id = 
        CASE 
            WHEN chats.client_id = ? THEN chats.freelancer_id 
            ELSE chats.client_id 
        END
    WHERE chats.client_id = ? OR chats.freelancer_id = ?
    ORDER BY chats.created_at DESC
");
$stmt->execute([$userId, $userId, $userId, $userId]);
$chats = $stmt->fetchAll();


drawHeader($session);
?>

<div class="messages-container">
  <h1>Your Messages</h1>
  <script src="/javascript/script.js" defer></script>

  <?php if (empty($chats)): ?>
    <p>No messages yet.</p>
  <?php else: ?>
    <div class="chat-list">
      <?php foreach ($chats as $chat): ?>
        <a href="chat_window.php?chat_id=<?= $chat['id'] ?>" class="chat-card">
        <div class="chat-avatar"><?= strtoupper($chat['other_username'][0]) ?></div>
        <div class="chat-info">
            <div class="chat-username"><?= htmlspecialchars($chat['other_username']) ?></div>
            <div class="chat-preview">Click to open conversation</div>
        </div>
  <?php if ((int)$chat['unread_count'] > 0): ?>
            <div class="unread-dot"></div>
            <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php drawFooter(); ?>
