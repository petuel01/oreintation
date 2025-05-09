<?php
session_start();
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $description = trim($_POST['description']);
    $motto = trim($_POST['motto']);
    $established_year = intval($_POST['established_year']);
    $type = trim($_POST['type']);
    $accreditation_status = trim($_POST['accreditation_status']);
    $website = trim($_POST['website']);

    // Validate inputs
    if (empty($name) || empty($contact) || empty($description) || empty($type) || empty($accreditation_status) || empty($website)) {
        die("All fields are required.");
    }

    // Handle the logo upload
    $logoPath = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['logo']['tmp_name']);
        $fileSize = $_FILES['logo']['size'];

        // Validate file type
        if (!in_array($fileType, $allowedTypes)) {
            die("Invalid file type for logo. Only JPG, PNG, GIF, and WEBP are allowed.");
        }

        // Validate file size (max 5MB)
        if ($fileSize > 5 * 1024 * 1024) {
            die("Logo file size exceeds the maximum limit of 5MB.");
        }

        // Sanitize and generate a unique file name
        $targetDir = "../uploads/logos/";
        $fileExtension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $uniqueFileName = uniqid("university_logo_", true) . "." . $fileExtension;
        $targetFilePath = $targetDir . $uniqueFileName;

        // Ensure the uploads directory exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath)) {
            $logoPath = "uploads/logos/" . $uniqueFileName; // Save relative path for database
        } else {
            die("Error uploading the logo.");
        }
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO universities (name, contact, description, logo, motto, established_year, type, accreditation_status, website) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sssssssss", $name, $contact, $description, $logoPath, $motto, $established_year, $type, $accreditation_status, $website);

    if ($stmt->execute()) {
        header("Location: institutions.php");
        exit();
    } else {
        die("Error inserting university: " . $stmt->error);
    }
}
?>