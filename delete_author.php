<?php
include 'navbar.php';
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid author id.");
}

// because bookauthor has ON DELETE CASCADE, this will remove links automatically
mysqli_query($conn, "DELETE FROM author WHERE author_id = $id");

header("Location: authors.php");
exit;
