<?php
declare(strict_types=1);

class Category {
    private $id;
    private $name;

    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }

    public static function getAllCategories(PDO $db): array {
       
    $stmt = $db->query('SELECT id, name FROM categories ORDER BY name');
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return as simple array

}

    public static function getById(PDO $db, int $id): ?Category {
        $stmt = $db->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        $category = $stmt->fetch();
        
        return $category ? new Category(
            (int)$category['id'],
            $category['name']
        ) : null;
    }
}