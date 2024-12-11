<?php
// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']); // Assuming you store the user's ID in the session
?>

<div class="sidebar">
        <!-- Logo Section -->
        <div class="sidebar-logo">
            <h2>Logo</h2>
        </div>

        <!-- Navigation Links -->
        <div class="sidebar-section">
        <a href="<?php echo $isLoggedIn ? '/user.php' : '/index.php'; ?>" class="sidebar-link active">
            <i class="icon">🏠</i> Home
        </a>
                
            </a>
            <a href="#" class="sidebar-link">
                <i class="icon">🔥</i> Popular
            </a>
        </div>

        <!-- Topics Section -->
        <div class="sidebar-section">
            <h3>Categories</h3>
            <ul>

                <li><a href="index.php?category=Technology"><i class="icon">💻</i> Technology</a></li>
                <li><a href="index.php?category=Lifestyle"><i class="icon">🌟</i> Lifestyle</a></li>
                <li><a href="index.php?category=Travel"><i class="icon">🎬</i> Travel</a></li>
            </ul>
        </div>

       
        <!-- <div class="sidebar-section">
            <h3>Resources</h3>
            <ul>
                <li><a href="#"><i class="icon">📄</i> About Reddit</a></li>
                <li><a href="#"><i class="icon">📢</i> Advertise</a></li>
                <li><a href="#"><i class="icon">❓</i> Help</a></li>
                <li><a href="#"><i class="icon">✍️</i> Blog</a></li>
                <li><a href="#"><i class="icon">💼</i> Careers</a></li>
                <li><a href="#"><i class="icon">🖋️</i> Press</a></li>
            </ul>
        </div>  -->

        <footer>
    <div class="footer-content">
        <p>&copy; 2024 Blogging System. All rights reserved.</p>
    </div>
    </footer>
    </body>
    </html>
    </div>

    

    <style>

footer {
    background-color: #121212; 
    padding: 15px;
    width: 100%;
    text-align: center;
    color: white;
    border-top: 1px solid #999999;
    position: absolute; 
    bottom: 0; 
    left: 0;
}



footer .footer-content {
    font-size: 14px;
}


/* Sidebar */
.sidebar {
    background-color: #121212; 
    height: 95%; 
    overflow-y: auto;
    position: fixed;
    margin-top: -15px; 
    width: 250px; 
    color: #ffffff; 
    animation: slideIn 0.5s ease-out; 
    box-shadow: 3px 0 5px rgba(0, 0, 0, 0.3); 
}

/* Sidebar Logo */
.sidebar-logo h2 {
    color: #00bfff;
    margin-top: 30px;
    margin-bottom: 20px;
    padding-left: 50px;
    font-size: 1.5rem;
    font-weight: bold;
    animation: fadeIn 0.6s ease-in-out; 
}

/* Sidebar sections */
.sidebar-section {
    padding-left: 30px;
    padding-right: 30px;
    margin-bottom: 30px;
    animation: fadeInUp 0.7s ease-in-out; 
}

.sidebar-section h3 {
    font-size: 1rem;
    color: #ccc;
    text-transform: uppercase;
    margin-bottom: 10px;
    font-weight: 600;
}

/* Sidebar links */
.sidebar-link,
.sidebar-section ul li a {
    display: flex;
    align-items: center;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    padding: 10px;
    border-radius: 8px;
    transition: background-color 0.2s;
    font-size: 1rem;
    animation: fadeInUp 0.8s ease-in-out; 
}

.sidebar-link .icon,
.sidebar-section ul li a .icon {
    margin-right: 10px;
    font-size: 1.2rem;
}

.sidebar-link.active,
.sidebar-link:hover,
.sidebar-section ul li a:hover {
    background-color: #333333;
}

/* Sidebar list items */
.sidebar-section ul {
    list-style: none;
    padding: 0;
}

.sidebar-section ul li {
    margin: 5px 0;
}

/* Scrollbar styling */
.sidebar::-webkit-scrollbar {
    width: 8px;
}

.sidebar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}

/* Animations */
@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

@keyframes fadeInUp {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
    0% { transform: translateX(-30px); opacity: 0; }
    100% { transform: translateX(0); opacity: 1; }
}

@keyframes slideInLeft {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(0); }
}

@keyframes slideInUp {
    0% { transform: translateY(30px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}
</style>