<!-- filepath: c:\xampp\htdocs\oreintation\school_rep\manage_scholarships.php -->
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

// Fetch scholarships for the university
$scholarships = [];
$result = $conn->query("SELECT id, scholarship_name, description, eligibility FROM scholarships WHERE university_id = $university_id");
if ($result) {
    $scholarships = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM scholarships WHERE id = $delete_id");
    header("Location: manage_scholarships.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Scholarships</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Scholarships</h2>
        <a href="add_scholarship.php" class="btn btn-success mb-3">Add New Scholarship</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Scholarship Name</th>
                    <th>Description</th>
                    <th>Eligibility</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($scholarships)): ?>
                    <?php foreach ($scholarships as $index => $scholarship): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><?= htmlspecialchars($scholarship['scholarship_name']); ?></td>
                            <td><?= htmlspecialchars($scholarship['description']); ?></td>
                            <td><?= htmlspecialchars($scholarship['eligibility']); ?></td>
                            <td>
                                <a href="edit_scholarship.php?id=<?= $scholarship['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="manage_scholarships.php?delete_id=<?= $scholarship['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this scholarship?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No scholarships found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>