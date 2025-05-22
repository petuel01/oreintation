<?php
session_start();
$current_page = "users.php";
include("sidebar.php");
include("../config/db.php");

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'school_rep'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch users
$result = $conn->query("SELECT * FROM users");
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
<body class="bg-light">
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar">
           <?php include("sidebar.php"); ?>
        </nav>

    <div class="main-content flex-grow-1 p-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
        </header>
        <h2 class="text-dark">Manage Users</h2>
        <a href="dashboard.php" class="btn btn-dark-brown mb-3">Back to Dashboard</a>

        <h3 class="text-dark">Registered Users</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark" style="background-color: #4d2600; color: white;">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td>
                                <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-dark-brown btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .btn-dark-brown {
        background-color: #5C4033;
        color: white;
    }
    .btn-dark-brown:hover {
        background-color: #4A3228;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</html>
