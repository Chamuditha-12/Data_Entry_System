<?php
// export_excel.php - Export all marks entries as a CSV file (opens in Excel)
session_start();
require 'config.php';

// Protect this page - only logged in admins can export
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch all entries with marker name
$stmt = $conn->query("
    SELECT me.student_index, me.group_number, me.part_a_marks, me.part_b_marks, m.marker_name, me.created_at
    FROM marks_entries me
    JOIN markers m ON me.marker_id = m.id
    ORDER BY me.created_at DESC
");
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set headers to force download as .csv (opens directly in Excel)
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=student_marks_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, ['Student Index', 'Group Number', 'Part A Marks', 'Part B Marks', 'Marker Name', 'Date Added']);

// Data rows
foreach ($entries as $row) {
    fputcsv($output, [
        $row['student_index'],
        $row['group_number'],
        $row['part_a_marks'],
        $row['part_b_marks'],
        $row['marker_name'],
        $row['created_at']
    ]);
}

fclose($output);
exit;
?>