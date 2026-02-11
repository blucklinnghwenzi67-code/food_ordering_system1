<?php
header('Content-Type: application/json');
session_start();
include '../db_connect.php';

// Hakikisha admin amelogin
if(!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1){
    echo json_encode([]);
    exit;
}

$sql = "SELECT f.food_id, f.name, f.description, f.price, f.image, c.category_name 
        FROM foods f
        JOIN categories c ON f.category_id = c.category_id
        ORDER BY c.category_name, f.name";

$result = $conn->query($sql);

$foods = [];
while($row = $result->fetch_assoc()){
    // Set default image kama DB haina
    if(!$row['image'] || trim($row['image'])==''){
        $row['image'] = 'https://via.placeholder.com/200x150?text=No+Image';
    }
    $foods[] = $row;
}

echo json_encode($foods);