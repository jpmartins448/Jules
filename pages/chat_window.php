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

// Validate that user is part of the chat and get chat details
$stmt = $db->prepare("
    SELECT chats.*, 
           client.username AS client_username,
           freelancer.username AS freelancer_username
    FROM chats
    JOIN users AS client ON chats.client_id = client.id
    JOIN users AS freelancer ON chats.freelancer_id = freelancer.id
    WHERE chats.id = ? AND (chats.client_id = ? OR chats.freelancer_id = ?)
");
$stmt->execute([$chatId, $userId, $userId]);
$chat = $stmt->fetch();

if (!$chat) {
  die('Chat not found or access denied.');
}

// Mark messages as read when opening chat
$db->prepare("
  UPDATE messages 
  SET is_read = 1 
  WHERE chat_id = ? 
    AND sender_id != ?
")->execute([$chatId, $userId]);

// Handle new message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
  $content = trim($_POST['content']);
  if (!empty($content)) {
    $stmt = $db->prepare("INSERT INTO messages (chat_id, sender_id, content) VALUES (?, ?, ?)");
    $stmt->execute([$chatId, $userId, $content]);
    
    // Mark as unread for recipient
    $recipientId = ($chat['client_id'] == $userId) ? $chat['freelancer_id'] : $chat['client_id'];
    $db->prepare("UPDATE messages SET is_read = 0 WHERE chat_id = ? AND sender_id = ? ORDER BY id DESC LIMIT 1")
       ->execute([$chatId, $userId]);
  }
  header("Location: chat_window.php?chat_id=$chatId");
  exit;
}

// Get messages
$stmt = $db->prepare("
  SELECT messages.*, users.username 
  FROM messages 
  JOIN users ON messages.sender_id = users.id 
  WHERE messages.chat_id = ? 
  ORDER BY messages.sent_at ASC
");
$stmt->execute([$chatId]);
$messages = $stmt->fetchAll();

// Determine who we're chatting with
$otherUser = ($userId == $chat['client_id']) ? $chat['freelancer_username'] : $chat['client_username'];

drawHeader($session);
?>

<div style="max-width: 800px; margin: 20px auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
  <div style="padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
    <h2 style="margin: 0;">Chat with <?= htmlspecialchars($otherUser) ?></h2>
    <script src="/javascript/script.js" defer></script>

    <a href="../pages/messages.php" style="color: #666; text-decoration: none;">← Back to messages</a>
  </div>
  
  <div id="messages-container" style="height: 500px; overflow-y: auto; padding: 20px;">
    <?php foreach ($messages as $msg): ?>
      <div style="margin-bottom: 15px; display: flex; flex-direction: column; align-items: <?= $msg['sender_id'] == $userId ? 'flex-end' : 'flex-start' ?>;">
        <div style="font-weight: bold; color: <?= $msg['sender_id'] == $userId ? '#007bff' : '#333' ?>;">
          <?= $msg['sender_id'] == $userId ? 'You' : htmlspecialchars($msg['username']) ?>
        </div>
        <div style="background: <?= $msg['sender_id'] == $userId ? '#e3f2fd' : '#f5f5f5' ?>; 
                  padding: 10px 15px; 
                  border-radius: 18px; 
                  max-width: 70%; 
                  word-wrap: break-word;">
          <?= htmlspecialchars($msg['content']) ?>
        </div>
        <div style="font-size: 0.8em; color: #999; margin-top: 4px;">
          <?= date('M j, g:i a', strtotime($msg['sent_at'])) ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  
  <div style="padding: 15px 20px; border-top: 1px solid #eee;">
    <form method="post" style="display: flex; gap: 10px;">
      <input type="text" 
             name="content" 
             placeholder="Type your message..." 
             required 
             style="flex-grow: 1; 
                    padding: 12px 15px; 
                    border: 1px solid #ddd; 
                    border-radius: 24px; 
                    outline: none;">
      <button type="submit" 
              style="padding: 12px 20px; 
                     background-color: #0e7a57; 
                     color: white; 
                     border: none; 
                     border-radius: 24px; 
                     cursor: pointer;">
        Send
      </button>
    </form>
  </div>
</div>

<script>
// Auto-scroll to bottom of messages
window.onload = function() {
  const container = document.getElementById('messages-container');
  container.scrollTop = container.scrollHeight;
};
</script>

<?php drawFooter(); ?>