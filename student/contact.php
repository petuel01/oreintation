<?php
session_start();
require_once '../vendor/autoload.php';
require_once '../config/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$name = $email = $message = "";
$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $message && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'baifempetuel0.2@gmail.com'; // Your Gmail address
            $mail->Password = 'mceq hojx joal awrx'; // Your Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('baifempetuel0.2@gmail.com', 'KamerGuide Contact');
            $mail->addReplyTo($email, $name);
            $mail->addAddress('kamerguide53@gmail.com', 'KamerGuide Admin');
            $mail->isHTML(true);
            $mail->Subject = "Contact Form Submission from $name";
            $mail->Body = "<strong>Name:</strong> $name<br>
                           <strong>Email:</strong> $email<br>
                           <strong>Message:</strong><br>" . nl2br(htmlspecialchars($message));

            $mail->send();
            $success = "Thank you for contacting us! We have received your message.";
            $name = $email = $message = "";
        } catch (Exception $e) {
            $error = "Sorry, your message could not be sent. Please try again later.";
        }
    } else {
        $error = "Please fill in all fields with a valid email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - KamerGuide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.css">
    <style>
        .btn-brown {
            background-color: #5D4037;
            color: #fff;
        }
        .btn-brown:hover {
            background-color: #3E2723;
            color: #fff;
        }
        .contact-info-icon {
            background: #5D4037;
            color: #fff;
            border-radius: 50%;
            padding: 10px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container my-5">
        <h1 class="mb-4" style="color:#5D4037;"><i class="fas fa-envelope me-2"></i>Contact Us</h1>
        <div class="row">
            <div class="col-md-7 mb-4">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="message" name="message" rows="5" required><?= htmlspecialchars($message) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-brown"><i class="fas fa-paper-plane me-2"></i>Send Message</button>
                </form>
            </div>
            <div class="col-md-5">
                <div class="mb-4">
                    <h5 style="color:#5D4037;"><i class="fas fa-info-circle contact-info-icon"></i>Contact Information</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-envelope contact-info-icon"></i> info@kamerguide.com</li>
                        <li class="mb-2"><i class="fas fa-phone contact-info-icon"></i> +237 6XX XXX XXX</li>
                        <li><i class="fas fa-map-marker-alt contact-info-icon"></i> Yaoundé, Cameroon</li>
                    </ul>
                </div>
                <div>
                    <h5 style="color:#5D4037;"><i class="fas fa-share-alt contact-info-icon"></i>Connect With Us</h5>
                    <a href="#" class="text-white text-decoration-none me-2"><i class="fab fa-facebook-f contact-info-icon"></i></a>
                    <a href="#" class="text-white text-decoration-none me-2"><i class="fab fa-twitter contact-info-icon"></i></a>
                    <a href="#" class="text-white text-decoration-none me-2"><i class="fab fa-instagram contact-info-icon"></i></a>
                    <a href="#" class="text-white text-decoration-none me-2"><i class="fab fa-linkedin contact-info-icon"></i></a>
                </div>
            </div>
        </div>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>