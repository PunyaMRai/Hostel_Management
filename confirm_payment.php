<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'punya_minor'); // Update with your DB details

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the form
$student_id = $_POST['student_id'];
$payment_reference = $_POST['payment_reference'];
$amount = $_POST['amount'];

// Insert the payment confirmation into the database
$sql = "INSERT INTO payment_confirmations (student_id, payment_reference, amount, status)
        VALUES ('$student_id', '$payment_reference', '$amount', 'Pending')";

if ($conn->query($sql) === TRUE) {
    echo "Payment confirmation submitted successfully. We will verify and update the status soon.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
