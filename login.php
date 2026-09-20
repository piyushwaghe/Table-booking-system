<?php
session_start();
include "db.php";
?>

<link rel="stylesheet" href="style.css">

<div class="container">
<h2>Login</h2>

<form method="POST" action="">

  <input type="email" name="email" placeholder="Email" required>

  <input type="password" name="password" placeholder="Password" required>

  <select name="role" required>
    <option value="">Login As</option>
    <option value="user">User</option>
    <option value="admin">Admin</option>
  </select>

  <button type="submit" name="login">Login</button>

</form>

<a href="register.php">Create Account</a>

<?php
if(isset($_POST['login'])){

  $email = trim($_POST['email']);
  $pass  = trim($_POST['password']);
  $role  = $_POST['role'];

  // ================= ADMIN LOGIN =================
  if($role == "admin"){

    // Hardcoded admin
    if($email === "admin@gmail.com" && $pass === "admin123"){

      $_SESSION['admin'] = true;

      header("Location: admin_dashboard.php");
      exit;

    } else {
      echo "<p style='color:red;text-align:center;'>❌ Invalid Admin Login</p>";
    }

  }

  // ================= USER LOGIN =================
  else if($role == "user"){

    $res = mysqli_query($conn,
      "SELECT * FROM users WHERE email='$email' AND password='$pass'"
    );

    if(mysqli_num_rows($res) > 0){

      $row = mysqli_fetch_assoc($res);
      $_SESSION['user_id'] = $row['id'];

      header("Location: user_dashboard.php");
      exit;

    } else {
      echo "<p style='color:red;text-align:center;'>❌ Invalid User Login</p>";
    }

  }

  // ================= NO ROLE SELECTED =================
  else {
    echo "<p style='color:red;text-align:center;'>❌ Please select login role</p>";
  }
}
?>
</div>