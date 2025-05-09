<?php
session_start();
$current_page = "careers.php";
include("sidebar.php");
include("../config/db.php");

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'school_rep'])) {
    header("Location: ../login.php");
    exit();
}

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
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
</head>
<body>
<div class="d-flex">
    
  <!-- Mobile Toggle Button -->
  <button class="mobile-menu-button" onclick="toggleSidebar()">☰ Menu</button>
    <!-- Sidebar -->
    <nav class="sidebar">
           <?php include("sidebar.php"); ?>
        </nav>
    <div class="main-content flex-grow-1 p-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <a href="dashboard.php" class="btn btn-dark-brown mb-3">Back to Dashboard</a>
        </header>
        <h2 class="mb-4">Manage Career Guides</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Add New Career Guide</h5>
                <form action="add_career.php" method="post">
                    <div class="mb-3">
                        <label for="title" class="form-label">Career Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Career Title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Career Description</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Career Description" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Add Career</button>
                </form>
            </div>
        </div>
        <h3 class="mb-3">Career Guides List</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars(substr($row['description'], 0, 50)) ?>...</td>
                            <td>
                                <a href="delete_career.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
