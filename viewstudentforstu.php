<?php
session_start(); // Start the session

// Include the database connection
include 'database.php';

$message = ''; // Initialize message variable

// Fetch all students
$studentsQuery = "SELECT student_id, name, COALESCE(room_number, 'No Room Assigned') AS room_number FROM students";
$studentsResult = $conn->query($studentsQuery);

if (!$studentsResult) {
    die("Error executing query: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
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
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #008080;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.html" class="btn" title="Return to the Admin Dashboard">Go Back</a>
    </div>
    <h1>Students List</h1>

    <?php if (!empty($message)): ?>
        <p style="color: <?= strpos($message, 'Error') !== false ? 'red' : 'green'; ?>; text-align: center;">
            <?= $message ?>
        </p>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Room</th>
                <!-- Removed Actions column -->
            </tr>
        </thead>
        <tbody>
            <?php if ($studentsResult->num_rows > 0): ?>
                <?php while ($student = $studentsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= $student['student_id'] ?></td>
                        <td><?= $student['name'] ?></td>
                        <td><?= $student['room_number'] ?></td>
                        <!-- Removed Actions cell -->
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No students available.</td> <!-- Adjusted colspan for no actions column -->
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
