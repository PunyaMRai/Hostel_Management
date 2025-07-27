<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'punya_minor'); // Update with your DB details

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the form
$id = $_POST['id'];
$status = $_POST['status'];

// Update the status in the database
$sql = "UPDATE payment_confirmations SET status = '$status' WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Payment status updated successfully.');
            window.location.href = 'view_payments.php';
          </script>";
} else {
    echo "<script>
            alert('Error updating payment status.');
            window.location.href = 'view_payments.php';
          </script>";
}

$conn->close();
?>
