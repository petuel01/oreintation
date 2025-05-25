<!-- filepath: c:\xampp\htdocs\oreintation\school_rep\add_program.php -->
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

// Fetch faculties for the university
$faculties = [];
$result = $conn->query("SELECT id, faculty_name FROM faculties WHERE university_id = $university_id");
if ($result) {
    $faculties = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faculty_id = intval($_POST['faculty_id']);
    $program_name = trim($_POST['program_name']);
    $duration = trim($_POST['duration']);
    $degree_type = trim($_POST['degree_type']);
    $language = trim($_POST['language']);
    $admission_requirements = trim($_POST['admission_requirements']);

    // Insert the program with the university_id
    $stmt = $conn->prepare("INSERT INTO programs (university_id, faculty_id, program_name, duration, degree_type, language, admission_requirements) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssss", $university_id, $faculty_id, $program_name, $duration, $degree_type, $language, $admission_requirements);

    if ($stmt->execute()) {
        header("Location: manage_programs.php");
        exit();
    } else {
        die("Error adding program: " . $stmt->error);
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
        <h2>Add New Program</h2>
        <form method="POST" action="add_program.php">
            <div class="mb-3">
                <label for="faculty_id" class="form-label">Faculty</label>
                <select name="faculty_id" id="faculty_id" class="form-control" required>
                    <option value="">Select Faculty</option>
                    <?php foreach ($faculties as $faculty): ?>
                        <option value="<?= $faculty['id']; ?>"><?= htmlspecialchars($faculty['faculty_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="program_name" class="form-label">Program Name</label>
                <input type="text" name="program_name" id="program_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration</label>
                <input type="text" name="duration" id="duration" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="degree_type" class="form-label">Degree Type</label>
                <input type="text" name="degree_type" id="degree_type" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="language" class="form-label">Language</label>
                <input type="text" name="language" id="language" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="admission_requirements" class="form-label">Admission Requirements</label>
                <textarea name="admission_requirements" id="admission_requirements" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Program</button>
        </form>
    </div>
    </div>
</body>
</html>