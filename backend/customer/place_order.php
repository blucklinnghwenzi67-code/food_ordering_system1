<?php
session_start();
require_once _DIR_."/../db_connect.php";
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(["status"=>"error","message"=>"Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$items = $data['items'] ?? [];

if(empty($items)){
    echo json_encode(["status"=>"error","message"=>"Cart is empty"]);
    exit;
}

$total = 0;
foreach($items as $item){ $total += $item['price'] * $item['qty']; }

$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?,?,?,NOW())");
$stmt->bind_param("ids", $_SESSION['user_id'], $total, $status="Pending");
$stmt->execute();
$order_id = $stmt->insert_id;

echo json_encode(["status"=>"success","message"=>"Order placed successfully!"]);
?>