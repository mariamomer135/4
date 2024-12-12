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
    echo "Property ID not provided.";
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

// Fetch the property details
$sql = "SELECT * FROM Properties WHERE propertyID = ? AND userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $propertyID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Property not found or you do not have permission to edit it.";
    exit();
}

$property = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property</title>
    <link rel="stylesheet" href="css/edit.css">
</head>
<body>
    <h1>Edit Property</h1>
    <form action="edit_property_process.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="propertyID" value="<?php echo $property['propertyID']; ?>">

        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" value="<?php echo $property['price']; ?>" required><br>

        <label for="location">Location:</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($property['location']); ?>" required><br>

        <label for="age">Age of Property (in years):</label>
        <input type="number" name="age" value="<?php echo $property['age']; ?>" required><br>

        <label for="floorPlan">Floor Plan (e.g., number of rooms or area in sqft):</label>
        <input type="number" name="floorPlan" value="<?php echo $property['floorPlan']; ?>" required><br>

        <label for="bedrooms">Number of Bedrooms:</label>
        <input type="number" name="bedrooms" value="<?php echo $property['numBedrooms']; ?>" required><br>

        <label for="bathrooms">Number of Bathrooms:</label>
        <input type="number" name="bathrooms" value="<?php echo $property['numBathrooms']; ?>" required><br>

        <label for="hasGarden">Has Garden:</label>
        <input type="checkbox" name="hasGarden" <?php echo $property['hasGarden'] ? 'checked' : ''; ?>><br>

        <label for="hasParking">Has Parking:</label>
        <input type="checkbox" name="hasParking" <?php echo $property['hasParking'] ? 'checked' : ''; ?>><br>

        <label for="proximityFacilities">Proximity to Facilities:</label>
        <input type="text" name="proximityFacilities" value="<?php echo htmlspecialchars($property['proximityToTowns']); ?>"><br>

        <label for="proximityMainRoads">Proximity to Main Roads:</label>
        <input type="text" name="proximityMainRoads" value="<?php echo htmlspecialchars($property['proximityToMainRoads']); ?>"><br>


        <label for="propertyTax">Property Tax (in currency):</label>
        <input type="number" step="0.01" name="propertyTax" value="<?php echo $property['propertyTax']; ?>" required><br>




        <!-- Show existing image if available -->
        <?php if (!empty($property['imagePath'])): ?>
            <p>Current Image: <img src="<?php echo $property['imagePath']; ?>" alt="Current Property Image" style="max-width: 200px;"></p>
        <?php endif; ?>

        <label for="propertyImage">Upload New Property Image (optional):</label>
        <input type="file" name="propertyImage"><br>

        <input type="submit" value="Update Property">
    </form>
</body>
</html>
