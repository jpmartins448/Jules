<?php
require_once('User.php');

class Admin extends User {

    public function __construct($id, $username, $email, $name) {
        parent::__construct($id, $username, $email, $name, 'admin');
    }

    public function canEditUsers() {
        return true;
    }

    public function canAccessAdminPanel() {
        return true;
    }
}
