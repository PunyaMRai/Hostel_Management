<?php
// Include database connection
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = $_POST['room_number'] ?? null;
    $capacity = $_POST['capacity'] ?? null;
    $hostel_section = $_POST['hostel_section'] ?? null;
    $occupied = 0; // Default to not occupied

    if ($room_number && $capacity && $hostel_section) {
        try {
            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO rooms1 (room_number, capacity, hostel_section, occupied) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iisi", $room_number, $capacity, $hostel_section, $occupied); // i for int, s for string
            if ($stmt->execute()) {
                echo "Room added successfully!";
            } else {
                echo "Error adding room: " . $conn->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h2{
            text-align: center;
            color:#4CAF50;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
        }
        input, select, button {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        a{
            text-decoration:none;
        }
    </style>
</head>
<body>
<a href="index1.html">
            <button class="back-button">Go Back to Dashboard</button>
        </a>
    <h2>Add Room</h2>
    <form method="POST" action="add_room.php">
        <label for="room_number">Room Number:</label>
        <input type="number" name="room_number" id="room_number" required>

        <label for="capacity">Capacity:</label>
        <input type="number" name="capacity" id="capacity" required>

        <label for="hostel_section">Hostel Section:</label>
        <select name="hostel_section" id="hostel_section" required>
            <option value="Mens">Mens</option>
            <option value="Womens">Womens</option>
        </select>

        <button type="submit">Add Room</button>
    </form>
</body>
</html>
