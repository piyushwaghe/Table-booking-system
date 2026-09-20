<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

if(isset($_GET['id'])){
  $id = (int)$_GET['id'];

  // Only cancel if it belongs to this user
  $stmt = mysqli_prepare($conn,
    "UPDATE bookings SET status='Cancelled' WHERE id=? AND user_id=?"
  );
  mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
  mysqli_stmt_execute($stmt);

  header("Location: my_bookings.php");
  exit;
}
?>