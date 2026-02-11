<?php
$password = "admin123"; // password unayotaka
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo $hashed;
?>