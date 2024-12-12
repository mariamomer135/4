<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property</title>
    <link rel="stylesheet" href="css/add.css">
</head>
<body>
    <h1>Add Property</h1>
    <form action="add_property.php" method="post" enctype="multipart/form-data">
    <label for="location">Location:</label>
    <input type="text" name="location" required><br>

    <label for="age">Age of Property (in years):</label>
    <input type="number" name="age" required><br>

    <label for="floorPlan">Floor Plan (e.g., number of rooms or area in sqft):</label>
    <input type="number" name="floorPlan" required><br>

    <label for="bedrooms">Number of Bedrooms:</label>
    <input type="number" name="bedrooms" required><br>

    <label for="bathrooms">Number of Bathrooms:</label>
    <input type="number" name="bathrooms" required><br>

    <label for="hasGarden">Has Garden:</label>
    <input type="checkbox" name="hasGarden"><br>

    <label for="hasParking">Has Parking:</label>
    <input type="checkbox" name="hasParking"><br>

    <label for="proximityFacilities">Proximity to Facilities:</label>
    <input type="text" name="proximityFacilities"><br>

    <label for="proximityMainRoads">Proximity to Main Roads:</label>
    <input type="text" name="proximityMainRoads"><br>

    <label for="propertyTax">Property Tax (in currency):</label>
    <input type="number" step="0.01" name="propertyTax" required><br>

    <label for="price">Price:</label>
    <input type="number" step="0.01" name="price" required><br>

    <label for="propertyImage">Upload Property Image:</label>
    <input type="file" name="propertyImage" required><br>

    <input type="submit" value="Add Property">
</form>
</body>
</html>
