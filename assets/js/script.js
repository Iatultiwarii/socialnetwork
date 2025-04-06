document.addEventListener('DOMContentLoaded', function () {
    const postForm = document.getElementById('post_form');
    if (postForm) {
        postForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(postForm);
            const postBtn = document.getElementById('post_btn');
            postBtn.disabled = true;
            postBtn.textContent = 'Posting...';
            fetch('post_action.php?action=create_post', {
                method: 'POST',
                body: formData
            })
            .then(async (response) => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('Raw response (not JSON):', text);
                    throw new Error('Response is not JSON');
                }
                return response.json();
            })
            .then(data => {
                console.log('Parsed JSON Response:', data);
                if (data.status === 'success') {
                    const newPost = document.createElement('div');
                    newPost.className = 'post';
                    newPost.innerHTML = `
                        <div class="post-header">
                            <img src="${document.querySelector('.profile-img').src}" class="user-profile-img">
                            <span class="post-user">${data.post.description}</span>
                        </div>
                        <img src="${data.post.image}" class="post-img">
                        <small>Posted just now</small>
                        <div class="post-actions">
                            <button class="like-btn">👍 Like</button>
                            <span class="like-count">0</span>
                        </div>
                    `;
                    document.getElementById('my_content_post').prepend(newPost);
                    postForm.reset();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                alert('Failed to create post');
            })
            .finally(() => {
                postBtn.disabled = false;
                postBtn.textContent = 'Post';
            });
        });
    }
});
