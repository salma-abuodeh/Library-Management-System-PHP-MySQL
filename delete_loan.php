<?php
include 'navbar.php';
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Only admin (or staff, if you want) can delete loans
if ($_SESSION['role'] === 'student') {
    die("Access denied.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid loan id.");
}

// get book_id for this loan so we can free the book
$res  = mysqli_query($conn, "SELECT book_id FROM loan WHERE loan_id = $id");
$loan = mysqli_fetch_assoc($res);

if ($loan) {
    $bookId = (int)$loan['book_id'];

    // delete the loan record
    mysqli_query($conn, "DELETE FROM loan WHERE loan_id = $id");

    // if there was a book, mark it available again
    if ($bookId > 0) {
        mysqli_query($conn, "UPDATE book SET available = 1 WHERE book_id = $bookId");
    }
}

header("Location: loans.php");
exit;
