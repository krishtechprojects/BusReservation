<?php
// Start the session
session_start();

// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "busreserve";

// Initialize variables for search criteria
$departureCity = $arrivalCity = "";

// Initialize an array to store search results
$searchResults = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $departureCity = $_POST["departureCity"];
    $arrivalCity = $_POST["arrivalCity"];

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check for a successful connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query to retrieve available buses based on search criteria
    $sql = "SELECT r.route_id, r.route_name, r.departure_city, r.arrival_city, r.departure_time, r.arrival_time, r.total_seats, r.bus_type, r.bus_fare, b.booking_date
            FROM BusRoutes r
            LEFT JOIN BusBookings b ON r.route_id = b.route_id
            WHERE r.departure_city = ? AND r.arrival_city = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $departureCity, $arrivalCity);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch and store the search results in an array
    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
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
    <title>Reserve a Bus</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Bootstrap Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Bus Reservation System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="passengerPanel.php">Reserve Bus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="myBookings.php">View Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Search Form -->
    <div class="container mt-5">
        <h1>Search for Available Buses</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="departureCity">Departure City:</label>
                    <input type="text" class="form-control" id="departureCity" name="departureCity" required value="<?php echo $departureCity; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="arrivalCity">Arrival City:</label>
                    <input type="text" class="form-control" id="arrivalCity" name="arrivalCity" required value="<?php echo $arrivalCity; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Display search results -->
        <?php if (!empty($searchResults)): ?>
            <h2>Available Buses</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Route Name</th>
                        <th>Departure City</th>
                        <th>Arrival City</th>
                        <th>Bus Type</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Total Seats</th>
                        <th>Fare</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $row): ?>
                        <tr>
                            <td><?php echo $row["route_name"]; ?></td>
                            <td><?php echo $row["departure_city"]; ?></td>
                            <td><?php echo $row["arrival_city"]; ?></td>
                            <td><?php echo $row["bus_type"]; ?></td>
                            <td><?php echo $row["departure_time"]; ?></td>
                            <td><?php echo $row["arrival_time"]; ?></td>
                            <td><?php echo $row["total_seats"]; ?></td>
                            <td><?php echo $row["bus_fare"]; ?></td>
                            <td>
                                <?php if (isset($_SESSION['passenger_id'])): ?>
                                    <a href="bookBus.php?route_id=<?php echo $row["route_id"]; ?>" class="btn btn-primary">Reserve</a>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-primary">Login to Book</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Add Bootstrap JS and jQuery links here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
