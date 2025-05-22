<!-- filepath: c:\xampp\htdocs\oreintation\admin\add_career.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $requirements = trim($_POST['requirements']);
    $related_programs = isset($_POST['related_programs']) ? implode(',', $_POST['related_programs']) : '';

    if (!empty($title) && !empty($category) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO careers (title, category, description, requirements, related_programs) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $category, $description, $requirements, $related_programs);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Career guide added successfully.";
        } else {
            $_SESSION['error'] = "Failed to add career guide.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Please fill in all required fields.";
    }

    header("Location: add_career.php");
    exit();
}

// Fetch programs for the related programs dropdown
$programs = $conn->query("SELECT id, program_name FROM programs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
    </head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar">
           <?php include("sidebar.php"); ?>
        </nav>


            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <h2>Add Career Guide</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="add_career.php">
            <div class="mb-3">
                <label for="title" class="form-label">Career Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select name="category" id="category" class="form-select" required>
                    <option value="Engineering">Engineering</option>
                    <option value="Medicine">Medicine</option>
                    <option value="Business">Business</option>
                    <option value="Arts">Arts</option>
                    <option value="Technology">Technology</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="requirements" class="form-label">Requirements</label>
                <textarea name="requirements" id="requirements" class="form-control" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="related_programs" class="form-label">Related Programs</label>
                <select name="related_programs[]" id="related_programs" class="form-select" multiple>
                    <?php while ($program = $programs->fetch_assoc()): ?>
                        <option value="<?= $program['id']; ?>"><?= htmlspecialchars($program['program_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Career Guide</button>
        </form>
        <main/>
    </div>
</body>
</html>