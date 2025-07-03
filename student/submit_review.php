<?php
// Handles review submission for a university
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Login required.']);
    exit();
}
require_once '../config/db.php';
header('Content-Type: application/json');

$student_id = $_SESSION['user_id'];
$university_id = intval($_POST['university_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if ($university_id < 1 || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit();
}

// Prevent duplicate review by same student for same university
$stmt = $conn->prepare("SELECT id FROM student_reviews WHERE university_id = ? AND student_id = ?");
$stmt->bind_param("ii", $university_id, $student_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'You have already reviewed this university.']);
    exit();
}
$stmt->close();

$stmt = $conn->prepare("INSERT INTO student_reviews (university_id, student_id, rating_overall, comment) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $university_id, $student_id, $rating, $comment);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to submit review.']);
}
$stmt->close();
