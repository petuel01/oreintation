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
            $stmt->bind_result($university_id);
            $stmt->fetch();
            $stmt->close();

            // If the university does not exist, insert it
            if (!$university_id) {
                $stmt = $conn->prepare("INSERT INTO universities (name) VALUES (?)");
                $stmt->bind_param("s", $university_name);
                $stmt->execute();
                $university_id = $conn->insert_id;
                $stmt->close();
            }
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
                // Send email to the school representative
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'baifempetuel0.2@gmail.com'; // Your Gmail address
                    $mail->Password = 'mceq hojx joal awrx'; // Your Gmail app password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Email to the school representative
                    // Send email to the admin
                
                    $mail->clearAddresses();
                    $mail->addAddress('baifempetuel0.2@gmail.com'); // Replace with admin email
                    $mail->Subject = 'New School Representative Registration';
                    $mail->Body = "A new school representative has registered.<br><br>Name: $name<br>Email: $email<br>University: $university_name<br><br><a href='http://localhost/oreintation/admin/approve_school_reps.php'>Click here to approve the account</a>";

                    $mail->send();
                } catch (Exception $e) {
                    $errors[] = "Admin notification email could not be sent. Mailer Error: {$mail->ErrorInfo}";
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
    $_SESSION['errors'] = $errors;
    header("Location: register.php");
    exit();
}
?>