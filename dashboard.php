<?php 
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
}

// OPTIONAL: get user name
$user_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT name FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($res);
?>

<link rel="stylesheet" href="style.css">

<div class="container" style="text-align:center;">

  <h2>Dashboard</h2>

  <p style="margin-bottom:15px;">
    👋 Welcome, <b><?php echo $user['name']; ?></b>
  </p>

  <!-- BOOK TABLE -->
  <a href="book_table.php">
    <button>🍽️ Book Table</button>
  </a>

  <!-- MY BOOKINGS -->
  <a href="my_bookings.php">
    <button style="margin-top:10px;">📋 My Bookings</button>
  </a>

  <!-- LOGOUT -->
  <a href="logout.php">
    <button style="margin-top:10px; background:red;">Logout</button>
  </a>

</div>