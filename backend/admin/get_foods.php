<?php
include '../db_connect.php';

$sql = "SELECT f.*, c.category_name 
        FROM foods f
        JOIN categories c ON f.category_id = c.category_id
        ORDER BY c.category_name";

$result = $conn->query($sql);

$foods = [];
while ($row = $result->fetch_assoc()) {
    $foods[] = $row;
}

echo json_encode($foods);