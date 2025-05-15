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
$chatId = (int) ($_GET['chat_id'] ?? 0);

// Validate that user is part of the chat
$stmt = $db->prepare("SELECT * FROM chats WHERE id = ? AND (client_id = ? OR freelancer_id = ?)");
$stmt->execute([$chatId, $userId, $userId]);
$chat = $stmt->fetch();
if (!$chat) {
  die('Chat not found or access denied.');
}

$db->prepare("
  UPDATE messages 
  SET is_read = 1 
  WHERE chat_id = ? 
    AND sender_id != ?
")->execute([$chatId, $userId]);

// Handle new message post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
  $content = trim($_POST['content']);
  if (!empty($content)) {
    $stmt = $db->prepare("INSERT INTO messages (chat_id, sender_id, content) VALUES (?, ?, ?)");
    $stmt->execute([$chatId, $userId, $content]);
  }
  header("Location: chat_window.php?chat_id=$chatId");
  exit;
}

// Fetch messages
$stmt = $db->prepare("
  SELECT messages.*, users.username 
  FROM messages 
  JOIN users ON messages.sender_id = users.id 
  WHERE messages.chat_id = ? 
  ORDER BY messages.sent_at ASC
");
$stmt->execute([$chatId]);
$messages = $stmt->fetchAll();

drawHeader($session);
?>

<h2>Chat</h2>
<div style="max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px;">
  <div style="max-height: 400px; overflow-y: auto; margin-bottom: 20px;">
    <?php foreach ($messages as $msg): ?>
    <div style="margin-bottom: 10px;">
        <strong style="color: <?= $msg['sender_id'] == $userId ? '#007bff' : '#333' ?>;">
        <?= $msg['sender_id'] == $userId ? 'You' : htmlspecialchars($msg['username']) ?>:
        </strong>
        <?= htmlspecialchars($msg['content']) ?>
        <div style="font-size: 0.8em; color: #999;">
        <?= $msg['sent_at'] ?>
        </div>
    </div>
<?php endforeach; ?>
  </div>

  <form method="post" style="display: flex; gap: 10px;">
    <input type="text" name="content" placeholder="Type your message..." required style="flex-grow: 1; padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
    <button type="submit" style="padding: 10px 16px; background-color: #0e7a57; color: white; border: none; border-radius: 6px; cursor: pointer;">Send</button>
  </form>
</div>

<?php drawFooter(); ?>
