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
            <div class="profile-picture-container">
                <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'default.png'); ?>" alt="Profile Image" class="profile-img editable" id="profile_img_preview">
                <input type="file" id="profile_img_input" style="display:none;" accept="image/*">
            </div>
            <p class="editable" data-field="full_name">
                <strong>Name:</strong>
                <span id="full_name_text"><?php echo htmlspecialchars($user['full_name'] ?? 'User'); ?></span>
                <input type="text" id="full_name_input" value="<?php echo htmlspecialchars($user['full_name'] ?? 'User'); ?>" style="display:none;">
            </p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <a href="index.php?route=logout" class="logout-btn">Logout</a>
        </div>

        <div class="right-panel">
            <div class="post-form">
                <h2>Create a Post</h2>
                <form id="post_form" method="post" enctype="multipart/form-data" action="index.php?route=post_action&action=create_post">
                    <input type="file" name="blog_image" accept="image/*" required>
                    <textarea name="description" placeholder="Write something..." required></textarea>
                    <button type="submit" id="post_btn">Post</button>
                </form>
            </div>

            <div class="posts">
                <h2>Previous Posts</h2>
                <div id="my_content_post">
                    <?php foreach ($posts as $row): ?>
                        <div class="post" id="post_<?php echo $row['id']; ?>">
                            <div class="post-header">
                                <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'default.png'); ?>" alt="Profile Image" class="user-profile-img">
                                <span class="post-user"><?php echo htmlspecialchars($row['description']); ?></span>
                            </div>
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Post Image" class="post-img">
                            <small>Posted on <?php echo htmlspecialchars($row['created_at']); ?></small>
                            <div class="post-actions">
                                <button class="delete-btn" data-id="<?php echo $row['id']; ?>">🗑️ Delete</button>
                                <button class="like-btn" data-post-id="<?php echo $row['id']; ?>">👍 Like</button>
                                <span id="like-count-<?php echo $row['id']; ?>">0</span>
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
        $(document).on('click', '.delete-btn', function () {
            const postId = $(this).data('id');
            $.ajax({
                url: 'index.php?route=delete_post',
                type: 'POST',
                data: { id: postId },
                success: function (response) {
                    $(`#post_${postId}`).remove();
                },
                error: function (xhr, status, error) {
                    console.error('Delete failed:', error);
                }
            });
        });
    </script>
</body>
</html>
