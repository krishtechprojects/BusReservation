<?php
// Start the session
session_start();

// Check if the passenger is logged in
if (!isset($_SESSION['adminEmail'])) {
    header("Location: admin.php");
    exit();
}

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

// SQL query to fetch data from multiple tables using JOINs
$sql = "SELECT 
            bb.booking_id, 
            p.first_name, 
            p.last_name, 
            br.route_name, 
            bb.booking_date, 
            bb.seat_number, 
            py.payment_amount, 
            py.payment_date
        FROM 
            BusBookings bb
        INNER JOIN 
            Passengers p 
        ON 
            bb.passenger_id = p.passenger_id
        INNER JOIN 
            BusRoutes br 
        ON 
            bb.route_id = br.route_id
        LEFT JOIN 
            Payments py 
        ON 
            bb.booking_id = py.booking_id";

$result = $conn->query($sql);
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
        <a class="navbar-brand" href="#">Bus Reservation System</a>
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
				<li class="nav-item">
                    <a class="nav-link" href="viewAllBookings.php">View All Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>All Bookings</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Passenger Name</th>
                    <th>Route Name</th>
                    <th>Booking Date</th>
                    <th>Seat Number</th>
                    <th>Payment Amount</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch and display data from the query
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["booking_id"] . "</td>";
                        echo "<td>" . $row["first_name"] . " " . $row["last_name"] . "</td>";
                        echo "<td>" . $row["route_name"] . "</td>";
                        echo "<td>" . $row["booking_date"] . "</td>";
                        echo "<td>" . $row["seat_number"] . "</td>";
                        echo "<td>$" . $row["payment_amount"] . "</td>";
                        echo "<td>" . $row["payment_date"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No bookings found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Bootstrap JS and jQuery links here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
