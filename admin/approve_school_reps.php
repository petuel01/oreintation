<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../config/db.php';

// Approve a school_rep
if (isset($_GET['approve_id']) && is_numeric($_GET['approve_id'])) {
    $approve_id = intval($_GET['approve_id']);
    $stmt = $conn->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $approve_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['success'] = "User approved successfully.";
    header("Location: approve_school_reps.php");
    exit();
}

// Fetch pending school_reps
$stmt = $conn->prepare("SELECT id, name, email FROM users WHERE role = 'school_rep' AND status = 'pending'");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve School Representatives</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
           <?php include("sidebar.php"); ?>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
<div class="container mt-5">
    <h2>Pending School Representatives</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td>
                        <a href="?approve_id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>
</body>
</html>