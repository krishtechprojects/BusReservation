
<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$dbpassword = "";
$database = "busreserve";

// Initialize variables for login and error messages
$loginError = "";
$successMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Create a database connection
    $conn = new mysqli($servername, $username, $dbpassword, $database);

    // Check for a successful connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query to check if the user exists in the 'Passengers' table
    $sql = "SELECT * FROM Passengers WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // User authentication successful
        $successMessage = "Login successful!";
        
        // Retrieve passenger_id from the database
        $row = $result->fetch_assoc();
        $passengerId = $row["passenger_id"];
        
        // Start a session
        session_start();
        
        // Store passenger_id and email in session variables
        $_SESSION['passenger_id'] = $passengerId;
        $_SESSION['email'] = $email;
        
        // Redirect to passengerPanel.php
        header("Location: passengerPanel.php");
        exit();
    } else {
        $loginError = "Login failed. Please check your email and password.";
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
    <title>Bus Reservation System - Login</title>
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

    <!-- Centered Login Panel -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title text-center">Login</h1>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>

                        <!-- Display login status -->
                        <?php if (!empty($loginError)): ?>
                            <div class="mt-3 alert alert-danger">
                                <?php echo $loginError; ?>
                            </div>
                        <?php elseif (!empty($successMessage)): ?>
                            <div class="mt-3 alert alert-success">
                                <?php echo $successMessage; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Link to Register Page -->
                <div class="mt-3 text-center">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS and jQuery links here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
