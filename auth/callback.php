<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();

$client = new Google_Client();
$client->setClientId('1041692668860-ltlh4m15m6nsdtaqmbodli294r6o7bme.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/oreintation/auth/callback.php');
$client->addScope("email");
$client->addScope("profile");

// Database connection
$conn = new mysqli('localhost', 'root', '', 'orientation_system');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle Google authentication
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        die("Error fetching access token: " . $token['error']);
    }

    $client->setAccessToken($token);

    $google_oauth = new Google_Service_Oauth2($client);
    $userInfo = $google_oauth->userinfo->get();

    // Set session variables for email and name
    $_SESSION['email'] = $userInfo->email;
    $_SESSION['name'] = $userInfo->name;

    // Check if the user exists in the database
    $stmt = $conn->prepare("SELECT id, role, status, university_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $stmt->bind_result($user_id, $role, $status, $university_id);
    $stmt->fetch();

    if ($user_id) {
        // User exists
        $_SESSION['user_id'] = $user_id;
        $_SESSION['status'] = $status;
        $_SESSION['university_id'] = $university_id;

        if ($role) {
            // User has a role, set it in the session
            $_SESSION['user_role'] = $role;

            // Redirect based on role and status
            if ($role === 'school_rep' && $status === 'pending') {
                // Send email to the admin
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'baifempetuel0.2@gmail.com';
                    $mail->Password = 'mceq hojx joal awrx';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('admin@example.com', 'Admin');
                    $mail->addAddress('admin@example.com');
                    $mail->isHTML(true);
                    $mail->Subject = 'New School Representative Registration';
                    $mail->Body = "A new school representative has registered.<br><br>Name: {$_SESSION['name']}<br>Email: {$_SESSION['email']}<br><br><a href='http://localhost/oreintation/admin/approve_school_reps.php'>Click here to approve the account</a>";

                    $mail->send();
                } catch (Exception $e) {
                    error_log("Admin notification email could not be sent. Mailer Error: {$mail->ErrorInfo}");
                }

                // Redirect to the pending approval page
                header('Location: /oreintation/school_rep/pending_approval.php');
                exit();
            }

            if ($status !== 'approved') {
                echo "Your account is pending approval by an admin.";
                exit();
            }

            if ($role === 'admin') {
                header('Location: /oreintation/admin/dashboard.php');
                exit();
            } elseif ($role === 'school_rep') {
                header('Location: /oreintation/school_rep/dashboard.php');
                exit();
            } else {
                header('Location: /oreintation/index.php');
                exit();
            }
        } else {
            // User exists but has no role, prompt for role selection
            $_SESSION['user_role'] = null;
            $stmt->close();
            showRoleSelectionForm();
            exit();
        }
    } else {
        // New user, prompt for role selection
        $stmt->close();
        showRoleSelectionForm();
        exit();
    }
}

// Handle role submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    if (!isset($_SESSION['email']) || !isset($_SESSION['name'])) {
        die("Session variables are not set. Please authenticate with Google again.");
    }

    $role = $_POST['role'];
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $status = ($role === 'school_rep') ? 'pending' : 'approved'; // Pending approval for school_rep

    $university_id = null;
    if ($role === 'school_rep') {
        $university_name = trim($_POST['university']);

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

    // Check if the user already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User exists, update their role, status, and university_id
        $stmt->close();
        $stmt = $conn->prepare("UPDATE users SET role = ?, status = ?, university_id = ? WHERE email = ?");
        $stmt->bind_param("ssis", $role, $status, $university_id, $email);
        $stmt->execute();
        // Get the user id for session
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();
        $_SESSION['user_id'] = $user_id;
    } else {
        // New user, insert into the database
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO users (email, name, role, status, university_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $email, $name, $role, $status, $university_id);
        $stmt->execute();
        $_SESSION['user_id'] = $conn->insert_id;
    }

    $_SESSION['user_role'] = $role;
    $_SESSION['status'] = $status;
    $_SESSION['university_id'] = $university_id;

    if ($role === 'admin') {
        header('Location: /oreintation/admin/dashboard.php');
        exit();
    } elseif ($role === 'school_rep') {
        if ($status === 'pending') {
            header('Location: /oreintation/school_rep/pending_approval.php');
            exit();
        } else {
            header('Location: /oreintation/school_rep/dashboard.php');
            exit();
        }
    } else {
        header('Location: /oreintation/index.php');
        exit();
    }
}

// Function to display the role selection form
function showRoleSelectionForm() {
    echo '<form method="POST" action="callback.php" style="background-color: #3E2723; color: #FFFFFF; padding: 20px; border-radius: 8px; width: 300px; margin: 50px auto; font-family: Arial, sans-serif;">';
    echo '<h2 style="text-align: center; color: #FFCCBC;">Select Your Role</h2>';
    echo '<label style="display: block; margin-bottom: 10px;">Choose one:</label>';
    echo '<div style="margin-bottom: 15px;">';
    echo '<input type="radio" id="student" name="role" value="student" required style="margin-right: 10px;">';
    echo '<label for="student" style="cursor: pointer;">Student</label><br>';
    echo '<input type="radio" id="school_rep" name="role" value="school_rep" required style="margin-right: 10px;">';
    echo '<label for="school_rep" style="cursor: pointer;">School Representative</label>';
    echo '</div>';
    echo '<div id="university-input" style="display: none; margin-bottom: 15px;">';
    echo '<label for="university" style="display: block; margin-bottom: 5px;">University Name:</label>';
    echo '<input type="text" id="university" name="university" class="form-control" placeholder="Enter University Name" style="width: 100%;" required>';
    echo '</div>';
    echo '<button type="submit" style="background-color: #5D4037; color: #FFFFFF; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px;">Submit</button>';
    echo '</form>';

    // JavaScript to toggle university input
    echo '<script>
        document.querySelectorAll("input[name=\'role\']").forEach(radio => {
            radio.addEventListener("change", function() {
                const universityInput = document.getElementById("university-input");
                if (this.value === "school_rep") {
                    universityInput.style.display = "block";
                } else {
                    universityInput.style.display = "none";
                }
            });
        });
    </script>';
}
?>