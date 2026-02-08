<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../frontend/login.html");
    exit();
}

// Role check
$isAdmin = $_SESSION['role_id'] == 1;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>
<h2>Welcome, <?php echo $_SESSION['full_name']; ?></h2>
<a href="logout.php">Logout</a>

<?php if($isAdmin): ?>
    <h3>Admin Panel</h3>
    <ul>
        <li><a href="manage_foods.php">Manage Foods</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="view_orders.php">View / Update Orders</a></li>
    </ul>
<?php else: ?>
    <h3>Customer Panel</h3>
    <div id="menu"></div>
<?php endif; ?>

<script src="../frontend/js/main.js"></script>
</body>
</html>