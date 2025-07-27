<?php
// Assuming database connection is already established
// Replace with your database connection code
// Example: $conn = new mysqli("localhost", "root", "", "punya_minor");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            max-width: 500px;
            width: 100%;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        select, button {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #008080;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h1>Laundry Booking</h1>

        <form action="book_laundry.php" method="POST">
            <label for="slot">Select Slot:</label>
            <select name="slot_id" id="slot" required>
                <?php
                // Fetch available laundry slots
                $slots = $conn->query("SELECT * FROM laundry_slots WHERE seats_available > 0");
                while ($slot = $slots->fetch_assoc()) {
                    echo "<option value='{$slot['slot_id']}'>
                            {$slot['start_time']} - {$slot['end_time']} ({$slot['seats_available']} seats left)
                          </option>";
                }
                ?>
            </select>

            <!-- Replace '101' with dynamic student ID from session or login -->
            <input type="hidden" name="student_id" value="101"> <!-- Example: Student with ID 101 -->

            <button type="submit">Book Slot</button>
        </form>
    </div>

</body>
</html>
