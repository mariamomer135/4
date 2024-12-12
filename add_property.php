<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$host = "localhost";
$user = "momer3";
$pass = "momer3";
$dbname = "momer3";

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $location = trim($_POST['location']);
    $age = trim($_POST['age']);
    $floorPlan = trim($_POST['floorPlan']);
    $bedrooms = trim($_POST['bedrooms']);
    $bathrooms = trim($_POST['bathrooms']);
    $hasGarden = isset($_POST['hasGarden']) ? 1 : 0;
    $hasParking = isset($_POST['hasParking']) ? 1 : 0;
    $proximityFacilities = trim($_POST['proximityFacilities']);
    $proximityMainRoads = trim($_POST['proximityMainRoads']);
    $propertyTax = trim($_POST['propertyTax']);
    $price = isset($_POST['price']) ? trim($_POST['price']) : null; // Ensure price is provided

  

    // Handle image upload
    if (isset($_FILES['propertyImage']) && $_FILES['propertyImage']['error'] === 0) {
        $imagePath = 'uploads/' . basename($_FILES['propertyImage']['name']);
        if (!move_uploaded_file($_FILES['propertyImage']['tmp_name'], $imagePath)) {
            die("Failed to move uploaded file.");
        }
    } else {
        die("Image upload failed. Please try again.");
    }
    
    // Insert property details into the database
    $sql = "INSERT INTO Properties (userID, location, age, floorPlan, numBedrooms, numBathrooms, hasGarden, hasParking, proximityToTowns, proximityToMainRoads, propertyTax, imagePath, price)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isiiissssdssd", $userID, $location, $age, $floorPlan, $bedrooms, $bathrooms, $hasGarden, $hasParking, $proximityFacilities, $proximityMainRoads, $propertyTax, $imagePath, $price);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
