<?php
session_start();
require_once('app/config/DatabaseConnect.php');
require_once('includes/navbar.php');



// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="Styles/index.css">
    <link rel="stylesheet" href="Styles/Buttons.css">
    <link rel="stylesheet" href="Styles/modal.css">
</head>
<body>
    
<?php
if (isset($_SESSION['success'])) {
    echo "<div class='success-message'>" . htmlspecialchars($_SESSION['success']) . "</div>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<div class='error-message'>" . htmlspecialchars($_SESSION['error']) . "</div>";
    unset($_SESSION['error']);
}
?>

<div class="main-container">
    <?php include('includes/sidebar.php'); ?>
    <!-- Content Section -->
    <div class="content">
        <div class="feed">
            <?php
            if ($conn) {
                // Fetch all posts from the database
                $query = "SELECT p.id, p.title, p.content, p.category, p.image_url, p.vote, p.created_at, p.user_id, u.username 
                          FROM posts p
                          JOIN users u ON p.user_id = u.id
                          ORDER BY p.created_at DESC";

                $stmt = $conn->prepare($query);

                try {
                    $stmt->execute();
                    $posts = $stmt->fetchAll();

                    if ($posts) {
                        foreach ($posts as $post) {
                            ?>
                            <div class="post">
                                <!-- Post Image -->
                                <?php if (!empty($post['image_url'])) { ?>
                                    <div class="post-image">
                                        <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="Post Image" style="max-width: 100%; height: auto;">
                                    </div>
                                <?php } ?>
                                <!-- Post Title -->
                                <div class="post-title"><?php echo htmlspecialchars($post['title']); ?></div>
                                <!-- Post Category -->
                                <div class="post-category">
                                    <strong>Category:</strong> <?php echo htmlspecialchars($post['category']); ?>
                                </div>
                                <!-- Post Content -->
                                <div class="post-content">
                                    <?php echo htmlspecialchars(substr($post['content'], 0, 150)); ?>...
                                    <a href="#" class="text-primary">Read More</a>
                                </div>
                                <!-- Post Metadata -->
                                <div class="post-meta">
                                    Posted by <strong><?php echo htmlspecialchars($post['username']); ?></strong> 
                                    on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                </div>
                                <!-- Vote Section -->
                                <div class="vote-buttons">
                                    <button class="upvote">▲</button>
                                    <span><?php echo htmlspecialchars($post['vote']); ?></span>
                                    <button class="downvote">▼</button>
                                </div>
                                <!-- Post Actions for the Author -->
                                <?php if ($_SESSION['user_id'] == $post['user_id']) { ?>
                                    <div class="post-actions">
                                        <a href="#" class="edit-link" data-post-id="<?php echo $post['id']; ?>">Edit</a>
                                        <a href="app/posts/delete_post.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php
                        }
                        
                    } else {
                        echo "<p>No posts available. Be the first to post!</p>";
                    }
                } catch (PDOException $e) {
                    echo "Error fetching posts: " . $e->getMessage();
                }
            } else {
                echo "<p>Database connection failed. Please try again later.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Create Post Button -->
    <button class="create-post-btn" onclick="openAddPostModal()" style="position: fixed; bottom: 30px; right: 30px; z-index: 1000;">+</button>
</div>

<?php include('addpost_modal.php'); ?>
<?php include('editpost_modal.php'); ?>

<script>
    // Open the Add Post Modal
    function openAddPostModal() {
        const modal = document.getElementById('addPostModal');
        modal.style.display = 'flex';
    }

    // Open the Edit Post Modal
    function openEditPostModal(postId) {
        fetch('app/posts/fetch_post_data.php?id=' + postId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate modal fields with fetched data
                    document.getElementById('postTitle').value = data.post.title;
                    document.getElementById('postDescription').value = data.post.content;
                    document.getElementById('postCategory').value = data.post.category;
                    document.getElementById('currentImage').innerHTML = 'Current image: <img src="' + data.post.image_url + '" alt="Post Image" width="100">';

                    // Set the form's action to point to the correct edit_post.php URL
                    document.getElementById('editPostForm').action = 'app/posts/edit_post.php?id=' + postId;

                    // Display the Edit Post Modal
                    document.getElementById('editPostModal').style.display = 'flex';
                } else {
                    console.error('Error fetching post data: ', data.message);
                }
            })
            .catch(error => console.error('Error fetching post data:', error));
    }

    // Close modal function
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside the modal content
window.onclick = function(event) {
    const editModal = document.getElementById('editPostModal');
    const addModal = document.getElementById('addPostModal');
    if (event.target === editModal) {
        closeModal('editPostModal');
    }
    if (event.target === addModal) {
        closeModal('addPostModal');
    }
};

    // Open the Edit Post Modal when an edit link is clicked
    const editLinks = document.querySelectorAll('.edit-link');
    editLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const postId = this.getAttribute('data-post-id');
            openEditPostModal(postId);
        });
    });

    // Close the Add Post Modal
    function closeAddPostModal() {
        const modal = document.getElementById('addPostModal');
        modal.style.display = 'none';
    }
</script>

</body>
</html>
