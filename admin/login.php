<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #3e2723; /* Dark brown background */
            color: #ffffff; /* White text */
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #5d4037; /* Lighter brown for the form */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .form-control {
            background-color: #4e342e; /* Input background */
            color: #ffffff; /* Input text color */
            border: none;
        }
        .form-control:focus {
            background-color: #4e342e;
            color: #ffffff;
            box-shadow: none;
            border: 1px solid #d7ccc8; /* Light brown border on focus */
        }
        .btn-primary {
            background-color: #795548; /* Button color */
            border: none;
        }
        .btn-primary:hover {
            background-color: #6d4c41; /* Darker button on hover */
        }
        .register-link {
            color: #d7ccc8; /* Light brown for the link */
            text-decoration: none;
        }
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">Admin Login</h2>
        <form action="process_login.php" method="post" onsubmit="return validateForm()">
            <div class="mb-3">
                <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="register.php" class="register-link">Not registered? Sign up here</a>
        </div>
    </div>
    <script>
        function validateForm() {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            if (username === '' || password === '') {
                alert('Both fields are required.');
                return false;
            }

            if (password.length < 6) {
                alert('Password must be at least 6 characters long.');
                return false;
            }

            return true;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
