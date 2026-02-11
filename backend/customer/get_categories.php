<?php
// Include database connection
require_once __DIR__ . "/../db_connect.php";

// Query all categories
$query = $conn->query("SELECT category_id, category_name FROM categories ORDER BY category_id ASC");

$categories = [];
while ($row = $query->fetch_assoc()) {
    $categories[] = $row;
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($categories);