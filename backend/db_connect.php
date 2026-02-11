<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "food_ordering_system1";

// Create connection
$conn = new mysqli($servername,$username,$password,$database);

// Check connection
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}
?>