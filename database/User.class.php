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
}
