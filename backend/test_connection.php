<?php
require 'db_connect.php';

try {
    $stmt = $pdo->query("SELECT NOW() as time");
    $row = $stmt->fetch();
    echo "Database connected successfully! Current time: " . $row['time'];
} catch(PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}