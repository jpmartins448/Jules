<?php
declare(strict_types=1);

class Service {
    private $id;
    private $userId;
    private $categoryId;
    private $title;
    private $description;
    private $price;
    private $deliveryTime;
    private $createdAt;

    protected string $username = 'unknown';
    protected string $thumbnailPath = 'uploads/images/default.png';
    protected int $rating = 4;

    public function __construct(
        int $id,
        int $userId,
        ?int $categoryId,
        string $title,
        string $description,
        float $price,
        int $deliveryTime,
        string $createdAt
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->categoryId = $categoryId;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->deliveryTime = $deliveryTime;
        $this->createdAt = $createdAt;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getCategoryId(): ?int { return $this->categoryId; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): string { return $this->description; }
    public function getPrice(): float { return $this->price; }
    public function getDeliveryTime(): int { return $this->deliveryTime; }
    public function getCreatedAt(): string { return $this->createdAt; }

    // Create new service
    public static function create(PDO $db, int $userId, array $data): ?Service {
        $stmt = $db->prepare('
            INSERT INTO services (user_id, category_id, title, description, price, delivery_time)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        
        $success = $stmt->execute([
            $userId,
            $data['category_id'] ?? null,
            $data['title'],
            $data['description'],
            $data['price'],
            $data['delivery_time']
        ]);

        if ($success) {
            $serviceId = $db->lastInsertId();
            return self::getById($db, (int)$serviceId);
        }
        return null;
    }

    // Get service by ID
    public static function getById(PDO $db, int $id): ?Service {
        $stmt = $db->prepare('SELECT * FROM services WHERE id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        
        return $data ? new Service(
            (int)$data['id'],
            (int)$data['user_id'],
            $data['category_id'] ? (int)$data['category_id'] : null,
            $data['title'],
            $data['description'],
            (float)$data['price'],
            (int)$data['delivery_time'],
            $data['created_at']
        ) : null;
    }

    // Add image to service
    public static function addImage(PDO $db, int $serviceId, string $imagePath, bool $isPrimary = false): bool {
        $stmt = $db->prepare('
            INSERT INTO service_images (service_id, image_path, is_primary)
            VALUES (?, ?, ?)
        ');
        return $stmt->execute([$serviceId, $imagePath, (int)$isPrimary]);
    }

    // Add video to service
    public static function addVideo(PDO $db, int $serviceId, string $videoPath): bool {
        $stmt = $db->prepare('
            INSERT INTO service_videos (service_id, video_path)
            VALUES (?, ?)
        ');
        return $stmt->execute([$serviceId, $videoPath]);
    }

    public static function getAll(PDO $db): array {
        $stmt = $db->query('
            SELECT services.*, users.username, si.image_path AS primary_image
            FROM services
            JOIN users ON services.user_id = users.id
            LEFT JOIN service_images si ON services.id = si.service_id AND si.is_primary = 1
            ORDER BY services.created_at DESC
        ');
    
        $services = [];
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $service = new Service(
                (int)$row['id'],
                (int)$row['user_id'],
                $row['category_id'] ? (int)$row['category_id'] : null,
                $row['title'],
                $row['description'],
                (float)$row['price'],
                (int)$row['delivery_time'],
                $row['created_at']
            );
    
            $service->username = $row['username'];
            $service->thumbnailPath = $row['primary_image'] ?? 'uploads/images/default.png';
            $service->rating = rand(3, 5); // placeholder
    
            $services[] = $service;
        }
    
        return $services;
    }
    
    
    public function getUsername(): string {
        return $this->username ?? 'unknown';
    }
    
    public function getThumbnailPath(): string {
        return '/' . ($this->thumbnailPath ?? 'uploads/images/default.png');
        }
    
    public function getRating(): int {
        return $this->rating ?? 4;
    }

    public function setThumbnailPath(PDO $db, string $path): void {
        // Set all existing images for this service as non-primary
        $stmt = $db->prepare('
            UPDATE service_images
            SET is_primary = 0
            WHERE service_id = ?
        ');
        $stmt->execute([$this->id]);
    
        // Then either update the existing image to primary or insert it if missing
        $stmt = $db->prepare('
            INSERT INTO service_images (service_id, image_path, is_primary)
            VALUES (?, ?, 1)
            ON CONFLICT(service_id, image_path) DO UPDATE SET is_primary = 1
        ');
        $stmt->execute([$this->id, $path]);
    
        // Update the object in memory too
        $this->thumbnailPath = $path;
    }
    
    
    
}