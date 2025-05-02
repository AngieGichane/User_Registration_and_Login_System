<?php
// Start session
session_start();

// Database connection parameters
$host = "localhost";
$dbname = "user_auth";
$username = "root";
$password = "mayaswan";

// Connect to database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Variable to store messages
$message = '';
$messageType = ''; // success or error

// Registration process
if (isset($_POST['register'])) {
    // Get form data and sanitize
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "Please fill all fields";
        $messageType = "error";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match";
        $messageType = "error";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters long";
        $messageType = "error";
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $message = "Username already exists";
            $messageType = "error";
        } else {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $message = "Email already exists";
                $messageType = "error";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert user into database
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, NOW())");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                
                if ($stmt->execute()) {
                    $message = "✅ Registration successful! You can now login.";
                    $messageType = "success";
                } else {
                    $message = "Something went wrong. Please try again.";
                    $messageType = "error";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            max-width: 600px;
            margin: 60px auto;
            padding: 20px;
        }
        h1 {
            color: #4b0082;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .form-container {
            width: 100%;
            padding: 25px;
            border-radius: 12px;
            background: linear-gradient(145deg, #ffffff, #f0f0ff);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            border: 2px solid #6a5acd;
        }
        h2 {
            color: #4b0082;
            margin-top: 0;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #4b0082;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 2px solid #9370db;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: #4b0082;
            box-shadow: 0 0 8px rgba(106, 90, 205, 0.5);
            outline: none;
        }
        input[type="submit"] {
            background: linear-gradient(145deg, #9370db, #6a5acd);
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            display: block;
            width: 100%;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        input[type="submit"]:hover {
            background: linear-gradient(145deg, #8a2be2, #9400d3);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }
        .error {
            background-color: #ffe0e0;
            color: #d8000c;
            border: 2px solid #ffbaba;
        }
        .success {
            background-color: #e0ffe0;
            color: #4F8A10;
            border: 2px solid #DFF2BF;
        }
        .form-title {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .form-title i {
            font-size: 24px;
            margin-right: 10px;
        }
        .emoji {
            font-size: 24px;
            margin-right: 10px;
        }
        .login-link {
            margin-top: 20px;
            text-align: center;
        }
        .login-link a {
            color: #6a5acd;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .login-link a:hover {
            color: #4b0082;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <h1>✨ User Authentication System ✨</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message <?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <!-- Registration Form -->
        <div class="form-container">
            <div class="form-title">
                <span class="emoji">👤</span>
                <h2>Register</h2>
            </div>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                <div>
                    <input type="submit" name="register" value="Create Account 🚀">
                </div>
            </form>
            <div class="login-link">
                <p>Already have an account? <a href="index.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>
</html>