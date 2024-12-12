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

// Fetch properties for the logged-in seller
$sql = "SELECT * FROM Properties WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$properties = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="dashboard">
        <h1>Welcome to your Dashboard</h1>
        <p><a href="add_property_form.php">Add a new property</a></p>

        <?php if (count($properties) > 0): ?>
            <div class="property-list">
                <?php foreach ($properties as $property): ?>
                    <a href="property_details.php?propertyID=<?php echo $property['propertyID']; ?>" class="property-card" style="background-image: url('<?php echo $property['imagePath']; ?>');">
                        
                    <div class="property-details">
                            <h2><?php echo htmlspecialchars($property['location']); ?></h2>
                            <p class="price">$<?php echo number_format($property['price'], 2); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>You haven't added any properties yet. <a href="add_property_form.php">Add a property now!</a></p>
        <?php endif; ?>
    </div>
</body>
</html>