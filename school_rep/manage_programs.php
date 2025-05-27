<!-- filepath: c:\xampp\htdocs\oreintation\school_rep\manage_programs.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'school_rep' || $_SESSION['status'] !== 'approved') {
    header("Location: ../auth/login.php");
    exit();
}

include '../config/db.php';

// Ensure university_id is set in the session
if (!isset($_SESSION['university_id'])) {
    die("Error: University ID is not set in the session. Please log in again.");
}

$university_id = $_SESSION['university_id'];

// Fetch programs for the university
$programs = [];
$result = $conn->query("SELECT p.id, p.program_name, p.duration, p.degree_type, p.language FROM programs p 
                        JOIN faculties f ON p.faculty_id = f.id 
                        WHERE f.university_id = $university_id");
if ($result) {
    $programs = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM programs WHERE id = $delete_id");
    header("Location: manage_programs.php");
    exit();
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
        <h2>Manage Programs</h2>
        <a href="add_program.php" class="btn btn-success mb-3">Add New Program</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Program Name</th>
                    <th>Duration</th>
                    <th>Degree Type</th>
                    <th>Language</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($programs)): ?>
                    <?php foreach ($programs as $index => $program): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($program['program_name']); ?></td>
                            <td><?= htmlspecialchars($program['duration']); ?></td>
                            <td><?= htmlspecialchars($program['degree_type']); ?></td>
                            <td><?= htmlspecialchars($program['language']); ?></td>
                            <td>
                                <a href="edit_program.php?id=<?= $program['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="manage_programs.php?delete_id=<?= $program['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this program?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No programs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
</body>
</html>