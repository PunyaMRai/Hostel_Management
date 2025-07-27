<?php
include 'db_connection.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    try {
        // Use prepared statements with PDO
        $sql = "INSERT INTO food_menu (item_name, description, price) VALUES (:item_name, :description, :price)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);

        if ($stmt->execute()) {
            echo "<p style='color: green; text-align: center;'>Menu item added successfully!</p>";
        } else {
            echo "<p style='color: red; text-align: center;'>Failed to add menu item.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red; text-align: center;'>Error: " . $e->getMessage() . "</p>";
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
            height: 100vh;
            flex-direction: column; /* Align elements vertically */
        }

        .go-back-button {
            background-color: #008080; /* Original color */
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            padding: 15px 25px; /* Larger padding for bigger button */
            border-radius: 10px; /* Larger radius for more prominent shape */
            width: auto;
            margin-bottom: 20px; /* Adds space between the button and the form */
            text-align: center;
            font-size: 18px; /* Larger font size */
        }

        .go-back-button:hover {
            background-color: #006666; /* Darker on hover */
        }

        .form-container {
            background-color: #fff;
            border-radius: 15px; /* Increased radius for a bigger look */
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3); /* Bigger shadow for more emphasis */
            padding: 40px 50px; /* Larger padding for a bigger form */
            width: 100%;
            max-width: 600px; /* Wider form */
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 30px;
            color: #333;
            font-size: 30px; /* Larger header font */
            font-weight: bold;
        }

        .form-container input,
        .form-container textarea,
        .form-container button {
            width: 100%;
            padding: 15px; /* Larger padding for inputs and buttons */
            margin-bottom: 20px; /* More space between inputs */
            border: 1px solid #ccc;
            border-radius: 8px; /* Rounded corners */
            font-size: 18px; /* Larger font size */
        }

        .form-container textarea {
            resize: none;
            height: 120px; /* Larger textarea */
        }

        .form-container button {
            background-color: #008080;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px; /* Larger button font */
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #006666; /* Darker on hover */
        }

        .form-container p {
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <!-- Go Back Button placed at the top -->
    <a href="index1.html" class="go-back-button" title="Return to the Admin Dashboard">Go Back</a>

    <!-- Centered Form Container -->
    <div class="form-container">
        <h2>Add Menu Item</h2>
        <form method="POST" action="add_menu_item.php">
            <input type="text" name="item_name" placeholder="Item Name" required>
            <textarea name="description" placeholder="Description" rows="4"></textarea>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <button type="submit">Add Menu Item</button>
        </form>
    </div>

</body>
</html>
