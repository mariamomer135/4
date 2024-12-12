<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$propertyID = isset($_POST['propertyID']) ? $_POST['propertyID'] : null;

if (!$propertyID) {
    echo "Property ID not provided.";
    exit();
}

// Collect form data
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

// Handle image upload (optional)
$imagePath = NULL; // Default to NULL (no new image)

if (!empty($_FILES['propertyImage']['name'])) {
    // Process new image upload
    $targetDir = "uploads/";
    $imageName = basename($_FILES["propertyImage"]["name"]);
    $targetFile = $targetDir . $imageName;

    // Ensure file upload is successful
    if (move_uploaded_file($_FILES["propertyImage"]["tmp_name"], $targetFile)) {
        $imagePath = $targetFile; // Set the new image path
    } else {
        echo "Sorry, there was an error uploading your file.";
        exit();
    }
} else {
    // If no new image is uploaded, retain the old image
    // Fetch current image path from the database
    $conn = new mysqli("localhost", "momer3", "momer3", "momer3");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT imagePath FROM Properties WHERE propertyID = ? AND userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $propertyID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();
    $imagePath = $property['imagePath']; // Use the existing image path if no new image is uploaded
    $stmt->close();
    $conn->close();
}

// Database connection
$conn = new mysqli("localhost", "momer3", "momer3", "momer3");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the property details in the database
$sql = "UPDATE Properties 
        SET location = ?, age = ?, floorPlan = ?, numBedrooms = ?, numBathrooms = ?, hasGarden = ?, hasParking = ?, proximityToTowns = ?, proximityToMainRoads = ?, propertyTax = ?, imagePath = ?, price = ? 
        WHERE propertyID = ? AND userID = ?";

$stmt = $conn->prepare($sql);

// Bind parameters
if ($imagePath === NULL) {
    $stmt->bind_param("siisissssdssi", $location, $age, $floorPlan, $bedrooms, $bathrooms, $hasGarden, $hasParking, $proximityFacilities, $proximityMainRoads, $propertyTax, $imagePath, $price, $propertyID, $userID);
} else {
    $stmt->bind_param("siisissssdssii", $location, $age, $floorPlan, $bedrooms, $bathrooms, $hasGarden, $hasParking, $proximityFacilities, $proximityMainRoads, $propertyTax, $imagePath, $price, $propertyID, $userID);
}

if ($stmt->execute()) {
    header("Location: property_details.php?propertyID=" . $propertyID); // Redirect to the updated property details page
    exit();
} else {
    echo "Error updating property.";
}

$stmt->close();
$conn->close();
?>
