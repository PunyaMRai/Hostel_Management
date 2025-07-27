<?php
// Include the database connection file
include 'database.php';

// Fetch available rooms (where occupied = 0)
$availableRoomsQuery = "SELECT * FROM rooms1 WHERE occupied = 0 ORDER BY room_id";
$availableRoomsResult = $conn->query($availableRoomsQuery);

// Fetch all rooms
$allRoomsQuery = "SELECT * FROM rooms1 ORDER BY room_id";
$allRoomsResult = $conn->query($allRoomsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Rooms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .rooms-section {
            margin-bottom: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status {
            font-weight: bold;
        }
        .available {
            color: green;
        }
        .occupied {
            color: red;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
        

        <!-- Go Back Button -->
        <a href="index1.html" class="btn" title="Return to the Admin Dashboard">Go Back</a>

       
        
    </div>

<div class="container">
    <h1>Room Management</h1>

    <!-- Display Available Rooms -->
    <div class="rooms-section">
        <h2>Available Rooms</h2>
        <?php if ($availableRoomsResult->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Room Number</th>
                        <th>Capacity</th>
                        <th>Hostel Section</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($room = $availableRoomsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $room['room_number']; ?></td>
                            <td><?php echo $room['capacity']; ?></td>
                            <td><?php echo $room['hostel_section']; ?></td>
                            <td class="status available">Available</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No available rooms at the moment.</p>
        <?php endif; ?>
    </div>

    <!-- Display All Rooms (Including Occupied) -->
    <div class="rooms-section">
        <h2>All Rooms</h2>
        <?php if ($allRoomsResult->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Room Number</th>
                        <th>Capacity</th>
                        <th>Hostel Section</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($room = $allRoomsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $room['room_number']; ?></td>
                            <td><?php echo $room['capacity']; ?></td>
                            <td><?php echo $room['hostel_section']; ?></td>
                            <td class="status <?php echo ($room['occupied'] == 0) ? 'available' : 'occupied'; ?>">
                                <?php echo ($room['occupied'] == 0) ? 'Available' : 'Occupied'; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No rooms found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
