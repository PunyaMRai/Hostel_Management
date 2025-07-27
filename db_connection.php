<?php
$servername = "localhost";  // Change $host to $servername
$username = "root";
$password = ""; // Leave blank if there's no password
$dbname = "punya_minor"; // Ensure the database name is correct

try {
    // Corrected: Use $servername and $dbname consistently
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Uncomment the line below if you want to confirm the connection
    // echo "Connected successfully"; 
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
