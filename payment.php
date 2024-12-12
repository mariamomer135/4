<?php
$host = "localhost";
$user = "momer3";
$pass = "momer3";
$dbname = "momer3";

// Start the session to retrieve the logged-in user's ID
session_start();
if (!isset($_SESSION['userID'])) {
    die("Unauthorized access. Please log in or register.");
}

// Retrieve the userID from the session
$userID = $_SESSION['userID'];

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS Payments (
    paymentID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    name VARCHAR(100) NOT NULL,                  
    cardType VARCHAR(50) NOT NULL,            
    cardNumber VARCHAR(255) NOT NULL,     
    expirationDate DATE NOT NULL,             
    coupon VARCHAR(50),                            
    billingAddress VARCHAR(255) NOT NULL,     
    phoneNumber VARCHAR(15) NOT NULL,           
    FOREIGN KEY (userID) REFERENCES Users(id) ON DELETE CASCADE
)";
if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}
//check if form is submitted 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $cardType = trim($_POST['cardType']);
    $cardNumber = trim($_POST['cardNumber']);
    $expirationDate = trim($_POST['expirationDate']) . "-01";
    $coupon = isset($_POST['coupon']) ? trim($_POST['coupon']) : null;
    $billingAddress = trim($_POST['billingAddress']);
    $phoneNumber = trim($_POST['phoneNumber']);

    // Validate card number (ensure only digits)
    if (!preg_match('/^\d{13,16}$/', $cardNumber)) {
        die("Invalid card number");
    }

    // Hash the card number for security
    $hashedCardNumber = password_hash($cardNumber, PASSWORD_DEFAULT);

    // Validate expiration date (make sure it in the future)
    $currentDate = date('Y-m-d');
    if ($expirationDate <= $currentDate) {
        die("Card expiration date is invalid.");
    }

    // Validate phone number (US format)
    if (!preg_match('/^\+?[0-9\s()-]{7,20}$/', $phoneNumber)) {
        die("Invalid phone number.");
    }

    // Insert data into the database
    $sql = "INSERT INTO Payments (userID, name, cardType, cardNumber, expirationDate, coupon, billingAddress, phoneNumber)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssss", $userID, $name, $cardType, $hashedCardNumber, $expirationDate, $coupon, $billingAddress,$phoneNumber);

    if ($stmt->execute()) {
        echo "Registration successful! Redirecting to login page...";
        header("Refresh: 3; URL=login.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();

?>