<!-- filepath: c:\xampp\htdocs\oreintation\settings.php -->
<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['status'] !== 'approved') {
    header("Location: ../login.php");
    exit();
}

include '../config/db.php';

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Fetch user details from the `users` table
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate passwords
    if ($new_password !== $confirm_password) {
        $error_message = "New password and confirm password do not match.";
    } else {
        // Fetch the current password hash
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($password_hash);
        $stmt->fetch();
        $stmt->close();

        // Verify the current password
        if (!password_verify($current_password, $password_hash)) {
            $error_message = "Current password is incorrect.";
        } else {
            // Update the password
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }
            $stmt->bind_param("si", $new_password_hash, $user_id);

            if ($stmt->execute()) {
                $success_message = "Password updated successfully.";
            } else {
                $error_message = "Error updating password: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
    </head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar">
           <?php include("sidebar.php"); ?>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Display user information -->
        <div class="mb-4">
            <h4>Account Information</h4>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars(ucfirst($user['role'])); ?></p>
        </div>

        <!-- Password update form -->
        <form method="POST" action="settings.php">
            <h4>Change Password</h4>
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
        <main/>
    </div>
</body>
</html>