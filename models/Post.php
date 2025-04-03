<?php
class Post {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($user_id, $image, $description) {
        $sql = "INSERT INTO posts (user_id, image, description) VALUES (?, ?, ?)";
        return $this->db->execute($sql, [$user_id, $image, $description], "iss");
    }

    public function getPostsByUser($user_id) {
        $sql = "SELECT p.*, u.full_name, u.profile_picture FROM posts p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.user_id = ? 
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql, [$user_id], "i");
    }

    public function getAllPosts() {
        $sql = "SELECT p.*, u.full_name, u.profile_picture FROM posts p 
                JOIN users u ON p.user_id = u.id 
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function deletePost($post_id, $user_id) {
        $sql = "DELETE FROM posts WHERE id = ? AND user_id = ?";
        return $this->db->execute($sql, [$post_id, $user_id], "ii");
    }
}
?>