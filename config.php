<?php
$servername = "localhost";
$username = "root";
$password = ""; // default XAMPP MySQL password is empty
$dbname = "agroboost"; // use your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
