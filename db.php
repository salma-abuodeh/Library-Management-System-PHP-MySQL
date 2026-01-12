<?php
$host = "localhost";
$user = "root";
$pass = "";  
$db = "libr";  

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>