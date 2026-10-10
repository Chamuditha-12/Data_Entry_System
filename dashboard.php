<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$markers = $conn->query("SELECT id, marker_name FROM markers ORDER BY marker_name ASC")->fetchAll(PDO::FETCH_ASSOC);
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

    <div class="grid">

      <div class="card">
        <div class="card-head">
          <div class="card-title">Add New Entry</div>
        </div>
        <div class="card-sub">Add a new row to the marks form</div>

        <form action="actions/add_entry.php" method="POST">
          <div class="field">
            <label>Student Index Number</label>
            <input type="text" name="student_index" placeholder="Enter index number" required>
          </div>
          <div class="field">
            <label>Student Group Number</label>
            <input type="text" name="group_number" placeholder="Enter group number" required>
          </div>
          <div class="two-col">
            <div class="field">
              <label>Paper Marks Part A</label>
              <input type="number" name="part_a_marks" placeholder="Marks" required>
            </div>
            <div class="field">
              <label>Paper Marks Part B</label>
              <input type="number" name="part_b_marks" placeholder="Marks" required>
            </div>
          </div>
          <div class="field">
            <label>Marker Name</label>
            <select name="marker_id" required>
              <option value="">Select marker</option>
              <?php foreach ($markers as $marker): ?>
                <option value="<?php echo $marker['id']; ?>"><?php echo htmlspecialchars($marker['marker_name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn-primary">Save Entry</button>
        </form>
      </div>

    </div>
  </div>

</body>
</html>