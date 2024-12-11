<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/DatabaseConnect.php");

// Validate and retrieve the `post_id` parameter from the GET request
if (!isset($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
    exit;
}

$post_id = intval($_GET['post_id']);
$db = new DatabaseConnect();
$conn = $db->connectDB();

try {
    // Prepare a query to fetch the post using the `post_id`
    $stmt = $conn->prepare('SELECT * FROM posts WHERE post_id = :post_id');
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        echo json_encode(['success' => true, 'post' => $post]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Post not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
