<?php
header('Content-Type: application/json');
session_start();
include '../db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if(!$email || !$password){
    echo json_encode(['status'=>'error','message'=>'All fields are required']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows==0){
    echo json_encode(['status'=>'error','message'=>'User not found']);
    exit;
}

$user = $result->fetch_assoc();
if(!password_verify($password,$user['password'])){
    echo json_encode(['status'=>'error','message'=>'Incorrect password']);
    exit;
}

// Set session
$_SESSION["user_id"] = $user['user_id'];
$_SESSION["full_name"] = $user['full_name'];
$_SESSION["role_id"] = $user['role_id'];

echo json_encode(['status'=>'success','message'=>'Login successful']);
?>