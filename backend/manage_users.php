<?php
session_start();
require 'db_connect.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1){
    header("Location: ../frontend/login.html");
    exit();
}

// Fetch all users
$users = $pdo->query("SELECT user_id, full_name, email, role_id FROM users")->fetchAll(PDO::FETCH_ASSOC);

// Delete User
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id=?");
    $stmt->execute([$id]);
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body>
<h2>Manage Users</h2>
<a href="dashboard.php">Back to Dashboard</a>

<table border="1" cellpadding="5">
<tr><th>Full Name</th><th>Email</th><th>Role</th><th>Action</th></tr>
<?php foreach($users as $user): ?>
<tr>
    <td><?php echo $user['full_name']; ?></td>
    <td><?php echo $user['email']; ?></td>
    <td><?php echo ($user['role_id']==1)? 'Admin' : 'Customer'; ?></td>
    <td>
        <?php if($user['role_id'] != 1): // can't delete admin ?>
            <a href="?delete=<?php echo $user['user_id']; ?>" onclick="return confirm('Delete this user?')">Delete</a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>