<?php
class Like {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function toggleLike($user_id, $post_id) {
        $sql = "SELECT id FROM likes WHERE user_id = ? AND post_id = ?";
        $existing = $this->db->fetchSingle($sql, [$user_id, $post_id], "ii");
        
        if ($existing) {
            $sql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
            $this->db->execute($sql, [$user_id, $post_id], "ii");
            return 'unliked';
        } else {
            $sql = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
            $this->db->execute($sql, [$user_id, $post_id], "ii");
            return 'liked';
        }
    }

    public function getLikeCount($post_id) {
        $sql = "SELECT COUNT(*) as count FROM likes WHERE post_id = ?";
        $result = $this->db->fetchSingle($sql, [$post_id], "i");
        return $result['count'];
    }

    public function userLikedPost($user_id, $post_id) {
        $sql = "SELECT id FROM likes WHERE user_id = ? AND post_id = ?";
        $result = $this->db->fetchSingle($sql, [$user_id, $post_id], "ii");
        return $result !== null;
    }
}
?>