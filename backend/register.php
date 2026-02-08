<?php
session_start();
require 'db_connect.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role_id = 2;

    if(empty($full_name) || empty($email) || empty($password)){
        echo "All fields are required";
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    if($stmt->rowCount()>0){
        echo "Email already exists";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (role_id, full_name, email, password) VALUES (?, ?, ?, ?)");
    if($stmt->execute([$role_id, $full_name, $email, $hashed_password])){
        echo "success";
    } else {
        echo "Something went wrong";
    }
}
?>