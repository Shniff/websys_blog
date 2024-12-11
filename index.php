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

    <style>
    /* Style for the post title */
    .post-title a {
        font-size: 2em; /* Largest font size for the title */
        font-weight: bold;
        color: inherit; /* Inherits color from the parent, so it respects your theme */
    }

    /* Style for the category */
    .post-category {
        font-size: 1.2em; /* Slightly smaller than the title */
        margin-top: 10px;
        font-weight: normal;
        color: inherit; /* A subtle color for category */
    }

    /* Style for the post content */
    .post-content {
        font-size: .7em; /* A bit smaller than the category */
        margin-top: 10px;
        color: inherit; /* Slightly darker color for content */
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

<link rel="stylesheet" href="Styles/Buttons.css">
<div class="main-container">
    
    <?php include('includes/sidebar.php'); ?>
    <!-- Content Section -->
    <div class="content">
        <div class="feed">
            <?php
            if ($conn) {
                // Fetch posts from the database
                $query = "SELECT p.user_id, p.title, p.content, p.category, p.image_url, p.vote, p.created_at, u.username 
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
                                        <a href="login.php">
                                            <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="Post Image" style="max-width: 100%; height: auto;">
                                        </a>
                                    </div>
                                <?php } ?>
                                <!-- Post Title -->
                                <div class="post-title">
                                <a href="login.php" style="text-decoration: none; color: inherit;">
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
                                <a href="login.php" class="text-primary">Read More</a>
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
