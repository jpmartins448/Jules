<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/User.class.php');
require_once(__DIR__ . '/../database/Category.class.php'); // You'll need to create this

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/service.adder.tpl.php'); // New template file for services

$db = getDatabaseConnection();

// Get current user
$user = User::getUser($db, $session->getId());

// Get all categories for dropdown
$categories = Category::getAllCategories($db); // Implement this method in Category.class.php
error_log(print_r($categories, true));

drawHeader($session);
drawServiceForm($categories); 
drawFooter();
?>