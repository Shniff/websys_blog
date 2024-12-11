<?php

// Retrieve input
$username = $_POST["username"];
$password = $_POST["password"];

session_start();

// Include configurations and database connection
require_once(__DIR__."/../config/Directories.php");
include("../config/DatabaseConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new DatabaseConnect();
    $conn = $db->connectDB();

    try {
        // Query to find the user by username
        $stmt = $conn->prepare('SELECT * FROM `users` WHERE username = :p_username');
        $stmt->bindParam(':p_username', $username);
        $stmt->execute();
        $users = $stmt->fetchAll();

        if ($users) {
            // Verify the provided password against the hashed password in the database
            if (password_verify($password, $users[0]["password"])) {
                session_regenerate_id(true); // Prevent session fixation

                // Store user data in session
                $_SESSION['user_id'] = $users[0]['user_id'];
                $_SESSION['username'] = $users[0]['username'];
                $_SESSION['fullname'] = $users[0]['fullname'];
                $_SESSION['is_admin'] = $users[0]['is_admin'];

                // Redirect based on user type
                if ($users[0]['is_admin']) {
                    header("Location: " . BASE_URL . "admin.php");
                } else {
                    header("Location: " . BASE_URL . "user.php");
                }
                exit;
            } else {
                // Incorrect password handling
                $_SESSION["error"] = "Incorrect Password";
                header("Location: ". BASE_URL . "login.php");
                exit;
            }
        } else {
            // User not found handling
            $_SESSION["error"] = "User does not exist";
            header("Location: " . BASE_URL . "login.php");
            exit;
        }
    } catch (Exception $e) {
        // Handle any database connection or query issues
        $_SESSION["error"] = "Connection Failed: " . $e->getMessage();
        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}
?>
