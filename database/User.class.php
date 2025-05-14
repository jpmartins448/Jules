<?php

class User {
    protected $id;
    protected $username;
    protected $email;
    protected $name;
    protected $role; // 'user' or 'admin'

    public function __construct($id, $username, $email, $name, $role = 'user') {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->name = $name;
        $this->role = $role;
    }

    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function isFreelancer() {
        return $this->role === 'freelancer';
    }

    public function getName() {
        return $this->name;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getId() {
        return $this->id;
    }

    public function getRole() {
        return $this->role;
    }

    public function setName(PDO $db, string $newName): bool {
        $this->name = htmlspecialchars(trim($newName));
        $stmt = $db->prepare('UPDATE users SET name = ? WHERE id = ?');
        return $stmt->execute([$this->name, $this->id]);
    }
    

    static function getUserWithPassword(PDO $db, string $email, string $password): ?User {
      $stmt = $db->prepare('
          SELECT * FROM users
          WHERE lower(email) = ?
      ');
      $stmt->execute([strtolower($email)]);
      $user = $stmt->fetch();
  
      if ($user && password_verify($password, $user['password'])) {
          return new User(
              $user['id'],
              $user['username'],
              $user['email'],
              $user['name'],
              $user['role']
          );
      } else {
          return null;
      }
  }
  
  static function getUser(PDO $db, int $id): User {
    $stmt = $db->prepare('
        SELECT id, username, email, name, role
        FROM users
        WHERE id = ?
    ');

    $stmt->execute([$id]);
    $user = $stmt->fetch();

    return new User(
        $user['id'],
        $user['username'],
        $user['email'],
        $user['name'],
        $user['role']
    );
}
  
    }

