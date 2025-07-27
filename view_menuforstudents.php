<?php
include 'db_connection.php'; // Include the PDO connection

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
        a{
            background-color:#008080;
            text-decoration:none;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
        

        <!-- Go Back Button -->
        <a href="index.html" class="btn" title="Return to the Admin Dashboard">Go Back</a>

       
        
    </div>
    <div class="menu-container">
        <h2 style="text-align: center;">Food Menu</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Price</th>
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
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No menu items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>