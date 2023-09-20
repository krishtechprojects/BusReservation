<?php
// Include your database connection configuration here
$servername = "localhost";
$username = "root";
$password = "";
$database = "busreserve";

// Initialize variables for form submission
$driverName = $licenseNumber = "";
$successMessage = $errorMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $driverName = $_POST["driverName"];
    $licenseNumber = $_POST["licenseNumber"];

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check for a successful connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query to insert a new driver into the 'BusDrivers' table
    $sql = "INSERT INTO BusDrivers (driver_name, license_number)
            VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $driverName, $licenseNumber);

    if ($stmt->execute()) {
        $successMessage = "Driver added successfully!";
    } else {
        $errorMessage = "Error adding driver: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bus Drivers</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="addRoutes.php">Add Routes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="addDrivers.php">Add Drivers</a>
                </li>
				<li class="nav-item">
                    <a class="nav-link" href="addAssignments.php">Bus Assignments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="viewPassengers.php">View Passengers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="viewPayments.php">View Payments</a>
                </li>
                
                <!-- Add other navigation items here -->
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content Container -->
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Add Bus Drivers</h1>
            </div>
            <div class="card-body">
                <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success"><?php echo $successMessage; ?></div>
                <?php endif; ?>
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                <?php endif; ?>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="driverName">Driver Name:</label>
                        <input type="text" class="form-control" id="driverName" name="driverName" required>
                    </div>
                    <div class="form-group">
                        <label for="licenseNumber">License Number:</label>
                        <input type="text" class="form-control" id="licenseNumber" name="licenseNumber" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Driver</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS and jQuery links here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
