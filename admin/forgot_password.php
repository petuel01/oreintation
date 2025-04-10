<?php
require_once __DIR__ . '/../config/db.php';
require __DIR__ . '/../vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendPasswordResetEmail($email, $resetLink) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'baifempetuel0.2@gmail.com'; // Your Gmail address
        $mail->Password = 'mceq hojx joal awrx'; // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Your App Name');
        $mail->addAddress($email);
        $mail->addReplyTo('your-email@gmail.com', 'Your App Name'); // Add Reply-To header

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "Click the link below to reset your password:<br><a href='$resetLink'>$resetLink</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if (empty($errors)) {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Generate a unique token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Save the token and expiration in the database
            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
            $stmt->bind_param("sss", $token, $expires, $email);
            $stmt->execute();

            // Send the reset email
            $resetLink = "http://localhost/oreintation/admin/reset_password.php?token=$token";
            $result = sendPasswordResetEmail($email, $resetLink);

            if ($result === true) {
                $success = "A password reset link has been sent to your email. Please check your inbox and spam folder.";
            } else {
                $errors[] = $result; // Display PHPMailer error
            }
        } else {
            $errors[] = "No account found with that email.";
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
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #5D4037; /* Darker brown */
            color: #FFFFFF;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #3E2723; /* Dark brown */
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
        .btn-primary {
            background-color: #795548;
            border-color: #795548;
        }
        .btn-primary:hover {
            background-color: #6D4C41;
            border-color: #6D4C41;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Forgot Password</h2>

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

    <form method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label">Enter your email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
    </form>
</div>
</body>
</html>