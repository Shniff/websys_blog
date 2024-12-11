<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/DatabaseConnect.php");

// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();

// Change: use 'post_id' instead of 'id'
$postId = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if ($conn) {
    // Fetch the post details (updated 'post_id' instead of 'id')
    $postQuery = "SELECT p.post_id, p.title, p.content, p.category, p.image_url, p.vote, p.created_at, u.username 
                  FROM posts p
                  JOIN users u ON p.user_id = u.user_id
                  WHERE p.post_id = :post_id";
    $postStmt = $conn->prepare($postQuery);
    $postStmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $postStmt->execute();
    $post = $postStmt->fetch();

    // Fetch the comments (updated 'comment_id' and 'post_id' field names)
    $commentQuery = "SELECT c.comment_id, c.comment, c.created_at, u.username, c.user_id AS comment_user_id
                     FROM comments c
                     JOIN users u ON c.user_id = u.user_id
                     WHERE c.post_id = :post_id
                     ORDER BY c.created_at ASC";
    $commentStmt = $conn->prepare($commentQuery);
    $commentStmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $commentStmt->execute();
    $comments = $commentStmt->fetchAll();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment'] ?? '';
    $userId = $_SESSION['user_id'] ?? null;

    if ($userId && $comment) {
        $insertQuery = "INSERT INTO comments (post_id, user_id, comment, created_at) 
                        VALUES (:post_id, :user_id, :comment, NOW())";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->execute([
            ':post_id' => $postId,
            ':user_id' => $userId,
            ':comment' => $comment,
        ]);
        header("Location: view_post.php?post_id=" . $postId);
        exit();
    }
}

// Handle comment editing
if (isset($_GET['edit_comment_id']) && isset($_SESSION['user_id'])) {
    $editCommentId = $_GET['edit_comment_id'];
    $userId = $_SESSION['user_id'];

    // Check if the logged-in user is the owner of the comment
    $editQuery = "SELECT * FROM comments WHERE comment_id = :comment_id AND user_id = :user_id";
    $editStmt = $conn->prepare($editQuery);
    $editStmt->execute([':comment_id' => $editCommentId, ':user_id' => $userId]);
    $commentToEdit = $editStmt->fetch();

    if ($commentToEdit) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edited_comment'])) {
            $editedComment = $_POST['edited_comment'];

            // Update the comment in the database
            $updateQuery = "UPDATE comments SET comment = :edited_comment, created_at = NOW() WHERE comment_id = :comment_id";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->execute([
                ':edited_comment' => $editedComment,
                ':comment_id' => $editCommentId
            ]);
            header("Location: view_post.php?post_id=" . $postId);
            exit();
        }
    }
}

// Handle comment deletion
if (isset($_GET['delete_comment_id']) && isset($_SESSION['user_id'])) {
    $deleteCommentId = $_GET['delete_comment_id'];
    $userId = $_SESSION['user_id'];

    // Check if the logged-in user is the owner of the comment
    $deleteQuery = "SELECT * FROM comments WHERE comment_id = :comment_id AND user_id = :user_id";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->execute([':comment_id' => $deleteCommentId, ':user_id' => $userId]);
    $commentToDelete = $deleteStmt->fetch();

    if ($commentToDelete) {
        // Delete the comment from the database
        $deleteQuery = "DELETE FROM comments WHERE comment_id = :comment_id";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->execute([':comment_id' => $deleteCommentId]);
        header("Location: view_post.php?post_id=" . $postId);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <link rel="stylesheet" href="/Styles/index.css">
    <link rel="stylesheet" href="/Styles/Buttons.css">
    <link rel="stylesheet" href="/Styles/modal.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Function to ask for confirmation before deleting a comment
        function confirmDelete(event) {
            if (!confirm("Are you sure you want to delete this comment?")) {
                event.preventDefault();
            }
        }
    </script>
</head>
<body>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/includes/navbar.php"); ?>

<div class="container">
    <div class="row">
        <div class="col-md-3">
        <?php include($_SERVER["DOCUMENT_ROOT"] . "/includes/sidebar.php"); ?>
        </div>
        <div class="col-md-9">
            <div class="post-container">
                <?php if ($post) { ?>
                    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($post['category']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    <?php if (!empty($post['image_url'])) { ?>
                        <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="Post Image" style="max-width: 100%; height: auto;">
                    <?php } ?>
                    <p><strong>Posted by:</strong> <?php echo htmlspecialchars($post['username']); ?> on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></p>
                <?php } else { ?>
                    <p>Post not found.</p>
                <?php } ?>

                <!-- Comments Section -->
                <div class="comments-section">
                    <h2>Comments</h2>
                    <?php if ($comments) { ?>
                        <?php foreach ($comments as $comment) { ?>
                            <div class="comment">
                                <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong></p>
                                <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                <p><em><?php echo date('F j, Y, g:i a', strtotime($comment['created_at'])); ?></em></p>
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['comment_user_id']) { ?>
                                    <!-- Edit and Delete buttons for the comment owner -->
                                    <a href="view_post.php?post_id=<?php echo $postId; ?>&edit_comment_id=<?php echo $comment['comment_id']; ?>">Edit</a>
                                    <a href="view_post.php?post_id=<?php echo $postId; ?>&delete_comment_id=<?php echo $comment['comment_id']; ?>" 
                                       onclick="confirmDelete(event)">Delete</a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p>No comments yet. Be the first to comment!</p>
                    <?php } ?>

                    <!-- Add Comment Form -->
                    <form method="POST" action="">
                        <textarea name="comment" rows="5" cols="50" placeholder="Write a comment..." required></textarea>
                        <button type="submit">Add Comment</button>
                    </form>
                </div>
            </div>

            <!-- Edit Comment Form (if editing a comment) -->
            <?php if (isset($commentToEdit)) { ?>
                <div class="edit-comment-form">
                    <h3>Edit Comment</h3>
                    <form method="POST" action="">
                        <textarea name="edited_comment" rows="5" cols="50" required><?php echo htmlspecialchars($commentToEdit['comment']); ?></textarea>
                        <button type="submit">Save Changes</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>