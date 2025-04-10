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

// Handle role submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    if (!isset($_SESSION['email']) || !isset($_SESSION['name'])) {
        die("Session variables are not set. Please authenticate with Google again.");
    }

    $_SESSION['role'] = $_POST['role'];
    $email = $_SESSION['email'];
    $name = $_SESSION['name'];
    $role = $_POST['role'];

    // Save to database
    $conn = new mysqli('localhost', 'root', '', 'orientation_system');
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

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

    // Redirect to dashboard or a clean URL
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

    // If role not submitted, show role selection form
    if (!isset($_SESSION['role'])) {
        echo '<form method="POST" action="callback.php">';
        echo '<label>Select your role:</label><br>';
        echo '<input type="radio" name="role" value="student" required> Student<br>';
        echo '<input type="radio" name="role" value="school_representative" required> School Representative<br>';
        echo '<button type="submit">Submit</button>';
        echo '</form>';
        exit();
    }
} else {
    echo "Google authentication failed.";
}
?>