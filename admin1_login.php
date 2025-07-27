<?php
session_start();

// Include database connection
include 'database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to verify administrator credentials
    $query = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Credentials valid, set session
            $_SESSION['admin_logged_in'] = true;
            header("Location: index1.html");
            exit();
        } else {
            $message = "Invalid username or password.";
        }
    } else {
        $message = "Error in query preparation: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #333;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        form {
            background: #f9f9f9;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        form h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #28a745;
        }

        label {
            margin-bottom: 8px;
            display: block;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        button:hover {
            background: #1e7e34;
        }

        .message {
            text-align: center;
            color: red;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .footer a {
            color: #28a745;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="">
            <h2>Administrator Login</h2>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Enter your username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
            
            <button type="submit">Login</button>

            <?php if ($message): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </form>
    </div>
    <div class="footer">
        <p>Need help? <a href="#">Contact support</a></p>
    </div>
</body>
</html>
