<?php


declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/Services.class.php');
require_once(__DIR__ . '/../database/User.class.php');
require_once(__DIR__ . '/../database/Category.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/loged_in.tpl.php');

$db = getDatabaseConnection();

// Ler filtros do GET
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? '';
$rating = $_GET['rating'] ?? '';

// Buscar categorias
$categories = Category::getAllCategories($db);

// Buscar serviços filtrados (você precisa implementar este método na classe Service)
$services = Service::search($db, $search, $category, $sort, $rating);

drawHeader($session, 'homepage', '../css/services.css');
drawHomepage($services, $categories, $category, $sort, $rating, $search);
drawFooter();
?>
