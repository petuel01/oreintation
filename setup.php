<?php
$host = "localhost";
$user = "root"; // Change if you have a different MySQL user
$pass = ""; // Change if you have a MySQL password
$dbname = "orientation_system";

// Create connection
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create 'admins' table
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'admins' created successfully.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Insert default admin if not exists
$admin_check = $conn->query("SELECT * FROM admins WHERE username='admin'");
if ($admin_check->num_rows == 0) {
    $password = hash("sha256", "admin123"); // Hash password
    $conn->query("INSERT INTO admins (username, password) VALUES ('admin', '$password')");
    echo "Default admin account created (Username: admin, Password: admin123).<br>";
}

// Create 'institutions' table
$sql = "CREATE TABLE IF NOT EXISTS institutions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    contact VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    ranking INT NOT NULL,
    category VARCHAR(255) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'institutions' created successfully.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Create 'careers' table
$sql = "CREATE TABLE IF NOT EXISTS careers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'careers' created successfully.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Create 'users' table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('student', 'school_representative') NOT NULL,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

$conn->close();
echo "Setup completed successfully!";
?>
