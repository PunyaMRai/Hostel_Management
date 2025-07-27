<?php
// Query to get available rooms
$query = "SELECT * FROM rooms WHERE status = 'Available' ORDER BY room_id";
$result = $conn->query($query);

// Check if rooms are available
if ($result->num_rows > 0) {
    echo "<h2>Available Rooms</h2>";
    echo "<table border='1'>";
    echo "<thead><tr><th>Room ID</th><th>Room Type</th><th>Availability</th><th>Action</th></tr></thead><tbody>";

    // Loop through and display available rooms
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['room_id'] . "</td>";
        echo "<td>" . $row['room_type'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td><a href='book_room.php?room_id=" . $row['room_id'] . "'>Book Room</a></td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>No available rooms at the moment.</p>";
}
?>
