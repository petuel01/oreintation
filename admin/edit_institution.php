<?php
session_start();
include("../config/db.php");

if (!isset($_GET['id'])) {
    header("Location: institutions.php");
    exit;
}

$id = $_GET['id'];

// Fetch institution details
$stmt = $conn->prepare("SELECT * FROM institutions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$institution = $result->fetch_assoc();

if (!$institution) {
    echo "Institution not found.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $location = $_POST['location'];
    $image = null;

    if (!empty($_FILES['image']['tmp_name'])) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    if ($image) {
        $stmt = $conn->prepare("UPDATE institutions SET name = ?, contact = ?, location = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $contact, $location, $image, $id);
    } else {
        $stmt = $conn->prepare("UPDATE institutions SET name = ?, contact = ?, location = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $contact, $location, $id);
    }

    if ($stmt->execute()) {
        header("Location: institutions.php");
        exit;
    } else {
        echo "Error updating institution.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Institution</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Institution</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($institution['name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="contact">Contact</label>
            <input type="text" name="contact" id="contact" class="form-control" value="<?= htmlspecialchars($institution['contact']) ?>" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" class="form-control" value="<?= htmlspecialchars($institution['location']) ?>" required>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control-file">
            <?php if (!empty($institution['image'])): ?>
                <p>Current Image:</p>
                <img src="data:image/jpeg;base64,<?= base64_encode($institution['image']) ?>" alt="Institution Image" style="width: 100px; height: auto;">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="institutions.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
