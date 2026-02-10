<?php
require_once "backend/config/database.php";

if ($conn) {
    echo "<h2 style='color:green'>Database connection successful!</h2>";
} else {
    echo "<h2 style='color:red'>Database connection failed!</h2>";
}
?>