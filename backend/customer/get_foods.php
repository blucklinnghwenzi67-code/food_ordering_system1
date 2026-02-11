<?php
require_once _DIR_."/../db_connect.php";
header('Content-Type: application/json');

$result = $conn->query("
    SELECT f.food_id, f.name, f.description, f.price, f.image
    FROM foods f
");

$foods=[];
while($row=$result->fetch_assoc()){
    $foods[]=$row;
}
echo json_encode($foods);
?>