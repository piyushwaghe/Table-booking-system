<?php
include("db.php");

$id = $_GET['id'];

$query = "UPDATE bookings SET status='Booked' WHERE id='$id'";
mysqli_query($conn, $query);

header("Location: admin_dashboard.php");
?>