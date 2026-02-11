<?php
header('Content-Type: application/json');
include '../db_connect.php';

try {
    $stmt = $conn->prepare("SELECT category_id, category_name FROM categories ORDER BY category_name ASC");
    $stmt->execute();
    $result = $stmt->get_result();

    $categories = [];
    while($row = $result->fetch_assoc()){
        $categories[] = $row;
    }

    echo json_encode(['status'=>'success','data'=>$categories]);
} catch(Exception $e){
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
?>