<!-- filepath: c:\xampp\htdocs\oreintation\admin\dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin' || $_SESSION['status'] !== 'approved') {
    header("Location: ../login.php");
    exit();
}

include '../config/db.php';

// Fetch statistics
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$total_institutions = $conn->query("SELECT COUNT(*) AS count FROM universities")->fetch_assoc()['count'];
$total_career_guides = $conn->query("SELECT COUNT(*) AS count FROM programs")->fetch_assoc()['count'];
$total_applications = $conn->query("SELECT COUNT(*) AS count FROM programs")->fetch_assoc()['count'];

// Fetch institutions data
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
    <style>
        .stat-card {
            background-color: #4d2600;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 10px;
            text-align: center;
            flex: 1;
        }
        .stat-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .stat-card h3 {
            margin: 0;
            font-size: 1.5rem;
        }
        .stat-card p {
            margin: 0;
            font-size: 1rem;
        }
        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <?php include("sidebar.php"); ?>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <header class="d-flex justify-content-between align-items-center py-3">
                    <h2>Admin Dashboard</h2>
                    <button class="btn btn-dark"><i class="fas fa-plus"></i> Add New</button>
                </header>

                <!-- Dashboard Stats -->
                <div class="d-flex flex-wrap">
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <h3><?= $total_users ?></h3>
                        <p>Total Users</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-university"></i>
                        <h3><?= $total_institutions ?></h3>
                        <p>Total Institutions</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-book"></i>
                        <h3><?= $total_career_guides ?></h3>
                        <p>Career Guides</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-file-alt"></i>
                        <h3><?= $total_applications ?></h3>
                        <p>Total Applications</p>
                    </div>
                </div>

                <!-- Institutions List -->
                <div class="card mt-4">
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
            </main>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>