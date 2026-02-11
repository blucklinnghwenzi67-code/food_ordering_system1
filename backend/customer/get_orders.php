<?php
header('Content-Type: application/json');
session_start();
if(!isset($_SESSION['user_id'])){
    echo json_encode([]);
    exit;
}

include '../db_connect.php';
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = [];
while($row = $result->fetch_assoc()){ $orders[] = $row; }
echo json_encode($orders);
?>