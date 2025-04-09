<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'models/Post.php';
require_once 'config/database.php';
class PostController
{
    private $postModel;
    private $uploadDir;

    public function __construct()
    {
        $db = new Database();
        $this->postModel = new Post($db);
        $this->uploadDir = realpath(__DIR__ . '/../assets/blogImage/') . '/';
    }
    public function deletePost()
    {
        $id = $_POST['id'] ?? null;
        $user_id = $_SESSION['user_id'] ?? null;

        if ($id && $user_id) {
            require_once 'models/Post.php';
            require_once 'config/database.php';
            $db = new Database();
            $postModel = new Post($db);
            $result = $postModel->deletePost($id, $user_id);
            if ($result) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Post not deleted']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        }
    }
    public function createPost()
    {
        header('Content-Type: application/json');
        $response = ["status" => "error", "message" => "Initial error"];
        try {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("User not logged in");
            }
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Invalid request method");
            }
            if (empty($_FILES['blog_image']['tmp_name'])) {
                throw new Exception("Please select an image");
            }
            $image = $_FILES['blog_image'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($image['tmp_name']);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($mime, $allowedTypes)) {
                throw new Exception("Only JPG/PNG images allowed");
            }
            if ($image['size'] > 2 * 1024 * 1024) {
                throw new Exception("Image too large (max 2MB)");
            }
            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $destination = $this->uploadDir . $filename;

            if (!move_uploaded_file($image['tmp_name'], $destination)) {
                throw new Exception("Failed to save image");
            }
            $description = htmlspecialchars(trim($_POST['description'] ?? ''), ENT_QUOTES, 'UTF-8');
            if (empty($description)) {
                throw new Exception("Description cannot be empty");
            }
            $user_id = $_SESSION['user_id'];
            $webPath = 'assets/blogImage/' . $filename;

            if ($this->postModel->create($user_id, $webPath, $description)) {
                $response = [
                    "status" => "success",
                    "message" => "Post created!",
                    "post" => [
                        "image" => $webPath,
                        "description" => $description,
                        "user_id" => $user_id,
                        "user_profile_picture" => 'assets/profilePicture/' . ($_SESSION['user_profile_picture'] ?? 'default.png'),
                        "user_full_name" => $_SESSION['full_name'] ?? 'User'
                    ]
                ];
            } else 
            {
                throw new Exception("Database error while saving post");
            }
        } 
        catch (Exception $e)
        {
            $response["message"] = $e->getMessage();
            error_log("Post error: " . $e->getMessage());
        }
        ob_clean();
        echo json_encode($response);
        exit();
    }
}
