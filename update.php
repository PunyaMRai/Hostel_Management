<?php
session_start();
include 'db_connection.php'; // Include your database connection

$message = '';

// Handle adding a new update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_update'])) {
    $update_title = $_POST['update_title'];
    $update_details = $_POST['update_details'];

    try {
        // Insert new update into the database
        $sql = "INSERT INTO hostel_updates (update_title, update_details) VALUES (:update_title, :update_details)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':update_title', $update_title);
        $stmt->bindParam(':update_details', $update_details);

        if ($stmt->execute()) {
            $message = "<p style='color: green; text-align: center;'>Update added successfully!</p>";
        } else {
            $message = "<p style='color: red; text-align: center;'>Failed to add update.</p>";
        }
    } catch (PDOException $e) {
        $message = "<p style='color: red; text-align: center;'>Error: " . $e->getMessage() . "</p>";
    }
}

// Handle deleting an update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_update'])) {
    $update_id = $_POST['update_id'];

    try {
        // Delete the update from the database
        $sql = "DELETE FROM hostel_updates WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $update_id);

        if ($stmt->execute()) {
            $message = "<p style='color: green; text-align: center;'>Update deleted successfully!</p>";
        } else {
            $message = "<p style='color: red; text-align: center;'>Failed to delete update.</p>";
        }
    } catch (PDOException $e) {
        $message = "<p style='color: red; text-align: center;'>Error: " . $e->getMessage() . "</p>";
    }
}

try {
    // Fetch all updates from the database
    $sql = "SELECT * FROM hostel_updates ORDER BY created_at DESC";
    $stmt = $conn->query($sql); // Execute query
    $updates = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
} catch (PDOException $e) {
    die("Error fetching hostel updates: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Updates</title>
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
        .update-form {
            background: #f4f4f9;
            padding: 20px;
            margin-top: 20px;
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
        .delete-button {
            background-color: #ff4444;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #cc0000;
        }
        .message {
            text-align: center;
        }
        .update {
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
        }
        .update h3 {
            margin: 0 0 10px 0;
        }
        .go-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #008080;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .go-back:hover {
            background-color: #005959;
        }
    </style>
</head>
<body>
    <header>
        <h1>Hostel Updates</h1>
    </header>
    <div class="container">
        <?= $message ?>

        <!-- Display updates for everyone -->
        <h2>Hostel Updates</h2>
        <?php if (!empty($updates)) : ?>
            <?php foreach ($updates as $update) : ?>
                <div class="update">
                    <h3><?= htmlspecialchars($update['update_title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($update['update_details'])) ?></p>
                    <small>Posted on: <?= $update['created_at'] ?></small>
                    <br><br>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="update_id" value="<?= $update['id'] ?>">
                        <button type="submit" name="delete_update" class="delete-button">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No updates available.</p>
        <?php endif; ?>

        <!-- Form to add new update -->
        <h2>Add New Update</h2>
        <div class="update-form">
            <form method="POST">
                <input type="text" name="update_title" placeholder="Update Title (e.g., Special Dinner Today)" required>
                <textarea name="update_details" placeholder="Details about the update" rows="5" required></textarea>
                <button type="submit" name="add_update">Add Update</button>
            </form>
        </div>

        <a href="index1.html" class="go-back">Go Back</a>
    </div>
</body>
</html>
