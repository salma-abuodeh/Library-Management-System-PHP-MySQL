<?php
include 'navbar.php';
require 'db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first   = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last    = mysqli_real_escape_string($conn, $_POST['last_name']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $bio     = mysqli_real_escape_string($conn, $_POST['bio']);

    $sql = "
        INSERT INTO author(first_name, last_name, country, bio)
        VALUES('$first', '$last', '$country', '$bio')
    ";
    mysqli_query($conn, $sql);
    header("Location: authors.php");
    exit;
}
?>

<div style="margin-top:120px;"></div>
<div class="container col-md-6">
    <h2>Add Author</h2>
    <form method="post">
        <div class="mb-3">
            <label>First name</label>
            <input name="first_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Last name</label>
            <input name="last_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Country</label>
            <input name="country" class="form-control">
        </div>
        <div class="mb-3">
            <label>Bio</label>
            <textarea name="bio" class="form-control" rows="3"></textarea>
        </div>
        <button class="btn btn-success">Save</button>
        <a href="authors.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
