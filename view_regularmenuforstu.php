<?php
include 'db_connection.php'; // Include database connection

try {
    // Fetch menu items from the database
    $sql = "SELECT id, item_name, description FROM menu_items";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all menu items as an associative array
    $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<p style='color: red; text-align: center;'>Error: " . $e->getMessage() . "</p>");
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
            background: linear-gradient(135deg, #f7f9fa, #d3e4eb);
            margin: 0;
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #008080;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .no-items {
            text-align: center;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>
<a href="index.html">
            <button class="back-button">Go Back to Dashboard</button>
        </a>
    <h1>Menu Items</h1>
    <?php if (!empty($menu_items)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menu_items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['id']) ?></td>
                        <td><?= htmlspecialchars($item['item_name']) ?></td>
                        <td><?= htmlspecialchars($item['description']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-items">No menu items found.</p>
    <?php endif; ?>
</body>
</html>
