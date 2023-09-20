<?php
// Include your database connection configuration here
$servername = "localhost";
$username = "root";
$password = "";
$database = "busreserve";

// Initialize variables for form submission
$routeName = $departureCity = $arrivalCity = $departureTime = $arrivalTime = $totalSeats = $busType = $busFare = "";
$successMessage = $errorMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $routeName = $_POST["routeName"];
    $departureCity = $_POST["departureCity"];
    $arrivalCity = $_POST["arrivalCity"];
    $departureTime = $_POST["departureTime"];
    $arrivalTime = $_POST["arrivalTime"];
    $totalSeats = $_POST["totalSeats"];
    $busType = $_POST["busType"];
    $busFare = $_POST["busFare"]; // Add this line to retrieve bus_fare

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check for a successful connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query to insert a new route into the 'BusRoutes' table
    $sql = "INSERT INTO BusRoutes (route_name, departure_city, arrival_city, departure_time, arrival_time, total_seats, bus_type, bus_fare) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssisd", $routeName, $departureCity, $arrivalCity, $departureTime, $arrivalTime, $totalSeats, $busType, $busFare);

    if ($stmt->execute()) {
        $successMessage = "Route added successfully!";
    } else {
        $errorMessage = "Error adding route: " . $stmt->error;
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
    <title>Add Bus Routes</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                <!-- Add other navigation links here -->
            </ul>
        </div>
    </nav>

    <!-- Content Container -->
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Add Bus Routes</h1>
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
                        <label for="routeName">Route Name:</label>
                        <input type="text" class="form-control" id="routeName" name="routeName" required pattern="^[A-Za-z0-9\s]+$">

                    </div>
                    <div class="form-group">
                        <label for="departureCity">Departure City:</label>
                        <input type="text" class="form-control" id="departureCity" name="departureCity" required pattern="^[A-Za-z\s\-]+$">
                    </div>
                    <div class="form-group">
                        <label for="arrivalCity">Arrival City:</label>
                        <input type="text" class="form-control" id="arrivalCity" name="arrivalCity" required pattern="^[A-Za-z\s\-]+$">
                    </div>
                    <div class="form-group">
                        <label for="departureTime">Departure Time:</label>
                        <input type="datetime-local" class="form-control" id="departureTime" name="departureTime" required>
                    </div>
                    <div class="form-group">
                        <label for="arrivalTime">Arrival Time:</label>
                        <input type="datetime-local" class="form-control" id="arrivalTime" name="arrivalTime" required>
                    </div>
                    <div class="form-group">
                        <label for="totalSeats">Total Seats:</label>
                        <input type="number" class="form-control" id="totalSeats" name="totalSeats" required>
                    </div>
                    <div class="form-group">
                        <label for="busType">Bus Type:</label>
                        <select class="form-control" id="busType" name="busType">
                            <option value="sleeper">Sleeper</option>
                            <option value="semi sleeper">Semi Sleeper</option>
                            <option value="delux">Delux</option>
                            <option value="luxury">Luxury</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="busFare">Bus Fare:</label>
                        <input type="text" class="form-control" id="busFare" name="busFare" required pattern="^\d+(\.\d+)?$">

                    </div>
                    <button type="submit" class="btn btn-primary">Add Route</button>
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
