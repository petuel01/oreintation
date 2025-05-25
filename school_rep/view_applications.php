<!-- filepath: c:\xampp\htdocs\oreintation\school_rep\view_applications.php -->
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

// Fetch applications for the university
$applications = [];
$result = $conn->query("SELECT id, student_name, program_id, status, application_date FROM applications WHERE university_id = $university_id");
if ($result) {
    $applications = $result->fetch_all(MYSQLI_ASSOC);
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
        <h2>Applications</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>Program</th>
                    <th>Status</th>
                    <th>Application Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($applications)): ?>
                    <?php foreach ($applications as $index => $application): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($application['student_name']); ?></td>
                            <td><?= htmlspecialchars($application['program_id']); ?></td>
                            <td><?= htmlspecialchars($application['status']); ?></td>
                            <td><?= htmlspecialchars($application['application_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No applications found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
</body>
</html>