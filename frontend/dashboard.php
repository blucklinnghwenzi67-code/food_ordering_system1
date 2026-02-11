<?php
session_start();
if(!isset($_SESSION["user_id"])){
    header("Location: login.html"); exit;
}
$role = $_SESSION["role_id"];
if($role==1){header("Location: admin_dashboard.php"); exit;}
elseif($role==2){header("Location: customer_dashboard.php"); exit;}
else{session_destroy(); header("Location: login.html"); exit;}
?>