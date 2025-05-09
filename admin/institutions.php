<?php
session_start();
$current_page = "institutions.php";
include("sidebar.php");
include("../config/db.php");

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'school_rep'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch Institutions
$result = $conn->query("SELECT * FROM universities");
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar">
           <?php include("sidebar.php"); ?>
        </nav>


    <!-- Main Content -->
    <div class="main-content flex-grow-1 p-4" style="background-color: #f8f9fa;">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <a href="dashboard.php" class="btn  mb-3"  style="background-color: #4d2600; color: white;">Back to Dashboard</a>
        </header>

        <h2 class="mb-4">Manage Institutions</h2>

        <div class="card mb-4">
            <div class="card-header " style="backgroung: brown;">
                Add Institution
            </div>
            <div class="card-body">
            <form action="add_institution.php" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Institution Name</label>
        <input type="text" name="name" id="name" class="form-control" placeholder="Institution Name" required>
    </div>
    <div class="mb-3">
        <label for="contact" class="form-label">Contact Info</label>
        <input type="text" name="contact" id="contact" class="form-control" placeholder="Contact Info" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" placeholder="Description" required></textarea>
    </div>
    <div class="mb-3">
        <label for="logo" class="form-label">Logo</label>
        <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
    </div>
    <div class="mb-3">
        <label for="motto" class="form-label">Motto</label>
        <input type="text" name="motto" id="motto" class="form-control" placeholder="Motto">
    </div>
    <div class="mb-3">
        <label for="established_year" class="form-label">Established Year</label>
        <input type="number" name="established_year" id="established_year" class="form-control" placeholder="Established Year">
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select name="type" id="type" class="form-control" required>
            <option value="Public">Public</option>
            <option value="Private">Private</option>
            <option value="Religious">Religious</option>
            <option value="International">International</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="accreditation_status" class="form-label">Accreditation Status</label>
        <input type="text" name="accreditation_status" id="accreditation_status" class="form-control" placeholder="Accreditation Status" required>
    </div>
    <div class="mb-3">
        <label for="website" class="form-label">Website</label>
        <input type="url" name="website" id="website" class="form-control" placeholder="Website URL" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Institution</button>
</form>
            </div>
        </div>

        <h3>Institution List</h3>
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
</body>
</html>
