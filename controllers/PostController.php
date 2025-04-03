<?php
require_once 'models/Post.php';

class PostController {
    private $postModel;
    private $uploadDir;

    public function __construct() {
        $db = new Database();
        $this->postModel = new Post($db);
        $this->uploadDir = realpath(__DIR__ . '/../assets/blogImage/') . '/';
        $this->verifyUploadDirectory();
    }
    private function verifyUploadDirectory() {
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
        if (!is_writable($this->uploadDir)) {
            error_log("Upload directory not writable: " . $this->uploadDir);
            throw new Exception("Upload directory not writable");
        }
    }
    public function createPost() {
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
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
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
                        "user_id" => $user_id
                    ]
                ];
            } else {
                throw new Exception("Database error");
            }

        } catch (Exception $e) {
            $response["message"] = $e->getMessage();
            error_log("Post error: " . $e->getMessage());
        }

        echo json_encode($response);
        exit();
    }
}
?>