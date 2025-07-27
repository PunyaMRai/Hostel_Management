<?php
session_start();

// Hardcoded admin credentials (for demonstration)
$admin_username = 'admin';
$admin_password = 'admin123'; // You should hash and securely store the password in production

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate login credentials
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_id'] = session_id(); // Set a session variable for admin
        header("Location: manage_laundry.php"); // Redirect to the manage laundry page
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Failed</title>
</head>
<body>

<?php if (isset($error_message)): ?>
    <p style="color: red; text-align: center;"><?= $error_message ?></p>
<?php endif; ?>

<a href="admin_login.php">Go back to login</a>

</body>
</html>
