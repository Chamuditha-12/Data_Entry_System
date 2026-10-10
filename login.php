<?php
session_start();
require 'config.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password_hash FROM admins WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Correct login
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

  <div class="topbar">
    <div class="brand">
      <div class="brand-title">Student Marks</div>
      <div class="brand-sub">Admin Login</div>
    </div>
    <a href="index.php" class="admin-chip" style="text-decoration:none; background:#ffffff; color:#16324f; border:1px solid #dbe3ec;">← Back to Form</a>
  </div>

  <div class="page" style="max-width:420px; margin:80px auto;">
    <div class="card">
      <div class="card-title" style="font-size:22px; margin-bottom:6px;">Admin Login</div>
      <div class="card-sub">Enter your credentials to access the dashboard</div>

      <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="field">
          <label>Username</label>
          <input type="text" name="username" placeholder="Enter username" required autofocus>
        </div>
        <div class="field">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter password" required>
        </div>
        <button type="submit" class="btn-primary">Login</button>
      </form>
    </div>
  </div>

</body>
</html>