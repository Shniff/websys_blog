<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/DatabaseConnect.php");

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to delete a post.";
    header("Location: /login.php");
    exit;
}

// Check if post ID is provided
if (!isset($_GET['post_id']) || empty($_GET['post_id'])) {
    $_SESSION['error'] = "Invalid post ID.";
    header("Location: /user.php");
    exit;
}

$db = new DatabaseConnect();
$conn = $db->connectDB();

if ($conn) {
    // Validate ownership of the post
    $post_id = intval($_GET['post_id']);
    $query = "SELECT * FROM posts WHERE post_id = :post_id AND user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $post = $stmt->fetch();

    if (!$post) {
        $_SESSION['error'] = "Post not found or you do not have permission to delete this post.";
        header("Location: /user.php");
        exit;
    }

    // Delete the post
    $deleteQuery = "DELETE FROM posts WHERE post_id = :post_id";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);

    if ($deleteStmt->execute()) {
        $_SESSION['success'] = "Post deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete post.";
    }

    // Redirect to the user's page
    header("Location: /user.php");
    exit;
} else {
    $_SESSION['error'] = "Database connection failed.";
    header("Location: /user.php");
    exit;
}
