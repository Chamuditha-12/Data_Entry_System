<?php
// actions/delete_marker.php - Delete a paper marker
require '../config.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // Check if this marker is used in any marks_entries (foreign key protection)
        $check = $conn->prepare("SELECT COUNT(*) FROM marks_entries WHERE marker_id = :id");
        $check->execute(['id' => $id]);
        $used_count = $check->fetchColumn();

        if ($used_count > 0) {
            // Marker is already used in entries — block deletion
            header("Location: ../dashboard.php?status=marker_in_use");
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM markers WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header("Location: ../dashboard.php?status=marker_deleted");
        exit;

    } catch (PDOException $e) {
        file_put_contents('../db_errors.log', date("Y-m-d H:i:s") . " - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
        header("Location: ../dashboard.php?status=error");
        exit;
    }
}
?>