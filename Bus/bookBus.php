<?php
// Start the session
session_start();

// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "busreserve";

// Initialize variables for booking status and payment status
$bookingStatus = $paymentStatus = "";

// Check if the passenger is logged in
if (!isset($_SESSION["passenger_id"])) {
    header("Location: login.php");
    exit();
}

// Check if the route_id is provided as a query parameter
if (!isset($_GET["route_id"])) {
    header("Location: reserve.php");
    exit();
}

// Retrieve passenger_id and route_id from session and query parameter
$passengerId = $_SESSION["passenger_id"];
$routeId = $_GET["route_id"];

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for a successful connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query to retrieve route information based on route_id
$sqlRoute = "SELECT route_name, departure_city, arrival_city, departure_time, arrival_time, total_seats, bus_fare
            FROM BusRoutes
            WHERE route_id = ?";
$stmtRoute = $conn->prepare($sqlRoute);

if (!$stmtRoute) {
    die("Error preparing the statement: " . $conn->error);
}

$stmtRoute->bind_param("i", $routeId);
$stmtRoute->execute();
$resultRoute = $stmtRoute->get_result();

if ($resultRoute->num_rows > 0) {
    $rowRoute = $resultRoute->fetch_assoc();
    $routeName = $rowRoute["route_name"];
    $departureCity = $rowRoute["departure_city"];
    $arrivalCity = $rowRoute["arrival_city"];
    $departureTime = $rowRoute["departure_time"];
    $arrivalTime = $rowRoute["arrival_time"];
    $totalSeats = $rowRoute["total_seats"];
    $fare = $rowRoute["bus_fare"];
} else {
    echo "Route not found.";
    exit();
}

// Close the statement
$stmtRoute->close();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input for payment and number of seats
    $paymentAmount = $_POST["paymentAmount"];
	//echo $paymentAmount ;
    $numSeats = $_POST["numSeats"];
    $paymentDate = date("Y-m-d H:i:s"); // Current date and time

    // Check if the number of seats is valid and available
    if ($numSeats <= 0 || $numSeats > $totalSeats) {
        $bookingStatus = "Invalid number of seats.";
    } else {
        // Start a transaction
        $conn->begin_transaction();

        // Prepare and execute the SQL query to insert the booking into the 'BusBookings' table
        $sqlBooking = "INSERT INTO BusBookings (passenger_id, route_id, booking_date, seat_number)
                        VALUES (?, ?, ?, ?)";
        $stmtBooking = $conn->prepare($sqlBooking);

        // Bind parameters and execute the booking query
        $stmtBooking->bind_param("iisi", $passengerId, $routeId, $paymentDate, $numSeats);
        if ($stmtBooking->execute()) {
            $bookingId = $conn->insert_id; // Get the newly inserted booking_id
            $bookingStatus = "Booking successful!";

            // Update the total seats in the 'BusRoutes' table
            $newTotalSeats = $totalSeats - $numSeats;
            $sqlUpdateSeats = "UPDATE BusRoutes SET total_seats = ? WHERE route_id = ?";
            $stmtUpdateSeats = $conn->prepare($sqlUpdateSeats);
            $stmtUpdateSeats->bind_param("ii", $newTotalSeats, $routeId);

            // Execute the seat update query
            if ($stmtUpdateSeats->execute()) {
                // Insert payment information into the 'payments' table
                $sqlPayment = "INSERT INTO payments (booking_id, payment_amount, payment_date)
                                VALUES (?, ?, ?)";
                $stmtPayment = $conn->prepare($sqlPayment);
                $stmtPayment->bind_param("ids", $bookingId, $paymentAmount, $paymentDate);
                if ($stmtPayment->execute()) {
                    $conn->commit(); // Commit the transaction
                    $paymentStatus = "Payment successful!";
                } else {
                    $conn->rollback(); // Rollback if payment insertion fails
                    $paymentStatus = "Payment insertion error.";
                }
                $stmtPayment->close();
            } else {
                $conn->rollback(); // Rollback if the seat update fails
                $bookingStatus = "Booking failed. Seat update error.";
            }

            // Close the statement
            $stmtUpdateSeats->close();
        } else {
            $conn->rollback(); // Rollback if the booking fails
            $bookingStatus = "Booking failed. Please try again later.";
        }

        // Close the booking statement
        $stmtBooking->close();
    }

    // Close the database connection
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Bus</title>
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

    <div class="container-fluid">
        <div class="row mt-5">
            <!-- Left Panel -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">Route Information</h1>
                        <!-- Display route information based on route_id -->
                        <p><strong>Route Name:</strong> <?php echo $routeName; ?></p>
                        <p><strong>Departure City:</strong> <?php echo $departureCity; ?></p>
                        <p><strong>Arrival City:</strong> <?php echo $arrivalCity; ?></p>
                        <p><strong>Departure Time:</strong> <?php echo $departureTime; ?></p>
                        <p><strong>Arrival Time:</strong> <?php echo $arrivalTime; ?></p>
                        <p><strong>Total Seats:</strong> <?php echo $totalSeats; ?></p>
                        <p><strong>Fare:</strong> $<?php echo $fare; ?></p>
                    </div>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">Booking Information</h1>
                        <!-- Display booking and payment status -->
                        <?php if (!empty($bookingStatus)): ?>
                            <div class="alert <?php echo $bookingStatus === "Booking successful!" ? 'alert-success' : 'alert-danger'; ?>">
                                <?php echo $bookingStatus; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Booking and Payment Form -->
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?route_id=" . $routeId; ?>">
                            <div class="form-group">
                                <label for="numSeats">Number of Seats:</label>
                                <input type="number" class="form-control" id="numSeats" name="numSeats" min="1" max="<?php echo $totalSeats; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="paymentAmount">Total Payable Amount ($<?php echo $fare; ?> per seat):</label>
                                <input type="text" class="form-control" id="paymentAmount" name="paymentAmount" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary">Book</button>
                        </form>

                        <!-- QR Code Image -->
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS and jQuery links here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript to calculate the total payable amount -->
    <script>
        document.getElementById('numSeats').addEventListener('input', function() {
            const numSeats = parseInt(this.value);
            const fare = <?php echo $fare; ?>;
            const totalAmount = numSeats * fare;
            document.getElementById('paymentAmount').value =  totalAmount.toFixed(2);
        });
    </script>
</body>
</html>
