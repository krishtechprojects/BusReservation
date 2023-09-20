<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Reservation System</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS for panel images */
        .panel-image {
            max-width: 100%;
        }
        /* Custom CSS to align the navigation to the right */
        .navbar {
            justify-content: flex-end;
        }
    </style>
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

    <!-- Content goes here -->
    <div class="container mt-5">
        <h1>Welcome to the Bus Reservation System</h1>
        <p>This is the home page of our bus reservation system. You can use the navigation bar above to access different sections of the system.</p>
    </div>

    <!-- Panels with Images and Introduction -->
    <div class="container mt-4">
        <div class="row">
            <!-- Panel 1 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="img/bus1.jpg" class="card-img-top panel-image" alt="Bus Image 1">
                    <div class="card-body">
                        <h5 class="card-title">Explore Our Routes</h5>
                        <p class="card-text">Discover a wide range of bus routes to your favorite destinations.</p>
                    </div>
                </div>
            </div>

            <!-- Panel 2 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="img/bus2.jpg" class="card-img-top panel-image" alt="Bus Image 2">
                    <div class="card-body">
                        <h5 class="card-title">Book Your Seat</h5>
                        <p class="card-text">Easily book your seat online and secure your journey.</p>
                    </div>
                </div>
            </div>

            <!-- Panel 3 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="img/bus3.jpg" class="card-img-top panel-image" alt="Bus Image 3">
                    <div class="card-body">
                        <h5 class="card-title">About Us</h5>
                        <p class="card-text">Learn more about our company and commitment to quality service.</p>
                    </div>
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
