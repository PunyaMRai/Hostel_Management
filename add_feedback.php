<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "punya_minor";

// Establish the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$feedbackText = $studentId = "";
$feedbackErr = $idErr = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate student ID
    if (empty($_POST["student_id"])) {
        $idErr = "Student ID is required";
    } elseif (!is_numeric($_POST["student_id"])) {
        $idErr = "Invalid Student ID";
    } else {
        $studentId = $_POST["student_id"];
        
        // Check if the student_id exists in the students table
        $checkStudent = $conn->prepare("SELECT student_id FROM students WHERE student_id = ?");
        $checkStudent->bind_param("i", $studentId);
        $checkStudent->execute();
        $checkStudent->store_result();
        
        if ($checkStudent->num_rows == 0) {
            $idErr = "Student ID does not exist in the system";
        }
        $checkStudent->close();
    }

    // Validate feedback text
    if (empty($_POST["feedback_text"])) {
        $feedbackErr = "Feedback is required";
    } else {
        $feedbackText = trim($_POST["feedback_text"]);
    }

    // Insert data into the database if there are no errors
    if (empty($idErr) && empty($feedbackErr)) {
        $stmt = $conn->prepare("INSERT INTO feedback (student_id, feedback_text, date_submitted) VALUES (?, ?, NOW())");

        if ($stmt) {
            $stmt->bind_param("is", $studentId, $feedbackText);
            if ($stmt->execute()) {
                echo "<p style='color:green;'>Feedback submitted successfully!</p>";
            } else {
                echo "<p style='color:red;'>Error submitting feedback: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color:red;'>Prepare statement failed: " . $conn->error . "</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Feedback</title>
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        h1 {
            color: #008080;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 0 auto;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            font-size: 0.9em;
        }

        .success {
            color: green;
            font-size: 1em;
        }
        h1{
            text-align: center;
        }
    </style>
</head>
<body>
<a href="index.html">
            <button class="back-button">Go Back to Dashboard</button>
        </a>
    <h1>Add Feedback</h1>
    <div class="form-container">
        <form method="POST" action="add_feedback.php">
            <label for="student_id">Student ID:</label>
            <input type="text" id="student_id" name="student_id" value="<?= htmlspecialchars($studentId) ?>">
            <div class="error"><?= $idErr ?></div>

            <label for="feedback_text">Feedback:</label>
            <textarea id="feedback_text" name="feedback_text"><?= htmlspecialchars($feedbackText) ?></textarea>
            <div class="error"><?= $feedbackErr ?></div>

            <button type="submit">Submit Feedback</button>
        </form>
    </div>
</body>
</html>
