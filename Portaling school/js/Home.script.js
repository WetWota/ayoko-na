function toggleComments(postId) {
    console.log("Toggling comments for post ID:", postId); // Debugging line
    var commentForm = document.getElementById('comment-form-' + postId);
    var commentsSection = document.getElementById('comments-' + postId);

    if (commentForm.style.display === "none") {
        commentForm.style.display = "block"; // Show comment form
    } else {
        commentForm.style.display = "none"; // Hide comment form
    }

    if (commentsSection.style.display === "none") {
        commentsSection.style.display = "block"; // Show comments
    } else {
        commentsSection.style.display = "none"; // Hide comments
    }
}

function filterPosts() {
    const selectedAccountType = document.getElementById('account-type-filter').value;
    const posts = document.querySelectorAll('.post');

    posts.forEach(post => {
        const postAuthorType = post.getAttribute('data-author-type');
        if (selectedAccountType === 'all' || postAuthorType === selectedAccountType) {
            post.style.display = 'block'; // Show post
        } else {
            post.style.display = 'none'; // Hide post
        }
    });
}

function confirmVoid(postId) {
    if (confirm("Are you sure you want to void this post?")) {
        // If confirmed, create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'Home.php'; // Change this to your actual action URL

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'void_post_id'; // This should match the name in your PHP handling code
        input.value = postId;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmVoidComment(commentId) {
    if (confirm("Are you sure you want to void this comment?")) {
        // If confirmed, create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'Home.php'; // Change this to your actual action URL

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'void_comment_id'; // This should match the name in your PHP handling code
        input.value = commentId;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}