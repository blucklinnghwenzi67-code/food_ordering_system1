<?php
header('Content-Type: application/json');
session_start();
include '../db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$full_name = trim($data['full_name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if(!$full_name || !$email || !$password){
    echo json_encode(['status'=>'error','message'=>'All fields are required']);
    exit;
}

// Check duplicate email
$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows>0){
    echo json_encode(['status'=>'error','message'=>'Email already exists']);
    exit;
}

// Hash password
$hashed = password_hash($password,PASSWORD_DEFAULT);

// Assign default role_id = 2 (customer)
$role_id = 2;

$stmt = $conn->prepare("INSERT INTO users(full_name,email,password,role_id) VALUES(?,?,?,?)");
$stmt->bind_param("sssi",$full_name,$email,$hashed,$role_id);
if($stmt->execute()){
    echo json_encode(['status'=>'success','message'=>'Registration successful']);
}else{
    echo json_encode(['status'=>'error','message'=>'Server error: '.$stmt->error]);
}
?>