<?php
// Start the session
session_start();

// Check if the passenger is logged in
if (!isset($_SESSION["passenger_id"])) {
    header("Location: login.php");
    exit();
}

// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "busreserve";

// Initialize an array to store passenger's bookings
$bookings = [];

// Retrieve passenger_id from session
$passengerId = $_SESSION["passenger_id"];

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for a successful connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query to retrieve passenger's bookings
$sql = "SELECT b.booking_id, r.route_name, r.departure_city, r.arrival_city, r.departure_time, r.arrival_time, b.booking_date
        FROM BusBookings b
        JOIN BusRoutes r ON b.route_id = r.route_id
        WHERE b.passenger_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $passengerId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch and store the passenger's bookings in an array
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings</title>
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
                <li class="nav-item active">
                    <a class="nav-link" href="viewBookings.php">View Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Booking Table -->
    <div class="container mt-5">
        <h1>My Bookings</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Route Name</th>
                    <th>Departure City</th>
                    <th>Arrival City</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Booking Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo $booking["route_name"]; ?></td>
                        <td><?php echo $booking["departure_city"]; ?></td>
                        <td><?php echo $booking["arrival_city"]; ?></td>
                        <td><?php echo $booking["departure_time"]; ?></td>
                        <td><?php echo $booking["arrival_time"]; ?></td>
                        <td><?php echo $booking["booking_date"]; ?></td>
                        <td>
                            <a href="deleteBooking.php?booking_id=<?php echo $booking["booking_id"]; ?>" class="btn btn-danger">Delete</a>
                        </td>
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
