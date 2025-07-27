<?php
// Include your database connection
include 'database.php';

// Inserting a password hash for testing
$hashed_password = password_hash('password123', PASSWORD_DEFAULT);

// Define the email address as a variable
$email = 'punyarai2003@gmail.com';

// Update the password for a student in the database
$query = "UPDATE students SET password = ? WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $hashed_password, $email);  // Pass the email as a variable
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Password updated successfully!";
} else {
    echo "No changes made. Check the email address.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
