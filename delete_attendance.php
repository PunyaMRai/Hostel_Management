<?php
// Include database connection
include 'database.php';

// Check if the attendance ID is passed via POST
if (isset($_POST['attendance_id'])) {
    $attendance_id = $_POST['attendance_id'];

    // Prepare the DELETE query
    $delete_query = "DELETE FROM attendance WHERE attendance_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $attendance_id); // Bind the attendance ID as an integer
    $stmt->execute(); // Execute the delete query

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        // If successful, redirect to the attendance view page with a success message
        header("Location: view_attendance.php?message=Record Deleted Successfully");
    } else {
        // If no rows were affected, show an error
        header("Location: view_attendance.php?message=Failed to Delete Record");
    }

    exit(); // Ensure that the script stops executing after the redirect
}
?>
