<?php
// Include your database connection configuration here
$servername = "localhost";
$username = "root";
$password = "";
$database = "busreserve";

// Initialize variables for form submission
$routeId = $driverId = $busNumber = "";
$successMessage = $errorMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $routeId = $_POST["routeId"];
    $driverId = $_POST["driverId"];
    $busNumber = $_POST["busNumber"];

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check for a successful connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query to insert a new assignment into the 'BusAssignments' table
    $sql = "INSERT INTO BusAssignments (route_id, driver_id, bus_number)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $routeId, $driverId, $busNumber);

    if ($stmt->execute()) {
        $successMessage = "Assignment added successfully!";
    } else {
        $errorMessage = "Error adding assignment: " . $stmt->error;
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
    <title>Add Bus Assignments</title>
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
                <h1 class="card-title">Add Bus Assignments</h1>
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
                        <label for="routeId">Select Route:</label>
                        <select class="form-control" id="routeId" name="routeId" required>
                            <?php
                            // Create a database connection
                            $conn = new mysqli($servername, $username, $password, $database);

                            // Check for a successful connection
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            // Retrieve routes from the 'BusRoutes' table
                            $sql = "SELECT route_id, route_name FROM BusRoutes";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["route_id"] . "'>" . $row["route_name"] . "</option>";
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="driverId">Select Driver:</label>
                        <select class="form-control" id="driverId" name="driverId" required>
                            <?php
                            // Create a database connection
                            $conn = new mysqli($servername, $username, $password, $database);

                            // Check for a successful connection
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            // Retrieve drivers from the 'BusDrivers' table
                            $sql = "SELECT driver_id, driver_name FROM BusDrivers";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["driver_id"] . "'>" . $row["driver_name"] . "</option>";
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="busNumber">Bus Number:</label>
                        <input type="text" class="form-control" id="busNumber" name="busNumber" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Assignment</button>
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
