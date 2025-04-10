<?php
$current_page = "dashboard.php";
session_start();

include '../config/db.php';

// Fetch institutions data from database 
$sql = "SELECT * FROM institutions";
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
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Ranking</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['location']; ?></td>
                                    <td><?php echo $row['ranking']; ?></td>
                                    <td><?php echo $row['category']; ?></td>
                                    
                                    <td>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>"><i class="fas fa-edit text-primary"></i></a>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash text-danger"></i>
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
