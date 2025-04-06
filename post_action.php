<?php
session_start();
require_once 'config/database.php';
require_once 'controllers/PostController.php';

$response = ["status" => "error", "message" => "Invalid request"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_GET['action']) && $_GET['action'] == 'create_post') {
        $postController = new PostController();
        $postController->createPost(); // This should echo proper JSON and exit
        exit;
    }
}

echo json_encode($response);
