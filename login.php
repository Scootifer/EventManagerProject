<?php
session_start();

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $host = 'localhost';
    $db = 'userdb';
    $user = 'server';
    $pass = 'server';

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Query the database for the user with the given username and password
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // If the user exists, set the session variable and redirect to the homepage
        $_SESSION['username'] = $username;

        //get userID for storage in session
        //poses a security risk by alteration of UserID to be a super admin user
        $sql = "SELECT users.UserID FROM users WHERE username='$username'";
        $result = $conn->query($sql);
        print_r($result);
        $row = $result->fetch_assoc();
        $_SESSION['UserID'] = $row['UserID'];


        header('Location: homepage.php');
        exit;
    } else {
        // If the user doesn't exist, show an error message
        $error = 'Invalid username or password';
    }

    $conn->close(); // close the database connection
}



// Load the login page HTML file
require 'login.html';
?>
