<?php
session_start();
require_once('app/config/DatabaseConnect.php');
require_once('includes/navbar.php');

// Initialize the database connection
$db = new DatabaseConnect();
$conn = $db->connectDB();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogging System</title>
</head>

<link rel="stylesheet" href="Styles/Buttons.css">
<div class="main-container">
    
    <?php include('includes/sidebar.php'); ?>
    <!-- Content Section -->
    <div class="content">
        <div class="feed">
            <?php
            if ($conn) {
                // Fetch posts from the database
                $query = "SELECT p.id, p.title, p.content, p.category, p.image_url, p.vote, p.created_at, u.username 
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
                                    <button class="upvote" onclick="window.location.href='login.php';">▲</button>
                                    <span><?php echo htmlspecialchars($post['vote']); ?></span>
                                    <button class="downvote" onclick="window.location.href='login.php';">▼</button>
                                </div>

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
    <a href="login.php" class="create-post-btn">+</a>

</div>
