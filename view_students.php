<?php
session_start(); // Start the session



// Include the database connection
include 'database.php';

$message = ''; // Initialize message variable

// Handle Delete Student
if (isset($_GET['delete']) && filter_var($_GET['delete'], FILTER_VALIDATE_INT)) {
    $student_id = $_GET['delete'];

    // Delete related feedback records first
    $deleteFeedbackQuery = "DELETE FROM feedback WHERE student_id = ?";
    $deleteFeedbackStmt = $conn->prepare($deleteFeedbackQuery);
    $deleteFeedbackStmt->bind_param('i', $student_id);

    if (!$deleteFeedbackStmt->execute()) {
        $message = "Error deleting feedback. Please try again later.";
    } else {
        // Now delete the student
        $query = "DELETE FROM students WHERE student_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $student_id);
        if ($stmt->execute()) {
            $message = "Student deleted successfully!";
        } else {
            $message = "Error deleting student. Please try again later.";
        }
    }
} else if (isset($_GET['delete'])) {
    $message = "Invalid student ID.";
}

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
    </style>
</head>
<body>
    <div class="container">
        <a href="index1.html" class="btn" title="Return to the Admin Dashboard">Go Back</a>
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($studentsResult->num_rows > 0): ?>
                <?php while ($student = $studentsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= $student['student_id'] ?></td>
                        <td><?= $student['name'] ?></td>
                        <td><?= $student['room_number'] ?></td>
                        <td>
                            <a href="view_students.php?delete=<?= $student['student_id'] ?>" onclick="return confirm('Are you sure you want to delete this student?')">
                                <button class="delete-btn">Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No students available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
