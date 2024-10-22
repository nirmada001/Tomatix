<?php
include '../Model/Connect.php';

$email = $username = $upassword = $cpassword = '';
$emailErr = $usernameErr = $upasswordErr = $cpasswordErr = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //check if email field is empty or in valid format
    if (empty($_POST['email'])) {
        $emailErr = "Please enter a valid email";
    } else {
        $email = test_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    //check if username is empty
    if (empty($_POST['username'])) {
        $usernameErr = "Please enter a valid username";
    } else {
        $username = test_input($_POST['username']);

        // Check if the username already exists in the database
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $usernameErr = "Username already exists";
        }
    }

    //password field empty
    if (empty($_POST['password'])) {
        $upasswordErr = "Please enter a valid Password";
    } else {
        $upassword = test_input($_POST['password']);
        
    }

    //confirm password empty
    if(empty($_POST['cpassword'])){
        $cpasswordErr = "Please re-enter your Password";
    } else{
        $cpassword = test_input($_POST['cpassword']);
        
    }

    //password and confirm password
    if($upassword !== $cpassword){
        $cpasswordErr = "Passwords do not match";
    }else{  
        // Hash the password
        $upassword = password_hash($upassword, PASSWORD_BCRYPT);
    }

    // Check if all error messages are empty, indicating valid data
    if (empty($emailErr) && empty($usernameErr) && empty($passwordErr)) {
        $query = "INSERT INTO users VALUES ('','$username', '$email', '$upassword')";

        if ($conn->query($query) === TRUE) {
            echo '<script>alert("Registration Successfull")</script>';
            // Redirect to the home page
            echo '<script>window.location.href = "Login.php";</script>';
            exit();
        } else {
            echo '<script>alert("Registration unsuccessfull: ' . $conn->error . '")</script>';
        }
    }

}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>