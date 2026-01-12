<?php
include 'navbar.php';
require 'db.php';


if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid borrower id.");
}

// check if borrower has loans or sales
$hasLoanRes = mysqli_query($conn,
    "SELECT 1 FROM loan WHERE borrower_id = $id LIMIT 1");
$hasSaleRes = mysqli_query($conn,
    "SELECT 1 FROM sale WHERE borrower_id = $id LIMIT 1");

if (mysqli_fetch_assoc($hasLoanRes) || mysqli_fetch_assoc($hasSaleRes)) {
    die("Cannot delete this borrower because there are loans or sales linked to them.");
}

mysqli_query($conn, "DELETE FROM borrower WHERE borrower_id = $id");

header("Location: borrowers.php");
exit;
