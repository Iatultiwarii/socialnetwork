$(document).ready(function () {
    const $postForm = $('#post_form');
    if ($postForm.length) {
        $postForm.on('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const $postBtn = $('#post_btn');
            $postBtn.prop('disabled', true).text('Posting...');
            $.ajax({
                url: 'post_action.php?action=create_post',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response, status, xhr) {
                    const contentType = xhr.getResponseHeader('Content-Type');
                    if (!contentType || !contentType.includes('application/json')) {
                        console.error('Raw response (not JSON):', response);
                        alert('Response is not JSON');
                        return;
                    }
                    let data;
                    try {
                        data = typeof response === 'string' ? JSON.parse(response) : response;
                    } catch (err) {
                        console.error('JSON parse error:', err);
                        alert('Invalid JSON response');
                        return;
                    }
                    console.log('Parsed JSON Response:', data);
                    if (data.status === 'success') {
                        const newPost = `
                            <div class="post" id="post_${data.post.id}">
                                <div class="post-header">
                                    <img src="${$('.profile-img').attr('src')}" class="user-profile-img">
                                    <span class="post-user">${data.post.description}</span>
                                    <button class="delete-btn" data-id="${data.post.id}">Delete</button>
                                </div>
                                <img src="${data.post.image}" class="post-img">
                                <small>Posted just now</small>
                                <div class="post-actions">
                                    <button class="like-btn">👍 Like</button>
                                    <span class="like-count">0</span>
                                </div>
                            </div>
                        `;
                        $('#my_content_post').prepend(newPost);
                        $postForm[0].reset();
                    } else {
                        alert('Error: ' + data.message);
                    }
                },
                error: function (xhr, status, error) {
                              console.error('AJAX Error:', error);
                    alert('Failed to create post');
                },
                complete: function () {
                    $postBtn.prop('disabled', false).text('Post');
                }
            });
        });
    }
    $(document).on('click', '.editable', function () {
        console.log('Edit name clicked');
        $('#full_name_text').hide();
        $('#full_name_input').val($('#full_name_text').text()).show().focus();
    });
    $('#full_name_input').on('blur', function () {
        const updatedName = $(this).val().trim();
        console.log('Updating name to:', updatedName);
        if (!updatedName) return;
        const formData = new FormData();
        formData.append('action', 'update_name');
        formData.append('full_name', updatedName);
        $.ajax({
            url: 'index.php?route=update_profile',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                $('#full_name_text').text(updatedName).show();
                $('#full_name_input').hide();
            },
            error: function () {
                alert('Failed to update name');
            }
        });
    });
    $('#profile_img_preview').on('click', function () {
        console.log('Profile image clicked');
        $('#profile_img_input').click();
    });
    $('#profile_img_input').on('change', function () {
        const file = this.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('action', 'update_picture');
        formData.append('profile_picture', file);
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#profile_img_preview').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
        $.ajax({
            url: 'index.php?route=update_profile',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                console.log('Profile picture updated');
            },
            error: function () {
                alert('Failed to upload profile picture');
            }
        });
    });
    $(document).on('click', '.delete-btn', function () {
        const postId = $(this).data('id');
        console.log('Deleting post:', postId);
        $.ajax({
            url: 'index.php?route=delete_post',
            type: 'POST',
            data: { id: postId },
            success: function () {
                $(`#post_${postId}`).remove();
            },
            error: function (xhr, status, error) {
                console.error('Delete error:', error);
                alert('Failed to delete post');
            }
        });
    });
});