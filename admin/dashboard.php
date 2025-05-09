<?php
$current_page = "dashboard.php";

session_start();
// if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin']) || $_SESSION['status'] !== 'approved') {
//     header("Location: ../login.php");
//     exit();
// }


include '../config/db.php';

// Fetch institutions data from database 
$sql = "SELECT * FROM universities";
$result = $conn->query($sql);
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
<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
           <?php include("sidebar.php"); ?>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <header class="d-flex justify-content-between align-items-center p-3">
                <h2>Welcome, Admin</h2>
                <button class="btn btn-dark"><i class="fas fa-plus"></i> Add New</button>
            </header>

            <!-- Dashboard Stats -->
            <div class="stats d-flex flex-wrap">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h3>2481</h3>
                    <p>Total Users</p>
            </div>
            <div class="stat-card">
                    <i class="fas fa-university"></i>
                    <h3>156</h3>
                    <p>Institutions</p>
            </div>
            <div class="stat-card">
                    <i class="fas fa-book"></i>
                    <h3>89</h3>
                    <p>Career Guides</p>
            </div>
</div>
            

            <!-- Institutions List -->
            <div class="card">
                <div class="card-header">
                    <h4>Institutions List</h4>
                </div>
                <div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead style="background-color: #4d2600; color: white;">
            <tr>
                <th>Name</th>
                <th>Contact</th>
                <th>Logo</th>
                <th>Motto</th>
                <th>Established Year</th>
                <th>Type</th>
                <th>Accreditation Status</th>
                <th>Website</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['contact']) ?></td>
                    <td>
                        <?php if (!empty($row['logo'])): ?>
                            <img src="../<?= htmlspecialchars($row['logo']) ?>" alt="Institution Logo" style="width: 100px; height: auto;">
                        <?php else: ?>
                            No Logo Available
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['motto']) ?></td>
                    <td><?= htmlspecialchars($row['established_year']) ?></td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= htmlspecialchars($row['accreditation_status']) ?></td>
                    <td>
                        <?php if (!empty($row['website'])): ?>
                            <a href="<?= htmlspecialchars($row['website']) ?>" target="_blank"><?= htmlspecialchars($row['website']) ?></a>
                        <?php else: ?>
                            No Website
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_institution.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="delete_institution.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this institution?');">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
            </div>

        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>
