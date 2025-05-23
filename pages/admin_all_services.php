<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/Services.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

$session = new Session();
$db = getDatabaseConnection();

if (!$session->isLoggedIn()) {
    $_SESSION['error'] = 'You must be logged in to access this page.';
    header('Location: ../pages/login.php');
    exit();
}

// Verify admin role
$stmt = $db->prepare('SELECT role FROM users WHERE id = ?');
$stmt->execute([$session->getId()]);
$role = $stmt->fetchColumn();

if ($role !== 'admin') {
    $_SESSION['error'] = 'Access denied. Admins only.';
    header('Location: ../pages/loged_in.php');
    exit();
}

// Fetch all services
$stmt = $db->prepare('
    SELECT services.*, users.username 
    FROM services 
    JOIN users ON services.user_id = users.id
    ORDER BY created_at DESC
');
$stmt->execute();
$services = $stmt->fetchAll();

drawHeader($session, '', '../css/admin_all_services.css');
?>

<div class="homepage-container" style="max-width: 900px; margin: 40px auto;">
    <h1>All Services</h1>
    <script src="/javascript/script.js" defer></script>

    <?php if (empty($services)): ?>
        <p>No services available.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="padding: 10px; text-align: left;">Title</th>
                    <th style="padding: 10px; text-align: left;">Freelancer</th>
                    <th style="padding: 10px; text-align: left;">Price</th>
                    <th style="padding: 10px; text-align: left;">Created At</th>
                    <th style="padding: 10px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px;"><?= htmlspecialchars($service['title']) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($service['username']) ?></td>
                        <td style="padding: 10px;">$<?= number_format($service['price'], 2) ?></td>
                        <td style="padding: 10px;"><?= htmlspecialchars($service['created_at']) ?></td>
                        <td style="padding: 10px;">
                            <form action="../actions/action_delete_service.php" method="post" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                                <button type="submit" style="padding: 6px 10px; background: #c00; color: white; border: none; border-radius: 4px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div style="margin-top: 20px;">
        <a href="loged_in.php" style="text-decoration: none; color: #007bff;">← Back to Dashboard</a>
    </div>
</div>

<?php drawFooter(); ?>
