<?php
include 'navbar.php';
require 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: sales.php");
    exit;
}

$id = (int) $_GET['id'];

// get the book_id first
$sql = "SELECT book_id FROM sale WHERE sale_id = $id";
$r   = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($r);

if ($row) {
    $book_id = (int) $row['book_id'];

    // delete the sale
    mysqli_query($conn, "DELETE FROM sale WHERE sale_id = $id");

    // mark book available again
    if ($book_id > 0) {
        mysqli_query($conn, "UPDATE book SET available = 1 WHERE book_id = $book_id");
    }
}

header("Location: sales.php");
exit;
