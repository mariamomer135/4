<?php
session_start();

$host = "localhost";
$user = "momer3";
$pass = "momer3";
$dbname = "momer3";

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query to fetch user details
    $sql = "SELECT id, username, password, usertype FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($userID, $username, $hashedPassword, $usertype);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Store user info in session
            $_SESSION['userID'] = $userID;
            $_SESSION['username'] = $username;
            $_SESSION['usertype'] = $usertype;

            // Redirect based on usertype
            if ($usertype === 'seller') {
                header("Location: dashboard.php");
            } elseif ($usertype === 'buyer') {
                header("Location: dashboard.php");
            } elseif ($usertype === 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: login.html"); // usertype invaild redirect
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }

    $stmt->close();
}

$conn->close();
?>



