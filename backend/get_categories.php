<?php
header("Content-Type: application/json");
require 'db_connect.php';

$stmt = $pdo->query("SELECT category_id, category_name FROM categories");
$categories = $stmt->fetchAll();

echo json_encode($categories);