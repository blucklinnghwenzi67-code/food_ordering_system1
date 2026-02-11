<?php
header('Content-Type: application/json');
session_start();
if(!isset($_SESSION['user_id'])){
    echo json_encode(['status'=>'error','message'=>'Not logged in']);
    exit;
}

include '../db_connect.php';
$input = json_decode(file_get_contents('php://input'), true);
$order_id = $input['order_id'] ?? 0;
$user_id = $_SESSION['user_id'];

if(!$order_id){
    echo json_encode(['status'=>'error','message'=>'Invalid order']);
    exit;
}

$stmt = $conn->prepare("UPDATE orders SET status='Paid' WHERE order_id=? AND user_id=?");
$stmt->bind_param("ii",$order_id,$user_id);
$stmt->execute();

if($stmt->affected_rows>0){
    echo json_encode(['status'=>'success','message'=>'Order paid successfully']);
}else{
    echo json_encode(['status'=>'error','message'=>'Order not found or already paid']);
}
?>