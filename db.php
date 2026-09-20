<?php
$conn = mysqli_connect("localhost", "root", "piyush", "hotel_booking");

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>