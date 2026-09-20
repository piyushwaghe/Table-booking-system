<?php 
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// 🔥 HANDLE BOOKING (WITH REDIRECT FIX)
if(isset($_POST['book'])){

  $date = $_POST['date'];
  $time = $_POST['time'];
  $table_id = $_POST['table_id'];

  // Prevent duplicate
  $check = mysqli_query($conn,
    "SELECT * FROM bookings 
     WHERE user_id='$user_id'
     AND table_id='$table_id'
     AND booking_date='$date'
     AND booking_time='$time'"
  );

  if(mysqli_num_rows($check) == 0){

    mysqli_query($conn,
      "INSERT INTO bookings(user_id,table_id,booking_date,booking_time,status)
       VALUES('$user_id','$table_id','$date','$time','Pending')"
    );

    header("Location: user_dashboard.php?success=1");
    exit;
  }
}
?>

<link rel="stylesheet" href="style.css">

<div class="container" style="width:900px;">

<h2>👤 User Dashboard</h2>

<?php
if(isset($_GET['success'])){
  echo "<p style='color:lightgreen;'>✅ Booking Request Sent!</p>";
}
?>

<!-- 📊 TABLE STATUS -->
<h3>📊 Table Status</h3>

<table border="1" width="100%" style="background:white;color:black;text-align:center;">
<tr>
  <th>Table</th>
  <th>Status</th>
</tr>

<?php
$tables = mysqli_query($conn, "SELECT * FROM tables");

while($t = mysqli_fetch_assoc($tables)){

  $table_id = $t['id'];

  // 🔥 GET LATEST BOOKING
  $booking = mysqli_query($conn,
    "SELECT * FROM bookings
     WHERE table_id='$table_id'
     ORDER BY id DESC
     LIMIT 1"
  );

  echo "<tr>";
  echo "<td>Table {$t['table_number']}</td>";

  if(mysqli_num_rows($booking) > 0){

    $b = mysqli_fetch_assoc($booking);

    if($b['status'] == 'Booked'){
      echo "<td style='color:red;'>❌ Booked</td>";
    }
    elseif($b['status'] == 'Pending'){
      echo "<td style='color:orange;'>⏳ Pending</td>";
    }
    else{
      echo "<td style='color:green;'>🟢 Available</td>";
    }

  } else {
    echo "<td style='color:green;'>🟢 Available</td>";
  }

  echo "</tr>";
}
?>
</table>

<br><hr>

<!-- 📝 BOOK TABLE -->
<h3>Book a Table</h3>

<form method="POST">

  <input type="date" name="date" required>
  <input type="time" name="time" required>

  <select name="table_id" required>
    <option value="">Select Table</option>

    <?php
    $tables = mysqli_query($conn, "SELECT * FROM tables");

    while($row = mysqli_fetch_assoc($tables)){

      $table_id = $row['id'];

      // Check latest status
      $check = mysqli_query($conn,
        "SELECT * FROM bookings 
         WHERE table_id='$table_id'
         ORDER BY id DESC LIMIT 1"
      );

      if(mysqli_num_rows($check) > 0){
        $b = mysqli_fetch_assoc($check);

        if($b['status'] == 'Booked'){
          echo "<option disabled>
                  Table {$row['table_number']} ❌ (Booked)
                </option>";
        }
        elseif($b['status'] == 'Pending'){
          echo "<option disabled>
                  Table {$row['table_number']} ⏳ (Pending)
                </option>";
        }
        else{
          echo "<option value='{$row['id']}'>
                  Table {$row['table_number']} 🟢 (Available)
                </option>";
        }

      } else {
        echo "<option value='{$row['id']}'>
                Table {$row['table_number']} 🟢 (Available)
              </option>";
      }
    }
    ?>
  </select>

  <button type="submit" name="book">Request Booking</button>

</form>

<br><hr>

<!-- 📋 USER BOOKINGS -->
<h3>📋 Your Bookings</h3>

<table border="1" width="100%" style="background:white;color:black;text-align:center;">
<tr>
  <th>Table</th>
  <th>Date</th>
  <th>Time</th>
  <th>Status</th>
</tr>

<?php
$res = mysqli_query($conn,
  "SELECT b.*, t.table_number 
   FROM bookings b
   JOIN tables t ON b.table_id = t.id
   WHERE b.user_id='$user_id'
   ORDER BY b.id DESC"
);

while($row = mysqli_fetch_assoc($res)){

  echo "<tr>
    <td>Table {$row['table_number']}</td>
    <td>{$row['booking_date']}</td>
    <td>{$row['booking_time']}</td>
    <td>";

  if($row['status'] == 'Booked'){
    echo "<span style='color:green;'>Booked</span>";
  } elseif($row['status'] == 'Pending'){
    echo "<span style='color:orange;'>Pending</span>";
  } else {
    echo "<span style='color:red;'>Cancelled</span>";
  }

  echo "</td></tr>";
}
?>

</table>

<br>
<a href="logout.php">Logout</a>

</div>