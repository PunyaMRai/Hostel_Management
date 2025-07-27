<?php
include 'db_connection.php'; // Include the PDO connection

// Handle deletion if delete_id is set
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $sql = "DELETE FROM food_menu WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $delete_id);
        if ($stmt->execute()) {
            echo "<p style='color: green; text-align: center;'>Menu item deleted successfully!</p>";
        } else {
            echo "<p style='color: red; text-align: center;'>Failed to delete menu item.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red; text-align: center;'>Error: " . $e->getMessage() . "</p>";
    }
}

try {
    // Fetch all menu items using PDO
    $sql = "SELECT * FROM food_menu";
    $stmt = $conn->query($sql); // Execute the query
    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch data as an associative array
} catch (PDOException $e) {
    die("Error fetching menu items: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f9f9f9, #e0e7ff);
            margin: 0;
            padding: 0;
        }
        .menu-container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px 15px;
            text-align: left;
            border: 1px solid #ccc;
        }
        th {
            background-color: #008080;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .delete-btn {
            color: white;
            background-color: #f44336;
            border: none;
            padding: 5px 10px;
            text-decoration: none;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
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
        }
        .go-back-button:hover {
            background-color: #006666; /* Darker color on hover */
        }
    </style>
</head>
<body>
    <!-- Go Back Button -->
    <a href="index1.html" class="go-back-button" title="Return to the Admin Dashboard">Go Back</a>

    <div class="menu-container">
        <h2 style="text-align: center;">Food Menu</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($menuItems)) : ?>
                    <?php foreach ($menuItems as $item) : ?>
                        <tr>
                            <td><?= htmlspecialchars($item['id']) ?></td>
                            <td><?= htmlspecialchars($item['item_name']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td><?= htmlspecialchars($item['price']) ?></td>
                            <td>
                                <a class="delete-btn" href="view_menu.php?delete_id=<?= $item['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No menu items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
