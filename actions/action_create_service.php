<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if (!$session->isLoggedIn()) die(header('Location: /'));

require_once(__DIR__ . '/../database/db.php');
require_once(__DIR__ . '/../database/Service.class.php');

$db = getDatabaseConnection();

// Validate and process form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceData = [
        'title' => trim($_POST['title']),
        'description' => trim($_POST['description']),
        'price' => (float)$_POST['price'],
        'delivery_time' => (int)$_POST['delivery_time'],
        'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null
    ];

    // Create the service
    $service = Service::create($db, $session->getId(), $serviceData);
    
    if ($service) {
        // Handle image uploads
        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../uploads/services/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
                if ($_FILES['images']['error'][$index] === UPLOAD_ERR_OK) {
                    $fileName = uniqid('img_') . '.' . pathinfo($_FILES['images']['name'][$index], PATHINFO_EXTENSION);
                    $filePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($tmpName, $filePath)) {
                        $isPrimary = ($index === 0); // First image is primary
                        Service::addImage($db, $service->getId(), 'uploads/services/' . $fileName, $isPrimary);
                    }
                }
            }
        }

        // Handle video upload
        if (!empty($_FILES['video']['name'])) {
            $uploadDir = __DIR__ . '/../uploads/videos/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if ($_FILES['video']['error'] === UPLOAD_ERR_OK) {
                $fileName = uniqid('vid_') . '.' . pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['video']['tmp_name'], $filePath)) {
                    Service::addVideo($db, $service->getId(), 'uploads/videos/' . $fileName);
                }
            }
        }

        $session->addMessage('success', 'Service created successfully!');
        header('Location: ../pages/manage_services.php');
        exit();
    }
}

$session->addMessage('error', 'Failed to create service');
header('Location: ../pages/create_service.php');