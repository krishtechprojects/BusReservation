<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "busreserve";

// Initialize variables for search criteria
$busType = "";

// Initialize an array to store search results
$searchResults = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $busType = $_POST["busType"];

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check for a successful connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query to retrieve buses based on bus type
    $sql = "SELECT * FROM BusRoutes WHERE bus_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $busType);
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
    <title>Search Buses by Type</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Bootstrap Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
				<li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking.php">Booking</a>
                    </li>
                
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Admin</a> <!-- Added Admin link -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="aboutus.php">About Us</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Search Form -->
    <div class="container mt-5">
        <h1>Search Buses by Type</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="busType">Select Bus Type:</label>
                <select class="form-control" id="busType" name="busType" required>
                    <option value="sleeper">Sleeper</option>
                    <option value="semi sleeper">Semi Sleeper</option>
                    <option value="delux">Delux</option>
                    <option value="luxury">Luxury</option>
                </select>
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
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Total Seats</th>
                        <th>Bus Type</th>
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
                            <td><?php echo $row["departure_time"]; ?></td>
                            <td><?php echo $row["arrival_time"]; ?></td>
                            <td><?php echo $row["total_seats"]; ?></td>
                            <td><?php echo $row["bus_type"]; ?></td>
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
