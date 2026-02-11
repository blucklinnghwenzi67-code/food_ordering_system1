<?php
header('Content-Type: application/json');
include '../db_connect.php';

$input = json_decode(file_get_contents('php://input'), true);
if(!$input){
    echo json_encode(['status'=>'error','message'=>'Invalid input']);
    exit;
}

$name = trim($input['name']);
$description = trim($input['description']);
$price = $input['price'];
$category_id = $input['category_id'];
$image = trim($input['image']);

if(!$name || !$price || !$category_id){
    echo json_encode(['status'=>'error','message'=>'Name, price and category are required']);
    exit;
}

try{
    $stmt = $conn->prepare("INSERT INTO foods (name, description, price, category_id, image) VALUES (?,?,?,?,?)");
    $stmt->bind_param("ssdiss",$name,$description,$price,$category_id,$image);
    $stmt->execute();
    echo json_encode(['status'=>'success','message'=>'Food added successfully']);
}catch(Exception $e){
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
?>