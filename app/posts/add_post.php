<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/DatabaseConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST["title"]);
    $content = htmlspecialchars($_POST["content"]);
    $category = htmlspecialchars($_POST["category"]);
    $image = htmlspecialchars($_POST["image_url"]);
    $user_id = $_SESSION['user_id'] ?? null; // Check if the user is logged in

    if (!$user_id) {
        $_SESSION["error"] = "You must be logged in to create a post.";
        header("Location: " . BASE_URL . "login.php");
        exit;
    }

    $db = new DatabaseConnect();
    $conn = $db->connectDB();

    try {
        $stmt = $conn->prepare('INSERT INTO posts (user_id, title, content, Category, image_url, created_at, updated_at) VALUES (:user_id, :title,:content, :category ,:images ,NOW(), NOW())');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':images', $image);
        $stmt->execute();

        $_SESSION["success"] = "Post created successfully!";
        header("Location: " . BASE_URL . "user.php");
        exit;

    } catch (Exception $e) {
        $_SESSION["error"] = "Error: " . $e->getMessage();
        echo $e;
        exit;
    }
}
?>