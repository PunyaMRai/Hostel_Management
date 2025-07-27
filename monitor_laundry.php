<?php
$conn = new mysqli("localhost", "root", "", "punya_minor");

$sql = "SELECT b.booking_id, s.name AS student_name, l.start_time, l.end_time
        FROM laundry_bookings b
        JOIN students s ON b.student_id = s.student_id
        JOIN laundry_slots l ON b.slot_id = l.slot_id
        WHERE l.start_time <= NOW() AND l.end_time >= NOW()
        ORDER BY l.start_time";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laundry Monitor</title>
</head>
<body>
    <h1>Current Laundry Usage</h1>
    <table border="1">
        <tr>
            <th>Booking ID</th>
            <th>Student Name</th>
            <th>Slot Time</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['booking_id']; ?></td>
            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
            <td><?php echo $row['start_time'] . " - " . $row['end_time']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
<?php $conn->close(); ?>
