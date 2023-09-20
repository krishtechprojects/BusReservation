<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    // Unset all of the session variables
    session_unset();

    // Destroy the session
    session_destroy();
}

// Redirect to the login page after logging out
header("Location: login.php");
exit();
?>
