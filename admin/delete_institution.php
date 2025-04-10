<?php
session_start();
include("../config/db.php");

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM institutions WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
}
header("Location: institutions.php");
?>
