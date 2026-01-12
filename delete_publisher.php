<?php
include 'navbar.php';
require 'db.php';


if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid publisher id.");
}

// check if publisher is used by any book
$bookRes = mysqli_query($conn,
    "SELECT 1 FROM book WHERE publisher_id = $id LIMIT 1");

if (mysqli_fetch_assoc($bookRes)) {
    die("Cannot delete this publisher because there are books linked to it.");
}

mysqli_query($conn, "DELETE FROM publisher WHERE publisher_id = $id");

header("Location: publishers.php");
exit;
