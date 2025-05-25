<!-- filepath: c:\xampp\htdocs\oreintation\school_rep\view_reviews.php -->
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

// Fetch reviews for the university
$reviews = [];
$result = $conn->query("SELECT id, rating_overall, comment, pros, cons, review_date FROM student_reviews WHERE university_id = $university_id");
if ($result) {
    $reviews = $result->fetch_all(MYSQLI_ASSOC);
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
        <h2>Student Reviews</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Overall Rating</th>
                    <th>Comment</th>
                    <th>Pros</th>
                    <th>Cons</th>
                    <th>Review Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $index => $review): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($review['rating_overall']); ?></td>
                            <td><?= htmlspecialchars($review['comment']); ?></td>
                            <td><?= htmlspecialchars($review['pros']); ?></td>
                            <td><?= htmlspecialchars($review['cons']); ?></td>
                            <td><?= htmlspecialchars($review['review_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No reviews found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
</body>
</html>