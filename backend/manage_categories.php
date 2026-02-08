<?php
session_start();
require 'db_connect.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1){
    header("Location: ../frontend/login.html");
    exit();
}

// Add Category
if(isset($_POST['add_category'])){
    $name = trim($_POST['category_name']);
    if($name != ''){
        $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->execute([$name]);
    }
}

// Edit Category
if(isset($_POST['edit_category'])){
    $id = $_POST['category_id'];
    $name = trim($_POST['category_name']);
    if($name != ''){
        $stmt = $pdo->prepare("UPDATE categories SET category_name=? WHERE category_id=?");
        $stmt->execute([$name, $id]);
    }
}

// Delete Category
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id=?");
    $stmt->execute([$id]);
}

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>
<h2>Manage Categories</h2>
<a href="dashboard.php">Back to Dashboard</a>

<h3>Add Category</h3>
<form method="POST">
    Category Name: <input type="text" name="category_name" required>
    <button type="submit" name="add_category">Add</button>
</form>

<h3>Edit Category</h3>
<form method="POST">
    <select name="category_id" required>
        <option value="">Select category</option>
        <?php foreach($categories as $cat): ?>
            <option value="<?php echo $cat['category_id']; ?>"><?php echo $cat['category_name']; ?></option>
        <?php endforeach; ?>
    </select>
    New Name: <input type="text" name="category_name" required>
    <button type="submit" name="edit_category">Update</button>
</form>

<h3>Existing Categories</h3>
<ul>
    <?php foreach($categories as $cat): ?>
        <li><?php echo $cat['category_name']; ?> 
            <a href="?delete=<?php echo $cat['category_id']; ?>">Delete</a>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>