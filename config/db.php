<?php
$host = "localhost";
$user = "root"; // Change this if you have a different MySQL user
$pass = ""; // Change if you have a password set
$dbname = "orientation_system";

// Create Connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
