<?php
include("config/db.php");
$name = 'Baifem Petuel';
$email = 'baifempetuel@gmail.com';
$password = 'Admin123!';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$role = 'admin';
$createdAt = date('Y-m-d H:i:s');

// Database connection and insertion
$sql = "INSERT INTO users (name, email, password, role, created_at) 
        VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $email, $hashedPassword, $role, $createdAt]);
?>