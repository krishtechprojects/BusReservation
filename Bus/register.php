<?php
// Database connection configuration (you should replace with your actual credentials)
$servername = "localhost";
$username = "root";
$dbpassword = "";
$database = "busreserve";

// Initialize variables to store registration status and validation errors
$registrationStatus = "";
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone_number = $_POST["phone_number"];

    // Validate inputs
    if (empty($first_name)) {
        $errors[] = "First Name is required.";
    } elseif (!preg_match("/^[a-zA-Z]+$/", $first_name)) {
        $errors[] = "First Name should contain only letters.";
    }

    if (empty($last_name)) {
        $errors[] = "Last Name is required.";
    } elseif (!preg_match("/^[a-zA-Z]+$/", $last_name)) {
        $errors[] = "Last Name should contain only letters.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password should be at least 6 characters long.";
    }

    if (empty($phone_number) || !preg_match("/^\d{10}$/", $phone_number)) {
        $errors[] = "Phone number should be 10 digits long.";
    }

    // If there are no validation errors, proceed with database insertion
    if (empty($errors)) {
        // Create a database connection
        $conn = new mysqli($servername, $username, $dbpassword, $database);

        // Check for a successful connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the SQL query to insert the user data into the 'Passengers' table
        $sql = "INSERT INTO Passengers (first_name, last_name, email, password, phone_number) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $phone_number);

        if ($stmt->execute()) {
            $registrationStatus = "Registration successful!";
        } else {
            $registrationStatus = "Registration failed. Please try again later.";
        }

        // Close the database connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Reservation System - Register Passenger</title>
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

    <!-- Registration Form -->
    <div class="container mt-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1 class="panel-title">Register Passenger</h1>
            </div>
            <div class="panel-body">
                <!-- Display registration status and validation errors -->
                <?php if (!empty($registrationStatus)): ?>
                    <div class="mt-3 alert <?php echo $registrationStatus === "Registration successful!" ? 'alert-success' : 'alert-danger'; ?>">
                        <?php echo $registrationStatus; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="mt-3 alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
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
