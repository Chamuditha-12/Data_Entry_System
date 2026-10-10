<?php
// actions/edit_entry.php - Update an existing marks entry
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id            = (int)$_POST['id'];
    $student_index = trim($_POST['student_index']);
    $group_number  = trim($_POST['group_number']);
    $part_a        = (int)$_POST['part_a_marks'];
    $part_b        = (int)$_POST['part_b_marks'];
    $marker_id     = (int)$_POST['marker_id'];

    try {
        $stmt = $conn->prepare("
            UPDATE marks_entries
            SET student_index = :student_index,
                group_number = :group_number,
                part_a_marks = :part_a,
                part_b_marks = :part_b,
                marker_id = :marker_id
            WHERE id = :id
        ");
        $stmt->execute([
            'student_index' => $student_index,
            'group_number'  => $group_number,
            'part_a'        => $part_a,
            'part_b'        => $part_b,
            'marker_id'     => $marker_id,
            'id'            => $id
        ]);

        header("Location: ../dashboard.php?status=updated");
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            header("Location: ../dashboard.php?status=duplicate");
        } else {
            file_put_contents('../db_errors.log', date("Y-m-d H:i:s") . " - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            header("Location: ../dashboard.php?status=error");
        }
        exit;
    }
}
?>