<!-- filepath: c:\xampp\htdocs\oreintation\school_rep\add_faculty.php -->
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faculty_name = trim($_POST['faculty_name']);

    $stmt = $conn->prepare("INSERT INTO faculties (university_id, faculty_name) VALUES (?, ?)");
    $stmt->bind_param("is", $university_id, $faculty_name);

    if ($stmt->execute()) {
        header("Location: manage_faculties.php");
        exit();
    } else {
        die("Error adding faculty: " . $stmt->error);
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
        <h2>Add New Faculty</h2>
        <form method="POST" action="add_faculty.php">
            <div class="mb-3">
                <label for="faculty_name" class="form-label">Faculty Name</label>
                <input type="text" name="faculty_name" id="faculty_name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Faculty</button>
        </form>
    </div>
    </div>
</body>
</html>