<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Join Social Network</h2>

        <form action="index.php?route=signup" method="post" enctype="multipart/form-data">
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <div class="profile-picture-container">
                <img id="profileImage" class="profile-picture" src="../assets/images/default-profile.png" alt="Profile Picture">
                <label for="profile_picture" class="upload-btn">Upload Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
            </div>

            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required>
            <span id="fullnameError" class="error-message"></span>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <span id="emailError" class="error-message"></span>

            <div class="row">
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                    <span id="passwordError" class="error-message"></span>
                </div>

                <div class="input-group">
                    <label for="confirm_password">Retype Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                    <span id="confirmPasswordError" class="error-message"></span>
                </div>
            </div>

            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" required>
            <span id="dobError" class="error-message"></span>

            <button type="submit">Sign Up</button>
            <p>Already have an account? <a href="index.php?route=login">Login</a></p>
        </form>
    </div>

    <script>
        document.getElementById("profile_picture").addEventListener("change", function (event) {
            let file = event.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById("profileImage").src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>