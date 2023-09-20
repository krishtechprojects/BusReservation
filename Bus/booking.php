<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "busreserve";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for a successful connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve all bookings
$sql = "SELECT b.booking_id, p.first_name, p.last_name, r.route_name, r.departure_city, r.arrival_city, r.departure_time, r.arrival_time, b.booking_date
        FROM BusBookings b
        JOIN Passengers p ON b.passenger_id = p.passenger_id
        JOIN BusRoutes r ON b.route_id = r.route_id
        ORDER BY b.booking_date DESC";

$result = $conn->query($sql);

// Check if there are bookings
if ($result->num_rows > 0) {
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $bookings = [];
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings</title>
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
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search.php">Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="booking.php">Booking</a>
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

    <!-- Booking Table -->
    <div class="container mt-5">
        <h1>All Bookings</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Passenger Name</th>
                    <th>Route Name</th>
                    <th>Departure City</th>
                    <th>Arrival City</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Booking Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo $booking["booking_id"]; ?></td>
                        <td><?php echo $booking["first_name"] . " " . $booking["last_name"]; ?></td>
                        <td><?php echo $booking["route_name"]; ?></td>
                        <td><?php echo $booking["departure_city"]; ?></td>
                        <td><?php echo $booking["arrival_city"]; ?></td>
                        <td><?php echo $booking["departure_time"]; ?></td>
                        <td><?php echo $booking["arrival_time"]; ?></td>
                        <td><?php echo $booking["booking_date"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Bootstrap JS and jQuery links here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
