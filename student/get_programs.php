<?php
// filepath: c:\xampp\htdocs\oreintation\student\get_programs.php
require_once '../config/db.php';
$university_id = intval($_GET['university_id'] ?? 0);
$programs = [];
if ($university_id) {
    $result = $conn->query("SELECT id, program_name FROM programs WHERE university_id = $university_id");
    while ($row = $result->fetch_assoc()) {
        $programs[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($programs);