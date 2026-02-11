<?php
session_start();
if(!isset($_SESSION["user_id"])||$_SESSION["role_id"]!=2){header("Location: login.html"); exit;}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Dashboard</title>
<style>body{margin:0;font-family:Arial;background:linear-gradient(135deg,#ff6a00,#ee0979);display:flex;justify-content:center;align-items:center;height:100vh;}
.card{background:#fff;padding:30px;border-radius:12px;width:400px;text-align:center;}
button{padding:12px 20px;border:none;border-radius:6px;color:#fff;background:linear-gradient(to right,#ff6a00,#ee0979);cursor:pointer;}
button:hover{opacity:0.9;}
</style>
</head>
<body>
<div class="card">
<h2>Customer Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?></p>
<button onclick="logout()">Logout</button>
</div>
<script>
function logout(){
    fetch("../backend/auth/logout.php")
    .then(res=>res.json())
    .then(data=>{if(data.status=="success")window.location.href="login.html";});
}
</script>
</body>
</html>