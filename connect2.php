<?php
$servername = "mysql14.000webhost.com";
$username = "a4887060_niki";
$password = "niki888";
$dbname="a4887060_gsm";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  
    die("Connection failed: " . $conn->connect_error);
}
?>