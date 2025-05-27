<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['status'] !== 'approved') {
    header("Location: ../auth/login.php");
    exit();
}

include 'config/db.php';

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
    <title>Account Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f7f3f0;
        }
        .settings-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(93,64,55,0.10);
            padding: 40px 32px;
            max-width: 520px;
            margin: 40px auto;
        }
        .settings-header {
            color: #5D4037;
            font-weight: bold;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .settings-header i {
            font-size: 2rem;
            color: #5D4037;
        }
        .account-info {
            background: #f3e8e1;
            border-radius: 10px;
            padding: 18px 20px;
            margin-bottom: 28px;
        }
        .account-info p {
            margin-bottom: 8px;
            font-size: 1.08rem;
        }
        .account-info i {
            color: #5D4037;
            margin-right: 8px;
        }
        .form-label {
            color: #5D4037;
            font-weight: 500;
        }
        .btn-primary {
            background: #5D4037;
            border: none;
        }
        .btn-primary:hover {
            background: #4E342E;
        }
        .form-control:focus {
            border-color: #5D4037;
            box-shadow: 0 0 0 0.2rem rgba(93,64,55,0.15);
        }
        .alert-success, .alert-danger {
            border-radius: 8px;
        }
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #4E342E;
            color: #fff;
            min-height: 100vh;
            float: left;
        }
        .main-content {
            margin-left: 250px;
        }
        @media (max-width: 768px) {
            .sidebar, .main-content { margin: 0; float: none; min-width: 0; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <?php
    // Sidebar include based on user role
    if ($user_role === 'admin') {
        include "admin/sidebar.php";
    } elseif ($user_role === 'school_rep') {
        include "school_rep/sidebar.php";
    } else {
        include "student/sidebar.php";
    }
    ?>
    <div class="main-content">
        <div class="settings-container">
            <div class="settings-header">
                <i class="fas fa-user-cog"></i>
                Account Settings
            </div>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <!-- Display user information -->
            <div class="account-info mb-4">
                <h5 class="mb-3" style="color:#5D4037;"><i class="fas fa-id-badge"></i> Account Information</h5>
                <p><i class="fas fa-user"></i> <strong>Username:</strong> <?= htmlspecialchars($user['name']); ?></p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                <p><i class="fas fa-user-tag"></i> <strong>Role:</strong> <?= htmlspecialchars(ucfirst($user['role'])); ?></p>
            </div>

            <!-- Password update form -->
            <form method="POST" action="settings.php" autocomplete="off">
                <h5 class="mb-3" style="color:#5D4037;"><i class="fas fa-key"></i> Change Password</h5>
                <div class="mb-3">
                    <label for="current_password" class="form-label"><i class="fas fa-lock"></i> Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label"><i class="fas fa-unlock-alt"></i> New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label"><i class="fas fa-check"></i> Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i>Update Password</button>
            </form>
        </div>
    </div>
</body>
</html>