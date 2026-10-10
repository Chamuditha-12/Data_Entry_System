<?php
// index.php - Public Student Marks Entry Form
require 'config.php';

// Fetch markers from DB for the dropdown
$markers = $conn->query("SELECT id, marker_name FROM markers ORDER BY marker_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Message to show after form submission (passed via redirect)
$message = "";
$message_type = "";
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = "Marks submitted successfully!";
        $message_type = "success";
    } elseif ($_GET['status'] === 'duplicate') {
        $message = "This Student Index Number already has an entry.";
        $message_type = "error";
    } elseif ($_GET['status'] === 'error') {
        $message = "Something went wrong. Please try again.";
        $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Marks Entry</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

  <div class="topbar">
    <div class="brand">
      <div class="brand-title">Chemistry Paper Marks</div>
    </div>
    <a href="login.php" class="admin-chip" style="text-decoration:none;">Login as admin</a>
  </div>

  <div class="page" style="max-width:600px; margin:60px auto;">
    <div class="card">
      <div class="card-title" style="font-size:24px; margin-bottom:20px;">Student Marks</div>

      <?php if ($message): ?>
        <div style="padding:12px 16px; border-radius:10px; margin-bottom:18px; font-size:14px; font-weight:600;
          <?php echo $message_type === 'success' ? 'background:#e6f7f0; color:#0b7c56;' : 'background:#fdeeee; color:#dc2626;'; ?>">
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <form action="actions/add_entry.php" method="POST">

        <div class="field">
          <label>Student Index Number</label>
          <input type="text" name="student_index" placeholder="Enter index number" required>
        </div>

        <div class="field">
          <label>Student Group Number</label>
          <input type="text" name="group_number" placeholder="Enter group number" required>
        </div>

        <div class="two-col" style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
          <div class="field">
            <label>Paper Marks Part A</label>
            <input type="number" name="part_a_marks" placeholder="Enter marks" required>
          </div>
          <div class="field">
            <label>Paper Marks Part B</label>
            <input type="number" name="part_b_marks" placeholder="Enter marks" required>
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

        <button type="submit" class="btn-primary">Submit Marks</button>

      </form>
    </div>
  </div>

</body>
</html>