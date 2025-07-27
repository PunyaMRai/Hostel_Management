<?php
// Start session
session_start();

// Include database connection
include 'database.php';

// Query to get all attendance records
$query = "SELECT * FROM attendance";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View All Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .no-records {
            text-align: center;
            color: #f44336;
            font-weight: bold;
        }

        .go-back-button {
            background-color: #008080;
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            padding: 12px;
            border-radius: 5px;
            width: 800px;
            margin-bottom: 20px;
        }

        .go-back-button:hover {
            background-color: #006666;
        }
    </style>
</head>
<body>
    <a href="index1.html" class="go-back-button" title="Return to the Admin Dashboard">Go Back</a>
    <h2>All Attendance Records</h2>

    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Date</th>
                        <th>Attendance Status</th>
                        <th>Meal Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['student_id'] ?></td>
                            <td><?= $row['date'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td>
                                <?php 
                                    if ($row['meal_status'] == 'None') {
                                        echo 'No meals skipped';
                                    } else {
                                        echo 'Skipped meals: ' . $row['meal_status'];
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-records">No attendance records found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
