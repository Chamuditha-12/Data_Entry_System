<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$markers = $conn->query("SELECT id, marker_name FROM markers ORDER BY marker_name ASC")->fetchAll(PDO::FETCH_ASSOC);

$per_page = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $per_page;

$total_entries = $conn->query("SELECT COUNT(*) FROM marks_entries")->fetchColumn();
$total_pages = ceil($total_entries / $per_page);

$stmt = $conn->prepare("
    SELECT me.*, m.marker_name
    FROM marks_entries me
    JOIN markers m ON me.marker_id = m.id
    ORDER BY me.created_at DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

$marker_counts = $conn->query("
    SELECT marker_id, COUNT(*) as cnt FROM marks_entries GROUP BY marker_id
")->fetchAll(PDO::FETCH_KEY_PAIR);
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
      <a href="export_excel.php" class="btn-export">⬇ Download Excel</a>
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

      <div class="card">
        <div class="card-head">
          <div class="card-title">Form Entries</div>
        </div>
        <div class="card-sub">All submitted marks — edit or delete any row</div>

        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Index No.</th>
                <th>Group</th>
                <th>Part A</th>
                <th>Part B</th>
                <th>Marker</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($entries as $row): ?>
              <tr>
                <td><span class="idx-badge"><?php echo htmlspecialchars($row['student_index']); ?></span></td>
                <td><span class="grp-badge"><?php echo htmlspecialchars($row['group_number']); ?></span></td>
                <td class="marks"><?php echo htmlspecialchars($row['part_a_marks']); ?></td>
                <td class="marks"><?php echo htmlspecialchars($row['part_b_marks']); ?></td>
                <td><?php echo htmlspecialchars($row['marker_name']); ?></td>
                <td>
                  <div class="actions">
                    <button class="btn-icon btn-edit" onclick="location.href='edit_entry_form.php?id=<?php echo $row['id']; ?>'">✎ Edit</button>
                    <a class="btn-icon btn-del" href="actions/delete_entry.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this entry?')">🗑 Delete</a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="table-foot">
          <div class="foot-stat">Total entries: <b><?php echo $total_entries; ?></b></div>
        </div>

        <?php if ($total_pages > 1): ?>
        <div class="pagination">
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
          <?php endfor; ?>
        </div>
        <?php endif; ?>
      </div>

    </div>

    <div class="card" style="margin-top:24px;">
      <div class="card-head">
        <div class="card-title">Paper Markers</div>
      </div>
      <div class="card-sub">Only admins can manage marker names — these appear in the form dropdown</div>

      <?php foreach ($markers as $marker): ?>
      <div class="marker-row">
        <div class="marker-info">
          <div>
            <div class="marker-name"><?php echo htmlspecialchars($marker['marker_name']); ?></div>
            <div class="marker-meta"><?php echo $marker_counts[$marker['id']] ?? 0; ?> entries marked</div>
          </div>
        </div>
        <a class="btn-remove" href="actions/delete_marker.php?id=<?php echo $marker['id']; ?>" onclick="return confirm('Remove this marker?')">✕ Remove</a>
      </div>
      <?php endforeach; ?>

      <form class="add-marker" action="actions/add_marker.php" method="POST">
        <input type="text" name="marker_name" placeholder="Enter new marker name" required>
        <button type="submit" class="btn-add-marker">Add Marker</button>
      </form>
    </div>

  </div>

</body>
</html>