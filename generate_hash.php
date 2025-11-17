<?php
// hash_generator.php
$password = 'admin123'; 
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;
// Outputnya akan berupa string panjang, contoh: $2y$10$QjE1T3h5R3U...
?>