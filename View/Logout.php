<?php
// Start the session
session_start();

// If the user is logged in, destroy the session and redirect to login page
if(isset($_SESSION['username'])) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect the user to the login page
    header("Location: Home.php");
    exit();
} else {
    // Redirect the user to the login page if not logged in
    header("Location: Login.php");
    exit();
}
?>
