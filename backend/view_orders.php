<?php
session_start();
require 'db_connect.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1){
    header("Location: ../frontend/login.html");
    exit();
}

// Update order status
if(isset($_POST['update_order'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['order_status'];
    $stmt = $pdo->prepare("UPDATE orders SET order_status=? WHERE order_id=?");
    $stmt->execute([$status, $order_id]);
}

// Fetch orders with items
$orders = $pdo->query("
SELECT o.order_id, o.order_date, o.order_status, u.full_name 
FROM orders o 
JOIN users u ON o.user_id=u.user_id 
ORDER BY o.order_date DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Orders</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>
<h2>Orders</h2>
<a href="dashboard.php">Back to Dashboard</a>

<?php foreach($orders as $order): ?>
    <hr>
    <h3>Order ID: <?php echo $order['order_id']; ?> | Customer: <?php echo $order['full_name']; ?> | Date: <?php echo $order['order_date']; ?></h3>
    <p>Status: <?php echo $order['order_status']; ?></p>

    <?php
    $stmt = $pdo->prepare("SELECT f.food_name, oi.quantity FROM order_items oi JOIN foods f ON oi.food_id=f.food_id WHERE order_id=?");
    $stmt->execute([$order['order_id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <ul>
        <?php foreach($items as $item): ?>
            <li><?php echo $item['food_name']; ?> x <?php echo $item['quantity']; ?></li>
        <?php endforeach; ?>
    </ul>

    <form method="POST">
        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
        <select name="order_status">
            <option value="Pending" <?php if($order['order_status']=='Pending') echo 'selected'; ?>>Pending</option>
            <option value="Completed" <?php if($order['order_status']=='Completed') echo 'selected'; ?>>Completed</option>
        </select>
        <button type="submit" name="update_order">Update Status</button>
    </form>
<?php endforeach; ?>
</body>
</html>