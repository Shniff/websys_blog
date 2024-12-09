<div class="sidebar">
        <!-- Logo Section -->
        <div class="sidebar-logo">
            <h2>Admin</h2>
        </div>
        <div class="border"></div>
        <br>
        <!-- Navigation Links -->
        <div class="sidebar-section">
            <a href="#" class="sidebar-link active">Overview</a>
            <a href="#" class="sidebar-link" > Manage Post </a>
            <a href="#" class="sidebar-link"> Manage User </a>
            <a href="#" class="sidebar-link"> Settings </a>
            <a href="Logout.php" class="sidebar-link"> Logout </a> 
        </div>  
    <!-- Footer -->

    <footer>
    <div class="footer-content">
        <p>&copy; 2024 Blogging System. All rights reserved.</p>
        </div>
    </footer>
    </body>
    </html>
    </div>


    <style>

        /* Footer */
footer {
    background-color: #121212;
    padding: 15px;
    text-align: center;
    color: white;
    position: absolute; 
    width: 100%;
    bottom: 0;
    border-top: 1px solid #999999;
}

footer .footer-content {
    font-size: 14px;
}

    .border{
        border-top: 1px solid #999999;
    }


/* Sidebar */
.sidebar {
    background-color: #121212; 
    height: 100vh; 
    overflow-y: auto;
    overflow-x: hidden; 
    box-sizing: border-box;
    position: fixed;
    box-shadow: 3px 0 5px rgba(0, 0, 0, 0.3); 
    margin-left: -15px;
    margin-top: -15px;
    width: 250px; 
    color: #ffffff; 
    animation: slideIn 0.5s ease-out; 
    display: flex; 
    flex-direction: column; 
    justify-content: flex-start; 
    padding-top: 30px; 
}

/* Sidebar Logo */
.sidebar-logo h2 {
    color: #00bfff;
    margin-bottom: 30px;
    text-align: center;
    font-size: 1.5rem;
    font-weight: bold;
    animation: fadeIn 0.6s ease-in-out; 
}

/* Sidebar sections */
.sidebar-section {
    padding-left: 0;
    padding-right: 0;
    margin-bottom: 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
    animation: fadeInUp 0.7s ease-in-out;
}

/* Sidebar links */
.sidebar-link,
.sidebar-section ul li a {
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    padding: 12px 20px; 
    border-radius: 8px;
    transition: background-color 0.2s, padding 0.2s; 
    font-size: 1rem;
    margin-bottom: 10px;
    animation: fadeInUp 0.8s ease-in-out; 
    width: 100%; 
}

.sidebar-link.active,
.sidebar-link:hover,
.sidebar-section ul li a:hover {
    background-color: #333333;
    padding-left: 20px; 
    padding-right: 20px; 
    border-radius: 8px; 
}

/* Sidebar list items */
.sidebar-section ul {
    list-style: none;
    padding: 0;
    width: 100%;
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