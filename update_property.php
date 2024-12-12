<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $propertyID = $_POST['propertyID'];
    $location = trim($_POST['location']);
    $age = trim($_POST['age']);
    $floorPlan = trim($_POST['floorPlan']);
    $bedrooms = trim($_POST['bedrooms']);
    $bathrooms = trim($_POST['bathrooms']);
    $price = trim($_POST['price']);

    // Handle image upload (optional)
    if (isset($_FILES['propertyImage']) && $_FILES['propertyImage']['error'] === 0) {
        $imagePath = 'uploads/' . basename($_FILES['propertyImage']['name']);
        move_uploaded_file($_FILES['propertyImage']['tmp_name'], $imagePath);
    } else {
        // Keep the old image if no new one is uploaded
        $imagePath = null;
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

    // Update query
    $sql = "UPDATE Properties SET location = ?, age = ?, floorPlan = ?, numBedrooms = ?, numBathrooms = ?, price = ?";
    if ($imagePath) {
        $sql .= ", imageURL = ?";
    }
    $sql .= " WHERE propertyID = ?";

    $stmt = $conn->prepare($sql);

    if ($imagePath) {
        $stmt->bind_param("ssiiiiisd", $location, $age, $floorPlan, $bedrooms, $bathrooms, $price, $imagePath, $propertyID);
    } else {
        $stmt->bind_param("ssiiiiid", $location, $age, $floorPlan, $bedrooms, $bathrooms, $price, $propertyID);
    }

    if ($stmt->execute()) {
        header("Location: property_details.php?id=$propertyID");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
