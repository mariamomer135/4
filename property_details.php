<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$propertyID = isset($_GET['propertyID']) ? $_GET['propertyID'] : null;

if (!$propertyID) {
    echo "No property selected.";
    exit();
}

$host = "localhost";
$user = "momer3";
$pass = "momer3";
$dbname = "momer3";

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the property based on the propertyID and userID
$sql = "SELECT * FROM Properties WHERE propertyID = ? AND userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $propertyID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No property found.";
    exit();
}

$property = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <link rel="stylesheet" href="css/details.css">
</head>
<body>
    <div class="property-details-page">
    <img src="<?php echo $property['imagePath']; ?>" alt="Property Image" class="property-image">

            <div class="details-overlay">
                <h2><?php echo htmlspecialchars($property['location']); ?></h2>
            
                <p class="price">$<?php echo number_format($property['price'], 2); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($property['location']); ?> </p>
                <p><strong>Age of Property:</strong> <?php echo htmlspecialchars($property['age']); ?></p>
                <p><strong>Floor Plan (sqft):</strong> <?php echo htmlspecialchars($property['floorPlan']); ?> sqft</p>
                <p><strong>Bedrooms:</strong> <?php echo htmlspecialchars($property['numBedrooms']); ?></p>
                <p><strong>Bathrooms:</strong> <?php echo htmlspecialchars($property['numBathrooms']); ?></p>
                <p><strong>Presence of Garden:</strong> <?php echo htmlspecialchars($property['hasGarden']); ?></p>
                <p><strong>Parking Availability:</strong> <?php echo htmlspecialchars($property['hasParking']); ?></p>
                <p><strong>Proximity to Nearby Facilities:</strong> <?php echo htmlspecialchars($property['proximityToTowns']); ?></p>
                <p><strong>Proximity to Main Roads:</strong> <?php echo htmlspecialchars($property['proximityToMainRoads']); ?></p>
                <p><strong>Property Tax:</strong> <?php echo htmlspecialchars($property['propertyTax']); ?></p>
                
                


                <a href="edit_property.php?propertyID=<?php echo $property['propertyID']; ?>" class="btn-edit">Edit Property</a>

<!-- Delete Button (calls delete function) -->
<form action="delete_property.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this property?');">
    <input type="hidden" name="propertyID" value="<?php echo $property['propertyID']; ?>">
    <button type="submit" class="btn-delete">Delete Property</button>
    <a href="dashboard.php" class="btn-return-dashboard">Return to Dashboard</a>

</form>
            </div>
        </div>
    </div>
</body>
</html>
