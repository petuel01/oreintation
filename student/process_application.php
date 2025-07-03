<?php
// Handles student application submission and sends email to school rep
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../config/db.php';
require_once '../utils/email_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['user_id'];
    $student_name = $_SESSION['user_name'] ?? '';
    $university_id = intval($_POST['university_id'] ?? 0);
    $program_id = intval($_POST['program_id'] ?? 0);
    $motivation = trim($_POST['motivation'] ?? '');
    $errors = [];

    if (!$university_id || !$program_id) {
        $errors[] = 'Please select a university and program.';
    }

    if (empty($errors)) {
        // Insert application
        $stmt = $conn->prepare("INSERT INTO applications (student_id, student_name, university_id, program_id, motivation, status, application_date) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
        $stmt->bind_param("isiis", $student_id, $student_name, $university_id, $program_id, $motivation);
        if ($stmt->execute()) {
            // Get school rep email for this university
            $rep_stmt = $conn->prepare("SELECT email, name FROM users WHERE university_id = ? AND role = 'school_rep' AND status = 'approved' LIMIT 1");
            $rep_stmt->bind_param("i", $university_id);
            $rep_stmt->execute();
            $rep_stmt->bind_result($rep_email, $rep_name);
            if ($rep_stmt->fetch()) {
                // Get program name
                $prog_stmt = $conn->prepare("SELECT program_name FROM programs WHERE id = ?");
                $prog_stmt->bind_param("i", $program_id);
                $prog_stmt->execute();
                $prog_stmt->bind_result($program_name);
                $prog_stmt->fetch();
                $prog_stmt->close();
                // Email body
                $subject = "New Student Application for Your University";
                $body = "Dear $rep_name,<br><br>A new student has applied to your university.<br><br>" .
                        "<strong>Student Name:</strong> $student_name<br>" .
                        "<strong>Program:</strong> $program_name<br>" .
                        (!empty($motivation) ? "<strong>Motivation:</strong> " . nl2br(htmlspecialchars($motivation)) . "<br>" : "") .
                        "<br>Please log in to your dashboard to review the application.";
                sendEmail($rep_email, $subject, $body);
            }
            $rep_stmt->close();
            header('Location: apply.php?success=1');
            exit();
        } else {
            $errors[] = 'Failed to submit application. Please try again.';
        }
        $stmt->close();
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: apply.php');
        exit();
    }
} else {
    header('Location: apply.php');
    exit();
}
