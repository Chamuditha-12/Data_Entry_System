<?php

$host = "localhost";
$db_name = "marks_db";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    
    $log_message = date("Y-m-d H:i:s") . " - Connection failed: " . $e->getMessage() . PHP_EOL;
    file_put_contents(__DIR__ . "/db_errors.log", $log_message, FILE_APPEND);

    die("Something went wrong. Please try again later.");
}
?>