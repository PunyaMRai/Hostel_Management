<?php
session_start();
include 'database.php'; // Include your database connection file

// Debugging: Print out the $_POST array to see what is being passed
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_POST);  // Print out the entire POST array
    echo "</pre>";
    
    // Check if the email and password fields are set
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if the student exists
        $query = "SELECT * FROM students WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        if ($student && password_verify($password, $student['password'])) {
            $_SESSION['student_id'] = $student['student_id'];
            header('Location: book_laundry.php'); // Redirect to the booking page
            exit;
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Please enter both email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
</head>
<body>
    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
