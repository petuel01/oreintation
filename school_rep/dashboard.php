<!-- filepath: c:\xampp\htdocs\oreintation\school_rep\dashboard.php -->
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

// Fetch university details
$stmt = $conn->prepare("SELECT name, logo FROM universities WHERE id = ?");
$stmt->bind_param("i", $university_id);
$stmt->execute();
$stmt->bind_result($university_name, $university_logo);
$stmt->fetch();
$stmt->close();

// Fallback for missing logo
if (empty($university_logo) || !file_exists("../" . $university_logo)) {
    $university_logo = "uploads/default_logo.png";
}

// Fetch overall statistics
$programs_count = $conn->query("SELECT COUNT(*) AS count FROM programs WHERE faculty_id IN (SELECT id FROM faculties WHERE university_id = $university_id)")->fetch_assoc()['count'];
$applications_count = $conn->query("SELECT COUNT(*) AS count FROM applications WHERE university_id = $university_id")->fetch_assoc()['count'];
$reviews_count = $conn->query("SELECT COUNT(*) AS count FROM student_reviews WHERE university_id = $university_id")->fetch_assoc()['count'];
$scholarships_count = $conn->query("SELECT COUNT(*) AS count FROM scholarships WHERE university_id = $university_id")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Rep Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
    <style>
        .logo-container {
            text-align: center;
            margin-top: 30px;
        }
        .logo-container img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .logo-container h2 {
            margin-top: 20px;
            font-size: 28px;
            font-weight: bold;
        }
        .edit-button {
            margin-top: 15px;
        }
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }
        .stat-box {
            flex: 1 1 calc(50% - 20px);
            max-width: 300px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
        }
        .stat-box h3 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .stat-box p {
            font-size: 18px;
            color: #6c757d;
        }
        @media (max-width: 768px) {
            .stat-box {
                flex: 1 1 100%;
            }
            .logo-container img {
                width: 150px;
                height: 150px;
            }
            .logo-container h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex flex-column flex-md-row">
        <!-- Sidebar -->
        <nav class="sidebar">
            <?php include("sidebar.php"); ?>
        </nav>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <header class="d-flex justify-content-between align-items-center mb-4">
                <h2>Welcome, <?= htmlspecialchars($_SESSION['name']); ?></h2>
            </header>

            <!-- University Logo and Name -->
            <div class="logo-container">
                <a href="edit_logo.php">
                    <img src="../<?= htmlspecialchars($university_logo); ?>" alt="University Logo" title="Click to update logo">
                </a>
                <h2><?= htmlspecialchars($university_name); ?></h2>
                <a href="edit_university.php" class="btn btn-primary edit-button">Edit University Information</a>
            </div>

            <!-- Statistics Section -->
            <div class="stats-container">
                <div class="stat-box">
                    <h3><?= $programs_count; ?></h3>
                    <p>Programs</p>
                </div>
                <div class="stat-box">
                    <h3><?= $applications_count; ?></h3>
                    <p>Applications</p>
                </div>
                <div class="stat-box">
                    <h3><?= $reviews_count; ?></h3>
                    <p>Reviews</p>
                </div>
                <div class="stat-box">
                    <h3><?= $scholarships_count; ?></h3>
                    <p>Scholarships</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>