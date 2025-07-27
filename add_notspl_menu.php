<?php
include 'db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];

    try {
        // Insert data into the new menu_items table
        $sql = "INSERT INTO menu_items (item_name, description) VALUES (:item_name, :description)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            $message = "<p style='color: green; text-align: center;'>Menu item added successfully!</p>";
        } else {
            $message = "<p style='color: red; text-align: center;'>Failed to add menu item.</p>";
        }
    } catch (PDOException $e) {
        $message = "<p style='color: red; text-align: center;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f0f8ff, #b2d8f7);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Added flex direction for vertical layout */
            height: 100vh;
        }

        /* Go Back Button Styling */
        .go-back-button {
            background-color: #008080;
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            width: auto;
            text-align: center;
            font-size: 18px;
            display: inline-block;
            margin-top: 20px; /* Make sure the button stays at the top */
        }

        .go-back-button:hover {
            background-color: #006666;
        }

        .form-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-container input,
        .form-container textarea,
        .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-container textarea {
            resize: none;
            height: 80px;
        }

        .form-container button {
            background-color: #008080;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #006666;
        }

        .form-container p {
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- Go Back Button -->
    <a href="index1.html" class="go-back-button" title="Return to the Admin Dashboard">Go Back</a>

    <div class="form-container">
        <h2>Add Menu Item</h2>
        <?php if (!empty($message)) echo $message; ?>
        <form method="POST" action="">
            <input type="text" name="item_name" placeholder="Item Name" required>
            <textarea name="description" placeholder="Description" rows="4"></textarea>
            <button type="submit">Add Menu Item</button>
        </form>
    </div>

</body>
</html>
