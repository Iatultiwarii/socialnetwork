<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboardstyles.css">
</head>
<body>
    <div class="dashboard">
        <div class="left-panel">
            <h2>Profile</h2>
            <img src="../assets/profilePicture/<?php echo htmlspecialchars($user['profile_picture'] ?? 'default.png'); ?>" alt="Profile Image" class="profile-img">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name'] ?? 'User'); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <a href="index.php?route=logout" class="logout-btn">Logout</a>
        </div>

        <div class="right-panel">
            <div class="post-form">
                <h2>Create a Post</h2>
                <form<form id="post_form" method="post" enctype="multipart/form-data" action="post_action.php">
                    <input type="file" name="blog_image" accept="image/*" required>
                    <textarea name="description" placeholder="Write something..." required></textarea>
                    <button type="submit" id="post_btn">Post</button>
                </form>
            </div>

            <div class="posts">
                <h2>Previous Posts</h2>
                <div id="my_content_post">
                    <?php foreach ($posts as $row): ?>
                        <div class="post">
                            <div class="post-header">
                                <img src="../assets/profilePicture/<?php echo htmlspecialchars($user['profile_picture'] ?? 'default-profile.png'); ?>" alt="User Image" class="user-profile-img">
                                <span class="post-user"><?php echo htmlspecialchars($row['description']); ?></span>
                            </div>
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Post Image" class="post-img">
                            <small>Posted on <?php echo htmlspecialchars($row['created_at']); ?></small>
                            <div class="post-actions">
                                <button class="like-btn" data-post-id="<?php echo $row['id']; ?>">👍 Like</button>
                                <span id="like-count-<?php echo $row['id']; ?>">0</span>
                                <button class="dislike-btn" data-post-id="<?php echo $row['id']; ?>">👎 Dislike</button>
                                <span id="dislike-count-<?php echo $row['id']; ?>">0</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        $(document).ready(function () {
            console.log("Dashboard Loaded");

            $('#post_btn').on('click', function (e) {
                e.preventDefault(); 
                let formData = new FormData($('#post_form')[0]); 
                
                $.ajax({
                    url: '../post_action.php',
                    type: 'POST',
                    data: formData,
                    processData: false, 
                    contentType: false, 
                    success: function (response) {
                        try {
                            let data = JSON.parse(response); 
                            console.log(response);

                            if (data.status === "success") {
                                let newPost = `
                                    <div class="post">
                                        <img src="../${data.image}" alt="Post Image">
                                        <p>${data.description}</p>
                                        <small>Posted just now</small>
                                    </div>`;
                                
                                $("#my_content_post").prepend(newPost);
                                $('#post_form')[0].reset();
                            } else {
                                alert("Error: " + data.message);
                            }
                        } catch (error) {
                            console.error("Error parsing JSON:", error);
                            alert("Unexpected error occurred.");
                        }
                    },
                    error: function () {
                        alert('Error submitting post.');
                    }
                });
            });
        });
    </script>
</body>
</html>