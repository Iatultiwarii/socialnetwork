<?php
session_start();
require_once 'config/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
$route = isset($_GET['route']) ? $_GET['route'] : 'login';

try {
    switch ($route) {
        case 'login':
            require 'controllers/AuthController.php';
            $auth = new AuthController();
            $auth->login();
            break;
        case 'signup':
            require 'controllers/AuthController.php';
            $auth = new AuthController();
            $auth->register();
            break;
        case 'dashboard':
            require 'controllers/DashboardController.php';
            $dashboard = new DashboardController();
            $dashboard->index();
            break;
        case 'logout':
            require 'controllers/AuthController.php';
            $auth = new AuthController();
            $auth->logout();
            break;
        case 'post_action':
            require_once 'controllers/PostController.php';
            $postController = new PostController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'create_post') {
                $postController->createPost();
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid post action"]);
            }
            break;
            case 'update_profile':
                require_once 'controllers/AuthController.php';
                $authController = new AuthController();
                $authController->updateProfile();
                break;
                
                
        case 'delete_post':
            require_once 'controllers/PostController.php';
            $postController = new PostController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $postId = $_POST['id'];
                $userId = $_SESSION['user']['id'];
                $postController->deletePost($postId, $userId);
            }
            exit;
            case 'like_post':
                require_once 'controllers/PostController.php';
                $postController = new PostController();
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                    $postId = $_POST['id'];
                    $userId = $_SESSION['user']['id'];
                    $postController->likePost($postId);
                }
                exit;
        default:
            require 'controllers/AuthController.php';
            $auth = new AuthController();
            $auth->login();
            break;
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>