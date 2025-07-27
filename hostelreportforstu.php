<?php
session_start(); // Ensure session is started

include 'db_connection.php'; // Include your database connection

$message = '';

// Handle adding a new facility
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_facility'])) {
    $facility_name = $_POST['facility_name'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    try {
        // Insert new facility into the database
        $sql = "INSERT INTO hostel_facilities (facility_name, location, description) VALUES (:facility_name, :location, :description)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':facility_name', $facility_name);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            $message = "<p style='color: green; text-align: center;'>Facility added successfully!</p>";
        } else {
            $message = "<p style='color: red; text-align: center;'>Failed to add facility.</p>";
        }
    } catch (PDOException $e) {
        $message = "<p style='color: red; text-align: center;'>Error: " . $e->getMessage() . "</p>";
    }
}

try {
    // Fetch facilities from the database
    $sql = "SELECT * FROM hostel_facilities";
    $stmt = $conn->query($sql); // Execute query
    $facilities = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
} catch (PDOException $e) {
    die("Error fetching hostel facilities: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Facilities Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #008080;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-bottom: 20px;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
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
            text-align: left;
        }
        th {
            background-color: #008080;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        form {
            margin-top: 20px;
            background: #f4f4f9;
            padding: 20px;
            border-radius: 8px;
        }
        form input, form textarea, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            background-color: #008080;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        form button:hover {
            background-color: #005959;
        }
        .message {
            text-align: center;
        }
        a.go-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #008080;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        a.go-back:hover {
            background-color: #005959;
        }
    </style>
</head>
<body>
    <header>
        <h1>Hostel Facilities Report</h1>
    </header>
    <div class="container">
        <?= $message ?>
        <h2>Facilities List</h2>
        <?php if (!empty($facilities)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Facility Name</th>
                        <th>Location</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($facilities as $facility) : ?>
                        <tr>
                            <td><?= htmlspecialchars($facility['facility_name']) ?></td>
                            <td><?= htmlspecialchars($facility['location']) ?></td>
                            <td><?= htmlspecialchars($facility['description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p style="text-align: center;">No facilities data available.</p>
        <?php endif; ?>

       

        <a href="index.html" class="go-back">Go Back</a>
    </div>
</body>
</html>
