<?php
// Start session
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protected Page</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff;
            color: #333;
        }
        .page-container {
            max-width: 800px;
            margin: 60px auto;
            padding: 20px;
        }
        h1 {
            color: #4b0082;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .content {
            padding: 25px;
            border-radius: 12px;
            background: linear-gradient(145deg, #ffffff, #f0f0ff);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            border: 2px solid #6a5acd;
            margin-bottom: 20px;
        }
        h2 {
            color: #4b0082;
            margin-top: 0;
        }
        .logout {
            display: inline-block;
            background: linear-gradient(145deg, #9370db, #6a5acd);
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .logout:hover {
            background: linear-gradient(145deg, #8a2be2, #9400d3);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }
        .user-info {
            background-color: rgba(106, 90, 205, 0.1);
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #6a5acd;
            margin-bottom: 20px;
        }
        .welcome-emoji {
            font-size: 24px;
            margin-right: 10px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <h1><span class="welcome-emoji">👋</span> Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
        
        <div class="content">
            <h2>✨ Protected Content ✨</h2>
            <p>This is a secured page that only authenticated users can access. You've successfully logged in!</p>
            
            <div class="user-info">
                <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']); ?></p>
                <p><strong>User ID:</strong> <?= htmlspecialchars($_SESSION['user_id']); ?></p>
                <p><strong>Session Status:</strong> Active</p>
            </div>
            
            <p>Thank you for using our authentication system. This content is protected from unauthorized access.</p>
        </div>
        
        <a href="index.php?logout=true" class="logout">Logout 🚪</a>
    </div>
</body>
</html>