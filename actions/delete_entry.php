<?php
// actions/delete_entry.php - Delete a marks entry
require '../config.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM marks_entries WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header("Location: ../dashboard.php?status=deleted");
        exit;

    } catch (PDOException $e) {
        file_put_contents('../db_errors.log', date("Y-m-d H:i:s") . " - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
        header("Location: ../dashboard.php?status=error");
        exit;
    }
}
?>