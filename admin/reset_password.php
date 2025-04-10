<?php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = trim($_POST['password']);
    $re_password = trim($_POST['re_password']);
    $errors = [];

    if (empty($password)) {
        $errors[] = 'Password is required.';
    }
    if ($password !== $re_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        // Validate the token
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Update the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
            $stmt->bind_param("ss", $hashedPassword, $token);
            $stmt->execute();

            // Redirect to the dashboard
            header("Location: admin/dashboard.php");
            exit;
        } else {
            $errors[] = "Invalid or expired token.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #5a3e36; /* Darker brown */
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #3e2a24; /* Dark brown */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Reset Password</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="re_password" class="form-label">Re-enter New Password</label>
            <input type="password" class="form-control" id="re_password" name="re_password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form>
</div>
</body>
</html>