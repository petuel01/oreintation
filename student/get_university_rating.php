<?php
// Returns average rating for a university (AJAX endpoint)
require_once '../config/db.php';
header('Content-Type: application/json');
$university_id = intval($_GET['university_id'] ?? 0);
if ($university_id < 1) {
    echo json_encode(['avg' => null, 'count' => 0]);
    exit();
}
$res = $conn->query("SELECT AVG(rating_overall) as avg_rating, COUNT(*) as count FROM student_reviews WHERE university_id = $university_id");
$row = $res ? $res->fetch_assoc() : null;
echo json_encode([
    'avg' => $row && $row['avg_rating'] !== null ? round($row['avg_rating'], 2) : null,
    'count' => $row ? intval($row['count']) : 0
]);
