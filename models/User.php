<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($fullname, $email, $password, $dob, $profile_picture) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (full_name, email, password, dob, profile_picture) VALUES (?, ?, ?, ?, ?)";
        return $this->db->execute($sql, [$fullname, $email, $hashed_password, $dob, $profile_picture], "sssss");
    }

    public function login($email, $password) {
        $sql = "SELECT id, full_name, email, password, profile_picture FROM users WHERE email = ?";
        $user = $this->db->fetchSingle($sql, [$email], "s");
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getUserById($id) {
        $sql = "SELECT id, full_name, email, profile_picture FROM users WHERE id = ?";
        return $this->db->fetchSingle($sql, [$id], "i");
    }

    public function emailExists($email) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $result = $this->db->fetchSingle($sql, [$email], "s");
        return $result !== null;
    }
}
?>