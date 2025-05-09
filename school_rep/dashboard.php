<!-- filepath: c:\xampp\htdocs\oreintation\school_rep\dashboard.php -->
<?php
session_start();
// if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'school_rep']) || $_SESSION['status'] !== 'approved') {
//     header("Location: ../login.php");
//     exit();
// }

include '../config/db.php';

// Fetch counts for dashboard stats
$programs_count = $conn->query("SELECT COUNT(*) AS count FROM programs")->fetch_assoc()['count'];
$scholarships_count = $conn->query("SELECT COUNT(*) AS count FROM scholarships")->fetch_assoc()['count'];
$applications_count = $conn->query("SELECT COUNT(*) AS count FROM applications")->fetch_assoc()['count'];
$reviews_count = $conn->query("SELECT COUNT(*) AS count FROM student_reviews")->fetch_assoc()['count'];
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
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <?php include("sidebar.php"); ?>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <header class="d-flex justify-content-between align-items-center p-3">
                <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></h2>
            </header>

            <!-- Dashboard Stats -->
            <div class="stats d-flex flex-wrap">
                <div class="stat-card">
                    <i class="fas fa-book"></i>
                    <h3><?= $programs_count; ?></h3>
                    <p>Programs</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-graduation-cap"></i>
                    <h3><?= $scholarships_count; ?></h3>
                    <p>Scholarships</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-file-alt"></i>
                    <h3><?= $applications_count; ?></h3>
                    <p>Applications</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-comments"></i>
                    <h3><?= $reviews_count; ?></h3>
                    <p>Reviews</p>
                </div>
            </div>

            <!-- General Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4>General Information</h4>
                </div>
                <div class="card-body">
                    <p>Welcome to the School Representative Dashboard. Use the navigation menu to manage programs, scholarships, applications, and reviews.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>