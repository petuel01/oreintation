<!-- filepath: c:\xampp\htdocs\oreintation\process_login.php -->
<?php
session_start();
include("../config/db.php");

// ✅ RECAPTCHA CONFIG
$recaptchaSecret = '6Le9iT4rAAAAAM161w-7H71_xmGDQOyQTBzOwc2Q'; // Replace with your actual secret key

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = []; // Initialize errors array

    // ✅ Validate reCAPTCHA
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

    // ✅ Validate email and password
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $errors[] = "Email is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // Proceed only if there are no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, name, email, password, role, status FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $email, $hashed_password, $role, $status);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_role'] = $role;
                $_SESSION['status'] = $status;

                if ($status !== 'approved') {
                    $errors[] = "Your account is not approved yet.";
                } else {
                    // Redirect based on role
                    if ($role === 'admin') {
                        header('Location: ../admin/dashboard.php');
                        exit();
                    } elseif ($role === 'school_rep') {
                        header('Location: ../school_rep/dashboard.php');
                        exit();
                    } else {
                        header('Location: ../index.php');
                        exit();
                    }
                }
            } else {
                $errors[] = "Invalid email or password.";
            }
        } else {
            $errors[] = "No user found with that email.";
        }

        $stmt->close();
    }

    // Store errors in session and redirect back to login page
    $_SESSION['errors'] = $errors;
    header("Location: login.php");
    exit();
} else {
    echo "Invalid request method.";
}
?>