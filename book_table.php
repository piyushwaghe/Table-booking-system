<?php 
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
}

$user_id = $_SESSION['user_id'];
?>

<link rel="stylesheet" href="style.css">

<div class="container">
<h2>Book Table</h2>

<!-- SELECT DATE & TIME -->
<form method="POST">
  <input type="date" name="date" required value="<?php echo $_POST['date'] ?? ''; ?>">
  <input type="time" name="time" required value="<?php echo $_POST['time'] ?? ''; ?>">
  <button name="check">Check Availability</button>
</form>

<a href="dashboard.php">⬅ Back</a>

<?php
// SHOW TABLE STATUS
if(isset($_POST['check']) || isset($_POST['book'])){

  $date = $_POST['date'];
  $time = $_POST['time'];

  echo "<h3 style='text-align:center;margin-top:15px;'>Select Table</h3>";

  echo "<form method='POST'>";
  echo "<input type='hidden' name='date' value='$date'>";
  echo "<input type='hidden' name='time' value='$time'>";

  echo "<select name='table_id' required>";

  $tables = mysqli_query($conn,"SELECT * FROM tables");

  while($row = mysqli_fetch_assoc($tables)){

    $table_id = $row['id'];

    // CHECK IF TABLE IS ALREADY BOOKED
    $check = mysqli_query($conn,
      "SELECT * FROM bookings 
       WHERE table_id='$table_id' 
       AND booking_date='$date' 
       AND booking_time='$time'
       AND status='Booked'"
    );

    if(mysqli_num_rows($check)>0){
      echo "<option disabled>Table {$row['table_number']} ❌ (Booked)</option>";
    } else {
      echo "<option value='{$row['id']}'>Table {$row['table_number']} 🟢 (Available)</option>";
    }
  }

  echo "</select>";
  echo "<button name='book'>Request Booking</button>";
  echo "</form>";
}
?>

<?php
// INSERT BOOKING (NOW PENDING)
if(isset($_POST['book'])){

  $date = $_POST['date'];
  $time = $_POST['time'];
  $table_id = $_POST['table_id'];

  mysqli_query($conn,
    "INSERT INTO bookings(user_id,table_id,booking_date,booking_time,status)
     VALUES('$user_id','$table_id','$date','$time','Pending')"
  );

  echo "<p style='text-align:center;'>⏳ Booking Request Sent! Waiting for Admin Approval</p>";
}
?>

</div>