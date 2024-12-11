<?php
session_start();
require_once('app/config/DatabaseConnect.php');
require_once('includes/navbar.php');

// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote'])) {
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id'];
    $voteValue = (int)$_POST['vote_value']; // 1 for upvote, -1 for downvote

    // Check if the user has already voted
    $checkVoteQuery = "SELECT vote_value FROM votes WHERE post_id = :post_id AND user_id = :user_id";
    $stmt = $conn->prepare($checkVoteQuery);
    $stmt->execute([':post_id' => $postId, ':user_id' => $userId]);
    $existingVote = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingVote) {
        if ($existingVote['vote_value'] == $voteValue) {
            // If the user clicks the same vote button, remove the vote
            $deleteVoteQuery = "DELETE FROM votes WHERE post_id = :post_id AND user_id = :user_id";
            $stmt = $conn->prepare($deleteVoteQuery);
            $stmt->execute([':post_id' => $postId, ':user_id' => $userId]);

            // Adjust the vote count by decrementing it
            $updateVoteQuery = "UPDATE posts SET vote = vote - :vote_value WHERE post_id = :post_id";
            $stmt = $conn->prepare($updateVoteQuery);
            $stmt->execute([':vote_value' => $voteValue, ':post_id' => $postId]);

            echo json_encode(['success' => true, 'new_vote_count' => 0]); // Reset to 0 since the vote is removed
        } else {
            // If the user switches vote (upvote to downvote or downvote to upvote)
            // Update the vote_value in the votes table
            $updateVoteQuery = "UPDATE votes SET vote_value = :vote_value WHERE post_id = :post_id AND user_id = :user_id";
            $stmt = $conn->prepare($updateVoteQuery);
            $stmt->execute([':vote_value' => $voteValue, ':post_id' => $postId, ':user_id' => $userId]);

            // Adjust the vote count by switching votes
            $adjustVoteQuery = "UPDATE posts SET vote = vote + :difference WHERE post_id = :post_id";
            $stmt = $conn->prepare($adjustVoteQuery);
            $difference = ($voteValue == 1) ? 2 : -2; // Switching from -1 to 1 or 1 to -1
            $stmt->execute([':difference' => $difference, ':post_id' => $postId]);

            echo json_encode(['success' => true, 'new_vote_count' => $difference]);
        }
    } else {
        // If no previous vote exists, insert a new vote
        $insertVoteQuery = "INSERT INTO votes (post_id, user_id, vote_value) VALUES (:post_id, :user_id, :vote_value)";
        $stmt = $conn->prepare($insertVoteQuery);
        $stmt->execute([':post_id' => $postId, ':user_id' => $userId, ':vote_value' => $voteValue]);

        // Update the vote count for the post
        $updateVoteQuery = "UPDATE posts SET vote = vote + :vote_value WHERE post_id = :post_id";
        $stmt = $conn->prepare($updateVoteQuery);
        $stmt->execute([':vote_value' => $voteValue, ':post_id' => $postId]);

        echo json_encode(['success' => true, 'new_vote_count' => $voteValue]);
    }
    exit();
}

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

    <style>
    /* Style for the post title */
    .post-title a {
        font-size: 2em; /* Largest font size for the title */
        font-weight: bold;
        color: inherit;
    }

    /* Style for the category */
    .post-category {
        font-size: 1.2em; /* Slightly smaller than the title */
        margin-top: 10px;
        font-weight: normal;
        color: inherit; 
    }

    /* Style for the post content */
    .post-content {
        font-size: .7em; /* A bit smaller than the category */
        margin-top: 10px;
        color: inherit; 
    }

    /* Style for the vote buttons */
    .vote-buttons {
        font-size: 1em; /* Standard font size for the vote buttons */
        margin-top: 10px;
    }

    /* Optional: Style the "Read More" link */
    .text-primary {
        color: #007bff;
        text-decoration: none;
    }

    .text-primary:hover {
        text-decoration: underline;
    }
    </style>
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
    <div class="content">
        <div class="feed">
            <?php
            if ($conn) {
                $query = "SELECT p.post_id, p.user_id, p.title, p.content, p.category, p.image_url, p.vote, p.created_at, u.username 
                          FROM posts p
                          JOIN users u ON p.user_id = u.user_id
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
                                        <a href="app/posts/view_post.php?post_id=<?php echo $post['post_id']; ?>">
                                            <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="Post Image" style="max-width: 100%; height: auto;">
                                        </a>
                                    </div>
                                <?php } ?>
                                <!-- Post Title -->
                                <div class="post-title">
                                    <a href="app/posts/view_post.php?post_id=<?php echo $post['post_id']; ?>" style="text-decoration: none; color: inherit;">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </div>
                                <!-- Post Category -->
                                <div class="post-category">
                                    <strong>Category:</strong> <?php echo htmlspecialchars($post['category']); ?>
                                </div>
                                <!-- Post Content -->
                                <div class="post-content">
                                    <?php echo htmlspecialchars(substr($post['content'], 0, 150)); ?>...
                                    <a href="app/posts/view_post.php?post_id=<?php echo $post['post_id']; ?>" class="text-primary">Read More</a>
                                </div>
                                <!-- Post Metadata -->
                                <div class="post-meta">
                                    Posted by <strong><?php echo htmlspecialchars($post['username']); ?></strong> 
                                    on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                </div>
                                 <!-- Vote Section -->
                                 <div class="vote-buttons">
                                    <form action="" method="POST" class="vote-form" data-post-id="<?php echo $post['post_id']; ?>">
                                        <button type="button" class="upvote" data-vote="1">▲</button>
                                        <span class="vote-count"><?php echo htmlspecialchars($post['vote']); ?></span>
                                        <button type="button" class="downvote" data-vote="-1">▼</button>
                                    </form>
                                </div>

                                <!-- Post Actions for the Author -->
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']) { ?>
                                    <div class="post-actions">
                                        <a href="javascript:void(0)" class="edit-link" data-post-id="<?php echo $post['post_id']; ?>" onclick="openEditPostModal(<?php echo $post['post_id']; ?>)">Edit</a>
                                        <a href="app/posts/delete_post.php?post_id=<?php echo $post['post_id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
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

    <button class="create-post-btn" onclick="openAddPostModal()" style="position: fixed; bottom: 30px; right: 30px; z-index: 1000;">+</button>
