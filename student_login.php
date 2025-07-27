<?php
session_start();

// Include database connection
include 'database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'email' and 'password' are set in the POST data before accessing them
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Get the form data and remove any leading/trailing whitespace
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Query to verify student credentials
        $query = "SELECT * FROM students WHERE email = ?";  // Use 'email' here
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $email);  // Bind the email parameter
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Fetch the student record
                $row = $result->fetch_assoc();
                
                // Directly compare the plain text password
                if ($password == $row['password']) {
                    // Credentials valid, set session
                    $_SESSION['student_logged_in'] = true;
                    $_SESSION['student_id'] = $row['student_id'];  // Store student ID in session
                    header("Location: index.html");  // Redirect to index.html
                    exit();
                } else {
                    $message = "Invalid email or password.";
                }
            } else {
                $message = "Invalid email or password.";
            }
        } else {
            $message = "Error in query preparation: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <style>
        /* Styling for the page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        form {
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #008080;
        }
        .message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Student Login</h2>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
    </form>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
</body>
</html>
