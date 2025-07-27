<?php
// Start session and ensure student is logged in
session_start();
if (!isset($_SESSION['student_id'])) {
    die("Error: You must be logged in as a student to mark attendance.");
}

// Include database connection
include 'database.php';

// Fetch the student's ID from the session
$student_id = $_SESSION['student_id'];

// Initialize a message variable
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the current date
    $date = date('Y-m-d');
    $status = $_POST['status'];  // Present or Absent
    
    // Check if skipped_meals is set (handle if no meals are skipped)
    $skipped_meals = isset($_POST['skipped_meals']) ? $_POST['skipped_meals'] : [];

    // If no meals are skipped, set it as 'None'
    $meal_status = empty($skipped_meals) ? 'None' : implode(', ', $skipped_meals);

    // Insert attendance record
    $query = "INSERT INTO attendance (student_id, date, status, meal_status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $student_id, $date, $status, $meal_status);

    if ($stmt->execute()) {
        $message = "Attendance and meal status marked successfully!";
    } else {
        $message = "Error marking attendance: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        select, button, input[type="checkbox"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .checkbox-group {
            margin-bottom: 20px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<a href="index.html">
    <button class="back-button">Go Back to Dashboard</button>
</a>
<h2>Mark Attendance</h2>

<div class="container">
    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="status">Attendance Status:</label>
            <select name="status" id="status" required>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
            </select>
        </div>

        <div class="checkbox-group">
            <label>Meals You Are Skipping:</label>
            <label><input type="checkbox" name="skipped_meals[]" value="Breakfast"> Breakfast</label><br>
            <label><input type="checkbox" name="skipped_meals[]" value="Lunch"> Lunch</label><br>
            <label><input type="checkbox" name="skipped_meals[]" value="Dinner"> Dinner</label><br>
            <!-- Removed the 'None' checkbox because it's redundant with the logic above -->
        </div>

        <button type="submit">Submit Attendance</button>
    </form>
</div>
</body>
</html>
