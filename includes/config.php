<?php
$host = "localhost";
$user = "root"; // Change if using a different MySQL user
$pass = "P@ssw0rd"; // Add password if applicable
$dbname = "watchent_db"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
