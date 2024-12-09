<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/Admin.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <!-- Include Sidebar -->
    <?php include("includes/adminsidebar.php"); ?> 

    <!-- Main Content -->
    <div class="main-content">
        <!-- Admin Panel Card -->
        <div class="admin-panel-card">
            <h1>Admin Panel</h1>
        </div>

        <!-- Dashboard Overview Section -->
        <div class="dashboard-overview">
            <h2>Dashboard Overview</h2>
            <div class="cards">
                <div class="card">
                    <h3>Total Posts</h3>
                    <p>45</p>
                </div>
                <div class="card">
                    <h3>Total Users</h3>
                    <p>128</p>
                </div>
                <div class="card">
                    <h3>Pending Approvals</h3>
                    <p>12</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="recent-activity">
            <h2>Recent Activity</h2>
            <div class="card">
                <h3>Post Moderation</h3>
                <p>You have 5 flagged posts to review.</p>
            </div>
        </div>
    </div>
</body>
</html>
