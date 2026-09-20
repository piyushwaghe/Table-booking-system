<?php include "db.php"; ?>

<link rel="stylesheet" href="style.css">

<div class="container">
<h2>Register</h2>

<form method="POST">
  <input type="text" name="name" placeholder="Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button name="register">Register</button>
</form>

<a href="login.php">Login</a>

<?php
if(isset($_POST['register'])){
  $name = $_POST['name'];
  $email = $_POST['email'];
  $pass = $_POST['password'];

  mysqli_query($conn,
    "INSERT INTO users(name,email,password) VALUES('$name','$email','$pass')"
  );

  echo "<p style='text-align:center;'>✅ Registered!</p>";
}
?>
</div>