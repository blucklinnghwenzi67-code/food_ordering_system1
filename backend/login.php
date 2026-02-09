<?php
session_start();
require 'db_connect.php';
header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['full_name'] = $user['full_name'];

        echo json_encode([
            'status'=>'success',
            'role_id'=>$user['role_id'],
            'message'=>'Login successful'
        ]);
    } else {
        echo json_encode([
            'status'=>'error',
            'message'=>'Invalid credentials'
        ]);
    }
}