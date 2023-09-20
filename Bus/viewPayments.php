<?php
// Include your database connection configuration here
$servername = "localhost";
$username = "root";
$password = "";
$database = "busreserve";

// Initialize a variable for error message
$errorMessage = "";

// Check if a payment is selected for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deletePayment"])) {
    // Get the selected payment's ID
    $paymentId = $_POST["deletePayment"];

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check for a successful connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query to delete the selected payment
    $sql = "DELETE FROM Payments WHERE payment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $paymentId);

    if ($stmt->execute()) {
        // Payment deleted successfully
    } else {
        $errorMessage = "Error deleting payment: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}

// Retrieve all payments from the 'Payments' table
$payments = [];

// Create a database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for a successful connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query to select all payments
$sql = "SELECT payment_id, booking_id, payment_amount, payment_date FROM Payments";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments</title>
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
                <!-- Add other navigation items here -->
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
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content Container -->
    <div class="container mt-5">
        <h1>View Payments</h1>
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Payment Amount</th>
                    <th>Payment Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?php echo $payment["booking_id"]; ?></td>
                        <td><?php echo $payment["payment_amount"]; ?></td>
                        <td><?php echo $payment["payment_date"]; ?></td>
                        <td>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="deletePayment" value="<?php echo $payment["payment_id"]; ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
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
