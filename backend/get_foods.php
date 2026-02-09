<?php
header("Content-Type: application/json");
require 'db_connect.php';

$cat_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

$stmt = $pdo->prepare("SELECT food_id, category_id, food_name, description, price FROM foods WHERE category_id=?");
$stmt->execute([$cat_id]);
$foods = $stmt->fetchAll();

echo json_encode($foods);