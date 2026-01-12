<?php
include 'navbar.php';
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Clean child tables first to avoid FK problems
mysqli_query($conn, "DELETE FROM sale WHERE book_id = $id");
mysqli_query($conn, "DELETE FROM loan WHERE book_id = $id");
mysqli_query($conn, "DELETE FROM bookauthor WHERE book_id = $id");

// Now delete book
mysqli_query($conn, "DELETE FROM book WHERE book_id = $id");

header("Location: books.php");
exit;
