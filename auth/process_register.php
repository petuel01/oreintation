<!-- filepath: c:\xampp\htdocs\oreintation\process_register.php -->
<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
include("config/db.php");

// Include PHPMailer for sending emails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptchaSecret = '6Le9iT4rAAAAAM161w-7H71_xmGDQOyQTBzOwc2Q'; // Replace with your actual secret key
    $errors = []; // Initialize errors array

    // Validate reCAPTCHA
    if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
        $errors[] = "Please complete the reCAPTCHA.";
    } else {
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
        $responseData = json_decode($verifyResponse);

        if (!$responseData || !$responseData->success) {
            $errors[] = "reCAPTCHA verification failed. Please try again.";
        }
    }

    // Validate form inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $re_password = trim($_POST['re_password']);
    $role = trim($_POST['role']);
    $university_name = isset($_POST['university']) ? trim($_POST['university']) : null;
    // $profile_pic = null; // Profile picture upload removed
    // Terms & Conditions
    if (!isset($_POST['terms'])) {
        $errors[] = 'You must agree to the Terms & Conditions.';
    }
    // Email uniqueness check
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = 'An account with this email already exists.';
    }
    $stmt->close();
    // Profile picture upload removed as per requirements

    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter.';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number.';
    } elseif (!preg_match('/[\W_]/', $password)) {
        $errors[] = 'Password must contain at least one special character.';
    }
    if ($password !== $re_password) {
        $errors[] = 'Passwords do not match.';
    }
    if (empty($role)) {
        $errors[] = 'Role is required.';
    }

    // Handle university logic for school representatives
    $university_id = null;
    if ($role === 'school_rep') {
        if (empty($university_name)) {
            $errors[] = 'University name is required for school representatives.';
        } else {
            // Check if the university already exists
            $stmt = $conn->prepare("SELECT id FROM universities WHERE name = ?");
            $stmt->bind_param("s", $university_name);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors[] = 'A university with this name already exists. Please contact the admin or select another university.';
            } else {
                // If the university does not exist, insert it
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO universities (name) VALUES (?)");
                $stmt->bind_param("s", $university_name);
                $stmt->execute();
                $university_id = $conn->insert_id;
            }
            $stmt->close();
        }
    }

    if (empty($errors)) {
        // Set status based on role
        $status = ($role === 'school_rep') ? 'pending' : 'approved';

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status, university_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $name, $email, $hashedPassword, $role, $status, $university_id);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = $role;
            $_SESSION['status'] = $status;

            if ($role === 'school_rep' && $status === 'pending') {
                require_once __DIR__ . '/../utils/email_helper.php';
                // Send email to admin
                $adminEmail = 'baifempetuel0.2@gmail.com'; // Replace with real admin email
                $subject = 'New School Representative Registration';
                $body = "A new school representative has registered.<br><br>Name: $name<br>Email: $email<br>University: $university_name<br><br><a href='http://localhost/oreintation/admin/approve_school_reps.php'>Click here to approve the account</a>";
                $result = sendEmail($adminEmail, $subject, $body);
                if ($result !== true) {
                    $errors[] = "Admin notification email could not be sent. Mailer Error: $result";
                }
                // Redirect to the pending approval page
                header("Location: pending_approval.php");
                exit();
            } elseif ($role === 'student') {
                // Redirect student to their dashboard
                header("Location: ../index.php");
                exit();
            }
        } else {
            $errors[] = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Store errors in session and redirect back to the registration page
    if (!empty($errors)) {
        // Only show the first error for clarity
        $_SESSION['errors'] = [reset($errors)];
    }
    header("Location: register.php");
    exit();
}
?>