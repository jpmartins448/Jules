<?php

class User {
    protected $id;
    protected $username;
    protected $email;
    protected $name;
    protected $profile_picture; // Assuming you have this column in your database
    protected $role; // 'user' or 'admin'

    public function __construct($id, $username, $email, $name,$profile_picture, $role = 'user') {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->name = $name;
        $this->profile_picture = $profile_picture; // Assuming you have this column in your database
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

    public function setEmail(PDO $db, string $newEmail): bool {
        $this->email = htmlspecialchars(trim($newEmail));
        $stmt = $db->prepare('UPDATE users SET email = ? WHERE id = ?');
        return $stmt->execute([$this->email, $this->id]);
    }

    public function setUsername(PDO $db, string $newUserName): bool {
        $this->username = htmlspecialchars(trim($newUserName));
        $stmt = $db->prepare('UPDATE users SET username = ? WHERE id = ?');
        return $stmt->execute([$this->username, $this->id]);
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
              $user['profile_picture'],
              $user['role']
          );
      } else {
          return null;
      }
  }
  
  static function getUser(PDO $db, int $id): User {
    $stmt = $db->prepare('
        SELECT id, username, email, name, profile_picture,role
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
        $user['profile_picture'], // Assuming you have this column in your database
        $user['role']
    );
}
  public function getProfilePicture(): ?string {
    return $this->profile_picture; // Assuming you have this column in your database
}

public function hasProfilePicture(): bool {
    return !empty($this->profile_picture);
}

public function setProfilePicture(PDO $db, string $filename): bool {
   try {
        $stmt = $db->prepare('UPDATE users SET profile_picture = ? WHERE id = ?');
        $result = $stmt->execute([$filename, $this->id]);
        
        if ($result && $stmt->rowCount() > 0) {
            $this->profile_picture = $filename;
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Database error in setProfilePicture: " . $e->getMessage());
        return false;
    }
}
    }

