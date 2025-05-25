<!-- filepath: c:\xampp\htdocs\oreintation\school_rep\edit_logo.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'school_rep' || $_SESSION['status'] !== 'approved') {
    header("Location: ../login.php");
    exit();
}

include '../config/db.php';

// Ensure university_id is set in the session
if (!isset($_SESSION['university_id'])) {
    die("Error: University ID is not set in the session. Please log in again.");
}

$university_id = $_SESSION['university_id'];

// Handle logo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo_tmp_name = $_FILES['logo']['tmp_name'];
        $logo_name = basename($_FILES['logo']['name']);
        $file_extension = pathinfo($logo_name, PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        // Validate file type
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            die("Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.");
        }

        // Ensure the uploads directory exists
        $uploads_dir = "../uploads/logos/";
        if (!is_dir($uploads_dir)) {
            if (!mkdir($uploads_dir, 0777, true)) {
                die("Failed to create directory for logo uploads.");
            }
        }

        // Generate unique file name
        $logo_path = $uploads_dir . uniqid("university_logo_", true) . "." . $file_extension;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($logo_tmp_name, $logo_path)) {
            // Save the relative path to the database
            $relative_logo_path = substr($logo_path, 3); // Remove "../" for database storage
            $stmt = $conn->prepare("UPDATE universities SET logo = ? WHERE id = ?");
            $stmt->bind_param("si", $relative_logo_path, $university_id);

            if ($stmt->execute()) {
                header("Location: dashboard.php");
                exit();
            } else {
                die("Error updating logo: " . $stmt->error);
            }
        } else {
            die("Error uploading logo.");
        }
    } else {
        die("No file uploaded or an error occurred.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Rep Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
</head>
<body>
    <div class="d-flex flex-column flex-md-row">
        <!-- Sidebar -->
        <nav class="sidebar">
            <?php include("sidebar.php"); ?>
        </nav>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
        <h2>Update University Logo</h2>
        <form method="POST" action="edit_logo.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="logo" class="form-label">Upload New Logo</label>
                <input type="file" name="logo" id="logo" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Logo</button>
        </form>
    </div>
    </div>
</body>
</html>