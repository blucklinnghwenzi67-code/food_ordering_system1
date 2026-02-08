<?php
require 'db_connect.php';
$cat_id = $_GET['category_id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM foods WHERE category_id=?");
$stmt->execute([$cat_id]);
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($foods);