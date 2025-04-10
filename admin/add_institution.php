<?php
session_start();
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $description = $_POST['description'];

    // Handle the image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "../uploads/";
        $imageName = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $imageName;

        // Ensure the uploads directory exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $imagePath = "uploads/" . $imageName; // Save relative path for database
        } else {
            die("Error uploading the image.");
        }
    } else {
        die("No image uploaded or an error occurred.");
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO institutions (name, contact, description, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $contact, $description, $imagePath);
    $stmt->execute();
    header("Location: institutions.php");
}
?>
