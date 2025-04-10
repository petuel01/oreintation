<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
session_start();

$client = new Google_Client();
$client->setClientId('1041692668860-ltlh4m15m6nsdtaqmbodli294r6o7bme.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/oreintation/admin/callback.php');

$client->addScope("email");
$client->addScope("profile");

// Database connection
$conn = new mysqli('localhost', 'root', '', 'orientation_system');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle role submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    if (!isset($_SESSION['email']) || !isset($_SESSION['name'])) {
        die("Session variables are not set. Please authenticate with Google again.");
    }

    $_SESSION['role'] = $_POST['role'];
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $role = $_POST['role'];

    // Check if the user already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User already exists, redirect to dashboard
        $stmt->close();
        header('Location: dashboard.php');
        exit();
    }

    $stmt->close();

    // Save to database
    $stmt = $conn->prepare("INSERT INTO users (email, name, role) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $email, $name, $role);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    // Redirect to dashboard
    header('Location: dashboard.php');
    exit();
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

    $_SESSION['email'] = $userInfo->email;
    $_SESSION['name'] = $userInfo->name;

    // Check if the user already exists and has a role
    $stmt = $conn->prepare("SELECT role FROM users WHERE email = ?");
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();

    if ($role) {
        // User already has a role, redirect to dashboard
        $_SESSION['role'] = $role; // Store the role in the session
        $stmt->close();
        header('Location: dashboard.php');
        exit();
    }

    $stmt->close();

    // If role not submitted, show role selection form
    if (!isset($_SESSION['role'])) {
        echo '<form method="POST" action="callback.php" style="background-color: #3E2723; color: #FFFFFF; padding: 20px; border-radius: 8px; width: 300px; margin: 50px auto; font-family: Arial, sans-serif;">';
        echo '<h2 style="text-align: center; color: #FFCCBC;">Select Your Role</h2>';
        echo '<label style="display: block; margin-bottom: 10px;">Choose one:</label>';
        echo '<div style="margin-bottom: 15px;">';
        echo '<input type="radio" id="student" name="role" value="student" required style="margin-right: 10px;">';
        echo '<label for="student" style="cursor: pointer;">Student</label><br>';
        echo '<input type="radio" id="school_representative" name="role" value="school_representative" required style="margin-right: 10px;">';
        echo '<label for="school_representative" style="cursor: pointer;">School Representative</label>';
        echo '</div>';
        echo '<button type="submit" style="background-color: #5D4037; color: #FFFFFF; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px;">Submit</button>';
        echo '</form>';
        exit();
    }
} else {
    echo "Google authentication failed.";
}
?>