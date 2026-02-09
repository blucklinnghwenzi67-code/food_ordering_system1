<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db_connect.php';
$stmt = $pdo->query("SELECT NOW() AS time");
$row = $stmt->fetch();
echo "Database connected successfully! Current time: " . $row['time'];