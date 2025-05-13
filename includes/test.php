<?php
require_once(__DIR__ . '/../includes/db.php'); // correct relative path
try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "✅ Connected successfully! Number of users in DB: " . $result['total'];
} catch (PDOException $e) {
    echo "❌ Query failed: " . $e->getMessage();
}
?>