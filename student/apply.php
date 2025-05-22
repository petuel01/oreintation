<!-- filepath: c:\xampp\htdocs\oreintation\apply.php -->
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Admission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="container mt-5">
        <h1>Apply for Admission</h1>
        <p>Select a university and program to apply for admission.</p>

        <form method="POST" action="process_application.php">
            <div class="mb-3">
                <label for="university" class="form-label">University</label>
                <select class="form-select" id="university" name="university_id" required>
                    <option value="" disabled selected>Select a university</option>
                    <?php
                    $query = "SELECT id, name FROM universities";
                    $result = $conn->query($query);

                    while ($row = $result->fetch_assoc()):
                    ?>
                        <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="program" class="form-label">Program</label>
                <select class="form-select" id="program" name="program_id" required>
                    <option value="" disabled selected>Select a program</option>
                    <!-- Populate dynamically based on selected university -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit Application</button>
        </form>
    </div>
    <footer>

    <?php include '../footer.php'; ?>
    </footer>
</body>
</html>