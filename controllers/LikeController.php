<?php
require_once '../models/Like.php';

class LikeController {
    private $likeModel;

    public function __construct() {
        $db = new Database();
        $this->likeModel = new Like($db);
    }

    public function toggleLike() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $response = ["status" => "error", "message" => "Something went wrong."];

            if (!isset($_SESSION['user_id'])) {
                $response["message"] = "User not logged in.";
                echo json_encode($response);
                exit();
            }

            $user_id = $_SESSION['user_id'];
            $post_id = $_POST['post_id'];

            $action = $this->likeModel->toggleLike($user_id, $post_id);
            $like_count = $this->likeModel->getLikeCount($post_id);

            $response = [
                "status" => "success",
                "action" => $action,
                "like_count" => $like_count
            ];

            echo json_encode($response);
            exit();
        }
    }
}
?>