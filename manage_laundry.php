<?php
session_start(); // Start the session



// Include the database connection file
include 'database.php';

// Handle Add Slot Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_slot'])) {
    $start_time = htmlspecialchars(trim($_POST['start_time']));
    $end_time = htmlspecialchars(trim($_POST['end_time']));
    $seats_available = filter_var($_POST['seats_available'], FILTER_VALIDATE_INT);

    if (!$seats_available || $seats_available < 1) {
        $message = "Invalid number of seats.";
    } else {
        $query = "INSERT INTO laundry_slots (start_time, end_time, seats_available) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssi', $start_time, $end_time, $seats_available);
        if ($stmt->execute()) {
            $message = "New slot added successfully!";
        } else {
            $message = "Error adding slot: " . $conn->error;
        }
    }
}

// Handle Delete Slot
if (isset($_GET['delete'])) {
    $slot_id = $_GET['delete'];

    // Check if there are any bookings before deleting the slot
    $checkQuery = "SELECT * FROM laundry_bookings WHERE slot_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('i', $slot_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $message = "Cannot delete this slot as it has active bookings.";
    } else {
        $query = "DELETE FROM laundry_slots WHERE slot_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $slot_id);
        if ($stmt->execute()) {
            $message = "Slot deleted successfully!";
        } else {
            $message = "Error deleting slot: " . $conn->error;
        }
    }
}

// Fetch All Slots
$slots = $conn->query("SELECT * FROM laundry_slots ORDER BY start_time ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Laundry Slots</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .form-container {
            margin: 20px 0;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            margin-top: 0;
        }
        .form-container form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        input[type="text"], input[type="time"], input[type="number"], button {
            padding: 10px;
            font-size: 1em;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        a.btn {
    position: fixed; /* Fixes the button at a specific position */
    top: 20px; /* Distance from the top of the page */
    left: 20px; /* Distance from the left side of the page */
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
}

a.btn:hover {
    background-color: #45a049;
}

    </style>
</head>
<body>
<div class="container">
        

        <!-- Go Back Button -->
        <a href="index1.html" class="btn" title="Return to the Admin Dashboard">Go Back</a>

       
        
    </div>
    <h1>Manage Laundry Slots</h1>

    <?php if (isset($message)): ?>
        <p style="color: <?= strpos($message, 'Error') !== false ? 'red' : 'green' ?>; text-align: center;">
            <?= $message ?>
        </p>
    <?php endif; ?>

    <!-- Add New Slot Form -->
    <div class="form-container">
        <h2>Add New Slot</h2>
        <form method="POST" action="">
            <input type="time" name="start_time" required placeholder="Start Time">
            <input type="time" name="end_time" required placeholder="End Time">
            <input type="number" name="seats_available" required placeholder="Seats Available" min="1">
            <button type="submit" name="add_slot">Add Slot</button>
        </form>
    </div>

    <!-- Display Existing Slots -->
    <h2>Existing Slots</h2>
    <table>
        <thead>
            <tr>
                <th>Slot ID</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Seats Available</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($slots->num_rows > 0): ?>
                <?php while ($slot = $slots->fetch_assoc()): ?>
                    <tr>
                        <td><?= $slot['slot_id'] ?></td>
                        <td><?= $slot['start_time'] ?></td>
                        <td><?= $slot['end_time'] ?></td>
                        <td><?= $slot['seats_available'] ?></td>
                        <td>
                            <a href="manage_laundry.php?delete=<?= $slot['slot_id'] ?>" onclick="return confirm('Are you sure you want to delete this slot?')">
                                <button class="delete-btn">Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No slots available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
