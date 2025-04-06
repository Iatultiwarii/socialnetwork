<?php
require_once 'models/User.php';

class AuthController
{
    private $userModel;
    private $uploadDir = __DIR__ . '/../assets/profilePicture/';
    public function __construct()
    {
        $db = new Database();
        $this->userModel = new User($db);
        $this->verifyUploadDirectory();
    }
    private function verifyUploadDirectory()
    {
        if (!is_dir($this->uploadDir)) {
            throw new Exception("Upload directory does not exist");
        }
        if (!is_writable($this->uploadDir)) {
            throw new Exception("Upload directory is not writable");
        }
    }
    public function login()
    {
        $error = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            if (empty($email) || empty($password)) {
                $error = "Both fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
            } else {
                $user = $this->userModel->login($email, $password);
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $email;
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['profile_picture'] = $user['profile_picture'];
                    header("Location: index.php?route=dashboard");
                    exit();
                } else {
                    $error = "Invalid email or password.";
                }
            }
        }
        require_once 'views/auth/login.php';
    }
    public function register()
    {
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = trim($_POST['fullname']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            $dob = trim($_POST['dob']);
            $profilePic = $_FILES['profile_picture'];
            if (
                empty($fullname) || empty($email) || empty($password) ||
                empty($confirm_password) || empty($dob) || empty($profilePic['name'])
            ) {
                $error = "All fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
            } elseif ($password !== $confirm_password) {
                $error = "Passwords do not match.";
            } elseif (strlen($password) < 6) {
                $error = "Password must be at least 6 characters.";
            } else {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                $fileType = mime_content_type($profilePic['tmp_name']);

                if (!in_array($fileType, $allowedTypes)) {
                    $error = "Invalid profile picture format. Only JPG, JPEG, PNG allowed.";
                } elseif ($profilePic['size'] > 2 * 1024 * 1024) {
                    $error = "Profile picture size must be less than 2MB.";
                } else {
                    $newFileName = uniqid() . "_" . basename($profilePic['name']);
                    $uploadPath = $this->uploadDir . $newFileName;

                    if (move_uploaded_file($profilePic['tmp_name'], $uploadPath)) {
                        $webPath = 'assets/profilePicture/' . $newFileName;

                        if ($this->userModel->register($fullname, $email, $password, $dob, $webPath)) {
                            $_SESSION['success'] = "Registration successful. Please login.";
                            header("Location: index.php?route=login");
                            exit();
                        } else {
                            $error = "Registration failed. Please try again.";
                        }
                    } else {
                        $error = "Failed to upload profile picture.";
                    }
                }
            }
        }
        require_once 'views/auth/register.php';
    }
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php?route=login");
        exit();
    }
}
?>