<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
session_start();

session_unset(); 
session_destroy();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title> <!-- This sets the title of the webpage displayed in the browser tab -->
</head>

<body style="background-color: #1a1a1a; color: #ffffff;">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card text-center shadow p-3" 
         style="width: 24rem; background-color: #2a2a2a; color: #ffffff; border: none;">
        <div class="card-body">
            <h5 class="card-title">You have been logged out</h5>
            <p class="card-text">Thank you for visiting. You are now logged out.</p>
            <a href="/index.php" class="btn btn-primary">Go to Home</a>
        </div>
    </div>
</div>
</body>
