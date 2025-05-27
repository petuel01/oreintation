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
        body {
            background: #f7f3f0;
        }
        .main-content {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(93,64,55,0.10);
            margin: 40px auto;
            max-width: 1200px;
        }
        .dashboard-header {
            color: #5D4037;
            font-weight: bold;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .dashboard-header i {
            color: #5D4037;
            font-size: 2rem;
        }
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }
        .stat-card {
            flex: 1 1 calc(25% - 20px);
            max-width: 260px;
            background: #f3e8e1;
            color: #5D4037;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(93,64,55,0.08);
            text-align: center;
            padding: 28px 18px 22px 18px;
            margin: 10px 0;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 24px rgba(93,64,55,0.13);
            background: #e9d6c7;
        }
        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            color: #5D4037;
        }
        .stat-card h3 {
            margin: 0;
            font-size: 2.2rem;
            font-weight: bold;
        }
        .stat-card p {
            margin: 0;
            font-size: 1.1rem;
            color: #7c5a43;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(93,64,55,0.07);
        }
        .card-header {
            background: #5D4037;
            color: #fff;
            border-radius: 12px 12px 0 0;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table thead {
            background-color: #4d2600;
            color: white;
        }
        .btn-primary, .btn-danger {
            border: none;
        }
        .btn-primary {
            background: #5D4037;
        }
        .btn-primary:hover {
            background: #4E342E;
        }
        .btn-danger {
            background: #b23c17;
        }
        .btn-danger:hover {
            background: #7c260a;
        }
        @media (max-width: 991px) {
            .main-content {
                margin: 80px 8px 24px 8px;
            }
            .stats-container {
                flex-direction: column;
                gap: 0;
            }
            .stat-card {
                max-width: 100%;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
<div class="d-flex flex-column flex-md-row">
    <!-- Sidebar -->
    <nav class="sidebar">
        <?php include("sidebar.php"); ?>
    </nav>

    <!-- Main Content -->
    <div class="main-content flex-grow-1 p-4 mt-4">
        <header class="dashboard-header mb-4">
            <i class="fas fa-tachometer-alt"></i>
            Admin Dashboard
        </header>

        <!-- Dashboard Stats -->
        <div class="stats-container">
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
                <h4><i class="fas fa-university me-2"></i>Institutions List</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
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
                                        <img src="../<?= htmlspecialchars($row['logo']) ?>" alt="Institution Logo" style="width: 80px; height: auto; border-radius:8px;">
                                    <?php else: ?>
                                        <span class="text-muted">No Logo</span>
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
                                        <span class="text-muted">No Website</span>
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