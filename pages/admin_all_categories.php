<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

$session = new Session();
$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    header('Location: ../pages/login.php');
    exit;
}

$stmt = $db->prepare('SELECT role FROM users WHERE id = ?');
$stmt->execute([$session->getId()]);
$role = $stmt->fetchColumn();

if ($role !== 'admin') {
    die('Access denied: Admins only.');
}

// Handle category addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $name = trim($_POST['category_name']);
    if (!empty($name)) {
        $stmt = $db->prepare('INSERT OR IGNORE INTO categories (name) VALUES (?)');
        $stmt->execute([$name]);
        header('Location: admin_all_categories.php');
        exit;
    }
}

// Get all categories
$stmt = $db->query('SELECT * FROM categories ORDER BY name ASC');
$categories = $stmt->fetchAll();

drawHeader($session, '', '../css/admin_all_categories.css');
?>

<div class="admin-page" style="max-width: 600px; margin: 40px auto;">
    <h2>All Categories</h2>

    <form method="POST" style="margin-bottom: 20px;">
        <input type="text" name="category_name" placeholder="New category name" required style="padding: 8px;">
        <button type="submit" style="padding: 8px 12px;">Add Category</button>
    </form>

    <ul style="list-style: disc; padding-left: 20px;">
        <?php foreach ($categories as $cat): ?>
            <li><?= htmlspecialchars($cat['name']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<?php drawFooter(); ?>
