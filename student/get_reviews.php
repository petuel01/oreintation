<?php
// Returns HTML for reviews for a university
require_once '../config/db.php';
$university_id = intval($_GET['university_id'] ?? 0);
if ($university_id < 1) {
    echo '<div class="alert alert-warning">No reviews found.</div>';
    exit();
}
$res = $conn->query("SELECT r.rating_overall, r.comment, r.review_date, u.name FROM student_reviews r JOIN users u ON r.student_id = u.id WHERE r.university_id = $university_id ORDER BY r.review_date DESC");
if ($res && $res->num_rows > 0) {
    echo '<ul class="list-group">';
    while ($row = $res->fetch_assoc()) {
        echo '<li class="list-group-item">';
        echo '<div><strong>' . htmlspecialchars($row['name']) . '</strong> <span class="text-warning">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= intval($row['rating_overall'])) {
                echo '<i class="fas fa-star"></i>';
            } else {
                echo '<i class="far fa-star"></i>';
            }
        }
        echo '</span> <span class="text-muted" style="font-size:0.9em;">' . date('M d, Y', strtotime($row['review_date'])) . '</span></div>';
        if (!empty($row['comment'])) {
            echo '<div class="mt-1">' . nl2br(htmlspecialchars($row['comment'])) . '</div>';
        }
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo '<div class="alert alert-info">No reviews yet. Be the first to review!</div>';
}
