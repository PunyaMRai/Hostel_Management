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

// Fetch feedback data
$sql = "SELECT f.feedback_id, s.name, f.feedback_text, f.date_submitted 
        FROM feedback AS f
        JOIN students AS s ON f.student_id = s.student_id";

$result = $conn->query($sql);

// Check for SQL query errors
if (!$result) {
    die("Error in query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <style>
        /* Add some styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        h1 {
            color: #008080;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #008080;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Go Back Button Styling */
        .go-back-button {
            background-color: #008080; /* Original color */
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            padding: 12px 20px; /* Adjusted padding */
            border-radius: 8px; /* Rounded corners */
            width: auto;
            text-align: center;
            font-size: 18px; /* Larger font size */
            display: inline-block;
            margin-bottom: 20px; /* Space between button and table */
            margin-top: 10px; /* Space between the top and button */
        }

        .go-back-button:hover {
            background-color: #006666; /* Darker color on hover */
        }
    </style>
</head>
<body>
    <!-- Go Back Button -->
    <a href="index1.html" class="go-back-button" title="Return to the Admin Dashboard">Go Back</a>

    <h1>Feedback Records</h1>
    <table>
        <thead>
            <tr>
                <th>Feedback ID</th>
                <th>Student Name</th>
                <th>Feedback</th>
                <th>Date Submitted</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["feedback_id"]) . "</td>
                            <td>" . htmlspecialchars($row["name"]) . "</td>
                            <td>" . htmlspecialchars($row["feedback_text"]) . "</td>
                            <td>" . htmlspecialchars($row["date_submitted"]) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No feedback found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
