<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Marks — Admin Dashboard</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

  <div class="topbar">
    <div class="brand">
      <div class="brand-title">Student Marks</div>
      <div class="brand-sub">Admin Dashboard</div>
    </div>
    <div class="admin-chip">
      <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
      <a href="logout.php" style="color:#fff; text-decoration:underline; margin-left:10px;">Logout</a>
    </div>
  </div>

  <div class="page">
    <div class="page-head">
      <div class="page-title">Marks <span class="accent">Dashboard</span></div>
    </div>
  </div>

</body>
</html>