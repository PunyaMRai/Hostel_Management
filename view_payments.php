<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'punya_minor'); // Update with your DB details

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch payment confirmations
$sql = "SELECT * FROM payment_confirmations ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Payments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #008080;
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
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #008080;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .button {
            padding: 8px 12px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .approve {
            background-color: #4CAF50;
        }
        .reject {
            background-color: #f44336;
        }
        .go-back-button {
            background-color:#008080; /* Orange-red color */
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            padding: 12px;
            border-radius: 5px;
            width: 800px;
            margin-bottom: 20px; /* Adds some space between the button and the form */
        }
        .go-back-button:hover {
            background-color:#008080; /* Darker orange-red on hover */
        }
    </style>
</head>
<body>
    <h1>Payment Confirmations</h1>
    <a href="index1.html" class="go-back-button" title="Return to the Admin Dashboard">Go Back</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student ID</th>
                <th>Transaction Reference</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['student_id']}</td>
                            <td>{$row['payment_reference']}</td>
                            <td>{$row['amount']}</td>
                            <td>{$row['status']}</td>
                            <td>{$row['created_at']}</td>
                            <td>
                                <form action='update_payment_status.php' method='post' style='display:inline-block;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <input type='hidden' name='status' value='Approved'>
                                    <button class='button approve' type='submit'>Approve</button>
                                </form>
                                <form action='update_payment_status.php' method='post' style='display:inline-block;'>
                                    <input type='hidden' name='id' value='{$row['id']}'>
                                    <input type='hidden' name='status' value='Rejected'>
                                    <button class='button reject' type='submit'>Reject</button>
                                </form>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No payment confirmations found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
