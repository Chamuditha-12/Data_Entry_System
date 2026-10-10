<?php
// actions/add_marker.php - Add a new paper marker
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marker_name = trim($_POST['marker_name']);

    if ($marker_name !== '') {
        try {
            $stmt = $conn->prepare("INSERT INTO markers (marker_name) VALUES (:name)");
            $stmt->execute(['name' => $marker_name]);

            header("Location: ../dashboard.php?status=marker_added");
            exit;

        } catch (PDOException $e) {
            file_put_contents('../db_errors.log', date("Y-m-d H:i:s") . " - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            header("Location: ../dashboard.php?status=error");
            exit;
        }
    } else {
        header("Location: ../dashboard.php?status=error");
        exit;
    }
}
?>