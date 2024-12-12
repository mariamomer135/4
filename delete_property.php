<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['userID'];
    $propertyID = $_POST['propertyID'];

    $host = "localhost";
    $user = "momer3";
    $pass = "momer3";
    $dbname = "momer3";

    // Database connection
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete property from the database
    $sql = "DELETE FROM Properties WHERE propertyID = ? AND userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $propertyID, $userID);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    // After deleting, redirect to dashboard.php
    header("Location: dashboard.php");
    exit();
}
?>
