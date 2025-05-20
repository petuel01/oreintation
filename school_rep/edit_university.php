<!-- filepath: c:\xampp\htdocs\oreintation\school_rep\edit_university.php -->
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
$stmt = $conn->prepare("SELECT name, contact, website, accreditation_status, motto, established_year, type FROM universities WHERE id = ?");
$stmt->bind_param("i", $university_id);
$stmt->execute();
$stmt->bind_result($university_name, $university_contact, $university_website, $university_accreditation_status, $university_motto, $university_established_year, $university_type);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated_name = trim($_POST['name']);
    $updated_contact = trim($_POST['contact']);
    $updated_website = trim($_POST['website']);
    $updated_accreditation_status = trim($_POST['accreditation_status']);
    $updated_motto = trim($_POST['motto']);
    $updated_established_year = intval($_POST['established_year']);
    $updated_type = trim($_POST['type']);

    $stmt = $conn->prepare("UPDATE universities SET name = ?, contact = ?, website = ?, accreditation_status = ?, motto = ?, established_year = ?, type = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $updated_name, $updated_contact, $updated_website, $updated_accreditation_status, $updated_motto, $updated_established_year, $updated_type, $university_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        die("Error updating university: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit University Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit University Information</h2>
        <form method="POST" action="edit_university.php">
            <div class="mb-3">
                <label for="name" class="form-label">University Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($university_name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input type="text" name="contact" id="contact" class="form-control" value="<?= htmlspecialchars($university_contact); ?>" required>
            </div>
            <div class="mb-3">
                <label for="website" class="form-label">Website</label>
                <input type="url" name="website" id="website" class="form-control" value="<?= htmlspecialchars($university_website); ?>">
            </div>
            <div class="mb-3">
                <label for="accreditation_status" class="form-label">Accreditation Status</label>
                <input type="text" name="accreditation_status" id="accreditation_status" class="form-control" value="<?= htmlspecialchars($university_accreditation_status); ?>">
            </div>
            <div class="mb-3">
                <label for="motto" class="form-label">Motto</label>
                <input type="text" name="motto" id="motto" class="form-control" value="<?= htmlspecialchars($university_motto); ?>">
            </div>
            <div class="mb-3">
                <label for="established_year" class="form-label">Established Year</label>
                <input type="number" name="established_year" id="established_year" class="form-control" value="<?= htmlspecialchars($university_established_year); ?>">
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="Public" <?= $university_type === 'Public' ? 'selected' : ''; ?>>Public</option>
                    <option value="Private" <?= $university_type === 'Private' ? 'selected' : ''; ?>>Private</option>
                    <option value="Religious" <?= $university_type === 'Religious' ? 'selected' : ''; ?>>Religious</option>
                    <option value="International" <?= $university_type === 'International' ? 'selected' : ''; ?>>International</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Information</button>
        </form>
    </div>
</body>
</html>