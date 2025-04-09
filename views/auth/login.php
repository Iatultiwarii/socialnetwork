<?php 
require_once 'config/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <form id="loginForm" action="index.php?route=login" method="post">
            <h2>Login</h2>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <span id="emailError" class="error-message"></span>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>
            <span id="passwordError" class="error-message"></span>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="index.php?route=signup">Create New Account</a></p>
        </form>
    </div>
</body>
</html>