<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #f3f9f9, #d9e4e4);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
        .form-container {
            background-color: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            margin-top: 0;
            text-align: center;
            color: #008080;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .form-container input,
        .form-container select,
        .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }
        .form-container select {
            appearance: none;
        }
        .form-container button {
            background-color: #007BFF; /* Blue color */
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-container button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        .message {
            text-align: center;
            margin-top: 10px;
            font-size: 1em;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Go Back Button placed at the top -->
    <a href="index1.html" class="go-back-button" title="Return to the Admin Dashboard">Go Back</a>

    <div class="form-container">
        <h2>Add New Student</h2>
        <?php
        include 'db_connection.php';

        $message = ""; // Initialize the message variable
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get data from POST
            $name = $_POST['name'];
            $gender = $_POST['gender'];
            $room_number = $_POST['room_number'];
            $contact = $_POST['contact'];
            $email = $_POST['email'];

            try {
                // Check if contact number is exactly 10 digits
                if (strlen($contact) != 10 || !is_numeric($contact)) {
                    $message = "Contact number must be exactly 10 digits.";
                } else {
                    // Begin transaction
                    $conn->beginTransaction();

                    // Step 1: Insert the student
                    $stmt = $conn->prepare("INSERT INTO students (name, gender, room_number, contact_number, email, date_of_admission) VALUES (?, ?, ?, ?, ?, NOW())");
                    $stmt->execute([$name, $gender, $room_number, $contact, $email]);

                    // Step 2: Update the room occupancy
                    $updateRoomSql = "UPDATE rooms1 SET occupied = occupied + 1 WHERE room_number = :room_number";
                    $stmt = $conn->prepare($updateRoomSql);
                    $stmt->execute([':room_number' => $room_number]);

                    // Commit the transaction
                    $conn->commit();

                    $message = "Student added successfully and room occupancy updated!";
                }
            } catch (PDOException $e) {
                // Rollback if there's an error
                $conn->rollBack();
                $message = "Error: " . $e->getMessage();
            }
        }
        ?>

        <!-- Student Add Form -->
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter full name" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="" disabled selected>Select gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label for="room_number">Room Number:</label>
            <input type="number" id="room_number" name="room_number" placeholder="Enter room number" required>

            <label for="contact">Contact Number:</label>
            <input type="text" id="contact" name="contact" placeholder="Enter contact number" pattern="\d{10}" maxlength="10" required title="Please enter a valid 10-digit contact number">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter email address" required>

            <button type="submit">Add Student</button>
        </form>

        <!-- Display success or error message -->
        <?php if (!empty($message)): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>
    </div>

</body>
</html>
