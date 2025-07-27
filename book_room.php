<?php
// Include the database connection
include 'database.php';

// Check if the room_id is set in the URL
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    // Update the room status to 'Occupied'
    $query = "UPDATE rooms SET status = 'Occupied' WHERE room_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $room_id);

    if ($stmt->execute()) {
        echo "<p>Room booked successfully!</p>";
    } else {
        echo "<p>Error booking room: " . $conn->error . "</p>";
    }
}
?>
