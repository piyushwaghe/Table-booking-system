<?php
session_start();
include("db.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: linear-gradient(to right, #6a5acd, #7b68ee);
}

.container {
    width: 90%;
    margin: 40px auto;
    padding: 25px;
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    text-align: center;
}

h2 { color: white; }

table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    border: 1px solid white;
    color: white;
}

th { background: rgba(255,255,255,0.2); }

.btn {
    padding: 6px 12px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
}

.approve { background: green; }
.reject { background: red; }

.available { color: lightgreen; }
.booked { color: red; }
.pending { color: orange; }

.logout {
    display: inline-block;
    margin-top: 20px;
    color: white;
    text-decoration: none;
}
</style>
</head>

<body>

<div class="container">

<!-- 🔔 PENDING -->
<h2>🔔 Pending Booking Requests</h2>

<table>
<tr>
<th>Name</th>
<th>Email</th>
<th>Table</th>
<th>Date</th>
<th>Time</th>
<th>Action</th>
</tr>

<?php
$q = "SELECT b.id, b.booking_date, b.booking_time,
             t.table_number, u.name, u.email
      FROM bookings b
      JOIN users u ON b.user_id = u.id
      JOIN tables t ON b.table_id = t.id
      WHERE b.status='Pending'";

$r = mysqli_query($conn,$q);

if(mysqli_num_rows($r)>0){
while($row=mysqli_fetch_assoc($r)){
?>

<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td>Table <?php echo $row['table_number']; ?></td>
<td><?php echo $row['booking_date']; ?></td>
<td><?php echo $row['booking_time']; ?></td>
<td>
<a class="btn approve" href="approve.php?id=<?php echo $row['id']; ?>">Approve</a>
<a class="btn reject" href="reject.php?id=<?php echo $row['id']; ?>">Reject</a>
</td>
</tr>

<?php } } else {
echo "<tr><td colspan='6'>No Pending Bookings</td></tr>";
}
?>
</table>


<!-- 📊 LIVE STATUS -->
<h2>📊 Live Table Status</h2>

<table>
<tr>
<th>Table</th>
<th>Status</th>
<th>Name</th>
<th>Email</th>
<th>Date</th>
<th>Time</th>
</tr>

<?php
$tables = mysqli_query($conn,"SELECT * FROM tables");

while($t=mysqli_fetch_assoc($tables)){

$table_id = $t['id'];

// 🔥 FIX: GET LATEST BOOKING (NO DATE LIMIT)
$booking = mysqli_query($conn,"
SELECT b.*, u.name, u.email
FROM bookings b
JOIN users u ON b.user_id = u.id
WHERE b.table_id='$table_id'
ORDER BY b.id DESC
LIMIT 1
");

echo "<tr>";
echo "<td>Table {$t['table_number']}</td>";

if(mysqli_num_rows($booking)>0){

$b = mysqli_fetch_assoc($booking);

if($b['status']=="Booked"){
echo "<td class='booked'>❌ Booked</td>";
}
elseif($b['status']=="Pending"){
echo "<td class='pending'>⏳ Pending</td>";
}
else{
echo "<td class='available'>🟢 Available</td>";
}

echo "<td>{$b['name']}</td>";
echo "<td>{$b['email']}</td>";
echo "<td>{$b['booking_date']}</td>";
echo "<td>{$b['booking_time']}</td>";

}else{

echo "<td class='available'>🟢 Available</td>";
echo "<td>-</td><td>-</td><td>-</td><td>-</td>";

}

echo "</tr>";
}
?>

</table>

<a class="logout" href="logout.php">Logout</a>

</div>

</body>
</html>