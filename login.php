<?php session_start(); 


require_once __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('1041692668860-ltlh4m15m6nsdtaqmbodli294r6o7bme.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/oreintation/callback.php');
$client->addScope("email");
$client->addScope("profile");

$auth_url = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .image-container {
            width: 50%;
            height: 100%;
        }
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .login-container {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgb(75, 44, 35); /* Lighter brown for the form */
            box-shadow: 8px 8px 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow to the borders */
        }
        .login-box {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background-color: #5d4037;
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
        @media (max-width: 768px) {
            .image-container {
            display: none;
            }
            .login-container {
            width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="image-container">
        <img src="assets/a.jpeg" alt="Login Illustration">
    </div>
    <div class="login-container">
        <div class="login-box">
            <h2 class="text-center" style="color: white;">Admin Login</h2>
            <form action="process_login.php" method="post" onsubmit="return validateForm()">
                <div class="mb-3">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">
                            Show
                        </button>
                    </div>
                </div>
                <script>
                    function togglePasswordVisibility() {
                        const passwordField = document.getElementById('password');
                        const button = event.target;
                        if (passwordField.type === 'password') {
                            passwordField.type = 'text';
                            button.textContent = 'Hide';
                        } else {
                            passwordField.type = 'password';
                            button.textContent = 'Show';
                        }
                    }
                </script>
                <a href="forgot_password.php" style="color:rgb(141, 125, 82);">Forgot Password?</a>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                <a href="register.php" class="register-link">Not registered? Sign up here</a>
            </div>
            <div class="text-center mt-3">
                <a href="<?= htmlspecialchars($auth_url) ?>">
                    <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" alt="Sign in with Google" />
                </a>
            </div>
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
