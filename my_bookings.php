<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// ✅ SERVER-SIDE CANCEL (SECURE)
if(isset($_GET['cancel'])){
  $id = (int)$_GET['cancel'];

  // Only cancel if booking belongs to logged-in user
  $stmt = mysqli_prepare($conn,
    "UPDATE bookings SET status='Cancelled' WHERE id=? AND user_id=?"
  );
  mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
  mysqli_stmt_execute($stmt);

  header("Location: my_bookings.php");
  exit;
}
?>

<link rel="stylesheet" href="style.css">

<div class="container" style="width:750px;">
<h2>My Bookings</h2>

<table border="1" width="100%" style="background:white;color:black;text-align:center;">
<tr>
  <th>Name</th>
  <th>Email</th>
  <th>Table</th>
  <th>Date</th>
  <th>Time</th>
  <th>Status</th>
  <th>Action</th>
</tr>

<?php
// ✅ JOIN QUERY (NAME + EMAIL + TABLE)
$query = "
SELECT 
  b.id,
  b.booking_date,
  b.booking_time,
  b.status,
  t.table_number,
  u.name,
  u.email
FROM bookings b
JOIN tables t ON b.table_id = t.id
JOIN users u ON b.user_id = u.id
WHERE b.user_id = '$user_id'
ORDER BY b.id DESC
";

$res = mysqli_query($conn, $query);

if(mysqli_num_rows($res) == 0){
  echo "<tr><td colspan='7'>No bookings found</td></tr>";
}

while($row = mysqli_fetch_assoc($res)){
  echo "<tr>
    <td>{$row['name']}</td>
    <td>{$row['email']}</td>
    <td>Table {$row['table_number']}</td>
    <td>{$row['booking_date']}</td>
    <td>{$row['booking_time']}</td>
    <td>{$row['status']}</td>
    <td>";

  // ✅ SHOW CANCEL ONLY IF BOOKED
  if($row['status'] == 'Booked'){
    echo "<a href='?cancel={$row['id']}' 
          onclick=\"return confirm('Cancel booking?')\">❌ Cancel</a>";
  } else {
    echo "-";
  }

  echo "</td></tr>";
}
?>

</table>

<br>
<a href="dashboard.php">⬅ Back</a>
</div>