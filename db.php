<?php
$servername = "localhost";
$username = "root"; // change as needed
$password = ""; // change as needed
$dbname = "grocery_list";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>