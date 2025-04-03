<?php
session_start();
require_once 'config/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set up error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

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
            
        default:
            header("HTTP/1.0 404 Not Found");
            require 'views/errors/404.php';
            break;
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>