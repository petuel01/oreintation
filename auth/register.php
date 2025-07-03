<!-- filepath: c:\xampp\htdocs\oreintation\register.php -->
<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('1041692668860-ltlh4m15m6nsdtaqmbodli294r6o7bme.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/oreintation/auth/callback.php');
$client->addScope("email");
$client->addScope("profile");

$auth_url = $client->createAuthUrl();

// Retrieve errors and success messages from session
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
unset($_SESSION['errors'], $_SESSION['success']); // Clear messages after displaying
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - KamerGuide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
        }
        .left-side {
            width: 50%;
            height: 100%;
        }
        .left-side img {
            width: 100%;
            height: 120vh;
            object-fit: cover;
        }
        .right-side {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #3e2723;
            padding: 20px;
        }
        .form-container {
            background-color: #5d4037;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 100%;
        }
        .btn-dark-brown {
            background-color: #5C4033;
            color: white;
        }
        .btn-dark-brown:hover {
            background-color: #4A3228;
        }
        @media (max-width: 991px) {
            .left-side {
                display: none;
            }
            .right-side {
                flex: 1;
                padding: 10px;
                min-width: 0;
            }
            .form-container {
                max-width: 100%;
                padding: 12px;
                box-shadow: none;
                border-radius: 0;
            }
        }
        @media (max-width: 575px) {
            .form-container {
                padding: 6px;
                font-size: 0.97rem;
            }
            .btn-dark-brown, .btn-dark-brown:hover {
                font-size: 1rem;
                padding: 8px 0;
            }
            .form-label, .form-control, .form-select {
                font-size: 0.97rem;
            }
        }
        .strength-bar {
            width: 30%;
            height: 5px;
            margin: 0 2px;
            border-radius: 3px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="left-side">
        <img src="../assets/Flux_Dev_Generate_an_elegant_image_of_a_confident_black_studen_0.jpeg" alt="KamerGuide Registration">
    </div>
    <div class="right-side">
        <div class="form-container">
            <h2 class="text-center">Register</h2>

            <!-- Display Errors -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Display Success Message -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="process_register.php" enctype="multipart/form-data">
                <!-- Profile picture upload removed as per requirements -->
                <!-- Terms & Conditions checkbox moved to the bottom -->
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
                    <div class="input-group">
                        <input type="password" class="form-control form-control-sm" id="password" name="password" required oninput="checkPasswordStrength()">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="togglePasswordVisibility('password')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="mt-2 d-flex justify-content-between">
                        <div id="weak-bar" class="strength-bar bg-secondary"></div>
                        <div id="average-bar" class="strength-bar bg-secondary"></div>
                        <div id="strong-bar" class="strength-bar bg-secondary"></div>
                    </div>
                    <small id="password-strength-text" class="text-muted"></small>
                </div>
                <div class="mb-2">
                    <label for="re_password" class="form-label">Re-enter Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-sm" id="re_password" name="re_password" required>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="togglePasswordVisibility('re_password')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-2">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select form-select-sm" id="role" name="role" onchange="toggleUniversityField()" required>
                        <option value="" disabled selected>Select role</option>
                        <option value="student">Student</option>
                        <option value="school_rep">School Representative</option>
                    </select>
                </div>
                <div class="mb-2" id="university-field" style="display: none;">
                    <label for="university" class="form-label">University Name</label>
                    <input type="text" class="form-control form-control-sm" id="university" name="university">
                </div>
                
                <div class="mb-2">
                    <div class="g-recaptcha" data-sitekey="6Le9iT4rAAAAAG9LWxMeJD5qIxltjDmyWwNQxRJr"></div>
                </div>
                <div class="mb-2 form-check mt-3">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">I agree to the <a href="../terms.php" target="_blank" style="color:#FFD54F;">Terms & Conditions</a></label>
                </div>
                <button type="submit" class="btn btn-dark-brown w-100">Register</button>
                <div class="text-center mt-2">
                    <a href="login.php" style="color:rgb(141, 125, 82);">Already have an account? Login</a>
                </div>
                <div class="text-center mt-3">
                <a href="<?= htmlspecialchars($auth_url) ?>">
                    <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" alt="Sign in with Google" />
                </a>
            </form>
        </div>
    </div>
    <script>
        function toggleUniversityField() {
            const role = document.getElementById('role').value;
            const universityField = document.getElementById('university-field');
            if (role === 'school_rep') {
                universityField.style.display = 'block';
            } else {
                universityField.style.display = 'none';
            }
        }

        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const weakBar = document.getElementById('weak-bar');
            const averageBar = document.getElementById('average-bar');
            const strongBar = document.getElementById('strong-bar');
            const strengthText = document.getElementById('password-strength-text');
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[\W_]/.test(password)) strength++;

            weakBar.className = 'strength-bar bg-secondary';
            averageBar.className = 'strength-bar bg-secondary';
            strongBar.className = 'strength-bar bg-secondary';

            if (strength >= 1) weakBar.className = 'strength-bar bg-danger';
            if (strength >= 3) averageBar.className = 'strength-bar bg-warning';
            if (strength >= 5) strongBar.className = 'strength-bar bg-success';

            switch (strength) {
                case 0:
                case 1:
                    strengthText.textContent = 'Weak';
                    break;
                case 2:
                case 3:
                    strengthText.textContent = 'Average';
                    break;
                case 4:
                case 5:
                    strengthText.textContent = 'Strong';
                    break;
            }
        }
    </script>
</body>
</html>