<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../config/db.php';

// Include PHPMailer for sending emails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../vendor/autoload.php';

// Approve a school_rep
if (isset($_GET['approve_id']) && is_numeric($_GET['approve_id'])) {
    $approve_id = intval($_GET['approve_id']);

    // Fetch the user's details
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ? AND role = 'school_rep' AND status = 'pending'");
    $stmt->bind_param("i", $approve_id);
    $stmt->execute();
    $stmt->bind_result($name, $email);
    if ($stmt->fetch()) {
        $stmt->close();

        // Approve the user
        $stmt = $conn->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
        $stmt->bind_param("i", $approve_id);
        if ($stmt->execute()) {
            $stmt->close();

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
                $mail->setFrom('admin@example.com', 'Admin');
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = 'Account Approved';
                $mail->Body = "Dear $name,<br>Your account has been approved. You can now log in to your dashboard.<br><br><a href='http://localhost/oreintation/login.php'>Click here to log in</a>";

                $mail->send();
                $_SESSION['success'] = "User approved successfully, and an email has been sent to the user.";
            } catch (Exception $e) {
                $_SESSION['error'] = "User approved, but the email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION['error'] = "Failed to approve the user.";
        }
    } else {
        $_SESSION['error'] = "User not found or already approved.";
    }

    header("Location: approve_school_reps.php");
    exit();
}

// Fetch pending school_reps
$stmt = $conn->prepare("SELECT id, name, email FROM users WHERE role = 'school_rep' AND status = 'pending'");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve School Representatives</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
           <?php include("sidebar.php"); ?>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
<div class="container mt-5">
    <h2>Pending School Representatives</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td>
                        <a href="?approve_id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>
</body>
</html>