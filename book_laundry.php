<?php
session_start();
include 'database.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    die("Please login to book a slot.");
}

$student_id = $_SESSION['student_id'];
$message = ""; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slot_id = $_POST['slot_id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Check if the slot has available seats
        $query = "SELECT seats_available FROM laundry_slots WHERE slot_id = ? AND seats_available > 0 FOR UPDATE";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param('i', $slot_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $slot = $result->fetch_assoc();

        if ($slot) {
            // Update the available seats
            $updateQuery = "UPDATE laundry_slots SET seats_available = seats_available - 1 WHERE slot_id = ?";
            $updateStmt = $conn->prepare($updateQuery);

            if (!$updateStmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $updateStmt->bind_param('i', $slot_id);
            $updateStmt->execute();

            // Insert the booking
            $insertQuery = "INSERT INTO laundry_bookings (slot_id, student_id) VALUES (?, ?)";
            $insertStmt = $conn->prepare($insertQuery);

            if (!$insertStmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $insertStmt->bind_param('ii', $slot_id, $student_id);
            $insertStmt->execute();

            // Commit transaction
            $conn->commit();

            $message = "Slot booked successfully!";
        } else {
            $message = "Slot is no longer available.";
            $conn->rollback();
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
    }
}

// Fetch available slots
$slots = $conn->query("SELECT * FROM laundry_slots WHERE seats_available > 0");
if (!$slots) {
    die("Error fetching slots: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Laundry Slot</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            width: 600px;
            text-align: center;
        }
        h1 {
            font-size: 2em;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        select, button {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1.2em;
        }
        button {
            background: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
        .message {
            margin-bottom: 15px;
            font-size: 1.2em;
            color: green;
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

        

        <!-- Go Back Button -->
        <a href="index.html" class="btn" title="Return to the Admin Dashboard">Go Back</a>

       
        
    
    <div class="container">
        
        <h1>Book Laundry Slot</h1>
        <?php if (!empty($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($slots->num_rows > 0): ?>
            <form action="book_laundry.php" method="POST">
                <label for="slot">Select Slot:</label>
                <select name="slot_id" id="slot" required>
                    <?php while ($slot = $slots->fetch_assoc()): ?>
                        <option value="<?= $slot['slot_id'] ?>">
                            <?= $slot['start_time'] ?> - <?= $slot['end_time'] ?> (<?= $slot['seats_available'] ?> seats left)
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Book Slot</button>
            </form>
        <?php else: ?>
            <p>No available slots found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
