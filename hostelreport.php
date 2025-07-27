<?php
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

// Handle deleting a facility
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_facility'])) {
    $facility_id = $_POST['facility_id'];

    try {
        // Delete the facility from the database
        $sql = "DELETE FROM hostel_facilities WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $facility_id);

        if ($stmt->execute()) {
            // Success, show a message
            $message = "<p style='color: green; text-align: center;'>Facility deleted successfully!</p>";
        } else {
            // Failure, show an error message
            $message = "<p style='color: red; text-align: center;'>Failed to delete facility.</p>";
        }
    } catch (PDOException $e) {
        // Error while trying to delete
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
        .new{
            width:100px;
        }
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
            border-radius: 8px;
        }
        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            width: 200px;  /* Make the button wider */
            padding: 15px;  /* Increase the padding for a bigger button */
            font-size: 16px; /* Larger text */
            background-color: #008080;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        form button:hover {
            background-color: #005959; /* Slightly darker shade on hover */
        }
        .delete-button {
            background-color: #ff4444;
            color: white;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #cc0000;
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($facilities as $facility) : ?>
                        <tr>
                            <td><?= htmlspecialchars($facility['facility_name']) ?></td>
                            <td><?= htmlspecialchars($facility['location']) ?></td>
                            <td><?= htmlspecialchars($facility['description']) ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="facility_id" value="<?= $facility['id'] ?>">
                                    <button type="submit" name="delete_facility" class="delete-button">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p style="text-align: center;">No facilities data available.</p>
        <?php endif; ?>

        <h2>Add New Facility</h2>
        <form method="POST">
            <input type="hidden" name="add_facility" value="1">
            <input type="text" name="facility_name" placeholder="Facility Name" required>
            <input type="text" name="location" placeholder="Location (e.g., 2nd Floor)" required>
            <textarea name="description" placeholder="Description of the facility" rows="5" required></textarea>
            <button type="submit" id="new">Add Facility</button>
        </form>

        <a href="index1.html" class="go-back">Go Back</a>
    </div>
</body>
</html>
