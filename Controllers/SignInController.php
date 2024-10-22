<?php
//including the database connection
include '../Model/Connect.php';

// Start the session
session_start();


if(isset($_POST['userlogin'])){

    $username = $_POST['lusername'];
    $password = $_POST['lpassword'];

    // get the user details from user table
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Executing the query
    $stmt->execute();

    // Geting the result
    $result = $stmt->get_result();
 
    if ($result->num_rows === 1) {
        // Fetching the user data
        $user = $result->fetch_assoc();

        // Verify the entered password with the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // if Password is correct
            // Store username in session
            $_SESSION['username'] = $username;

            // Redirect user to menu.php
            header("Location: menu.php");
            exit();
        } else {
            // Incorrect password show alert and redirect to the login.php page
            echo '<script>alert("Login unsuccessful..Please enter correct username and password")</script>';
            echo '<script>window.location.href = "Login.php";</script>';
            exit();
        }
    } else {
        // User not found..alert and direct to login page
        echo '<script>alert("Login unsuccessful..Please enter correct username and password")</script>';
        echo '<script>window.location.href = "Login.php";</script>';
        exit();
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();

   
}
?>
