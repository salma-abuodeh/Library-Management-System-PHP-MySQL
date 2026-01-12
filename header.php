<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<link rel="stylesheet" href="style.css">

<div class="navbar">
    <div class="navbar-left">
        <strong style="font-size:20px;">Library System</strong>

        <a href="books.php">Books</a>
        <a href="authors.php">Authors</a>
        <a href="publishers.php">Publishers</a>
        <a href="borrowers.php">Borrowers</a>
        <a href="loans.php">Loans</a>
        <a href="sales.php">Sales</a>
        <a href="reports.php">Reports</a>
        <a href="about.php">About</a>
    </div>

    <div class="navbar-right">
        Logged in as <strong><?=$_SESSION['username']?> (<?=$_SESSION['role']?>)</strong>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>
