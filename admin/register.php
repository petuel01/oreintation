<?php
require_once __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('1041692668860-ltlh4m15m6nsdtaqmbodli294r6o7bme.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/oreintation/admin/callback.php');
$client->addScope("email");
$client->addScope("profile");

$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));





// Include database connection
include("../config/db.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $re_password = trim($_POST['re_password']);
    $role = trim($_POST['role']);
    $errors = [];

    // Form validation
    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }
    if ($password !== $re_password) {
        $errors[] = 'Passwords do not match.';
    }
    if (empty($role)) {
        $errors[] = 'Role is required.';
    }

    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert into the database
        $stmt = $conn->prepare("INSERT INTO users (username, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $name, $email, $hashedPassword, $role);

        if ($stmt->execute()) {
            $success = "User registered successfully.";
        } else {
            $errors[] = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch users for display
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-dark-brown {
            background-color: #5C4033;
            color: white;
        }
        .btn-dark-brown:hover {
            background-color: #4A3228;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Register User</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="p-3 border rounded" style="background-color: #5C4033; color: white; max-width: 400px; margin: auto;">
        <div class="mb-2">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control form-control-sm" id="username" name="username" required>
        </div>
        <div class="mb-2">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control form-control-sm" id="name" name="name" required>
        </div>
        <div class="mb-2">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control form-control-sm" id="email" name="email" required>
        </div>
        <div class="mb-2">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control form-control-sm" id="password" name="password" required>
        </div>
        <div class="mb-2">
            <label for="re_password" class="form-label">Re-enter Password</label>
            <input type="password" class="form-control form-control-sm" id="re_password" name="re_password" required>
        </div>
        <div class="mb-2">
            <label for="role" class="form-label">Role</label>
            <select class="form-select form-select-sm" id="role" name="role" required>
                <option value="" disabled selected>Select role</option>
                <option value="student">Student</option>
                <option value="school_representative">School Representative</option>
            </select>
        </div>
        <button type="submit" class="btn btn-dark-brown w-100">Register</button>
        <div class="text-center mt-2">
            <a href="login.php" style="color:rgb(141, 125, 82);">Already have an account? Login</a>
        </div>
        <div class="text-center mt-3">
        <a href="<?= htmlspecialchars($auth_url) ?>">
  <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" alt="Sign in with Google" />
</a>
        </div>
    </form>
</div>
</body>
</html>