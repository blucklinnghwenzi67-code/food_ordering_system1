<?php
session_start();
require 'db_connect.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1){
    header("Location: ../frontend/login.html");
    exit();
}

// Fetch categories for dropdown
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Add Food
if(isset($_POST['add_food'])){
    $category_id = $_POST['category_id'];
    $food_name = $_POST['food_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO foods (category_id, food_name, description, price, status) VALUES (?,?,?,?,?)");
    $stmt->execute([$category_id, $food_name, $description, $price, $status]);
}

// Edit Food
if(isset($_POST['edit_food'])){
    $food_id = $_POST['food_id'];
    $category_id = $_POST['category_id'];
    $food_name = $_POST['food_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE foods SET category_id=?, food_name=?, description=?, price=?, status=? WHERE food_id=?");
    $stmt->execute([$category_id, $food_name, $description, $price, $status, $food_id]);
}

// Delete Food
if(isset($_GET['delete'])){
    $food_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM foods WHERE food_id=?");
    $stmt->execute([$food_id]);
}

// Fetch foods
$foods = $pdo->query("SELECT f.*, c.category_name FROM foods f JOIN categories c ON f.category_id=c.category_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Foods</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>
<h2>Manage Foods</h2>
<a href="dashboard.php">Back to Dashboard</a>

<h3>Add Food</h3>
<form method="POST">
    Category:
    <select name="category_id" required>
        <?php foreach($categories as $cat): ?>
            <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['category_name']; ?></option>
        <?php endforeach; ?>
    </select><br>
    Food Name: <input type="text" name="food_name" required><br>
    Description: <input type="text" name="description"><br>
    Price: <input type="number" step="0.01" name="price" required><br>
    Status:
    <select name="status">
        <option value="Available">Available</option>
        <option value="Unavailable">Unavailable</option>
    </select><br>
    <button type="submit" name="add_food">Add Food</button>
</form>

<h3>Edit Food</h3>
<form method="POST">
    Select Food:
    <select name="food_id" required>
        <?php foreach($foods as $food): ?>
            <option value="<?php echo $food['food_id']; ?>"><?php echo $food['food_name']; ?></option>
        <?php endforeach; ?>
    </select><br>
    Category:
    <select name="category_id" required>
        <?php foreach($categories as $cat): ?>
            <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['category_name']; ?></option>
        <?php endforeach; ?>
    </select><br>
    Food Name: <input type="text" name="food_name" required><br>
    Description: <input type="text" name="description"><br>
    Price: <input type="number" step="0.01" name="price" required><br>
    Status:
    <select name="status">
        <option value="Available">Available</option>
        <option value="Unavailable">Unavailable</option>
    </select><br>
    <button type="submit" name="edit_food">Update Food</button>
</form>

<h3>Existing Foods</h3>
<table border="1" cellpadding="5">
    <tr><th>Food</th><th>Category</th><th>Price</th><th>Status</th><th>Action</th></tr>
    <?php foreach($foods as $food): ?>
        <tr>
            <td><?php echo $food['food_name']; ?></td>
            <td><?php echo $food['category_name']; ?></td>
            <td><?php echo $food['price']; ?></td>
            <td><?php echo $food['status']; ?></td>
            <td><a href="?delete=<?php echo $food['food_id']; ?>">Delete</a></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>