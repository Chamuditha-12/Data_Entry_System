<?php
// actions/add_entry.php - Insert new marks entry
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_index = trim($_POST['student_index']);
    $group_number  = trim($_POST['group_number']);
    $part_a        = (int)$_POST['part_a_marks'];
    $part_b        = (int)$_POST['part_b_marks'];
    $marker_id     = (int)$_POST['marker_id'];

    // Detect where the request came from (public form vs dashboard)
    $redirect_to = strpos($_SERVER['HTTP_REFERER'], 'dashboard.php') !== false ? '../dashboard.php' : '../index.php';

    try {
        $stmt = $conn->prepare("
            INSERT INTO marks_entries (student_index, group_number, part_a_marks, part_b_marks, marker_id)
            VALUES (:student_index, :group_number, :part_a, :part_b, :marker_id)
        ");
        $stmt->execute([
            'student_index' => $student_index,
            'group_number'  => $group_number,
            'part_a'        => $part_a,
            'part_b'        => $part_b,
            'marker_id'     => $marker_id
        ]);

        header("Location: $redirect_to?status=success");
        exit;

    } catch (PDOException $e) {
        // Error code 23000 = integrity constraint violation (duplicate student_index)
        if ($e->getCode() == 23000) {
            header("Location: $redirect_to?status=duplicate");
        } else {
            file_put_contents('../db_errors.log', date("Y-m-d H:i:s") . " - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            header("Location: $redirect_to?status=error");
        }
        exit;
    }
}
?>