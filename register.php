<?php
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

// Create Users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    usertype ENUM('buyer', 'seller', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmpassword = trim($_POST['confirmpassword']);
    $usertype = trim($_POST['usertype']);

    // Server-side check if passwords match
    if ($password !== $confirmpassword) {
        die("Passwords do not match. Please go back and try again.");
    }

    // Check if the username or email already exists
    $checkSql = "SELECT * FROM Users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("Username or email already exists. Please choose a different one.");
    }

    // Encrypt password before saving to the database
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert data into the database
    $sql = "INSERT INTO Users (username, email, password, usertype)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $usertype);

    if ($stmt->execute()) {
         // Get the user ID of the newly registered user
         $userID = $conn->insert_id;
         // Store user ID in session
        session_start();
        $_SESSION['userID'] = $userID;
        
        echo "Registration successful! Redirecting to payment page...";
        header("Refresh: 3; URL=creditcard.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
