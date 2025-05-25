<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['university_id'])) {
    die("University not set.");
}
$university_id = $_SESSION['university_id'];

// Use the correct table name: location (not locations)
$loc_query = "SELECT * FROM locations WHERE university_id = $university_id";
$loc_result = $conn->query($loc_query);
if (!$loc_result) {
    die("Query failed: " . $conn->error);
}
$loc = $loc_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $country = trim($_POST['country']);
    $region = trim($_POST['region']);
    $city = trim($_POST['city']);
    $address = trim($_POST['address']);
    $map_link = trim($_POST['map_link']);

    if ($loc) {
        // Update existing location
        $stmt = $conn->prepare("UPDATE location SET country=?, region=?, city=?, address=?, map_link=?, updated_at=NOW() WHERE university_id=?");
        $stmt->bind_param("sssssi", $country, $region, $city, $address, $map_link, $university_id);
        $stmt->execute();
        $location_id = $loc['id'];
    } else {
        // Insert new location
        $stmt = $conn->prepare("INSERT INTO locations (university_id, country, region, city, address, map_link, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("isssss", $university_id, $country, $region, $city, $address, $map_link);
        $stmt->execute();
        $location_id = $conn->insert_id;
    }

    // Update the university's location_id column
    $conn->query("UPDATE universities SET location_id = $location_id WHERE id = $university_id");

    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Rep Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
</head>
<body>
 <div class="d-flex flex-column flex-md-row">
        <!-- Sidebar -->
        <nav class="sidebar">
            <?php include("sidebar.php"); ?>
        </nav>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <h2 class="mb-4" style="color:#5D4037;"><i class="fas fa-map-marker-alt me-2"></i>Edit University Location</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($loc['country'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Region</label>
                    <input type="text" name="region" class="form-control" value="<?= htmlspecialchars($loc['region'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($loc['city'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($loc['address'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Map Link (Google Maps URL)</label>
                    <input type="url" name="map_link" class="form-control" value="<?= htmlspecialchars($loc['map_link'] ?? '') ?>">
                </div>
                <button type="submit" class="btn" style="background:#5D4037; color:#fff;">
                    <i class="fas fa-save me-1"></i>Save Location
                </button>
                <a href="dashboard.php" class="btn btn-secondary ms-2">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>