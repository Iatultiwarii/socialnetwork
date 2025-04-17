<?php
require_once 'models/User.php';
require_once 'models/Post.php';

class DashboardController {
    private $userModel;
    private $postModel;
    private $likeModel;
        public function __construct() {
        $db = new Database();
        $this->userModel = new User($db);
        $this->postModel = new Post($db);
       
    }
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?route=login");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($user_id);
        $posts = $this->postModel->getPostsByUser($user_id);

        require 'views/posts/dashboard.php';
    }
}
?>