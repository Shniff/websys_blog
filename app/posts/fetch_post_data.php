<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/app/config/DatabaseConnect.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
    exit;
}

$post_id = $_GET['id'];
$db = new DatabaseConnect();
$conn = $db->connectDB();

$stmt = $conn->prepare('SELECT * FROM posts WHERE id = :id');
$stmt->bindParam(':id', $post_id);
$stmt->execute();
$post = $stmt->fetch();

if ($post) {
    echo json_encode(['success' => true, 'post' => $post]);
} else {
    echo json_encode(['success' => false, 'message' => 'Post not found']);
}
?>