<?php
session_start();
require 'db_connect.php';

if(!isset($_SESSION['user_id'])){
    echo "Please login first!";
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $_SESSION['user_id'];

if(!$data || count($data)===0){
    echo "Cart is empty!";
    exit();
}

// Create order
$stmt = $pdo->prepare("INSERT INTO orders (user_id) VALUES (?)");
$stmt->execute([$user_id]);
$order_id = $pdo->lastInsertId();

// Insert order items
foreach($data as $item){
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, food_id, quantity) VALUES (?,?,?)");
    $stmt->execute([$order_id, $item['id'], $item['qty']]);
}

echo "Order placed successfully!";