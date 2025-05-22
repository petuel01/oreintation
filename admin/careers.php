<!-- filepath: c:\xampp\htdocs\oreintation\admin\careers.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include("../config/db.php");

// Fetch career guides
$result = $conn->query("SELECT * FROM careers");
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
        <h2>Manage Career Guides</h2>
        <a href="add_career.php" class="btn btn-success mb-3">Add New Career Guide</a>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']); ?></td>
                            <td><?= htmlspecialchars($row['category']); ?></td>
                            <td><?= htmlspecialchars(substr($row['description'], 0, 50)); ?>...</td>
                            <td>
                                <a href="delete_career.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        </main>
    </div>
</body>
</html>