</div>

<?php include('addpost_modal.php'); ?>
<?php include('editpost_modal.php'); ?>

<script>
  document.querySelectorAll('.vote-buttons').forEach(container => {
    const upvoteBtn = container.querySelector('.upvote');
    const downvoteBtn = container.querySelector('.downvote');
    const voteCount = container.querySelector('.vote-count');

    upvoteBtn.addEventListener('click', function() {
        handleVote(1, voteCount);
    });

    downvoteBtn.addEventListener('click', function() {
        handleVote(-1, voteCount);
    });

    function handleVote(voteValue, voteCount) {
        // Get the current vote count from the span
        let currentVote = parseInt(voteCount.textContent);

        // Prevent the vote count from going below 0
        if (voteValue === -1 && currentVote <= 0) {
            return; // Don't allow decrement if the vote count is already 0 or less
        }

        // Increment or decrement based on the vote value
        currentVote += voteValue;

        // Update the vote count displayed on the page
        voteCount.textContent = currentVote;

        // Add class to style buttons based on vote
        if (voteValue === 1) {
            upvoteBtn.classList.add('voted');
            downvoteBtn.classList.remove('voted');
            voteCount.classList.add('green');
            voteCount.classList.remove('red');
        } else if (voteValue === -1) {
            downvoteBtn.classList.add('voted');
            upvoteBtn.classList.remove('voted');
            voteCount.classList.add('red');
            voteCount.classList.remove('green');
        }
    }
});
</script>



<script>
    // Open the Add Post Modal
    function openAddPostModal() {
        const modal = document.getElementById('addPostModal');
        modal.style.display = 'flex';
    }

    // Open the Edit Post Modal
    function openEditPostModal(postId) {
        fetch('app/posts/fetch_post_data.php?post_id=' + postId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate modal fields with fetched data
                    document.getElementById('postTitle').value = data.post.title;
                    document.getElementById('postDescription').value = data.post.content;
                    document.getElementById('postCategory').value = data.post.category;
                    document.getElementById('currentImage').innerHTML = 'Current image: <img src="' + data.post.image_url + '" alt="Post Image" width="100">';

                    // Set the form's action to point to the correct edit_post.php URL
                    document.getElementById('editPostForm').action = 'app/posts/edit_post.php?post_id=' + postId;

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

    // Close the Add Post Modal
    function closeAddPostModal() {
        const modal = document.getElementById('addPostModal');
        modal.style.display = 'none';
    }
</script>

</body>
</html>